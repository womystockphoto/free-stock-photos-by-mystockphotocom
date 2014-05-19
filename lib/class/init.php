<?php
 require_once 'config.php';


 class mystockphoto  extends msp {
   public function __construct()
  {
    parent::__construct();
   	add_action('init', array( __CLASS__ , 'init'));
	  register_deactivation_hook( MYSTP_FILE , array( __CLASS__ , 'uninstall'));
  }

   function init() {
    if (is_admin() && preg_match('#[admin|free*stock*photos]#isU', $_SERVER['REQUEST_URI']) ) {
      self::check_wp_version( MYSTP_WP_MIN );
      add_action('admin_menu', array(__CLASS__, 'admin_menu'));
       add_filter('plugin_action_links_' . basename(dirname( MYSTP_FILE)) . '/' . basename( MYSTP_FILE ),
                          array(__CLASS__, 'plugin_action_links'));
      add_action('admin_init', array(__CLASS__, 'register_settings'));
      self::default_settings(false);
      add_filter('media_buttons_context', array(__CLASS__, 'media_button'));
      add_action('admin_head', array(__CLASS__, 'admin_enqueues'));

	    wp_register_script('mystp', mystp_pg_url('lib/js/base.js', MYSTP_FILE ,array('jquery'),'2.7',true));
      wp_register_script('mystp_popup', mystp_pg_url('lib/js/jquery.colorbox.js', MYSTP_FILE ,array('jquery'),'2.7',true));
      wp_register_style('mystp_g_css','http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,200italic,300italic,400italic,600italic,700italic');
      wp_register_style('mystp_css', mystp_pg_url('lib/css/base.css',MYSTP_FILE));

      wp_enqueue_style('mystp_css');
      wp_enqueue_style('mystp_g_css');
      wp_enqueue_script('jquery');
      wp_enqueue_script( 'mystp_popup' );

	    add_action('admin_head', array(__CLASS__, 'popup'));
      add_action('admin_print_styles', array( __CLASS__ , 'css_remove'));
      add_action('admin_print_scripts', array( __CLASS__ , 'js_remove'));
      add_action('wp_ajax_mystp_proxy', array(__CLASS__ , 'ajax_proxy'));
    }
  } // init

  function js_remove(){
    $action = isset( $_GET['action']) ? trim( $_GET['action']) : '';
    if( in_array( $action , array('mystp_proxy','mystp_set_photo','mystp_get_photo') )) {
       global $wp_scripts;
       $no = array('mystp_scroll' ,'mystp_popup','mystp','mystp_img');
       $jsall = $wp_scripts->registered;
      if( is_array( $jsall) && count( $jsall) ){
          foreach( $jsall as $_vjs){
              if( !in_array( $_vjs->handle , $no)) {
                if( !preg_match('#^jquery#isU' , $_vjs->handle ))
                 wp_deregister_script( $_vjs->handle );
              }//if not in  plugin
          } // foreach
      }//if array
    } //js load plugin
  }

  function css_remove(){
    $action = isset( $_GET['action']) ? trim( $_GET['action']) : '';

    if( $action == 'mystp_proxy')
   {
    global $wp_styles;
    $cssall = array();
    $cssall = $wp_styles->registered;
    $no = array('mystp_css','mystp_g_css','mystp_p_css');
    if( is_array( $cssall) && count( $cssall )){
      foreach( $cssall as $_vcss){
         if( !in_array( $_vcss->handle , $no ) ){
          $wp_styles->remove( $_vcss->handle);
         }
      }
    }
   }
  }
    //ajax show
   function ajax_proxy()
  {
    include ('show.php');
    ob_start();
    $view = new msp_show();
    $html = $view->doAction();
    wp_enqueue_style('mystp_css');
    wp_enqueue_style('mystp_p_css');
    wp_enqueue_script("jquery");
    wp_enqueue_script('mystp');
    wp_enqueue_script("mystp_scroll");
    ob_end_flush();
    $body_id = 'media-upload';
    print msp::iframe( array($view,'out'),'image' );
   exit();
  }


   //add the  menu button
   public function admin_menu(){
	 //add_options_page('mystockphoto',__('Free Stock Photos', MYSTP_TEXT_DOMAIN), 'manage_options',MYSTP_OPTIONS_KEY, array(__CLASS__, 'options_page'));
  }

    // add settings link to plugins page
  function plugin_action_links($links) {
    $settings_link = '<a href="options-general.php?page='.MYSTP_OPTIONS_KEY.'" title="'.__( 'Free Stock Photos Plugin Settings', MYSTP_TEXT_DOMAIN).'">'.__('Settings', MYSTP_TEXT_DOMAIN).'</a>';
    array_unshift($links, $settings_link);
    return $links;
  }

   // all settings are saved in one option key
  function register_settings() {
    register_setting( MYSTP_OPTIONS_KEY, MYSTP_OPTIONS_KEY, array(__CLASS__, 'sanitize_settings'));

  } // register_settings

  // sanitize settings on save
  function sanitize_settings($values) {
    $old_options = get_option(MYSTP_OPTIONS_KEY);

    foreach ($values as $key => $value) {
      switch ($key) {
        case 'Maxtry':
            $values[$key] = intval($value) < 2 ? self::$Maxtry : (intval($value) > 15 ? self::$Maxtry : intval($value));
           break;
      } // switch
    } // foreach

    return array_merge($old_options, $values);
  }

   // add media button
  function media_button($editor) {
	   $post_id = isset( $_REQUEST['post_id'] )? intval( $_REQUEST['post_id'] ) : 0;
	    if (!$post_id) {
        global $post_ID, $temp_ID;
        $post_id = (int) (0 == $post_ID ? $temp_ID : $post_ID);
    }
  $mystp_btn  = '<a href="'.admin_url('admin-ajax.php?action=mystp_proxy&fpage=index');
	$mystp_btn .= $post_id ? '&post_id='.$post_id : '';
	$mystp_btn .= '"  id="mystp-button" class="mystp_popup " title="'.__('My Stock Photos - Download Thousands of pictures for Free', MYSTP_TEXT_DOMAIN).'"> <span class="mystp-button-pop"></span></a>';

   return $editor.$mystp_btn;
  } // media_button

   // admin enqueue
  function admin_enqueues() {
    if (strpos($_SERVER['REQUEST_URI'], 'show.php') !== false) {
      echo '<script type="text/javascript">if(typeof(ajaxurl) ==  "undefined" ){ var ajaxurl = "' . admin_url('admin-ajax.php') . '";}</script>';
     }
 }

    //pop up div js event bind
   function popup(){
    echo "<style> .mystp-button-pop {background: url('";
    echo mystp_pg_url('lib/images/button.png', MYSTP_FILE ) ;
    echo "') no-repeat top left;display: inline-block;vertical-align: text-top;margin: 0 2px;width:122px;height:18px;margin-bottom:10px;}</style>";
    echo "<script type='text/javascript'>";
    echo " jQuery(document).ready( function($){ $('.mystp_popup').colorbox({iframe:true, innerWidth:1000 , innerHeight:780 , title:false, closeButton:false, scrolling:false ,opacity:0.65 });}); ";
    echo "</script>";
   }

  // check if user has the minimal WP version required by the plugin
  function check_wp_version($min_version) {
    if (!version_compare(get_bloginfo('version'), $min_version,  '>=')) {
        add_action('admin_notices', array(__CLASS__, 'min_version_error'));
    }
  } // check_wp_version

   // min version error
  function min_version_error() {
    echo '<div class="error"><p>',__('My Stock photo', MYSTP_TEXT_DOMAIN),'<b>',__('This plugin support WP version ', MYSTP_TEXT_DOMAIN),'<b>',__(' or higher version , Your wordpress version is', MYSTP_TEXT_DOMAIN),get_bloginfo('version'),__('Please <a href="update-core.php">update</a> Your wordpress.' , MYSTP_TEXT_DOMAIN);
  } // min_version_error

  function uninstall() {
    delete_option( MYSTP_OPTIONS_KEY );
  }

  // set default settings
  function default_settings($force = false) {
    $defaults = array('page_length' => '25',
                      'Maxtry' => '6'
                );

    $options = get_option( MYSTP_OPTIONS_KEY );

    $options = get_option( MYSTP_OPTIONS_KEY);
    if(empty($options['Maxtry'])) {
      $options['Maxtry'] = self::$Maxtry;
      update_option(MYSTP_OPTIONS_KEY, $options);
    }
  } // default_settings

}
<?php
 define('MYSTP_CORE_VERSION', '1.0');
 define('MYSTP_OPTIONS_KEY', 'mstp_options');
 define('MYSTP_CACHE_LIST','mystp_cache_list');
 define('MYSTP_WP_MIN', '3.0');
 define('MYSTP_VERSION','1');
 define('MYSTP_APPNAME','Free Stock Photos');
 define('MYSTP_IMG_PP', 100);
 define('MYSTP_IMG_LIMIT', 1000);
 define('MYSTP_PAGES_LIMIT', 10);
 define('MYSTP_TEXT_DOMAIN','mystockphoto');
 define('MYSTP_DIR', plugin_dir_url( dirname(dirname(__FILE__))) );
 define('MYSTP_PATH',dirname(dirname(dirname( __FILE__ ))) . '/');
 define('MYSTP_FILE', dirname(dirname(dirname( __FILE__ ))) . '/free-stock-photos.php');
 load_plugin_textdomain( MYSTP_TEXT_DOMAIN, false, dirname( plugin_basename( MYSTP_DIR ) ) );


if(!function_exists('MYSTP_G_host') ){
 function  MYSTP_G_host(){
    $url='http://';
    if(isset($_SERVER['HTTPS']) && ( strtolower($_SERVER['HTTPS'])=='on'  || '1' == $_SERVER['HTTPS'])) {
        $url='https://';
    }
    else if( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ){
       $url ='https://';
    }
    if($_SERVER['SERVER_PORT']!='80'){
        $url.=$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
    }else{
        $url.=$_SERVER['SERVER_NAME'];
    }
    return $url;
 }
}

if( !function_exists('mystp_pg_url')) {
function mystp_pg_url($path = '', $plugin = '') {
          $pgdir =  dirname(dirname(__FILE__));
          $pgdir = dirname(dirname( $pgdir ));
          $pgdir =  str_replace('\\' ,'/', $pgdir );

          $mu_plugin_dir = WPMU_PLUGIN_DIR;
          foreach ( array('path', 'plugin', 'mu_plugin_dir') as $var ) {
                  $$var = str_replace('\\' ,'/', $$var); // sanitize for Win32 installs
                  $$var = preg_replace('|/+|', '/', $$var);
          }
           $pabs = str_replace( '\\', '/', ABSPATH );
           $url = MYSTP_G_host(). str_replace( $pabs  , '/', $pgdir );

          if ( !empty($plugin) && is_string($plugin) ) {
                  $folder = dirname(plugin_basename($plugin));
                   if ( '.' != $folder )
                           $url .= '/' . ltrim($folder, '/');
          }

           if ( $path && is_string( $path ) )
                  $url .= '/' . ltrim($path, '/');

        return apply_filters( 'mystp_pg_url', $url, $path, $plugin );
  }
}

class msp {
    protected  $oRequest;
    protected  $cookies = array();
    protected  $cookieName = 'mstpcity';
    protected static  $Maxtry = 6;
    protected  $page_length = 25;
    protected  $webUrl = 'http://www.mystockphoto.com/api/';

     public function __construct() {
      $this->oRequest = new WP_Http;
      $this->cookies = isset($_COOKIE[$this->cookieName]) ? unserialize(stripslashes($_COOKIE[$this->cookieName])) : array();
      $options = get_option( MYSTP_OPTIONS_KEY);
      $this->Maxtry = isset($options['maxtry']) ? intval($option['maxtry']) : 6;
      unset($options);
    }

  public function error( $msg ){
     echo "<style type=\"text/css\">
   .error {
     border: 1px solid;
     margin: 10px 0px;
     padding:15px 10px 15px 50px;
    background-repeat: no-repeat;
    background-position: 10px center;
    }
   .error {
    color: #D8000C;
    background-color: #FFBABA;
   }
   </style> ";

   echo '<div class="error"><p>'.$msg.'</p></div>';
  }

      public function request($url,$args=array()) {
        $k = self::$Maxtry ? self::$Maxtry : 6;
        //add language option parameter
        $url = $url. (preg_match('/\?/',$url) ? '&' : '?')
                    .'language='.get_bloginfo('language');

        do {

            $defaults = array('cookies' => $this->cookies);
            $args = wp_parse_args( $args, $defaults );

            $r = $this->oRequest->request($url,$args);
        } while ($k-- && ($r instanceof Wp_Error));

        if ($r instanceof Wp_Error) {
            return __('service unavailable!', MYSTP_TEXT_DOMAIN);
        }

        if (is_array($r['cookies']) && count($r['cookies'])) {
            $this->cookies = $r['cookies'];
        }

        setcookie($this->cookieName, serialize($this->cookies));
	   return $r['body'];
    }

public static function iframe( $content_func /* ... */ ){
  $admin_html_class = ( is_admin_bar_showing() ) ? 'wp-toolbar' : '';
?>
<!DOCTYPE html>
<!--[if IE 8]>
<html xmlns="http://www.w3.org/1999/xhtml" class="ie8 <?php echo $admin_html_class; ?>" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" class="<?php echo $admin_html_class; ?>" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e(MYSTP_APPNAME, MYSTP_TEXT_DOMAIN); ?></title>
<?php

// Check callback name for 'media'
if ( ( is_array( $content_func ) && ! empty( $content_func[1] )  )
  || ( ! is_array( $content_func ) ) )
wp_enqueue_style( 'ie' );
?>
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {'url':'<?php echo SITECOOKIEPATH; ?>','uid':'<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>','time':'<?php echo time(); ?>'};
var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
//]]>
</script>
<?php
do_action('admin_print_styles');
do_action('admin_print_scripts');
do_action('admin_head');

?>
</head>
<body<?php if ( isset($GLOBALS['body_id']) ) echo ' id="' . $GLOBALS['body_id'] . '"'; ?> class="wp-core-ui no-js">
<script type="text/javascript">
document.body.className = document.body.className.replace('no-js', 'js');
</script>
<?php
  $args = func_get_args();
  $args = array_slice($args, 1);
  call_user_func_array($content_func, $args);

  do_action('admin_print_footer_scripts');
?>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
</body>
</html>
<?php

    }
 }
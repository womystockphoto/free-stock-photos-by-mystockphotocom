<?php
 require_once 'config.php';

/*global $wp_scripts;
var_dump( $wp_scripts->registered );
exit(); */
 class msp_show extends msp{
	protected $html = '';

   public function __construct() {
		parent::__construct();
	}

	public function __destruct() {

	}

	public function out() {
		print $this->html;
	}

	public function doAction() {
		$action = isset($_REQUEST['fpage']) ? trim($_REQUEST['fpage']) : 'index';
		$params = $_GET + $_POST;
		$this->html = '';
		switch($action) {
			case 'index': $this->html = $this->index($params);break;
			case 'search': $this->html = $this->search($params);break;
			case 'cate': $this->html = $this->cate( $params );break;
			case 'detail': $this->html = $this->detail( $params );break;
			case 'resize': $this->html = $this->resize( $params );break;
			case 'show': $this->html = $this->show(  $params );break;
			case 'download':$this->html = $this->download( $params );break;
		}

		return $this->html;
	}


  public function cate( $param ){
     wp_register_script('jquery-lazyload',mystp_pg_url('lib/js/jquery.lazyload.js', MYSTP_FILE ,array('jquery'),'2.7',true));
	 wp_enqueue_script( 'jquery-lazyload' );

     $_param = array();
     $_param['npage'] = 'cate';
     $_param['page'] = isset( $param['page']) ? intval( $param['page'] ) : 1;
     $_param['category'] = isset( $param['category']) ? esc_attr( $param['category']) : '';
     $_param['post_id'] = 0;

     $post_id = isset( $_GET['post_id']) ? intval( $_GET['post_id']) : 0;
     $_param['post_id'] = $post_id;

     $_url=http_build_query($_param);
     $cnt=$this->request( $this->webUrl.'listing?'.$_url);
   	 $js_cnt=json_decode($cnt,true);

     $pagestr = '';
     $images = array();
     $now = 1;
     $to = 1;
     $all =  1;
     if( $js_cnt && isset( $js_cnt['files'])){
     	 $images = $js_cnt['files'];
     	 $now =1 + ($js_cnt['current_page'] -1 )*$js_cnt['page_length'];
     	 $to = $js_cnt['current_page'] * $js_cnt['page_length'];
     	 $all =$js_cnt['total_pages'] * $js_cnt['page_length'] ;
     	 $pages = $_param;
     	 unset($pages['page']);
         $pagestr = $this->pagestr(admin_url('admin-ajax.php?action=mystp_proxy&fpage=cate'), $js_cnt['current_page'] , $js_cnt['total_pages'] , $_param , false);
     	 unset( $js_cnt['files']);
     }
    require_once "list.tpl.php";
	return $_maintpl;
  }

  public function download( $params )
   {
   	$ret = array("code"=>0,"msg"=>"","data"=>NULL);
    $post_id   = trim($_POST['post_id']);
    $desc = trim( $_POST['desc'] );
    $url = trim( $_POST['url']);

    $ret["msg"] = __('Server Busy!',MYSTP_TEXT_DOMAIN);
    $tmp = download_url( $url );
    if (is_wp_error($tmp)) {
     $ret["msg"] = __('file-error' , MYSTP_TEXT_DOMAIN);
     echo json_encode( $ret );
     exit();
    }

    preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/isu', $url , $matches);
    if (!isset($matches[0])) {
      $ret["msg"] = __(' Image format warong', MYSTP_TEXT_DOMAIN);
      echo json_encode( $ret );
     exit();
    }

    $file_array['name'] = basename($matches[0]);
    $file_array['tmp_name'] = $tmp;

    if (is_wp_error($tmp)) {
      @unlink($file_array['tmp_name']);
      $file_array['tmp_name'] = '';
      $ret["msg"] = __('error storing temporarily', MYSTP_TEXT_DOMAIN);
      echo json_encode( $ret );
     exit();
    }

    $id = media_handle_sideload($file_array, $post_id, $desc);

    if ( is_wp_error($id) ) {
      @unlink($file_array['tmp_name']);
       $ret["msg"] = __('error storing permanently', MYSTP_TEXT_DOMAIN);
       echo json_encode( $ret );
      exit();
    }
    $imgurl = wp_get_attachment_url( $id );
    $ret['code'] = 1;
    $ret['data'] = $imgurl;
    echo json_encode( $ret );
    exit();
  }

  public function show( $params ){
  	 wp_register_style('mystp_p_css', mystp_pg_url('lib/css/spectrum.css',MYSTP_FILE));

      $post_id = isset( $params['post_id']) ?  intval( $params['post_id'] ) : 0;
      $images = array();
      $images['view_url'] = $params['view_url'];
      $images['img_url'] = $params['img_url'];
      $images['img_title'] = $params['img_title'];
      $images['img_align'] = $params['img_align'];
      $images['img_width'] = $params['img_width'];
      $images['img_height'] = $params['img_height'];
      $images['img_cate'] = $params['img_cate'];
      $images['img_border'] = $params['img_border'];
      $images['img_border_color'] = $params['img_border_color'];

     require_once "show.tpl.php";
	 return $_maintpl;
  }
  public function resize( $params ){
     $data = array();
     $ret = array("code"=>0,"msg"=>"","data"=> NULL);
     $ret['msg'] = __('Service Busy,try again later' ,MYSTP_TEXT_DOMAIN);

     $data['id'] = isset( $params['id']) ?  intval( $params['id'] ) : 0;
     $data['aspect_ratio'] = isset( $params['aspect_ratio']) ? $params['aspect_ratio'] : 'original';
     $data['width'] = isset( $params['Width']) ?  intval( $params['Width']) : 100;

     $_url = '';
     $_url=http_build_query( $data );
	 $cnt=$this->request( $this->webUrl.'resize?'.$_url);
	 $js_cnt = json_decode( $cnt , true);

     if( $js_cnt && $js_cnt['success']){
        $ret['code'] = 1;
        $ret['msg'] = 'OK';
        $ret['data'] = $js_cnt;
     }
     else{
     	$ret['msg'] =   __('Resized image  create failed!' ,MYSTP_TEXT_DOMAIN);
     }
     echo  json_encode($ret) ;
     exit();
  }

  public function detail( $params = array()){
	//load additional js /css
	 wp_register_style('mystp_p_css', mystp_pg_url('lib/css/spectrum.css',MYSTP_FILE));
	 wp_register_script('mystp_scroll',mystp_pg_url('lib/js/slide.js', MYSTP_FILE ,array('jquery'),'2.7',true));
     wp_register_script('jquery-spectrum',mystp_pg_url('lib/js/jquery.spectrum.js', MYSTP_FILE ,array('jquery'),'2.7',true));
     wp_register_script('mystp_img',mystp_pg_url('lib/js/img.js', MYSTP_FILE ,array('jquery'),'2.7',true));

     wp_enqueue_script( 'jquery-spectrum' );
     wp_enqueue_script( 'mystp_scroll');
	 wp_enqueue_script( 'mystp_img' );

	 $_parm = array();
	 $_parm['id'] = isset( $params['imgId']) ? intval( $params['imgId']) : 0;

	 $search = array();
     $npage = isset( $params['npage']) ? trim( $params['npage']) : 'search';
     if( 'search' == $npage) {
       $search['fpage'] = 'search';
	   $search['r'] = array();
	   $search['r']['page'] =isset( $params['page']) ?  intval( $params['page']) : 1;
	   $search['r']['color'] = isset( $params['color']) ? trim( $params['color']) : 'all';
	   $search['r']['keyword'] = isset( $params['keyword'] ) ?  strip_tags( $params['keyword']) :'';
	   $search['r']['post_id'] = isset( $params['post_id']) ?  intval( $params['post_id']) : 0;
	}
	else{
      $search['fpage'] = 'cate';
      $search['page'] = isset( $params['page']) ? intval( $params['page'] ) : 1;
      $search['category'] = isset( $params['category']) ? esc_attr( $params['category']) : '';
      $search['post_id'] = isset( $params['post_id']) ?  intval( $params['post_id']) : 0;
	}

	 $post_id = isset( $params['post_id']) ? intval( $params['post_id'] ) : 0;
   $search['post_id'] = $post_id;
	 $_surl = http_build_query( $search);
	 $_url = http_build_query($_parm);
	 $cnt = $this->request( $this->webUrl.'photo?'.$_url);
	 $js_cnt = json_decode( $cnt , true );

	 $_images = array();
	 $_images = $js_cnt && isset( $js_cnt['images']) ? (array)$js_cnt['images'] : NULL;
	 if( $images )
	 unset( $js_cnt['images']);


	 $images = array();
	 $images['original'] =  $_images && isset( $_images[0])  ?  $_images[0] : NULL;
	 $images['4:3'] =  $_images && isset( $_images[1])  ?  $_images[1] : NULL;
	 $images['1:1'] =  $_images && isset( $_images[2])  ?  $_images[2] : NULL;

	 require_once "detail.tpl.php";
	 return $_maintpl;
  }
  public function search($params=array()) {
	//lazy load image
	wp_register_script('jquery-lazyload',mystp_pg_url('lib/js/jquery.lazyload.js', MYSTP_FILE ,array('jquery'),'2.7',true));
	wp_enqueue_script( 'jquery-lazyload' );

	$_parm = array();
	$_parm = isset( $params['r']) ? (array) $params['r'] : NULL;
	$post_id = isset( $_GET['post_id']) ? intval( $_GET['post_id']) : 0;
    $search = array();
	$search['r'] = array();
	$search['r']['page'] =isset( $_parm['page']) ?  intval( $_parm['page']) : 1;
	$search['r']['color'] = isset( $_parm['color']) ? trim( $_parm['color']) : 'all';
	$search['r']['keyword'] = isset( $_parm['keyword'] ) ?  strip_tags( $_parm['keyword']) :'';
	$search['r']['post_id'] =  $post_id;

    $_parm['post_id'] = $post_id;
	$color = $_parm&&isset( $_parm['color']) ? trim( $_parm['color']) : 'all';

	$_url=http_build_query($_parm);
	$cnt=$this->request( $this->webUrl.'search?'.$_url);
   	$js_cnt=json_decode($cnt,true);

	$pics = array();
	$pics = $js_cnt && isset( $js_cnt['files']) ? (array)$js_cnt['files'] : NULL;
	$pagestr = '';
	if( $js_cnt && isset($js_cnt['total_pages']))
    {
     $pagestr = $this->pagestr(admin_url('admin-ajax.php?action=mystp_proxy&fpage=search&npage=search&post_id='.$post_id), $js_cnt['current_page'] , $js_cnt['total_pages'] , $search);
	}

	if( $pics )
	unset( $js_cnt['files']);
	require_once "search.tpl.php";
	return $_maintpl;
  }

   //search resualt pagine
   protected function pagestr( $url = '' , $pnow , $pall  , $param = array() , $s = true){
   	    $url .= '&';
        $strpage = '';
        $arrpage = array();
        if( $pnow < 1)
        	$pnow = 1;
        $arrpage['now'] = $pnow;
        if( $pall < 1)
        	$pall = 1;
        $arrpage['next'] = (( $pnow + 1) > $pall) ? 0 : ( 1 + $pnow); // 0 for no next page
        $arrpage['before'] =  (( $pnow -1)  < 1 ) ? 0 : ( $pnow -1 ); // 0  for no before page

      $gap = array();
      $gap['before'] = $arrpage['now'] - 1;
      $gap['next'] =  $pall - $arrpage['now'];

      $param['r']['page'] = $arrpage['before'];
       if( !$s )
          $param['page'] = $arrpage['before'];
      $strpage  = $arrpage['before'] ?  ( "<a class=\"btn prev\" href=\"".$url .http_build_query( $param )."\">&lt;</a>" ):'<span class="btn prev disabled">&lt;</span>';
      //page start
      if( $gap['before'] <= 4 )
      {
      	  for( $ic = 1 ;  $ic < $arrpage['now'] ; $ic ++)
      	  {
      	  	if( $s )
            $param['r']['page'] = $ic;
           else
           	$param['page'] = $ic;
            $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$ic."</a>" ;
           } //for
         $param['r']['page'] = $ic;
          if( !$s )
              	$param['page'] =  $ic;
         $strpage .= "<span class=\"current\">".$ic."</span>";

          $ic = $arrpage['now']  + 1;
         if( 10 < $pall)
         {
            for( $ic ; $ic < 8; $ic ++){
               $param['r']['page'] = $ic;
               if(!$s)
               	$param['page'] = $ic;
               $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$ic."</a>" ;
            } //for
            $strpage .= "<em>...</em>";
            $param['r']['page'] = $pall -1;
            if( !$s )
               $param['page'] = $pall -1;
            $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
            $param['r']['page'] = $pall ;
            if( !$s)
              $param['page'] = $pall;
            $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
         }
         else
         {
           for( $ic ;  $ic <= $pall ; $ic ++)
      	  {
            $param['r']['page'] = $ic;
            if( !$s )
            	$param['page'] = $ic;
            $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
           } //for
         }
      }
      else
      {
         if( $pall > 10 ){
            if( $gap['next'] > 5){
              $param['r']['page'] = 1;
              if( !$s )
              	$param['page'] = 1;
              $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">1</a>" ;
              $param['r']['page'] = 2 ;
              if( !$s )
              	$param['page'] = 2;
              $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">2</a>" ;
              $strpage .= "<em>...</em>";
              $param['r']['page'] = $arrpage['now'] - 1;
              if( !$s )
              	$param['page'] = $arrpage['now'] - 1;
              $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
              $strpage .=  "<span class=\"current\">".$arrpage['now']."</span>";
              $param['r']['page'] = $arrpage['now'] + 1;
               if( !$s )
              	$param['page'] =   $arrpage['now'] + 1;
              $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
              $param['r']['page'] = $arrpage['now'] + 2 ;
                if( !$s )
              	$param['page'] =   $arrpage['now'] + 2;
              $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
              $strpage .= "<em>...</em>";
              $param['r']['page'] = $pall - 1;
                if( !$s )
              	$param['page'] =   $pall - 1;
              $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
              $param['r']['page'] = $pall ;
               if( !$s )
              	$param['page'] =   $pall;
              $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
            }
            else
            {
            	 for( $ic = 1 ; $ic < (8 - $gap['next']); $ic ++) {
            	 	$param['r']['page'] = $ic;
            	 	 if( !$s )
              	    $param['page'] =   $ic;
                    $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$ic."</a>" ;
            	 }
                 $strpage .= "<em>...</em>";
                 $param['r']['page'] = $arrpage['now'] - 1;
                  if( !$s )
              	$param['page'] =  $arrpage['now'] - 1;
                 $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
                 $strpage .=  "<span class=\"current\">".$arrpage['now']."</span>";
                 $ic = $arrpage['now'] + 1;
                 for( $ic ; $ic <= $pall ; $ic ++ ){
                     $param['r']['page'] = $ic;
                      if( !$s )
              	       $param['page'] = $ic;
                     $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$param['r']['page']."</a>" ;
                 }

            }
         }
         else
        {
            for( $ic ;  $ic <= $pall ; $ic ++)
      	  {
            $param['r']['page'] = $ic;
             if( !$s )
              	$param['page'] =  $ic;
            if( $arrpage['now'] == $ic )
            {
              $strpage .= "<span class=\"current\">".$ic."</span>";
            }
            else{
            $strpage .=  "<a  href=\"".$url .http_build_query( $param )."\">".$ic."</a>" ;}
           } //for
        } //page less than 10
      }
      $param['page'] = $arrpage['next'];
      if( $s )
        $param['r']['page'] = $arrpage['next'];
     $strpage .= $arrpage['next'] ? ( "<a class=\"btn next\" href=\"".$url .http_build_query( $param )."\">&gt;</a>" ):"<span class=\"btn next disabled\">&gt;</span>";
      return $strpage;
   }

  public function index($params=array()) {
  	   $post_id = isset( $_GET['post_id']) ? intval( $_GET['post_id']) : 0;
  	   $_clist = array('Abstract','Animals/Wildlife','The Arts','Backgrounds/Textures','Beauty/Fashion','Buildings/Landmarks','Business/Finance','Editorial','Education',
                                   'Food and Drink','Healthcare/Medical','Holidays','Illustrations/Clip-Art','Industrial','Miscellaneous','Model Released Only','Nature','Objects',
                                    'Parks/Outdoor','People','Religion','Signs/Symbols','Sports/Recreation','Technology','Transportation','Vectors','Science'
              	 	           );
  	   $cates = array();
  	   //category cache
       $bdir  = MYSTP_PATH .'cache/';
       $cates = file_exists( $bdir .MYSTP_CACHE_LIST.'.cache') ? file_get_contents( $bdir .MYSTP_CACHE_LIST.'.cache') : false;
       if( $cates )
       {
           $cates = unserialize( $cates );
         if( ($t - $cates['t']) > 432000 )
            $cates = false;
       }
      if( !$cates ){
        $cnt=$this->request( $this->webUrl.'category');
   	    $js_cnt=json_decode($cnt,true);
   	    if( $js_cnt && isset( $js_cnt['categories'])){
          $cates = $js_cnt['categories'];
          $cates['t'] = time();
          $cacheCnt = serialize( $cates );
          file_put_contents($bdir.MYSTP_CACHE_LIST.'.cache', $cacheCnt );
   	    }
      }
	   require_once  "index.tpl.php";
	   return $_maintpl;
	}

 }
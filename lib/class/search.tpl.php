<?php
 ob_start();
?>
 <div class="modal search-modal">
	<button type="button" class="close mystp_close">&times;</button>
    <div class="modal-body">
		<div class="search-caption fn-clear">
		<form action="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=search&post_id='.$post_id);?>" method="POST" >
			<div class="homepage-search">
					<input type="text" name="r[keyword]" class="search-input" value="<?php echo $js_cnt['keyword'];?>"  />
					<button class="search-submit" type="submit">Search</button>
			</div>
			<!-- homepage-search end -->

			<select class="form-control" name="r[color]">
				<option value="0" <?php if( '0' == $color ) {?> selected <?php }?> ><?php _e('All Color' , MYSTP_TEXT_DOMAIN); ?></option>
				<option value="red" <?php if( 'red' == $color ) {?> selected <?php }?>  > <?php _e('Red',MYSTP_TEXT_DOMAIN); ?></a>
				<option value="orange" <?php if( 'orange' == $color ) {?> selected <?php }?> > <?php _e('Orange',MYSTP_TEXT_DOMAIN); ?></a>
				<option value="yellow" <?php if( 'yellow' == $color ) {?> selected <?php }?>><?php _e('Yellow',MYSTP_TEXT_DOMAIN); ?></a>
				<option value="green" <?php if( 'green' == $color ) {?> selected <?php }?>><?php _e('Green',MYSTP_TEXT_DOMAIN); ?></a>
				<option value="aqua" <?php if( 'aqua' == $color ) {?> selected <?php }?>><?php _e('Aqua',MYSTP_TEXT_DOMAIN); ?></a>
				<option value="blue" <?php if( 'blue' == $color ) {?> selected <?php }?>><?php _e('Blue', MYSTP_TEXT_DOMAIN); ?></a>
				<option value="purple" <?php if( 'purple' == $color ) {?> selected <?php }?>><?php _e('Purple', MYSTP_TEXT_DOMAIN); ?></a>
				<option value="pink" <?php if( 'pink' == $color ) {?> selected <?php }?>><?php _e('Pink', MYSTP_TEXT_DOMAIN); ?></a>
				<option value="brown" <?php if( 'brown' == $color ) {?> selected <?php }?>><?php _e('Brown', MYSTP_TEXT_DOMAIN); ?></a>
				<option value="white" <?php if( 'white' == $color ) {?> selected <?php }?>><?php _e('White', MYSTP_TEXT_DOMAIN); ?></a>
				<option value="black" <?php if( 'black' == $color ) {?> selected <?php }?>><?php _e('Black', MYSTP_TEXT_DOMAIN); ?></a>
			</select>
		  </form>
		</div>
		<!-- // search-caption end -->

		<div class="search-wrapper">
			<ul class="thumbnail-group fn-clear">
               <?php
                 if($pics && is_array( $pics ) && count( $pics)) {
                 	foreach( $pics as $_v) {
                ?>
				 <li>
					<a  href="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=detail&npage=search&imgId='.$_v['id']).'&'.$_url;?>"  >
						<span class="thumbnail-img">
						 <img  class="mystp_li_img" style="margin: -<?php echo $_v['thumb_height']/2;?>px 0 0 -<?php echo $_v['thumb_width']/2;?>px;"  title="<?php echo $_v['title'];?>" src="<?php echo $_v['thumb_url'];?>" alt="<?php echo $_v['title'];?>" />
						</span>
					</a>
				 </li>
               <?
                  }
                }
               ?>
			</ul>
			<!-- // thumbnail-group end -->
		</div>
		<!-- // search-wrapper end -->
    </div>
	<div class="modal-footer">
		<a href="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=index&post_id='.$post_id);?>" class="btn btn-primary fn-left"><?php _e('Back to Homepage', MYSTP_TEXT_DOMAIN);?></a>

		<div class="pagination fn-right">
		 <?php  echo $pagestr;?>
		</div>
		<!-- // pagination end -->
    </div>
 </div>
<!-- modal.homepage-modal end -->
<script type="text/javascript">
jQuery(document).ready( function( $ ) {
   $('img .mystp_li_img').lazyload();
 });
</script>
<?php
 $_maintpl=ob_get_contents();
ob_end_clean();
?>
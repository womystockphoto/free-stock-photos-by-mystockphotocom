<?php
 ob_start();
?>
<div class="modal listing-modal">
	<button type="button" class="close mystp_close">&times;</button>
    <div class="modal-body">
		<div class="breadcrumbs fn-clear">
			<div class="fn-left">
				<a href="http://www.mystockphoto.com/" target="_blank" alt="Goto  myStokcPhoto.com">myStockPhoto.com</a><em>&gt;</em><span><?php echo esc_html( $_param['category']);?></span>
			</div>
			<p><?php echo $now,'-',$to;?> of <?php echo number_format( $all );?> photos</p>
		</div>
		<!-- breadcrumbs end -->

		<div class="listing-wrapper">
			<ul class="thumbnail-group fn-clear">
               <?php
                 if( $images && is_array( $images) && count( $images)){
                 	 foreach( $images as $_vm){
               ?>
				<li>
					<a href="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=detail&imgId='.$_vm['id']).'&'.$_url;?>">
						<span class="thumbnail-img">
						  <img class="mystp_li_img" style="margin: -<?php echo $_vm['thumb_height']/2;?>px 0 0 -<?php echo $_vm['thumb_width']/2;?>px;" src="<?php echo $_vm['thumb_url'];?>"
						   title="<?php echo $_vm['thumb_title'];?>" alt="<?php echo $_vm['thumb_title'];?>" />
						</span>
					</a>
				</li>
				<?php
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
			<?php echo $pagestr;?>
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
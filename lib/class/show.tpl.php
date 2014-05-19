<?php
 ob_start();
?>

<div class="modal loading-modal fn-hide" style="display:none;" id="m_tip">
    <div class="modal-body">
		<span class="loading"><?php _e('Downloading file...', MYSTP_TEXT_DOMAIN);?></span>
    </div>
	 <div class="modal-footer">
		<a href="javascript:void(0);" id="mystp_cancel" class="btn btn-default"><?php _e('Cancel', MYSTP_TEXT_DOMAIN);?></a>
    </div>
 </div>
<!-- modal.loading-modal end -->

<div class="modal create-modal" id="m_model">
	<button type="button" class="close mystp_close">&times;</button>
    <div class="modal-body">
		<div class="detail-preview fn-clear" id="m_preview"
		  data-cate="<?php echo $images['img_cate'];?>" data-pid="<?php echo $post_id;?>"
		       data-align="<?php echo $images['img_align'];?>" data-title="<?php echo $images['img_title'];?>"
               date-url ="<?php echo $images['view_url'];?>"
		      >
			<div class="add-image-img" style="width:<?php echo $images['img_width'];?>px;padding: 4px;border: <?php echo $images['img_border'];?>px solid <?php echo $images['img_border_color'];?>;border-radius: 4px;box-shadow: 0 0 2px rgba(0,0,0,0.1);margin: 0 auto;">
				<a href="<?php echo $images['view_url'];?>" target="_blank" title="<?php echo $images['img_title'];?>">
				  <img style="display: block;width: <?php echo $images['img_width'];?>px; height:<?php echo $images['img_height'];?>px;overflow: hidden;" src="<?php echo $images['img_url'];?>" alt="<?php echo $images['img_title'];?>" />
				 </a>
				<p style="width: 100%;height: 28px;line-height: 28px;overflow: hidden;font-size: 13px;color: #666;">
				 <a style="float:right;" href="<?php echo $images['view_url'];?>" target="_blank">MyStockPhoto.com</a>
                  <?php echo wp_trim_words($images['img_title'],25,'...');?>
				 </p>
			</div>
		</div>
		<!-- detail-preview end -->
    </div>
	 <div class="modal-footer">
		<div class="create-radio fn-left">
			<label class="radio" for="host">
				<input type="radio" name="download" id="host" value="0" checked="checked" />
				Host the photo on MyStockPhoto.com (save bandwidth)
			</label>
			<label class="radio" for="host2">
				<input type="radio" name="download" id="host2" value="1" />
				<?php _e('Download and host the photo on my server', MYSTP_TEXT_DOMAIN);?>
			</label>
		</div>
		<a href="javascript:void(0);" id="mystp_add" class="btn btn-primary fn-right"><?php _e('Add to Page', MYSTP_TEXT_DOMAIN);?></a>
    </div>
 </div>
<?php
 $_maintpl=ob_get_contents();
ob_end_clean();
?>
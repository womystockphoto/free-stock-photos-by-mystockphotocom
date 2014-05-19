<?php
 ob_start();
?>
<div class="modal loading-modal fn-hide" id="m_tip"  >
    <form method="POST" action="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=show');?>" id="form1">
      <input type="hidden" id="frm_view_url" name="view_url" value="<?php echo $js_cnt['local_web_url'];?>">
      <input type="hidden" id="frm_post_id" name="post_id" value="<?php echo $post_id;?>">
      <input type="hidden" id="frm_img_url" name="img_url" value="">
      <input type="hidden" id="frm_img_title" name="img_title" value="<?php echo $js_cnt['local_title'];?>">
      <input type="hidden" id="frm_img_cate" name="img_cate" value="<?php echo $js_cnt['category'];?>">
      <input type="hidden" id="frm_img_width" name="img_width" value="">
      <input type="hidden" id="frm_img_height" name="img_hegiht" value="">
      <input type="hidden" id="frm_img_align" name="img_align" value="center">
      <input type="hidden" id="frm_img_border" name="img_border" value="1">
      <input type="hidden" id="frm_img_border_color" name="img_border_color" value="#ddd">
    </form>
    <div class="modal-body">
		<span class="loading"><?php _e('Generating previews, please wait ...', MYSTP_TEXT_DOMAIN);?></span>
    </div>
	 <div class="modal-footer">
		<a href="javascript:void(0);" id="rz_cancle" class="btn btn-default"><?php _e('Cancel', MYSTP_TEXT_DOMAIN);?></a>
    </div>
 </div>
<!-- modal.loading-modal end -->
<div class="modal listing-modal" id="m_list">
	<button type="button" class="close mystp_close">&times;</button>
    <div class="modal-body">
		<div class="detail-filter fn-clear">
			<div class="alignment fn-left">
				<h5><?php _e('Aspect Radio', MYSTP_TEXT_DOMAIN);?>:</h5>
				<ul class="align-list fn-clear">
					<li>
					    <a id="original" class="aspect" href="javascript:void(0);" data-width="<?php echo $images['original']['width'];?>" data-height="<?php echo $images['original']['height'];?>" data-img="<?php echo  $images['original']['image_url'];?>" >
					      <?php _e('Original', MYSTP_TEXT_DOMAIN);?>
					    </a>
					 </li>
					<li>
					      <a id="4:3"  class="current aspect"  data-width="<?php echo $images['4:3']['width'];?>" data-height="<?php echo $images['4:3']['height'];?>"  href="javascript:void(0);" data-img="<?php echo  $images['4:3']['image_url'];?>" >
					        4:3
					       </a>
					 </li>
					<li>
					     <a id="1:1"  class="aspect" href="javascript:void(0);"  data-width="<?php echo $images['1:1']['width'];?>" data-height="<?php echo $images['1:1']['height'];?>"  data-img="<?php echo  $images['1:1']['image_url'];?>" >
					        1:1
					     </a>
					 </li>
				</ul>
			</div>
			<div class="alignment fn-left">
				<h5><?php _e('Alignment', MYSTP_TEXT_DOMAIN )?>:</h5>
				<ul class="align-list fn-clear">
					<li><a id="align-left" class="current" href="javascript:void(0);"><?php _e('Left', MYSTP_TEXT_DOMAIN);?></a></li>
					<li><a id="align-center" href="javascript:void(0);"><?php _e('Center', MYSTP_TEXT_DOMAIN);?></a></li>
					<li><a id="align-right" href="javascript:void(0);"><?php _e('Right', MYSTP_TEXT_DOMAIN);?></a></li>
				</ul>
			</div>
			<div class="size-range fn-left">
				<h5><?php _e('Dimension', MYSTP_TEXT_DOMAIN );?>:</h5>
				<span id="size-max" data-aspect="4:3"><?php $w = intval($images['4:3']['width']); echo $w ? ($w/2) :108;?>px</span>
				<div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" data-W="<?php echo $images['4:3']['width'];?>">
				 <div class="ui-slider-range ui-widget-header" style="left: 0%; width: 50%;"></div>
				</div>
			</div>
			<div class="border-choose fn-right">
				<div class="border-size fn-clear">
					<label><?php _e('Border Size' , MYSTP_TEXT_DOMAIN);?></label>
					<div class="size">
						<input type="number" id="btn_border" class="form-control" value="1" />
					</div>
					<span>px</span>
				</div>
				<div class="border-color fn-clear">
					<label><?php _e('Border Color', MYSTP_TEXT_DOMAIN);?></label>
					<div class="color">
						<input class="colorpicker" val="#ddd"/>
					</div>
				</div>
			</div>
		</div>
		<!-- detail-filter end -->

		<div class="detail-preview fn-clear">
			<div class="add-image-img" style="width: <?php echo $w ? ($w/2) : 108;?>px;padding: 4px;border: 1px solid #ddd;border-radius: 4px;box-shadow: 0 0 2px rgba(0,0,0,0.1);float: left; margin: 5px 15px 0 0;">
				<a href="<?php echo $js_cnt['web_url'];?>" target="_blank" alt="<?php echo $js_cnt['local_title'];?>" title="<?php echo $js_cnt['local_title'];?>">
				    <img style="display: block;width: 100%;overflow: hidden;"
				     src="<?php if(isset($images['4:3'])&&isset( $images['4:3']['image_url'])) {echo $images['4:3']['image_url'];} ?>" <?php if(isset($js_cnt['tags'])&& trim($js_cnt['tags'])) { ?>alt="<?php echo $js_cnt['tags'];?>" <?php } ?> />
				 </a>
				<p style="width: 100%;height: 28px;line-height: 28px;overflow: hidden;font-size: 13px;color: #666;">
				  <a style="float:right;" href="http://<?php if($js_cnt['subdomain']) {echo trim($js_cnt['subdomain']); }else {echo 'www';}?>.mystockphoto.com"  title="<?php echo $js_cnt['site_name'];?>" target="_blank" >MyStockPhoto.com</a><?php echo wp_trim_words($js_cnt['local_title'],25,'...');?>
				</p>
			</div>
			<p style="font-size: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet tempus orci in rutrum. Aliquam orci purus, tempus consectetur hendrerit non, pharetra eu lorem. Vivamus interdum, turpis ac accumsan dignissim, urna ante ultricies tellus, ut eleifend lorem nisl at nisl. Nunc faucibus tincidunt ligula et elementum. Quisque semper ante eget ante laoreet fermentum. In pellentesque fringilla pharetra. Duis ultricies, tellus sed luctus consectetur, lectus lorem facilisis eros, eu ultricies nunc ante quis mauris. Nullam lacinia massa non tellus semper id vestibulum urna viverra. Nam aliquam gravida metus, vitae pretium quam facilisis et. Nam pharetra tempus lacus, sit amet hendrerit purus tempus in. Nulla in sem massa, sit amet consequat magna. Curabitur tincidunt erat quis purus sagittis vehicula. Sed convallis vulputate posuere. Curabitur id justo lacus, id dignissim metus. Cum sociis natoque penatibus et magnisLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet tempus orci in rutrum. Aliquam orci purus, tempus consectetur hendrerit non, pharetra eu lorem. Vivamus interdum, turpis ac accumsan dignissim, urna ante ultricies tellus, ut eleifend lorem nisl at nisl. Nunc faucibus tincidunt ligula et elementum. Quisque semper ante eget ante laoreet fermentum. In pellentesque fringilla pharetra. Duis ultricies, tellus sed luctus consectetur, lectus lorem facilisis eros, eu ultricies nunc ante quis mauris. Nullam lacinia massa non tellus semper id vestibulum urna viverra. Nam aliquam gravida metus, vitae pretium quam facilisis et. Nam pharetra tempus lacus, sit amet hendrerit purus tempus in. Nulla in sem massa, sit amet consequat magna. Curabitur tincidunt erat quis purus sagittis vehicula. Sed convallis vulputate posuere. Curabitur id justo lacus, id dignissim metus. Cum sociis natoque penatibus et magnisLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet tempus orci in rutrum. Aliquam orci purus, tempus consectetur hendrerit non, pharetra eu lorem. Vivamus interdum, turpis ac accumsan dignissim, urna ante ultricies tellus, ut eleifend lorem nisl at nisl. Nunc faucibus tincidunt ligula et elementum. Quisque semper ante eget ante laoreet fermentum. In pellentesque fringilla pharetra. Duis ultricies, tellus sed luctus consectetur, lectus lorem facilisis eros, eu ultricies nunc ante quis mauris. Nullam lacinia massa non tellus semper id vestibulum urna viverra. Nam aliquam gravida metus, vitae pretium quam facilisis et. Nam pharetra tempus lacus, sit amet hendrerit purus tempus in. Nulla in sem massa, sit amet consequat magna. Curabitur tincidunt erat quis purus sagittis vehicula. Sed convallis vulputate posuere. Curabitur id justo lacus, id dignissim metus. Cum sociis natoque penatibus et magnis.</p>
		</div>
		<!-- detail-preview end -->
    </div>
	 <div class="modal-footer">
		<a href="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=search&'.$_surl);?>" class="btn btn-default fn-left">
		   <?php _e('Back to Search', MYSTP_TEXT_DOMAIN);?>
		</a>
		<a href="javascript:void(0);" id="rz_start" data-id="<?php echo $js_cnt['id'];?>" class="btn btn-primary fn-right"> <?php _e('Continue', MYSTP_TEXT_DOMAIN);?></a>
    </div>
 </div>
<!-- modal.homepage-modal end -->
<?php
 $_maintpl=ob_get_contents();
ob_end_clean();
?>
<?php
 ob_start();
?>
<div class="modal homepage-modal">
    <button type="button" class="close mystp_close">&times;</button>
    <div class="modal-body">
		<div class="homepage-mark">
			<div class="homepage-text">
				<h1><?php _e('Download Thousands of pictures for Free', MYSTP_TEXT_DOMAIN);?></h1>
				<h4>Stock photos, illustrations, video and music -handpicked by creatives all over the world.</h4>
			</div>
			<!-- homepage-text end -->

			<div class="homepage-search">
				<form action="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=search&post_id='.$post_id);?>" method="POST" >
				    <input type="hidden" name="r[page]" value="1">
				    <input type="hidden" name="r[color]" value="all">
					<input type="text" name="r[keyword]" class="search-input" value="" placeholder="Enter Keywords" />
					<button class="search-submit" type="submit"><?php _e('Search', MYSTP_TEXT_DOMAIN );?></button>
				</form>
			</div>
			<!-- homepage-search end -->
		</div>
		<!-- homepage-mark end -->

		<div class="homepage-categories">
			<ul class="category-group fn-clear">
			<?php
			   if($cates && is_array($cates) && count($cates)) {
                  foreach( $cates as $_cv){
				?>
			 <li>
			   <a href="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=cate&post_id='.$post_id.'&category='.urlencode( $_cv['category_url']));?>">
			   <?php echo $_cv['category_name'];?>
			  </a>
			 </li>
		    <?php
              }
              }
              else
              {
              	 foreach( $_clist as $_v) {
		     ?>
				<li><a href="<?php echo admin_url('admin-ajax.php?action=mystp_proxy&fpage=cate&post_id='.$post_id.'&category='.urlencode($_v));?>"><?php echo $_v;?></a></li>

			<?php
		      }
			  }?>
			</ul>
		</div>
		<!-- // homepage-categories end -->
    </div>
 </div>
<!-- modal.homepage-modal end -->
<?php
 $_maintpl=ob_get_contents();
ob_end_clean();
?>
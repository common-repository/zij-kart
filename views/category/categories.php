<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zk_wrapper">
<?php	
	if(zijkart::getOption('showsitetitle') == 1){
		echo '<div id="zk_sitetitle">'.zijkart::getOption('sitetitle').'</div>';
	}
	$categories = zijkart::get_d('categories');
	if(!empty($categories)){
		foreach($categories AS $category){
			$image = ($category->photo != '') ? site_url('?page_id='.zijkart::getPageid().'&zkm=category&zktask=getimagebyid&imageid='.$category->categoryid) : ZIJKART_PLUGIN_URL.'includes/images/noimage.png';
			$col = 3;
			$width = (100 / $col);
			$width = 'width:'.$width.'%';
		?>
			<a id="zk_category" style="<?php echo $width; ?>" href="<?php echo site_url('?page_id='.zijkart::getPageid().'&zkm=product&zkl=products&zkcatid='.$category->categoryid); ?>">
				<div class="zk_category_wrapper">
					<div class="zk_cat_image">
						<img class="zk_cat_image" src="<?php echo $image; ?>" />
					</div>
					<div class="zk_cat_data">
						<span class="zk_title"><?php echo $category->categorytitle; ?></span>
						<span class="zk_products"><?php echo __('Products','zijkart').'&nbsp;('.$category->products.')'; ?></span>
					</div>
				</div>
			</a>
		<?php
		}
	}
?>
</div>
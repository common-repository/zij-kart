<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zk_wrapper">
<?php	
	if(zijkart::getOption('showsitetitle') == 1){
		echo '<div id="zk_sitetitle">'.zijkart::getOption('sitetitle').'</div>';
	}
	$brands = zijkart::get_d('brands');
	if(!empty($brands)){
		foreach($brands AS $brand){
			$image = ($brand->photo != '') ? site_url('?page_id='.zijkart::getPageid().'&zkm=brand&zktask=getimagebyid&imageid='.$brand->brandid) : ZIJKART_PLUGIN_URL.'includes/images/noimage.png';
			$col = 3;
			$width = (100 / $col);
			$width = 'width:'.$width.'%';
		?>
			<a id="zk_brand" style="<?php echo $width; ?>" href="<?php echo site_url('?page_id='.zijkart::getPageid().'&zkm=product&zkl=products&zkbrandid='.$brand->brandid); ?>">
				<div class="zk_brand_wrapper">
					<div class="zk_cat_image">
						<img class="zk_cat_image" src="<?php echo $image; ?>" />
					</div>
					<div class="zk_cat_data">
						<span class="zk_title"><?php echo $brand->brandtitle; ?></span>
						<span class="zk_products"><?php echo __('Products','zijkart').'&nbsp;('.$brand->products.')'; ?></span>
					</div>
				</div>
			</a>
		<?php
		}
	}
?>
</div>
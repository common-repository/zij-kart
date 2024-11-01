<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
	wp_enqueue_script( 'zijkart-colorpicker.js', ZIJKART_PLUGIN_URL.'includes/js/colorpicker.js', array(), '1.0.0', true );
    wp_register_style('zijkart-colorpicker', ZIJKART_PLUGIN_URL.'includes/css/colorpicker.css');
    wp_enqueue_style('zijkart-colorpicker');
?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Product Images','zijkart'); ?>
		<a href="admin.php?page=zk_product"><?php echo __('Products','zijkart'); ?></a>
	</div>
	<?php
		$product = zijkart::get_d('product');
		$images = zijkart::get_d('productimages');
		if(!empty($images) && is_array($images)){
			foreach($images AS $image){
				$array[$image->variationcolor][] = $image;
			}
			foreach($array AS $key => $value){
				$variation[][$key] = $value;
			}
		}
		$html = new zijkartHTML();
	?>
	<div id="zijproductwrapper">
		<div class="zijrow">
			<div class="zijrow-part">
				<div class="zijrow-title"><?php echo __('Title','zijkart'); ?></div>
				<div class="zijrow-value"><?php echo $product->title; ?></div>
			</div>
			<div class="zijrow-part">
				<div class="zijrow-title"><?php echo __('Brand','zijkart'); ?></div>
				<div class="zijrow-value"><?php echo $product->brandtitle; ?></div>
			</div>
		</div>
		<div class="zijrow">
			<div class="zijrow-part">
				<div class="zijrow-title"><?php echo __('Category','zijkart'); ?></div>
				<div class="zijrow-value"><?php echo $product->categorytitle; ?></div>
			</div>
			<div class="zijrow-part">
				<div class="zijrow-title"><?php echo __('Price','zijkart'); ?></div>
				<div class="zijrow-value"><?php echo $product->price; ?></div>
			</div>
		</div>
		<div class="zijrow">
			<div class="zijrow-part">
				<div class="zijrow-title"><?php echo __('Quantity','zijkart'); ?></div>
				<div class="zijrow-value"><?php echo $product->quantity; ?></div>
			</div>
			<div class="zijrow-part">
				<div class="zijrow-title"><?php echo __('Status','zijkart'); ?></div>
				<div class="zijrow-value"><?php echo $product->status; ?></div>
			</div>
		</div>
	</div>
	<form method="POST" action="admin.php?page=zk_productimages&zktask=save" enctype="multipart/form-data">
		<div class="zk_variationheading"><?php echo __('Variation One','zijkart'); ?></div>
		<div class="row">
			<?php
				echo $html->label(__('Variation Color','zijkart'),'variation1');
			?>
			<input type="text" name="variation1" id="variation1" style="background:<?php if(isset($variation[0])) echo key($variation[0]); ?>;" value="<?php if(isset($variation[0])) echo key($variation[0]); ?>" />
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Images','zijkart'),'variation1image');
			?>
			<input type="file" name="variation1image[]" id="variation1image" multiple />
			<div class="zk_variationimages">
				<?php
					if(isset($variation[0])){
						foreach($variation[0] AS $v){
							foreach($v AS $photo){
								if($photo->isdefault == 1){
									$defaultlink = 'javascript::void(0);';
									$deletelink = 'javascript::void(0);';
									$defaultclass = 'already';									
								}else{
									$defaultlink = admin_url('admin.php?page=zk_productimages&zktask=makeimagedefault&id='.$photo->id.'&productid='.$photo->productid);
									$deletelink = admin_url('admin.php?page=zk_productimages&zktask=deleteimage&id='.$photo->id.'&productid='.$photo->productid);
									$defaultclass = '';
								}
								?>
								<div class="zijimagewrapper">
									<img src="<?php echo zijkart::$uploadurl.'/'.zijkart::getOption('directory').'/products/product_'.zijkart::get_d('productid').'/'.$photo->photo; ?>" />										
									<a href="<?php echo $defaultlink; ?>" class="<?php echo $defaultclass; ?>"><?php echo __('Make default','zijkart'); ?></a>
									<a href="<?php echo $deletelink; ?>" class="<?php echo $defaultclass; ?>"><?php echo __('Delete image','zijkart'); ?></a>
								</div>
								<?php
							}
						}
					}
				?>
			</div>	
		</div>
		<div class="zk_variationheading"><?php echo __('Variation Two','zijkart'); ?></div>
		<div class="row">
			<?php
				echo $html->label(__('Variation Color','zijkart'),'variation2');
			?>
			<input type="text" name="variation2" id="variation2" style="background:<?php if(isset($variation[1])) echo key($variation[1]); ?>;" value="<?php if(isset($variation[1])) echo key($variation[1]); ?>" />
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Images','zijkart'),'variation1image');
			?>
			<input type="file" name="variation2image[]" id="variation2image" multiple />
			<div class="zk_variationimages">
				<?php
					if(isset($variation[1])){
						foreach($variation[1] AS $v){
							foreach($v AS $photo){
								if($photo->isdefault == 1){
									$defaultlink = 'javascript:void(0);';
									$deletelink = 'javascript:void(0);';
									$defaultclass = 'already';									
								}else{
									$defaultlink = admin_url('admin.php?page=zk_productimages&zktask=makeimagedefault&id='.$photo->id.'&productid='.$photo->productid);
									$deletelink = admin_url('admin.php?page=zk_productimages&zktask=deleteimage&id='.$photo->id.'&productid='.$photo->productid);
									$defaultclass = '';
								}
								?>
								<div class="zijimagewrapper">
									<img src="<?php echo zijkart::$uploadurl.'/'.zijkart::getOption('directory').'/products/product_'.zijkart::get_d('productid').'/'.$photo->photo; ?>" />										
									<a href="<?php echo $defaultlink; ?>" class="<?php echo $defaultclass; ?>"><?php echo __('Make default','zijkart'); ?></a>
									<a href="<?php echo $deletelink; ?>" class="<?php echo $defaultclass; ?>"><?php echo __('Delete image','zijkart'); ?></a>
								</div>
								<?php
							}
						}
					}
				?>
			</div>	
		</div>
		<div class="row">
			<?php
				echo $html->hidden('productid',zijkart::get_d('productid'));
		 		echo $html->submit(__('Save Images','zijkart'),array('class'=>'button'));
			?>
		</div>
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#variation1').ColorPicker({
			color: '<?php if(isset($variation[0])) echo key($variation[0]); else echo "#0000ff"; ?>',
			onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				jQuery('#variation1').css('backgroundColor', '#' + hex);
				jQuery('#variation1').val('#' + hex);
			}
		});	
		jQuery('#variation2').ColorPicker({
			color: '<?php if(isset($variation[1])) echo key($variation[1]); else echo "#0000ff"; ?>',
			onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				jQuery('#variation2').css('backgroundColor', '#' + hex);
				jQuery('#variation2').val('#' + hex);
			}
		});	
	});
</script>

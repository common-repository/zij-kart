<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Products','zijkart'); ?>
		<a href="admin.php?page=zk_product&zkl=form"><?php echo __('Add Product','zijkart'); ?></a>
	</div>
	<?php
		$html = new zijkartHTML();
		$search_form = zijkart::get_d('search_form');
	?>
	<div class="zij_searchform">
		<form id="zij_searchform" name="zij_searchform" method="POST" action="admin.php?page=zk_product&zkl=listing">
			<?php echo $html->text('search_title',$search_form,array('placeholder'=> __('Title','zijkart'))); ?>
			<?php echo $html->select('search_brand',zijkart::getModel('brand')->getBrandsForCombo(),$search_form); ?>
			<?php echo $html->select('search_category',zijkart::getModel('category')->getCategoriesForCombo(),$search_form); ?>
			<?php echo $html->select('search_status',$html->statusarray(),$search_form); ?>
			<?php echo $html->submit(__('Search','zijkart'),array('class'=>'button')); ?>
			<?php echo $html->submit(__('Reset','zijkart'),array('class'=>'button', 'onclick' => "resetAdminForm();")); ?>
		</form>
	</div>
	<div class="titles">
		<div class="zij w10"><?php echo __('Image','zijkart'); ?></div>			
		<div class="zij w20"><?php echo __('Title','zijkart'); ?></div>			
		<div class="zij w10"><?php echo __('Brand','zijkart'); ?></div>			
		<div class="zij w10"><?php echo __('Category','zijkart'); ?></div>			
		<div class="zij w10"><?php echo __('Price','zijkart'); ?></div>			
		<div class="zij w10"><?php echo __('Quantity','zijkart'); ?></div>			
		<div class="zij w10"><?php echo __('Status','zijkart'); ?></div>			
		<div class="zij w10"><?php echo __('Expiry','zijkart'); ?></div>			
		<div class="zij w10 center"><?php echo __('Action','zijkart'); ?></div>			
	</div>
	<?php
		$products = zijkart::get_d('products');
		if(!empty($products)){
			foreach($products AS $product){
				$image = ZIJKART_PLUGIN_URL."includes/images/noimage.png";
				if($product->photo != ''){
					$image = zijkart::$uploadurl.'/'.zijkart::getOption('directory')."/products/product_".$product->id."/".$product->photo;
				}
	?>
				<div class="row">
					<div class="zij w10"><img src="<?php echo $image; ?>" class="row-image" /></div>
					<div class="zij w20"><?php echo $product->title; ?></div>
					<div class="zij w10"><?php echo $product->brandtitle; ?></div>
					<div class="zij w10"><?php echo $product->categorytitle; ?></div>
					<div class="zij w10"><?php echo $product->price; ?></div>
					<div class="zij w10"><?php echo $product->quantity; ?></div>
					<div class="zij w10"><?php echo ($product->status == 1) ? __('Published','zijkart') : __('Un-published','zijkart'); ?></div>
					<div class="zij w10"><?php echo date_i18n(zijkart::getOption('dateformat'),strtotime($product->expiry)); ?></div>
					<div class="zij w10 center">
						<a href="admin.php?page=zk_product&zkl=form&id=<?php echo $product->id; ?>" ><img src="<?php echo ZIJKART_PLUGIN_URL."includes/images/editicon.png"; ?>" /></a>
						<a href="admin.php?page=zk_product&zktask=delete&id=<?php echo $product->id; ?>" onclick="return areyousure('<?php echo __('Are you sure to delete','zijkart'); ?>');" ><img src="<?php echo ZIJKART_PLUGIN_URL."includes/images/deleteicon.png"; ?>" /></a>
					</div>
				</div>
	<?php
			}
			$pagination = zijkart::get_d('pagination');
			echo $pagination;
		}
	?>
</div>

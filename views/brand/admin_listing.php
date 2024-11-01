<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Brands','zijkart'); ?>
		<a href="admin.php?page=zk_brand&zkl=form"><?php echo __('Add Brand','zijkart'); ?></a>
	</div>
	<?php
		$html = new zijkartHTML();
		$search_form = zijkart::get_d('search_form');
	?>
	<div class="zij_searchform">
		<form id="zij_searchform" name="zij_searchform" method="POST" action="admin.php?page=zk_brand&zkl=listing">
			<?php echo $html->text('search_title',$search_form,array('placeholder'=> __('Title','zijkart'))); ?>
			<?php echo $html->select('search_status',$html->statusarray(),$search_form); ?>
			<?php echo $html->submit(__('Search','zijkart'),array('class'=>'button')); ?>
			<?php echo $html->submit(__('Reset','zijkart'),array('class'=>'button', 'onclick' => "resetAdminForm();")); ?>
		</form>
	</div>
	<div class="titles">
		<div class="zij w10"><?php echo __('Image','zijkart'); ?></div>			
		<div class="zij w30"><?php echo __('Title','zijkart'); ?></div>			
		<div class="zij w20"><?php echo __('Status','zijkart'); ?></div>			
		<div class="zij w20"><?php echo __('Created','zijkart'); ?></div>			
		<div class="zij w20 center"><?php echo __('Action','zijkart'); ?></div>			
	</div>
	<?php
		$brands = zijkart::get_d('brands');
		if(!empty($brands)){
			foreach($brands AS $brand){
				$image = ZIJKART_PLUGIN_URL."includes/images/noimage.png";
				if($brand->photo != ''){
					$image = zijkart::$uploadurl.'/'.zijkart::getOption('directory')."/brands/brand_".$brand->id."/".$brand->photo;
				}
	?>
				<div class="row">
					<div class="zij w10"><img src="<?php echo $image; ?>" class="row-image" /></div>
					<div class="zij w30"><?php echo $brand->title; ?></div>
					<div class="zij w20"><?php echo ($brand->status == 1) ? __('Published','zijkart') : __('Un-published','zijkart'); ?></div>
					<div class="zij w20"><?php echo date_i18n(zijkart::getOption('dateformat'),strtotime($brand->created)); ?></div>
					<div class="zij w20 center">
						<a href="admin.php?page=zk_brand&zkl=form&id=<?php echo $brand->id; ?>" ><img src="<?php echo ZIJKART_PLUGIN_URL."includes/images/editicon.png"; ?>" /></a>
						<a href="admin.php?page=zk_brand&zktask=delete&id=<?php echo $brand->id; ?>" onclick="return areyousure('<?php echo __('Are you sure to delete','zijkart'); ?>');" ><img src="<?php echo ZIJKART_PLUGIN_URL."includes/images/deleteicon.png"; ?>" /></a>
					</div>
				</div>
	<?php
			}
			$pagination = zijkart::get_d('pagination');
			echo $pagination;
		}
	?>
</div>

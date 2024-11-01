<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Categories','zijkart'); ?>
		<a href="admin.php?page=zk_category&zkl=form"><?php echo __('Add Category','zijkart'); ?></a>
	</div>
	<?php
		$html = new zijkartHTML();
		$search_form = zijkart::get_d('search_form');
	?>
	<div class="zij_searchform">
		<form id="zij_searchform" name="zij_searchform" method="POST" action="admin.php?page=zk_category&zkl=listing">
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
		$categories = zijkart::get_d('categories');
		if(!empty($categories)){
			foreach($categories AS $category){
				$image = ZIJKART_PLUGIN_URL."includes/images/noimage.png";
				if($category->photo != ''){
					$image = zijkart::$uploadurl.'/'.zijkart::getOption('directory')."/categories/category_".$category->id."/".$category->photo;
				}
	?>
				<div class="row">
					<div class="zij w10"><img src="<?php echo $image; ?>" class="row-image" /></div>
					<div class="zij w30"><?php echo $category->title; ?></div>
					<div class="zij w20"><?php echo ($category->status == 1) ? __('Published','zijkart') : __('Un-published','zijkart'); ?></div>
					<div class="zij w20"><?php echo date_i18n(zijkart::getOption('dateformat'),strtotime($category->created)); ?></div>
					<div class="zij w20 center">
						<a href="admin.php?page=zk_category&zkl=form&id=<?php echo $category->id; ?>" ><img src="<?php echo ZIJKART_PLUGIN_URL."includes/images/editicon.png"; ?>" /></a>
						<a href="admin.php?page=zk_category&zktask=delete&id=<?php echo $category->id; ?>" onclick="return areyousure('<?php echo __('Are you sure to delete','zijkart'); ?>');" ><img src="<?php echo ZIJKART_PLUGIN_URL."includes/images/deleteicon.png"; ?>" /></a>
					</div>
				</div>
	<?php
			}
			$pagination = zijkart::get_d('pagination');
			echo $pagination;
		}
	?>
</div>

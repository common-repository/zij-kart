<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Product form','zijkart'); ?>
		<a href="admin.php?page=zk_product"><?php echo __('Products','zijkart'); ?></a>
	</div>
	<?php
		$form = zijkart::get_d('form');
		$html = new zijkartHTML();
	?>
	<form method="POST" action="admin.php?page=zk_product&zktask=save" enctype="multipart/form-data">
		<div class="row">
			<?php
				echo $html->label(__('Title','zijkart'),'title');
				echo $html->text('title',$form);
			?>
		</div>
		<?php if(zijkart::getOption('brandhide') == 1){ ?>
			<div class="row">
				<?php 
					echo $html->label(__('Brand','zijkart'),'brandid');
					echo $html->select('brandid',zijkart::getModel('brand')->getBrandsForCombo(),$form);
				?>
			</div>
		<?php } ?>
		<div class="row">
			<?php 
				echo $html->label(__('Category','zijkart'),'categoryid');
				echo $html->select('categoryid',zijkart::getModel('category')->getCategoriesForCombo(),$form);
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Price','zijkart'),'price');
				echo $html->text('price',$form);
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Discount','zijkart'),'isdiscount');
				echo $html->select('isdiscount',$html->yesnoarray(),$form);
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Discount type','zijkart'),'discounttype');
				echo $html->select('discounttype',$html->discounttypearray(),$form);
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Discount start','zijkart'),'discountstart');
				echo $html->text('discountstart',$form,array('class'=>'custom_date'));
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Discount end','zijkart'),'discountend');
				echo $html->text('discountend',$form,array('class'=>'custom_date'));
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Quantity','zijkart'),'quantity');
				echo $html->text('quantity',$form,array('class'=>''));
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Description','zijkart'),'description');
			?>
			<div class="editor">
				<?php 
					$value = isset($form->description) ? $form->description : '';
					wp_editor($value,'description',array('media_buttons' => false));
				?>
			</div>
		</div>
		<div class="row">
			<div class="featureheading"><?php echo __('Features','zijkart'); ?><a href="javascript:void(0);" onclick="addNewFeature();"><?php echo __('Add new','zijkart'); ?></a></div>
			<div class="featuretitles">
				<div class="zijtitles"><?php echo __('Title','zijkart'); ?></div>
				<div class="zijvalues"><?php echo __('Values','zijkart'); ?></div>
			</div>
			<div id="featurelist">
				<?php
					if(isset($form->features) && !empty($form->features)){
						$array = unserialize($form->features);
						foreach($array AS $title => $value){
				?>
						<div class="row">
							<div class="zijtitles"><input type="text" name="featuretitles[]" value="<?php echo $title; ?>" /></div>
							<div class="zijvalues"><input type="text" name="featurevalues[]" value="<?php echo $value; ?>" /></div>
						</div>
				<?php
						}
					}else{
				?>
						<div class="row">
							<div class="zijtitles"><input type="text" name="featuretitles[]" value="" /></div>
							<div class="zijvalues"><input type="text" name="featurevalues[]" value="" /></div>
						</div>
				<?php
					}
				?>
			</div>
		</div>
		<div class="row">
			<?php
				echo $html->label(__('Status','zijkart'),'status');
				echo $html->select('status',$html->statusarray(),$form);
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Expiry','zijkart'),'expiry');
				echo $html->text('expiry',$form,array('class'=>'custom_date'));
			?>
		</div>
		<div class="row">
			<?php
				echo $html->hidden('id',$form);
				echo $html->hidden('created',$form);
		 		echo $html->submit(__('Save Product','zijkart'),array('class'=>'button'));
			?>
		</div>
	</form>
</div>
<script type="text/javascript">
	function addNewFeature(){
		var html = '<div class="row">';
		html += '<div class="zijtitles"><input type="text" name="featuretitles[]" value="" /></div>';
		html += '<div class="zijvalues"><input type="text" name="featurevalues[]" value="" /></div>';
		html += '</div>';
		jQuery('div#featurelist').append(html);
	}
</script>

<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Brand form','zijkart'); ?>
		<a href="admin.php?page=zk_brand"><?php echo __('Brands','zijkart'); ?></a>
	</div>
	<?php
		$form = zijkart::get_d('form');
		$html = new zijkartHTML();
	?>
	<form method="POST" action="admin.php?page=zk_brand&zktask=save" enctype="multipart/form-data">
		<div class="row">
			<?php
				echo $html->label(__('Title','zijkart'),'title');
				echo $html->text('title',$form);
			?>
		</div>
		<div class="row">
			<?php 
				echo $html->label(__('Photo','zijkart'),'photo');
				echo $html->file('photo');
				if($form && $form->photo != '')
					echo '<img src="'.zijkart::$uploadurl.'/'.zijkart::getOption('directory')."/brands/brand_".$form->id."/".$form->photo.'" />'; 
			?>
		</div>
		<div class="row">
			<?php
				echo $html->label(__('Status','zijkart'),'status');
				echo $html->select('status',$html->statusarray(),$form);
			?>
		</div>
		<div class="row">
			<?php
				echo $html->hidden('id',$form);
				echo $html->hidden('alias',$form);
				echo $html->hidden('photo',$form);
				echo $html->hidden('created',$form);
		 		echo $html->submit(__('Save Brand','zijkart'),array('class'=>'button'));
			?>
		</div>
	</form>
</div>

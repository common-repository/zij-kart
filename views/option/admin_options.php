<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Options','zijkart'); ?>
	</div>
	<?php
		$options = zijkart::get_d('options');
		$data_array = array();
		if(!empty($options)){
			foreach($options AS $opt){
				$data_array[$opt->name] = (object)array($opt->name => $opt->value);
			}
		}		
		$html = new zijkartHTML();
		$dateformat = array(
							(object)array('value'=> 'm-d-Y', 'text'=>__('m-d-Y','zijkart')),
							(object)array('value'=> 'Y-m-d', 'text'=>__('Y-m-d','zijkart')),
							(object)array('value'=> 'd-m-Y', 'text'=>__('d-m-Y','zijkart'))
						);
		$showhide = array(
							(object)array('value'=> '1', 'text'=>__('Show','zijkart')),
							(object)array('value'=> '2', 'text'=>__('Hide','zijkart'))
						);
		$yesno = array(
							(object)array('value'=> '1', 'text'=>__('Yes','zijkart')),
							(object)array('value'=> '2', 'text'=>__('No','zijkart'))
						);
		$leftright = array(
							(object)array('value'=> '1', 'text'=>__('Left','zijkart')),
							(object)array('value'=> '2', 'text'=>__('Right','zijkart'))
						);
		$brandoption = array(
							(object)array('value'=> '1', 'text'=>__('Both logo and text','zijkart')),
							(object)array('value'=> '2', 'text'=>__('Only logo','zijkart')),
							(object)array('value'=> '3', 'text'=>__('Only text','zijkart'))
						);
		$query = "SELECT id, post_title FROM `".zijkart::$db->prefix."posts` WHERE post_type = 'page'";
		$page = zijkart::$db->get_results($query);
		$wppages = array();
		foreach($page AS $p){
			$wppages[] = (object)array('value' => $p->id, 'text' => $p->post_title);
		}
	?>
	<form method="POST" action="admin.php?page=zk_option&zktask=save" enctype="multipart/form-data">
		<div class="zk_options_wrapper">
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Site title','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->text('sitetitle',$data_array['sitetitle']); ?></div>
				<div class="zk_description"><?php echo __('Site title','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Show site title','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('showsitetitle',$showhide,$data_array['showsitetitle']); ?></div>
				<div class="zk_description"><?php echo __('Show site title or not','zijkart'); ?></div>
			</div>


			<div class="zk_row">
				<div class="zk_title"><?php echo __('Date format','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('dateformat',$dateformat,$data_array['dateformat']); ?></div>
				<div class="zk_description"><?php echo __('Set the default date format','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Data directory','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->text('directory',$data_array['directory']); ?></div>
				<div class="zk_description"><?php echo __('Set the default data directory','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Currency symbol','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->text('currencysymbol',$data_array['currencysymbol']); ?></div>
				<div class="zk_description"><?php echo __('Set your default currency symbol','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Currency alignment','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('currencysymbolalign',$leftright,$data_array['currencysymbolalign']); ?></div>
				<div class="zk_description"><?php echo __('Set the currency symbol left or right the price','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Products per row','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->text('list_col',$data_array['list_col']); ?></div>
				<div class="zk_description"><?php echo __('Set your number of products shown in row','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Brand','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('brandhide',$showhide,$data_array['brandhide']); ?></div>
				<div class="zk_description"><?php echo __('Set the brand option','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Brand option','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('brandoption',$brandoption,$data_array['brandoption']); ?></div>
				<div class="zk_description"><?php echo __('Set the brand shown option','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Category','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('categoryhide',$showhide,$data_array['categoryhide']); ?></div>
				<div class="zk_description"><?php echo __('Set the category option','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Quantity','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('quantityhide',$showhide,$data_array['quantityhide']); ?></div>
				<div class="zk_description"><?php echo __('Set the quantity option','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Default page','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('defaultpageid',$wppages,$data_array['defaultpageid']); ?></div>
				<div class="zk_description"><?php echo __('Set the default page for pages to work properly','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Paypal enable','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->select('paypal_enable',$yesno, $data_array['paypal_enable']); ?></div>
				<div class="zk_description"><?php echo __('Paypal express checkout payment enable','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Paypal username','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->text('paypal_username',$data_array['paypal_username']); ?></div>
				<div class="zk_description"><?php echo __('Paypal express checkout api username','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Paypal password','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->text('paypal_password',$data_array['paypal_password']); ?></div>
				<div class="zk_description"><?php echo __('Paypal express checkout api password','zijkart'); ?></div>
			</div>
			<div class="zk_row">
				<div class="zk_title"><?php echo __('Paypal signature','zijkart'); ?></div>
				<div class="zk_value"><?php echo $html->textarea('paypal_signature',$data_array['paypal_signature']); ?></div>
				<div class="zk_description"><?php echo __('Paypal express checkout api signature','zijkart'); ?></div>
			</div>
			<div class="row">
		 		<?php echo $html->submit(__('Save Options','zijkart'),array('class'=>'button')); ?>
			</div>
		</div>
	</form>
</div>

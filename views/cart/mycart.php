<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zk_wrapper">
<?php	
	if(zijkart::getOption('showsitetitle') == 1){
		echo '<div id="zk_sitetitle">'.zijkart::getOption('sitetitle').'</div>';
	}
?>
	<div id="zk_pagetitle"><?php echo __('My cart','zijkart'); ?></div>
<?php
	$cart = zijkart::get_d('cart');
	if(!empty($cart)){ ?>
		<div class="zk_cart_wrapper">
			<div class="zk_cart_title">
				<div class="zk_items title"><?php echo __('Items','zijkart'); ?></div>
				<div class="zk_items qty"><?php echo __('Quantity','zijkart'); ?></div>
				<div class="zk_items price"><?php echo __('Price','zijkart'); ?></div>
				<div class="zk_items total"><?php echo __('Total','zijkart'); ?></div>
				<div class="zk_items action"><?php echo __('Action','zijkart'); ?></div>
			</div>
			<?php
			$total = 0;
			foreach($cart AS $item){ ?>
				<div class="zk_cart_value">
					<div class="zk_items title">
						<?php 
							$image = ($item->photo != '') ? site_url('?page_id='.zijkart::getPageid().'&zkm=productimages&zktask=getimagebyid&imageid='.$item->photoid) : ZIJKART_PLUGIN_URL.'includes/images/noimage.png';
							echo '<img src="'.$image.'" style="height:50px;width:auto;" />';
							echo $item->name; 
						?>
					</div>
					<div class="zk_items qty">
						<input type="text" name="qty_<?php echo $item->itemid; ?>" id="qty_<?php echo $item->itemid; ?>" value="<?php echo $item->quantity; ?>" />
					</div>
					<div class="zk_items price"><?php echo $item->price; ?></div>
					<div class="zk_items total"><?php echo ($item->quantity * $item->price); ?></div>
					<div class="zk_items action">
						<img title="<?php echo __('Update','zijkart'); ?>" class="zk_update" data-id="<?php echo $item->itemid; ?>" src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/update.png'; ?>" />
						<img title="<?php echo __('Delete','zijkart'); ?>" class="zk_delete" data-id="<?php echo $item->itemid; ?>" src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/delete.png'; ?>" />
					</div>
				</div>
				<?php
				$total += ($item->quantity * $item->price);
			}
			?>
		</div>
		<div class="zk_total">
			<?php echo __('Total','zijkart').": ".zijkart::getOption('currencysymbol').' '.$total; ?>
		</div>		
	<?php
		$classes = array();
		$classnames = apply_filters('zijkart_payment_methods',$classes);

		foreach($classnames AS $class){
			$title = $class['title'];
			$classname = $class['class'];
			if (is_subclass_of($classname, 'zijkartPayment')) {
				echo '<a href="'.site_url('?page_id='.zijkart::getPageid().'&zkm=cart&zktask=getpaid&zijclass='.$classname).'">'.$title.'</a>';
			}
		}

	}else{
		echo '<h1 class="zk_error">'.__('You don\'t have any item in your cart','zijkart').'</h1>';
	}
?>
</div>
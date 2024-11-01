<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Purchase history','zijkart'); ?>		
	</div>
	<?php
		$cart = zijkart::get_d('cart');
		if(!empty($cart)){
	?>
		<div class="row purchasehistory">
			<div class="rowpurchasehistory">
				<div class="zij w30">
					<?php echo __('WP Name','zijkart').':&nbsp;'.$cart->name; ?>
				</div>
				<div class="zij w30">
					<?php echo __('WP Email','zijkart').':&nbsp;'.$cart->email; ?>
				</div>
				<div class="zij w30">
					<?php echo __('Amount','zijkart').':&nbsp;'.$cart->amount; ?>
				</div>
			</div>
			<div class="rowpurchasehistory">
				<div class="zij w30">
					<?php 
						echo __('Payment verified','zijkart').':&nbsp;';
						if($cart->paymentverified == 1) echo __('Yes','zijkart'); else echo __('No','zijkart'); 
					?>
				</div>
				<div class="zij w30">
					<?php echo __('Ordered date','zijkart').':&nbsp;'.date_i18n(zijkart::getOption('dateformat'), strtotime($cart->created)); ?>
				</div>
				<div class="zij w30">
					<?php echo __('Transaction number','zijkart').':&nbsp;'.$cart->token; ?>
				</div>
			</div>
			<div class="rowpurchasehistory">
				<div class="zij w30">
					<?php echo __('Payer first name','zijkart').':&nbsp;'.$cart->payer_firstname; ?>
				</div>
				<div class="zij w30">
					<?php echo __('Payer last name','zijkart').':&nbsp;'.$cart->payer_lastname; ?>
				</div>
				<div class="zij w30">
					<?php echo __('Payer email','zijkart').':&nbsp;'.$cart->payer_email; ?>
				</div>
			</div>
			<div class="rowpurchasehistory">
				<div class="zij w30">
					<?php echo __('Payer address','zijkart').':&nbsp;'.$cart->payer_address; ?>
				</div>
				<div class="zij w30">
					<?php echo __('Payer contact','zijkart').':&nbsp;'.$cart->payer_contact; ?>
				</div>
				<div class="zij w30">
					<?php echo __('Payer amount paid','zijkart').':&nbsp;'.$cart->amountpaid; ?>
				</div>
			</div>
			<div class="zij w30">
				<?php echo __('Payment method','zijkart').':&nbsp;'.$cart->payment_method; ?>
			</div>
		</div>
		<div class="cartdetail_items_heading">
			<?php echo __('Items','zijkart'); ?>
		</div>
		<div class="titles">
			<div class="zij w50"><?php echo __('Product name','zijkart'); ?></div>			
			<div class="zij w20"><?php echo __('Price','zijkart'); ?></div>			
			<div class="zij w10"><?php echo __('Quantity','zijkart'); ?></div>			
			<div class="zij w20 center"><?php echo __('Total Amount','zijkart'); ?></div>			
		</div>
		<?php 
			$cartitems = zijkart::get_d('cartitems');
			foreach($cartitems AS $item){ ?>
				<div class="row purchasehistory">
					<div class="zij w50">
						<?php echo $item->name; ?>
					</div>
					<div class="zij w20">
						<?php echo $item->price; ?>
					</div>
					<div class="zij w10">
						<?php echo $item->quantity; ?>
					</div>
					<div class="zij w20 center">
						<?php echo ($item->price * $item->quantity); ?>
					</div>
				</div>
			<?php
			}
		?>
	<?php
		}
	?>
</div>

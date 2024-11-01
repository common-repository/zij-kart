<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zijkart">
	<div class="heading">
		<?php echo __('Purchase history','zijkart'); ?>		
	</div>
	<?php
		$html = new zijkartHTML();
		$search_form = zijkart::get_d('search_form');
	?>
	<div class="zij_searchform">
		<form id="zij_searchform" name="zij_searchform" method="POST" action="admin.php?page=zk_purchasehistory&zkl=listing">
			<?php echo $html->text('search_wpname',$search_form,array('placeholder'=> __('WP Name','zijkart'))); ?>
			<?php echo $html->text('search_wpemail',$search_form,array('placeholder'=> __('WP Email','zijkart'))); ?>
			<?php echo $html->text('search_name',$search_form,array('placeholder'=> __('Payer Name','zijkart'))); ?>
			<?php echo $html->text('search_email',$search_form,array('placeholder'=> __('Payer Email','zijkart'))); ?>
			<?php echo $html->select('search_paymentverified',$html->paymentverifiedarray(),$search_form); ?>
			<?php echo $html->submit(__('Search','zijkart'),array('class'=>'button')); ?>
			<?php echo $html->submit(__('Reset','zijkart'),array('class'=>'button', 'onclick' => "resetAdminForm();")); ?>
		</form>
	</div>
	<?php
		$carts = zijkart::get_d('carts');
		if(!empty($carts)){
			foreach($carts AS $cart){
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
					<div class="zij w30">
						<a href="<?php echo admin_url('admin.php?page=zk_purchasehistory&zkl=cartdetail&cartid='.$cart->id); ?>"><?php echo __('Detail','zijkart'); ?></a>
					</div>
				</div>
	<?php
			}
			$pagination = zijkart::get_d('pagination');
			echo $pagination;
		}
	?>
</div>

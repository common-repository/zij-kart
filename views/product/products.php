<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zk_wrapper">
<?php	
	if(zijkart::getOption('showsitetitle') == 1){
		echo '<div id="zk_sitetitle">'.zijkart::getOption('sitetitle').'</div>';
	}
	$products = zijkart::get_d('products');
	if(!empty($products)){
		/*
		Brand option
		1 = Both logo and text
		2 = Only Logo
		3 = Only text
		*/
		$brandoption = zijkart::getOption('brandoption');		
		$brandhide = zijkart::getOption('brandhide');		
		foreach($products AS $product){
			$image = ($product->photo != '') ? site_url('?page_id='.zijkart::getPageid().'&zkm=productimages&zktask=getimagebyid&imageid='.$product->imageid) : ZIJKART_PLUGIN_URL.'includes/images/noimage.png';
			$col = (zijkart::getOption('list_col') > 6) ? 3 : zijkart::getOption('list_col');
			$width = (100 / $col);
			$width = 'width:'.$width.'%';
		?>
			<div id="zk_product" style="<?php echo $width; ?>">
				<div id="zk_productwrapper">
					<?php
						if($product->isdiscount == 1){
							$discountstart = date_i18n('Y-m-d',strtotime($product->discountstart));
							$discountend = date_i18n('Y-m-d',strtotime($product->discountend));
							$curdate = date_i18n('Y-m-d');
							if($discountstart <= $curdate && $discountend >= $curdate){
								$discount = $product->discount;
								if($product->discounttype == 1){
									$discount .= '%';
								}else{
									$discount .= '&nbsp;'.zijkart::getOption('currencysymbol');
								}
								echo '<span class="zk_pro_off">'.$discount.'&nbsp;'.__('Off','zijkart').'</span>';
							}
						}
						if($brandhide == 1 && ($brandoption == 1 || $brandoption == 2)){								
							echo '<img class="zk_pro_brand_logo" src="'.site_url('?page_id='.zijkart::getPageid().'&zkm=brand&zktask=getimagebyid&imageid='.$product->brandid).'"/>';
						}
					?>
					<span class="zk_pro_title"><a href="<?php echo site_url('?page_id='.zijkart::getPageid().'&zkm=product&zkl=viewproduct&zkid='.$product->alias.'-'.$product->id); ?>"><?php echo $product->title; ?></a></span>
					<div class="zk_pro_image">
						<img class="zk_pro_image" src="<?php echo $image; ?>" />
					</div>
					<?php if($brandhide == 1 && ($brandoption == 1 || $brandoption == 3)){ ?>
						<div class="zk_pro_data">
							<span class="zk_title"><?php echo __('Brand','zijkart'); ?></span>
							<span class="zk_value"><?php echo $product->brandtitle; ?></span>
						</div>
					<?php } ?>
					<?php if(zijkart::getOption('categoryhide') == 1){ ?>
						<div class="zk_pro_data">
							<span class="zk_title"><?php echo __('Category','zijkart'); ?></span>
							<span class="zk_value"><?php echo $product->categorytitle; ?></span>
						</div>
					<?php } ?>
					<?php if(zijkart::getOption('quantityhide') == 1){ ?>
						<div class="zk_pro_data">
							<span class="zk_title"><?php echo __('Quantity','zijkart'); ?></span>
							<span class="zk_value"><?php echo $product->quantity; ?></span>
						</div>
					<?php } ?>
					<div class="zk_pro_pricwrapper">
						<span class="zk_pro_price">
							<?php
								echo zijkart::getModel('common')->showPrice($product);
							?>
						</span>
					</div>
					<div class="zk_pro_button">
						<div class="zk_pro_qty">
							<span data-qty="<?php echo $product->quantity; ?>" class="zk_qty">0</span>
							<img src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/plus.png'; ?>" class="zk_plus"/>
							<img src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/minus.png'; ?>" class="zk_minus"/>
						</div>
						<img data-zij-proid="<?php echo $product->id; ?>" class="zk_addkart" src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/addcart.png'; ?>" />
					</div>
				</div>
			</div>
		<?php
		}
		$pagination = zijkart::get_d('pagination');
		echo $pagination;
	}
?>

<div id="zk_popup" style="display:none;"></div>
</div>
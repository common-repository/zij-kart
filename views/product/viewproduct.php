<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="zk_wrapper">
<?php	
    wp_register_style('zijkart-smoothproducts', ZIJKART_PLUGIN_URL.'includes/css/smoothproducts.css');
    wp_enqueue_style('zijkart-smoothproducts');
	wp_enqueue_script( 'zijkart-smoothproducts.min.js', ZIJKART_PLUGIN_URL.'includes/js/smoothproducts.min.js', array(), '1.0.0', true );

	if(zijkart::getOption('showsitetitle') == 1){
		echo '<div id="zk_sitetitle">'.zijkart::getOption('sitetitle').'</div>';
	}
	
	$product = zijkart::get_d('product');
	$productimages = zijkart::get_d('productimages');

	$images = $productimages;
	$option = false;
	if (!empty($images)) {
	    foreach ($images AS $img) {
	        if (!empty($img->variationcolor)) {
	            $option = true;
	            break;
	        }
	    }
	}
	$opimages = array();
	if ($option == true) {
	    foreach ($images AS $img) {
	        $opimages[$img->variationcolor][] = $img;
	    }
	}

?>
	<div id="zk_pagetitle"><?php echo __('Product detail','zijkart'); ?></div>
	<div class="zk_product_upper">
		<div class="zk_product_img">
            <div id="imagesliderwrapper">
                <div class="sp-loading"><img src="<?php echo ZIJKART_PLUGIN_URL; ?>includes/images/sp-loading.gif" alt=""><br>LOADING IMAGES...</div>
                <div class="sp-wrap">
                    <?php
                    if ($option == true) { // multi options images
                        foreach ($opimages AS $colorcode => $images) {
                            foreach ($images AS $image) {
                                echo '<a data-color="' . $colorcode . '" href="' . site_url('?page_id='.zijkart::getPageid().'&zkm=productimages&zktask=getimagebyid&imageid='.$image->id) . '"><img src="' . site_url('?page_id='.zijkart::getPageid().'&zkm=productimages&zktask=getimagebyid&imageid='.$image->id) . '" alt=""></a>';
                            }
                        }
                    } else { // single option images
                    }
                    ?>
                </div>
            </div>
		</div>
		<div class="zk_product_detail">
			<span class="zk_product_title"><?php echo $product->title; ?></span>
			<?php if(zijkart::getOption('categoryhide') == 1){ ?>
			<div class="zk_product_keyvalue_wrapper">
				<span class="zk_product_key"><?php echo __('Category','zijkart'); ?>:</span>
				<span class="zk_product_value"><?php echo $product->categorytitle; ?></span>
			</div>
			<?php } ?>
			<?php 
			$brandoption = zijkart::getOption('brandoption');		
			$brandhide = zijkart::getOption('brandhide');		
			if($brandhide == 1 && ($brandoption == 1 || $brandoption == 3)){ ?>
			<div class="zk_product_keyvalue_wrapper">
				<span class="zk_product_key"><?php echo __('Brand','zijkart'); ?>:</span>
				<span class="zk_product_value"><?php echo $product->brandtitle; ?></span>
			</div>
			<?php }
			if($brandhide == 1 && ($brandoption == 1 || $brandoption == 2)){ ?>
				<img id="zij_productdetail_brandlogo" src="<?php echo site_url('?page_id='.zijkart::getPageid().'&zkm=brand&zktask=getimagebyid&imageid='.$product->brandid); ?>" />
			<?php } ?>
			<?php if(zijkart::getOption('quantityhide') == 1){ ?>
			<div class="zk_product_keyvalue_wrapper">
				<span class="zk_product_key"><?php echo __('Quantity','zijkart'); ?>:</span>
				<span class="zk_product_value"><?php echo $product->quantity; ?></span>
			</div>
			<?php } ?>
			<div class="zk_pro_qty_wrapper">
				<div class="zk_pro_qty">
					<span data-qty="<?php echo $product->quantity; ?>" class="zk_qty">0</span>
					<img src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/plus.png'; ?>" class="zk_plus"/>
					<img src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/minus.png'; ?>" class="zk_minus"/>
				</div>
				<img data-zij-proid="<?php echo $product->id; ?>" class="zk_addkart" src="<?php echo ZIJKART_PLUGIN_URL.'includes/images/addcart.png'; ?>" />
			</div>
			<div class="zk_pro_pricwrapper">
				<span class="zk_pro_price">
					<?php
						echo zijkart::getModel('common')->showPrice($product);
					?>
				</span>
			</div>
            <?php 
                if ($option == true) {
                    if (COUNT($opimages) > 1) {
                        echo '<span class="product_colors">Multi color option</span>';
                        echo '<div class="product_colors_wrapper">';
                        foreach ($opimages AS $colorcode => $images) {
                            echo '<div class="colorbox" data-color="' . $colorcode . '" style="background:' . $colorcode . ';width:50px;height:50px;"></div>';
                        }
                        echo '</div>';
                    }
                }
            ?>
		</div>
	</div>
	<div class="zk_detail_box_wrapper">
		<div class="zk_detail_box_title"><?php echo __('Description','zijkart'); ?></div>
		<div class="zk_detail_box_data"><?php echo $product->description; ?></div>
	</div>
	<div class="zk_detail_box_wrapper">
		<div class="zk_detail_box_title"><?php echo __('Specs & Features','zijkart'); ?></div>
		<div class="zk_detail_box_data">
			<?php 
				$featurearray = unserialize($product->features);
				foreach($featurearray AS $label => $value){
					echo '<div class="zk_feature_row_wrapper">';
					echo '<span class="zk_feature_label">'.$label.':</span>';
					echo '<span class="zk_feature_value">'.$value.'</span>';
					echo '</div>';
				}
			?>
		</div>
	</div>
<div id="zk_popup" style="display:none;"></div>
</div>
<script type="text/javascript">
    /* wait for images to load */
    jQuery(window).load(function () {
        jQuery('.sp-wrap').smoothproducts();
        jQuery('div.colorbox').click(function () {
            var colorcode = jQuery(this).attr('data-color');
            jQuery('div.sp-thumbs.sp-tb-active').find('a').hide();
            jQuery('div.sp-thumbs.sp-tb-active').find('a[data-color="' + colorcode + '"]').show().click();
        });
    });

</script>

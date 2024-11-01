<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkcommon_model{

		function getPrice(&$product){
			$finalprice = '';
			if($product->isdiscount == 1){
				if($product->discounttype == 1){ //percentage
					if($product->discount == 0){
						$price = $product->price;
					}else{
						$price = ($product->price * $product->discount) / 100; 						
					}
					if($price > $product->price){
						$finalprice = 0;
					}else{
						$finalprice = $product->price - $price;
					}
				}else{ // amount
					if($product->discount > $product->price){
						$finalprice = 0;
					}else{
						$finalprice = $product->price - $product->discount;						
					}
				}
			}else{
				$finalprice = $product->price;
			}
			return $finalprice;
		}

		function showPrice(&$product){
			$finalprice = $this->getPrice($product);
			if(zijkart::getOption('currencysymbolalign') == 1){
				$finalprice = zijkart::getOption('currencysymbol').'&nbsp;'.$finalprice;
			}else{
				$finalprice = $finalprice.'&nbsp;'.zijkart::getOption('currencysymbol');
			}
			return $finalprice;
		}

	}
?>
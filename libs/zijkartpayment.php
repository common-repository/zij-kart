<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	abstract class zijkartPayment {
		public $payer_firstname = '';
		public $payer_lastname = '';
		public $payer_email = '';
		public $payer_address = '';
		public $payer_contact = '';
		public $amountpaid = '';
		public $paymentverified = '';
		public $token = '';
		public $payment_method = '';

		abstract function placeorder();

		abstract function getnotification();

		function updateNotified($cartid){
			$query = "UPDATE `".zijkart::$db->prefix."zijkart_cart` SET ";
			$query .= " payer_firstname = '".$this->payer_firstname."' ";
			$query .= ",payer_lastname = '".$this->payer_lastname."' ";
			$query .= ",payer_email = '".$this->payer_email."' ";
			$query .= ",payer_address = '".$this->payer_address."' ";
			$query .= ",payer_contact = '".$this->payer_contact."' ";
			$query .= ",amountpaid = '".$this->amountpaid."' ";
			$query .= ",paymentverified = '".$this->paymentverified."' ";
			$query .= ",token = '".$this->token."' ";
			$query .= ",payment_method = '".$this->payment_method."' ";
			$query .= " WHERE id = ".$cartid;
			zijkart::$db->query($query);
			if(isset($_SESSION['zijkart_cart']) && is_numeric($_SESSION['zijkart_cart']) && $_SESSION['zijkart_cart'] == $cartid){
				unset($_SESSION['zijkart_cart']);
			}
			$this->setRedirect();
		}

		function setRedirect(){
			$url = site_url('?page_id='.zijkart::getPageid().'&zkm=cart&zkl=mycart');
			wp_redirect($url);
			exit;
		}

		function getCartid(){
			if(isset($_SESSION['zijkart_cart']) && is_numeric($_SESSION['zijkart_cart'])){
				$cartid = $_SESSION['zijkart_cart'];
				//check existence
				$query = "SELECT COUNT(id) FROM `".zijkart::$db->prefix."zijkart_cart` WHERE id = ".$cartid;
				$result = zijkart::$db->get_var($query);
				if($result == 1){
					return $cartid;
				}else{
					return null;
				}
			}else{
				return null;
			}
		}

		function getCartItems($cartid){
			if(!is_numeric($cartid)) return false;
			$query = "SELECT item.name, item.price, item.quantity, cart.amount 
						FROM `".zijkart::$db->prefix."zijkart_cart` AS cart
						JOIN `".zijkart::$db->prefix."zijkart_cartitems` AS item ON item.cartid = cart.id
						WHERE cart.id = $cartid";
			$items = zijkart::$db->get_results($query);
			return $items;
		} 

	}

	function zijkart_payment_register( $classnames ) {
	    return $classnames;
	}
	add_filter( 'zijkart_payment_methods', 'zijkart_payment_register', 10, 1 );
	require_once(ZIJKART_PLUGIN_DIR.'includes/paymentclasses/zijkartpaypal.php');
?>
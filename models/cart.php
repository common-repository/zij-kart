<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkcart_model extends zkModel{

		public $id = '';
		public $uid = '';
		public $name = '';
		public $email = '';
		public $amount = 0;
		public $paymentverified = 0;
		public $status = 1;
		public $created = '';
		public $token = '';

		function __construct(){
			parent::__construct(zijkart::$db->prefix.'zijkart_cart','id');
		}

		public function getData($layout){
			switch($layout){
				case "mycart":
					$this->getMyCart();
				break;
			}
		}

		public function getMyCart(){
			$cartid = $this->getCartid();
			$items = false;
			if(is_numeric($cartid)){
				$query = "SELECT item.id AS itemid, item.name, item.price, item.quantity, cart.amount, p_image.photo, p_image.id AS photoid 
							FROM `".zijkart::$db->prefix."zijkart_cart` AS cart
							JOIN `".zijkart::$db->prefix."zijkart_cartitems` AS item ON item.cartid = cart.id
							LEFT JOIN `".zijkart::$db->prefix."zijkart_product_images` AS p_image ON p_image.productid = item.productid AND p_image.isdefault = 1
							WHERE cart.id = $cartid";
				$items = zijkart::$db->get_results($query);
			}
			zijkart::add_d('cart',$items);
		}

		private function getCartItems($cartid){
			if(!is_numeric($cartid)) return false;
			$query = "SELECT item.name, item.price, item.quantity, cart.amount 
						FROM `".zijkart::$db->prefix."zijkart_cart` AS cart
						JOIN `".zijkart::$db->prefix."zijkart_cartitems` AS item ON item.cartid = cart.id
						WHERE cart.id = $cartid";
			$items = zijkart::$db->get_results($query);
			return $items;
		} 

		private function getCartid(){
			if(isset($_SESSION['zijkart_cart']) && is_numeric($_SESSION['zijkart_cart'])){
				$cartid = $_SESSION['zijkart_cart'];
				//check existence
				if(!is_numeric($cartid)) return null;
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

		public function addtocart(){
			$cartid = $this->getCartid();
			$productid = zijkartRequest::getValue('productid');
			$quantity = zijkartRequest::getValue('quantity');
			if($cartid != null && is_numeric($cartid) && is_numeric($productid)){
				// Cart id exists
				// Update cart amount
				$data['id'] = $cartid;
				$query = "SELECT cart.amount FROM `".zijkart::$db->prefix."zijkart_cart` AS cart WHERE cart.id = ".$cartid;
				$cartamount = zijkart::$db->get_var($query);
				$query = "SELECT product.isdiscount,product.discounttype,product.discount,product.price,product.title FROM `".zijkart::$db->prefix."zijkart_products` AS product WHERE product.id = ".$productid;
				$product = zijkart::$db->get_row($query);
				$amount = zijkart::getModel('common')->getPrice($product);
				$amount = $amount * (int) $quantity;
				$amount = $amount + $cartamount;
				$productname = $product->title;
				$data['amount'] = $amount;
				$data = filter_var_array($data, FILTER_SANITIZE_STRING);
				$this->bind($data);
				$this->store();
				// Add to cart item
				zijkart::getModel('cartitems')->addtocartitem($cartid,$productname,$productid,$quantity);
				$html = '<div id="zk_cart_wrapper">
							<span class="zk_title">'.__('Cart updated','zijkart').'</span>
							<div class="zk_form_row">
								<span class="zk_cart_message">'.__('Product has been added to cart','zijkart').'</span>
							</div>
							<div class="zk_form_button">
								<input type="button" onclick="zk_cancelcart();" value="'.__('Close','zijkart').'" />
							</div>
						</div>';	
			}else{
				// Cart id not exists
				$uid = zijkart::getUID();
				if($uid == 0){ // User is guest
					$html = '<div id="zk_cart_wrapper">
								<span class="zk_title">'.__('Please fill the form and click proceed','zijkart').'</span>
								<div class="zk_form_row">
									<label class="zk_label" for="zk_name">'.__('Name').'</label>
									<input type="text" class="zk_field" id="zk_name" name="zk_name" value="" />
								</div>
								<div class="zk_form_row">
									<label class="zk_label" for="zk_emailaddress">'.__('Email address').'</label>
									<input type="text" class="zk_field" id="zk_emailaddress" name="zk_emailaddress" value="" />
								</div>
								<div class="zk_form_button">
									<input type="hidden" name="zk_productid" id="zk_productid" value="'.$productid.'" />
									<input type="hidden" name="zk_quantity" id="zk_quantity" value="'.$quantity.'" />
									<input type="button" onclick="zk_cancelcart();" value="'.__('Cancel','zijkart').'" />
									<input type="button" onclick="zk_submitcart();" value="'.__('Proceed','zijkart').'" />
								</div>
							</div>';	
				}else{ // User is logged in member					
					$this->savecart(true);
					$html = '<div id="zk_cart_wrapper">
								<span class="zk_title">'.__('Cart updated','zijkart').'</span>
								<div class="zk_form_row">
									<span class="zk_cart_message">'.__('Product has been added to cart','zijkart').'</span>
								</div>
								<div class="zk_form_button">
									<input type="button" onclick="zk_cancelcart();" value="'.__('Close','zijkart').'" />
								</div>
							</div>';	
				}
			}
			return $html;
		}

		public function savecart($user = null){
			$data = zijkartRequest::getServerArray('POST');
			$productid = zijkartRequest::getValue('productid');
			if(!is_numeric($productid)) return false;
			$query = "SELECT product.isdiscount,product.discounttype,product.discount,product.price,product.title FROM `".zijkart::$db->prefix."zijkart_products` AS product WHERE product.id = ".$productid;
			$product = zijkart::$db->get_row($query);
			$amount = zijkart::getModel('common')->getPrice($product);
			$amount = $amount * (int) $data['quantity'];
			$productname = $product->title;
			$data['amount'] = $amount;
			$data['uid'] = zijkart::getUID();
			$data['created'] = date_i18n('Y-m-d H:i:s');
			if($user == true){ // user is logged in
	            global $current_user;
	            get_currentuserinfo();
	            if(trim($current_user->user_firstname) != ''){
	            	$data['name'] = $current_user->user_firstname . ' ' . $current_user->user_lastname;	            	
	            }else{
	            	$data['name'] = $current_user->user_nicename;
	            }
	            $data['email'] = $current_user->user_email;
			}
			$data = filter_var_array($data, FILTER_SANITIZE_STRING);
			$this->bind($data);
			if($this->store()){
				$_SESSION['zijkart_cart'] = $this->id;
				zijkart::getModel('cartitems')->addtocartitem($this->id,$productname,$productid,$data['quantity']);
				$html = __('Product has been added to your cart','zijkart');
			}else{
				$html = __('Error! Product has not been added','zijkart');
			}
			return $html;
		}

		public function updateCart(){
			$itemid = zijkartRequest::getValue('itemid');
			$quantity = zijkartRequest::getValue('quantity');
			if(!is_numeric($itemid)) return false;			
			if(!is_numeric($quantity)) return false;			
			$query = "UPDATE `".zijkart::$db->prefix."zijkart_cartitems` SET quantity = ".$quantity." WHERE id = ".$itemid;
			if(zijkart::$db->query($query)){
				$this->_updateCart();
				return true;
			}else{
				return false;
			}
		}

		private function _updateCart(){
			// update the cart total
			$cartid = $this->getCartid();
			if(is_numeric($cartid)){
				$query = "SELECT price, quantity FROM `".zijkart::$db->prefix."zijkart_cartitems` WHERE cartid = ".$cartid;
				$results = zijkart::$db->get_results($query);
				$total = 0;
				if(is_array($results) && !empty($results)){
					foreach($results AS $rs){
						$total += ($rs->price * $rs->quantity);
					}					
				}
				$query = "UPDATE `".zijkart::$db->prefix."zijkart_cart` SET amount = ".$total." WHERE id = ".$cartid;
				zijkart::$db->query($query);
			}
		}

		public function deletecartitem(){
			$itemid = zijkartRequest::getValue('itemid');
			if(!is_numeric($itemid)) return false;			
			$query = "DELETE FROM `".zijkart::$db->prefix."zijkart_cartitems` WHERE id = ".$itemid;
			if(zijkart::$db->query($query)){
				$this->_updateCart();
				return true;
			}else{
				return false;
			}
		}
	}
?>

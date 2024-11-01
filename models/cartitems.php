<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkcartitems_model extends zkModel{

		public $id = '';
		public $cartid = '';
		public $name = '';
		public $productid = '';
		public $price = '';
		public $quantity = 0;
		public $status = 1;
		public $created = '';

		function __construct(){
			parent::__construct(zijkart::$db->prefix.'zijkart_cartitems','id');
		}

		public function addtocartitem($cartid,$productname,$productid,$quantity){
			if(!is_numeric($cartid)) return false;
			if(!is_numeric($productid)) return false;
			if(!is_numeric($quantity)) return false;
			//check if the cart already have product
			$query = "SELECT item.id, item.quantity, item.status FROM `".zijkart::$db->prefix."zijkart_cartitems` AS item WHERE item.cartid = ".$cartid." AND item.productid = ".$productid;
			$result = zijkart::$db->get_row($query);
			if($result){
				$data['id'] = $result->id;
				$data['quantity'] = $result->quantity + $quantity;
				$data['created'] = date_i18n('Y-m-d H:i:s');
			}else{
				$query = "SELECT product.isdiscount,product.discounttype,product.discount,product.price FROM `".zijkart::$db->prefix."zijkart_products` AS product WHERE product.id = ".$productid;
				$product = zijkart::$db->get_row($query);
				$amount = zijkart::getModel('common')->getPrice($product);
				$data['name'] = $productname;
				$data['productid'] = $productid;
				$data['cartid'] = $cartid;
				$data['price'] = $amount;
				$data['quantity'] = $quantity;
				$data['created'] = date_i18n('Y-m-d H:i:s');
			}
			$data = filter_var_array($data, FILTER_SANITIZE_STRING);
			$this->bind($data);
			$this->store();
			return;
		}

	}
?>
<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkpurchasehistory_model extends zkModel{

		public $id = '';
		public $uid = '';
		public $name = '';
		public $email = '';
		public $amount = '';
		public $paymentverified = '';
		public $status = '';
		public $created = '';
		public $token = '';
		public $payer_firstname = '';
		public $payer_lastname = '';
		public $payer_email = '';
		public $payer_address = '';
		public $payer_contact = '';
		public $amountpaid = '';
		public $paymentmethod = '';

		function __construct(){
			parent::__construct(zijkart::$db->prefix.'zijkart_cart','id');
		}

		public function getData($layout){
			switch($layout){
				case "admin_listing":
					$this->getListing();
				break;
				case "admin_cartdetail":
					$cartid = zijkartRequest::getValue('cartid');
					$this->getCartDetailByid($cartid);
				break;
			}
		}

		private function getCartDetailByid($cartid){
			if(!is_numeric($cartid)) return false;			
			$query = "SELECT * FROM `".zijkart::$db->prefix."zijkart_cart` WHERE id = ".$cartid;
			$cart = zijkart::$db->get_row($query);
			$query = "SELECT * FROM `".zijkart::$db->prefix."zijkart_cartitems` WHERE cartid = ".$cartid;
			$cartitems = zijkart::$db->get_results($query);
			zijkart::add_d('cart',$cart);
			zijkart::add_d('cartitems',$cartitems);
		}

		private function getListing(){
			$inquery = '';
			$search_wpname = zijkartRequest::getFilterValue('search_wpname');
			if($search_wpname){
				$inquery .= " AND name LIKE '%".$search_wpname."%' ";
			}
			$search_wpemail = zijkartRequest::getFilterValue('search_wpemail');
			if($search_wpemail){
				$inquery .= " AND email LIKE '%".$search_wpemail."%' ";
			}
			$search_name = zijkartRequest::getFilterValue('search_name');
			if($search_name){
				$inquery .= " AND (payer_firstname LIKE '%".$search_name."%' OR payer_lastname LIKE '%".$search_name."%')";
			}
			$search_email = zijkartRequest::getFilterValue('search_email');
			if($search_email){
				$inquery .= " AND payer_email LIKE '%".$search_email."%'";
			}
			$search_paymentverified = zijkartRequest::getFilterValue('search_paymentverified');
			if($search_paymentverified){
				if(is_numeric($search_paymentverified)){
					$inquery .= " AND paymentverified = ".$search_paymentverified." ";
				}
			}
			$query = "SELECT COUNT(id) FROM `".zijkart::$db->prefix."zijkart_cart` WHERE status = status ".$inquery;
			$total = zijkart::$db->get_var($query);
			$pagination = zijkart::pagination($total);
			$query = "SELECT *
						FROM `".zijkart::$db->prefix."zijkart_cart` 
						WHERE status = status ".$inquery."
						ORDER BY created DESC LIMIT ".zijkart::$pageOffset.", ".zijkart::$pageLimit;
			$results = zijkart::$db->get_results($query);
			$search_form = (object) array(
							'search_wpname' => $search_wpname,
							'search_wpemail' => $search_wpemail,
							'search_name' => $search_name,
							'search_email' => $search_email,
							'search_paymentverified' => $search_paymentverified
							);
			zijkart::add_d('search_form',$search_form);
			zijkart::add_d('carts',$results);
			zijkart::add_d('pagination',$pagination);
		}

	}
?>
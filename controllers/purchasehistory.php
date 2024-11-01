<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkpurchasehistory_controller{

		public $model = null;

		function __construct(){
			$this->model = zijkart::getModel('purchasehistory');
		}

		public function init(){
			$layout = zijkartRequest::getLayout('zkl','listing');
			$this->model->getData($layout);
			require_once(ZIJKART_PLUGIN_DIR."views/purchasehistory/$layout.php");
		}

		public function delete(){
			$id = zijkartRequest::getValue('id');
			$this->model->delete($id);
			$url = "admin.php?page=zk_purchasehistory";
			wp_redirect($url);
			exit;
		}

		public function save(){
			$this->model->save();
			$url = "admin.php?page=zk_purchasehistory";
			wp_redirect($url);
			exit;
		}

	}
	
?>

<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkproduct_controller{

		public $model = null;

		function __construct(){
			$this->model = zijkart::getModel('product');
		}

		public function init(){
			$layout = zijkartRequest::getLayout('zkl','listing');
			$this->model->getData($layout);
			zijkart::loadTemplate('product',$layout);
		}

		public function delete(){
			$id = zijkartRequest::getValue('id');
			$this->model->delete($id);
			$url = "admin.php?page=zk_product";
			wp_redirect($url);
			exit;
		}

		public function save(){
			$this->model->save();
			$url = "admin.php?page=zk_product&zkl=productimages&productid=".$this->model->id;
			wp_redirect($url);
			exit;
		}

	}
	
?>

<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkcart_controller{

		public $model = null;

		function __construct(){
			$this->model = zijkart::getModel('cart');
		}

		public function init(){
			$layout = zijkartRequest::getLayout('zkl','mycart');
			$this->model->getData($layout);
			require_once(ZIJKART_PLUGIN_DIR."views/cart/$layout.php");
		}

		public function notified(){
			$classname = zijkartRequest::getValue('zijclass');
			$class = new $classname;
			$class->getnotification();
		}

		public function getpaid(){
			$classname = zijkartRequest::getValue('zijclass');
			$class = new $classname;
			$class->placeorder();
		}

	}
	
?>

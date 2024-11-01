<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkoption_controller{

		public $model = null;

		function __construct(){
			$this->model = zijkart::getModel('option');
		}

		public function init(){
			$layout = zijkartRequest::getLayout('zkl','options');
			$this->model->getData($layout);
			require_once(ZIJKART_PLUGIN_DIR."views/option/$layout.php");
		}

		public function save(){
			$this->model->save();
			$url = "admin.php?page=zk_option";
			wp_redirect($url);
			exit;
		}

	}
	
?>

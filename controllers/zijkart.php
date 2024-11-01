<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkzijkart_controller{

		public function init(){
			$layout = zijkartRequest::getLayout('zkl','controlpanel');
			$model = zijkart::getModel('zijkart');
			$model->getData($layout);
			require_once(ZIJKART_PLUGIN_DIR."views/zijkart/$layout.php");
		}
	}
	
?>

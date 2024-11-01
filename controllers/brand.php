<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkbrand_controller{

		public $model = null;

		function __construct(){
			$this->model = zijkart::getModel('brand');
		}

		public function init(){
			$layout = zijkartRequest::getLayout('zkl','listing');
			$this->model->getData($layout);
			require_once(ZIJKART_PLUGIN_DIR."views/brand/$layout.php");
		}

		public function delete(){
			$id = zijkartRequest::getValue('id');
			$this->model->delete($id);
			$url = "admin.php?page=zk_brand";
			wp_redirect($url);
			exit;
		}

		public function save(){
			$this->model->save();
			$url = "admin.php?page=zk_brand";
			wp_redirect($url);
			exit;
		}

		public function getimagebyid(){
			$id = zijkartRequest::getValue('imageid');
			$filename = $this->model->getFileNameById($id);
			$mimetype = image_type_to_mime_type(exif_imagetype($filename));
			header('Content-Type: $mimetype');
			echo file_get_contents($filename);
			exit;
		}

	}
	
?>

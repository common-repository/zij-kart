<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkproductimages_controller{

		public $model = null;

		function __construct(){
			$this->model = zijkart::getModel('productimages');
		}

		public function save(){
			$this->model->save();
			$url = "admin.php?page=zk_product&zkl=listing&productid=".$this->model->productid;
			wp_redirect($url);
			exit;
		}

		public function makeimagedefault(){
			$id = zijkartRequest::getValue('id');
			$productid = zijkartRequest::getValue('productid');
			$this->model->makeImagedefault($productid,$id);
			$url = "admin.php?page=zk_product&zkl=productimages&productid=".$productid;
			wp_redirect($url);
			exit;
		}

		public function deleteimage(){
			$id = zijkartRequest::getValue('id');
			$productid = zijkartRequest::getValue('productid');
			$this->model->deleteImage($productid,$id);
			$url = "admin.php?page=zk_product&zkl=productimages&productid=".$productid;
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

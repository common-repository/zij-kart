<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkproductimages_model extends zkModel{

		public $id = '';
		public $productid = '';
		public $variationcolor = '';
		public $photo = '';
		public $photosize = '';
		public $isdefault = '';
		public $status = 1;
		public $created = '';

		function __construct(){
			parent::__construct(zijkart::$db->prefix.'zijkart_product_images','id');
		}

		public function save(){
			$uploadimage = false;
			//Store data for the variation 1
			if($_FILES['variation1image']['size'][0] > 0){
				$variation = zijkartRequest::getValue('variation1');
				$productid = zijkartRequest::getValue('productid');
				$files = array();
				foreach ($_FILES['variation1image'] as $k => $l) {
					foreach ($l as $i => $v) {
						if (!array_key_exists($i, $files))
							$files[$i] = array();
						$files[$i][$k] = $v;
					}
				}				
				foreach ($files as $file) {
					$result = $this->uploadPhoto($file,$productid);
					if($result != null){
						$uploadimage = true;
						$this->id = '';
						$data['productid'] = $productid;
						$data['variationcolor'] = $variation;
						$data['photo'] = $result;
						$data['photosize'] = $file['size'];
						$data['isdefault'] = 0;
						$data['created'] = date_i18n(zijkart::getOption('dateformat'));
						$data = filter_var_array($data, FILTER_SANITIZE_STRING);
						$this->bind($data);
						$this->store();						
					}
				}      
			}
			//Store data for the variation 2
			if($_FILES['variation2image']['size'][0] > 0){
				$variation = zijkartRequest::getValue('variation2');
				$productid = zijkartRequest::getValue('productid');
				$files = array();
				foreach ($_FILES['variation2image'] as $k => $l) {
					foreach ($l as $i => $v) {
						if (!array_key_exists($i, $files))
							$files[$i] = array();
						$files[$i][$k] = $v;
					}
				}				
				foreach ($files as $file) {
					$result = $this->uploadPhoto($file,$productid);
					if($result != null){
						$uploadimage = true;
						$this->id = '';
						$data['productid'] = $productid;
						$data['variationcolor'] = $variation;
						$data['photo'] = $result;
						$data['photosize'] = $file['size'];
						$data['isdefault'] = 0;
						$data['created'] = date_i18n(zijkart::getOption('dateformat'));
						$data = filter_var_array($data, FILTER_SANITIZE_STRING);
						$this->bind($data);
						$this->store();						
					}
				}      
			}
			if($uploadimage == true){
				if(is_numeric($productid)){
					$query = "UPDATE `".zijkart::$db->prefix."zijkart_product_images` SET isdefault = 0 WHERE productid = ".$productid;
					zijkart::$db->query($query);
				}
				if(is_numeric($this->id)){
					$query = "UPDATE `".zijkart::$db->prefix."zijkart_product_images` SET isdefault = 1 WHERE id = ".$this->id;
					zijkart::$db->query($query);
				}
			}
		}

		private function uploadPhoto($file,$id){
			$file_name = null;
			if($file['size'] > 0){
				$directory = zijkart::$uploaddir.'/'.zijkart::getOption('directory');
				if(!file_exists($directory))
					zijkart::makeDir($directory);
				$directory .= '/products';
				if(!file_exists($directory))
					zijkart::makeDir($directory);
				$directory .= '/product_'.$id;
				if(!file_exists($directory))
					zijkart::makeDir($directory);
                $file_name = str_replace(' ', '_', $file['name']);
                $namearray = explode('.', $file_name);
                $file_name = $namearray[0];
	            zijkart::includeLib('class.upload.php');
				$image = new Upload($file); 
				if ($image->uploaded) {
				   	// save uploaded image with a new name
				   	$image->file_new_name_body = $file_name;
					$image->image_resize          = true;
					$image->image_ratio_fill      = true;
					$image->image_y               = 600;
					$image->image_x               = 600;
					$image->image_text            = "zijsoft.com";
					$image->image_text_background = '#000000';
					$image->image_text_background_opacity = 50;
					$image->image_text_padding    = 10;
					$image->image_text_x          = -5;
					$image->image_text_y          = -5;
					$image->image_text_line_spacing = 10;
				   	$image->Process($directory);
				   	if ($image->processed) {
						//echo 'image renamed "foo" copied';
						$file_name = $image->file_dst_name;
				   	} else {
						//echo 'error : ' . $image->error;
						$file_name = null;
				   	}   
				}  
			}
			return $file_name;
		}

		public function delete($id){
			if(!is_numeric($id)) return false;
			if($this->isdependent($id) == 0){
				$query = "DELETE FROM `".zijkart::$db->prefix."zijkart_product_images` WHERE id = $id";
				if(zijkart::$db->query($query)){
					return true;
				}else{
					return false;
				}
			}
		}

		private function isdependent($id){
			if(!is_numeric($id)) return false;
			$query = "SELECT COUNT(id) FROM `".zijkart::$db->prefix."zijkart_product_images` WHERE id = $id AND isdefault = 1";
			$result = zijkart::$db->get_var($query);
			return $result;
		}

		public function makeImagedefault($productid,$id){
			if(!is_numeric($productid)) return false;
			if(!is_numeric($id)) return false;
			$query = "UPDATE `".zijkart::$db->prefix."zijkart_product_images` SET isdefault = 0 WHERE productid = $productid";
			zijkart::$db->query($query);
			$query = "UPDATE `".zijkart::$db->prefix."zijkart_product_images` SET isdefault = 1 WHERE id = $id";
			zijkart::$db->query($query);
			return;
		}

		public function deleteImage($productid,$id){
			if(!is_numeric($productid)) return false;
			if(!is_numeric($id)) return false;
			$query = "SELECT photo FROM `".zijkart::$db->prefix."zijkart_product_images` WHERE id = $id";
			$photo = zijkart::$db->get_var($query);
			$query = "DELETE FROM `".zijkart::$db->prefix."zijkart_product_images` WHERE id = $id";
			zijkart::$db->query($query);
			$photo = zijkart::$uploaddir.'/'.zijkart::getOption('directory').'/products/product_'.$productid.'/'.$photo;
			unlink($photo);
			return;
		}

		public function getFileNameById($id){
	        if(!is_numeric($id)){
	            return ZIJKART_PLUGIN_URL.'includes/images/noimage.png';
	        }
	        $query = "SELECT photo,productid FROM `".zijkart::$db->prefix."zijkart_product_images` WHERE id = $id";
	        $result = zijkart::$db->get_row($query);
	        return zijkart::$uploadurl.'/'.zijkart::getOption('directory').'/products/product_'.$result->productid.'/'.$result->photo;
		}

	}
?>
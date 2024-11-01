<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkproduct_model extends zkModel{

		public $id = '';
		public $title = '';
		public $alias = '';
		public $categoryid = '';
		public $brandid = '';
		public $description = '';
		public $features = '';
		public $price = 0;
		public $isdiscount = 0;
		public $discount = 0;
		public $discounttype = 0;
		public $discountstart = '';
		public $discountend = '';
		public $quantity = 0;
		public $expiry = '';
		public $status = 1;
		public $created = '';

		function __construct(){
			parent::__construct(zijkart::$db->prefix.'zijkart_products','id');
		}

		public function getData($layout){
			switch($layout){
				case "admin_listing":
					$this->getListing();
				break;
				case "admin_form":
					$id = zijkartRequest::getValue('id');
					$this->getFormData($id);
				break;
				case "admin_productimages":
					$id = zijkartRequest::getValue('productid');
					$this->getProductForImages($id);
				break;
				case "viewproduct":
					$id = zijkartRequest::getValue('zkid');
					$id = zijkart::parseID($id);
					$this->getProductDetail($id);
				break;
				case "products":
					$catid = zijkartRequest::getValue('zkcatid');
					$var['category'] = $catid;
					$brandid = zijkartRequest::getValue('zkbrandid');
					$var['brand'] = $brandid;
					zijkart::add_d('shortcodes_args',$var);
					$this->getProductsForListing();
				break;
			}
		}

		private function getProductDetail($id){
			if(!is_numeric($id)) return false;
			$query = "SELECT product.*, category.title AS categorytitle, brand.title AS brandtitle 
						FROM `".zijkart::$db->prefix."zijkart_products` AS product 
						JOIN `".zijkart::$db->prefix."zijkart_categories` AS category ON category.id = product.categoryid 
						LEFT JOIN `".zijkart::$db->prefix."zijkart_brands` AS brand on brand.id = product.brandid 
						WHERE product.id = $id";
			$product = zijkart::$db->get_row($query);
			zijkart::add_d('product',$product);
			$query = "SELECT * FROM `".zijkart::$db->prefix."zijkart_product_images` WHERE productid = $id";
			$productimages = zijkart::$db->get_results($query);
			zijkart::add_d('productimages',$productimages);
			zijkart::add_d('productid',$id);
		}

		private function getProductForImages($id){
			if(!is_numeric($id)) return false;
			$query = "SELECT product.*, category.title AS categorytitle, brand.title AS brandtitle 
						FROM `".zijkart::$db->prefix."zijkart_products` AS product 
						JOIN `".zijkart::$db->prefix."zijkart_categories` AS category ON category.id = product.categoryid 
						LEFT JOIN `".zijkart::$db->prefix."zijkart_brands` AS brand on brand.id = product.brandid 
						WHERE product.id = $id";
			$product = zijkart::$db->get_row($query);
			zijkart::add_d('product',$product);
			$query = "SELECT * FROM `".zijkart::$db->prefix."zijkart_product_images` WHERE productid = $id";
			$productimages = zijkart::$db->get_results($query);
			zijkart::add_d('productimages',$productimages);
			zijkart::add_d('productid',$id);
		}

		private function getFormData($id){
			if(!is_numeric($id)) return false;
			$query = "SELECT * FROM `".zijkart::$db->prefix."zijkart_products` WHERE id = $id";
			$result = zijkart::$db->get_row($query);
			zijkart::add_d('form',$result);
		}

		private function getListing(){
			$search_title = zijkartRequest::getFilterValue('search_title');
			$inquery = '';
			if($search_title){
				$inquery .= " AND product.title LIKE '%".$search_title."%' ";
			}
			$search_brand = zijkartRequest::getFilterValue('search_brand');
			if($search_brand){
				if(is_numeric($search_brand)){
					$inquery .= " AND product.brandid = ".$search_brand." ";
				}
			}
			$search_category = zijkartRequest::getFilterValue('search_category');
			if($search_category){
				if(is_numeric($search_category)){
					$inquery .= " AND product.categoryid = ".$search_category." ";
				}
			}
			$search_status = zijkartRequest::getFilterValue('search_status');
			if($search_status){
				if(is_numeric($search_status)){
					$inquery .= " AND product.status = ".$search_status." ";
				}
			}
			$query = "SELECT COUNT(product.id) 
						FROM `".zijkart::$db->prefix."zijkart_products` AS product 
						JOIN `".zijkart::$db->prefix."zijkart_categories` AS category ON category.id = product.categoryid 
						WHERE product.status = product.status ".$inquery;
			$total = zijkart::$db->get_var($query);
			$pagination = zijkart::pagination($total);
			$query = "SELECT product.*,category.title AS categorytitle,brand.title AS brandtitle, pimgs.photo  
						FROM `".zijkart::$db->prefix."zijkart_products` AS product 
						JOIN `".zijkart::$db->prefix."zijkart_categories` AS category ON category.id = product.categoryid 
						LEFT JOIN `".zijkart::$db->prefix."zijkart_brands` AS brand ON brand.id = product.brandid 
						LEFT JOIN `".zijkart::$db->prefix."zijkart_product_images` AS pimgs ON ( pimgs.productid = product.id AND pimgs.isdefault = 1 ) 
						WHERE product.status = product.status ".$inquery."
						ORDER BY product.created DESC LIMIT ".zijkart::$pageOffset.", ".zijkart::$pageLimit;
			$results = zijkart::$db->get_results($query);
			$search_form = (object) array(
							'search_title' => $search_title,
							'search_brand' => $search_brand,
							'search_category' => $search_category,
							'search_status' => $search_status
							);
			zijkart::add_d('search_form',$search_form);
			zijkart::add_d('products',$results);
			zijkart::add_d('pagination',$pagination);
		}

		public function save(){
			$data = zijkartRequest::getServerArray('POST');
			$data['created'] = ($data['created'] == '') ? date_i18n('Y-m-d H:i:s') : date('Y-m-d H:i:s',strtotime($data['created']));
			$data['expiry'] = ($data['expiry'] == '') ? date_i18n('Y-m-d H:i:s') : date('Y-m-d H:i:s',strtotime($data['expiry']));
			$data['alias'] = ($data['alias'] == '') ? strtolower(str_replace(' ', '-', $data['title'])) : $data['alias'];
			$titles = $data['featuretitles'];
			$featurearray = array();
			for($i = 0; $i < COUNT($data['featuretitles']); $i++){
				$title = $data['featuretitles'][$i];
				$value = $data['featurevalues'][$i];
				$featurearray[$title] = $value;
			}
			$data = filter_var_array($data, FILTER_SANITIZE_STRING);
			$data['description'] = wpautop(wptexturize(stripslashes($_POST['description'])));
			$data['features'] = serialize($featurearray);
			$this->bind($data);
			if($this->store()){
				$productid = $this->id;
				$this->uploadPhoto($productid);
			}
		}

		private function uploadPhoto($id){
			$file_name = null;
			if($_FILES['photo']['size'] > 0){
				$directory = zijkart::$uploaddir.'/'.zijkart::getOption('directory');
				zijkart::makeDir($directory);
				$directory .= '/products';
				zijkart::makeDir($directory);
				$directory .= '/product_'.$id;
				zijkart::makeDir($directory);
                $file_name = str_replace(' ', '_', $_FILES['photo']['name']);
                $namearray = explode('.', $file_name);
                $file_name = $namearray[0];
                $file_tmp = $_FILES['photo']['tmp_name']; 
	            array_map('unlink', glob("$directory/*"));
	            zijkart::includeLib('class.upload.php');
				$image = new Upload($_FILES['photo']); 
				if ($image->uploaded) {
				   	// save uploaded image with a new name
				   	$image->file_new_name_body = $file_name;
					$image->image_resize          = true;
					$image->image_ratio_fill      = true;
					$image->image_y               = 300;
					$image->image_x               = 300;
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
						$file_name = '';
				   	}   
				}  
			}
			return $file_name;
		}

		public function delete($id){
			if(!is_numeric($id)) return false;
			if($this->isdependent($id) == 0){
				$query = "DELETE FROM `".zijkart::$db->prefix."zijkart_products` WHERE id = $id";
				if(zijkart::$db->query($query)){
					return true;
				}else{
					return false;
				}
			}
		}

		private function isdependent($id){
			if(!is_numeric($id)) return false;
			return true;
			$query = "SELECT COUNT(p.id) FROM `".zijkart::$db->prefix."zijkart_products` AS p WHERE p.brandid = $id";
			$result = zijkart::$db->get_var($query);
			return $result;
		}

		public function getProductsForListing(){
			$params = zijkart::get_d('shortcodes_args');
			$inquery = '';
			if(is_numeric($params['category'])){
				$inquery .= ' AND product.categoryid = '.$params['category'];
			}
			$brandoption = zijkart::getOption('brandhide');
			if($brandoption == 1){
				if(is_numeric($params['brand'])){
					$inquery .= ' AND product.brandid = '.$params['brand'];
				}
			}
			$query = "SELECT COUNT(product.id) 
						FROM `".zijkart::$db->prefix."zijkart_products` AS product 
						JOIN `".zijkart::$db->prefix."zijkart_categories` AS category ON category.id = product.categoryid 
						WHERE product.status = 1 AND DATE(product.expiry) >= CURDATE() ";
			$query .= $inquery;
			$total = zijkart::$db->get_var($query);
			$pagination = zijkart::pagination($total);
			$query = "SELECT product.title,product.alias,category.title AS categorytitle,brand.title AS brandtitle,brand.id AS brandid,productimage.photo,product.id,product.price,product.isdiscount,product.discounttype,product.discountstart,
						product.discountend,product.discount,productimage.id AS imageid,product.quantity
						FROM `".zijkart::$db->prefix."zijkart_products` AS product 
						JOIN `".zijkart::$db->prefix."zijkart_categories` AS category ON category.id = product.categoryid 
						LEFT JOIN `".zijkart::$db->prefix."zijkart_brands` AS brand ON brand.id = product.brandid 
						LEFT JOIN `".zijkart::$db->prefix."zijkart_product_images` AS productimage ON (productimage.productid = product.id AND productimage.isdefault = 1 ) 
						WHERE product.status = 1 AND DATE(product.expiry) >= CURDATE() ";
			$query .= $inquery;
			$query .= " ORDER BY product.created LIMIT ".zijkart::$pageOffset.", ".zijkart::$pageLimit;
			$products = zijkart::$db->get_results($query);
			zijkart::add_d('products',$products);
			zijkart::add_d('pagination',$pagination);
			return;
		}

	}
?>

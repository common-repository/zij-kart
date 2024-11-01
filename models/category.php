<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkcategory_model extends zkModel{

		public $id = '';
		public $title = '';
		public $alias = '';
		public $photo = '';
		public $status = 1;
		public $created = '';

		function __construct(){
			parent::__construct(zijkart::$db->prefix.'zijkart_categories','id');
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
			}
		}

		private function getFormData($id){
			if(!is_numeric($id)) return false;
			$query = "SELECT * FROM `".zijkart::$db->prefix."zijkart_categories` WHERE id = $id";
			$result = zijkart::$db->get_row($query);
			zijkart::add_d('form',$result);
		}

		private function getListing(){
			$search_title = zijkartRequest::getFilterValue('search_title');
			$inquery = '';
			if($search_title){
				$inquery .= " AND title LIKE '%".$search_title."%' ";
			}
			$search_status = zijkartRequest::getFilterValue('search_status');
			if($search_status){
				if(is_numeric($search_status)){
					$inquery .= " AND status = ".$search_status." ";
				}
			}
			$query = "SELECT COUNT(id) FROM `".zijkart::$db->prefix."zijkart_categories` WHERE status = status ".$inquery;
			$total = zijkart::$db->get_var($query);
			$pagination = zijkart::pagination($total);
			$query = "SELECT id,title,photo,status,created 
						FROM `".zijkart::$db->prefix."zijkart_categories` 
						WHERE status = status ".$inquery."
						ORDER BY created DESC LIMIT ".zijkart::$pageOffset.", ".zijkart::$pageLimit;
			$results = zijkart::$db->get_results($query);
			$search_form = (object) array(
							'search_title' => $search_title,
							'search_status' => $search_status
							);
			zijkart::add_d('search_form',$search_form);
			zijkart::add_d('categories',$results);
			zijkart::add_d('pagination',$pagination);
		}

		public function save(){
			$data = zijkartRequest::getServerArray('POST');
			$data['created'] = ($data['created'] == '') ? date_i18n('Y-m-d H:i:s') : $data['created'];
			$data['alias'] = ($data['alias'] == '') ? strtolower(str_replace(' ', '-', $data['title'])) : $data['alias'];
			$data = filter_var_array($data, FILTER_SANITIZE_STRING);
			$this->bind($data);
			if($this->store()){
				$categoryid = $this->id;
				$filename = $this->uploadPhoto($categoryid);
				if($filename != null){
					$query = "UPDATE `".zijkart::$db->prefix."zijkart_categories` SET photo = '".$filename."' WHERE id = ".$categoryid;
					zijkart::$db->query($query);
				}
			}		
		}

		private function uploadPhoto($id){
			$file_name = null;
			if($_FILES['photo']['size'] > 0){
				$directory = zijkart::$uploaddir.'/'.zijkart::getOption('directory');
				if(!file_exists($directory))
					zijkart::makeDir($directory);
				$directory .= '/categories';
				if(!file_exists($directory))
					zijkart::makeDir($directory);
				$directory .= '/category_'.$id;
				if(!file_exists($directory))
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
				$query = "DELETE FROM `".zijkart::$db->prefix."zijkart_categories` WHERE id = $id";
				if(zijkart::$db->query($query)){
					return true;
				}else{
					return false;
				}
			}
		}

		private function isdependent($id){
			if(!is_numeric($id)) return false;
			$query = "SELECT COUNT(p.id) FROM `".zijkart::$db->prefix."zijkart_products` AS p WHERE p.categoryid = $id";
			$result = zijkart::$db->get_var($query);
			return $result;
		}

		public function getCategoriesForCombo(){
			$query = "SELECT id AS value, title AS text FROM `".zijkart::$db->prefix."zijkart_categories` WHERE status = 1";
			$list = zijkart::$db->get_results($query);
			$a = (object) array('value' => '', 'text' => __('Select category','zijkart'));
			array_unshift($list, $a);
			return $list;
		}

		public function getFileNameById($id){
	        if(!is_numeric($id)){
	            return ZIJKART_PLUGIN_URL.'includes/images/noimage.png';
	        }
	        $query = "SELECT photo,id FROM `".zijkart::$db->prefix."zijkart_categories` WHERE id = $id";
	        $result = zijkart::$db->get_row($query);
	        return zijkart::$uploadurl.'/'.zijkart::getOption('directory').'/categories/category_'.$result->id.'/'.$result->photo;
		}

		public function getCategoriesForListing(){
			$params = zijkart::get_d('shortcodes_args');
			$inquery = '';
			if(is_numeric($params['limit'])){
				$inquery .= ' LIMIT '.$params['limit'];
			}
			$query = "SELECT category.title AS categorytitle,category.id AS categoryid, category.photo AS photo,
						(SELECT COUNT(id) FROM `".zijkart::$db->prefix."zijkart_products` WHERE categoryid = category.id AND status = 1 AND DATE(expiry) >= CURDATE()) AS products
						FROM `".zijkart::$db->prefix."zijkart_categories` AS category  
						WHERE  category.status = 1 ORDER BY category.title ";
			$query .= $inquery;
			$categories = zijkart::$db->get_results($query);
			zijkart::add_d('categories',$categories);
			return;

		}

	}
?>
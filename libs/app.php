<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	class zijkart{

		public static $d = null;
		public static $db = null;
		public static $option = null;
		public static $uploaddir = null;
		public static $uploadurl = null;
		public static $pageLimit = null;
		public static $pageOffset = null;

		public function init(){
	        global $wpdb;
			zijkart::$db = $wpdb;
			$wpdir = wp_upload_dir();
			self::$uploaddir = $wpdir['basedir'];
			self::$uploadurl = $wpdir['baseurl'];
			$this->updateOption();
			$this->addLibs();
	        add_action("wp_ajax_zijkart_ajax", array($this, "zijkart_ajaxcall")); // when user is login
	        add_action("wp_ajax_nopriv_zijkart_ajax", array($this, "zijkart_ajaxcall")); // when user is not login
		}
		
		public static function deactivate(){
			$query = "DROP TABLE IF EXISTS `".zijkart::$db->prefix."zijkart_brands`;";
			zijkart::$db->query($query);
			$query = "DROP TABLE IF EXISTS `".zijkart::$db->prefix."zijkart_cart`;";
			zijkart::$db->query($query);
			$query = "DROP TABLE IF EXISTS `".zijkart::$db->prefix."zijkart_cartitems`;";
			zijkart::$db->query($query);
			$query = "DROP TABLE IF EXISTS `".zijkart::$db->prefix."zijkart_categories`;";
			zijkart::$db->query($query);
			$query = "DROP TABLE IF EXISTS `".zijkart::$db->prefix."zijkart_options`;";
			zijkart::$db->query($query);
			$query = "DROP TABLE IF EXISTS `".zijkart::$db->prefix."zijkart_products`;";
			zijkart::$db->query($query);
			$query = "DROP TABLE IF EXISTS `".zijkart::$db->prefix."zijkart_product_images`;";
			zijkart::$db->query($query);
	}
		
		public static function activate(){
		    $role = get_role('administrator');
		    $role->add_cap('zijkart_manage');
			$optiontable = zijkart::$db->prefix.'';
			if(!in_array('zijkart_options', zijkart::$db->tables)) {
			     //New installation
				$query = "CREATE TABLE `".zijkart::$db->prefix."zijkart_brands` (`id` int(11) NOT NULL AUTO_INCREMENT,`title` varchar(250) NOT NULL,`alias` varchar(250) NOT NULL,`photo` varchar(100) NOT NULL,`status` tinyint(1) NOT NULL,`created` datetime NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM;";
				zijkart::$db->query($query);
				$query = "CREATE TABLE `".zijkart::$db->prefix."zijkart_cart` (`id` int(11) NOT NULL AUTO_INCREMENT,`uid` int(11) NOT NULL,`name` varchar(250) NOT NULL,`email` varchar(150) NOT NULL,`amount` int(11) NOT NULL,`paymentverified` tinyint(1) NOT NULL,`status` tinyint(1) NOT NULL,`created` datetime NOT NULL,`token` text NOT NULL,`payer_firstname` varchar(250) NOT NULL,`payer_lastname` varchar(250) NOT NULL,`payer_email` varchar(360) NOT NULL,`payer_address` text NOT NULL,`payer_contact` varchar(100) NOT NULL,`amountpaid` varchar(50) NOT NULL,`payment_method` varchar(500) NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM;";
				zijkart::$db->query($query);
				$query = "CREATE TABLE `".zijkart::$db->prefix."zijkart_cartitems` (`id` int(11) NOT NULL AUTO_INCREMENT,`cartid` int(11) NOT NULL,`productid` int(11) NOT NULL,`name` varchar(250) NOT NULL,`price` int(11) NOT NULL,`quantity` int(11) NOT NULL,`status` tinyint(1) NOT NULL,`created` datetime NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM;";
				zijkart::$db->query($query);
				$query = "CREATE TABLE `".zijkart::$db->prefix."zijkart_categories` (`id` int(11) NOT NULL AUTO_INCREMENT,`title` varchar(250) NOT NULL,`alias` varchar(250) NOT NULL,`photo` varchar(100) NOT NULL,`status` tinyint(1) NOT NULL,`created` datetime NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM;";
				zijkart::$db->query($query);
				$query = "CREATE TABLE `".zijkart::$db->prefix."zijkart_options` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(100) NOT NULL,`value` varchar(300) NOT NULL,`for` varchar(50) NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM;";
				zijkart::$db->query($query);
				$query = "INSERT INTO `".zijkart::$db->prefix."zijkart_options` (`id`, `name`, `value`, `for`) VALUES (1, 'dateformat', 'Y-m-d', 'default'),(2, 'directory', 'zijkart', 'default'),(3, 'sitetitle', 'Zij Shopping Kart', 'default'),(4, 'showsitetitle', '1', 'default'),(5, 'currencysymbol', '$', 'default'),(6, 'currencysymbolalign', '1', 'default'),(7, 'list_col', '2', 'default'),(8, 'brandoption', '1', 'default'),(9, 'brandhide', '1', 'default'),(10, 'categoryhide', '1', 'default'),(11, 'quantityhide', '1', 'default'),(12, 'defaultpageid', '', 'default'),(13, 'paypal_username', '', 'paypal'),(14, 'paypal_password', '', 'paypal'),(15, 'paypal_signature', '', 'paypal'),(16, 'paypal_enable', '1', 'paypal'),(17, 'page_size', '10', 'default'),(18, 'system_slug', 'zijkart', 'default');";
				zijkart::$db->query($query);
				$query = "CREATE TABLE `".zijkart::$db->prefix."zijkart_products` (`id` int(11) NOT NULL AUTO_INCREMENT,`title` varchar(250) NOT NULL,`alias` varchar(250) NOT NULL,`categoryid` int(11) NOT NULL,`brandid` int(11) NOT NULL,`description` text NOT NULL,`features` text NOT NULL,`price` int(11) NOT NULL,`isdiscount` tinyint(1) NOT NULL,`discount` int(5) NOT NULL,`discounttype` tinyint(1) NOT NULL,`discountstart` datetime NOT NULL,`discountend` datetime NOT NULL,`quantity` int(11) NOT NULL,`expiry` datetime NOT NULL,`status` tinyint(1) NOT NULL,`created` datetime NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM;";
				zijkart::$db->query($query);
				$query = "CREATE TABLE `".zijkart::$db->prefix."zijkart_product_images` (`id` int(11) NOT NULL AUTO_INCREMENT,`productid` int(11) NOT NULL,`variationcolor` varchar(10) DEFAULT NULL,`photo` varchar(150) NOT NULL,`photosize` int(11) NOT NULL,`isdefault` tinyint(1) NOT NULL,`status` tinyint(1) NOT NULL,`created` datetime NOT NULL, PRIMARY KEY (id)) ENGINE=MyISAM;";
				zijkart::$db->query($query);
			}else{
				//Upgrade
		    	$installedversion = zijkart::getOption('current_version');
			}		
		}

		public static function includeLib($lib){
			require_once(ZIJKART_PLUGIN_DIR.'libs/'.$lib);
		}

		public function addLibs(){
			require_once(ZIJKART_PLUGIN_DIR.'libs/zkmodel.php');
			require_once(ZIJKART_PLUGIN_DIR.'libs/zijkartrequest.php');
			require_once(ZIJKART_PLUGIN_DIR.'libs/zijkartHTML.php');
			require_once(ZIJKART_PLUGIN_DIR.'libs/zijkartpayment.php');
			require_once(ZIJKART_PLUGIN_DIR.'libs/zkseo.php');
			if(is_admin()){
				require_once(ZIJKART_PLUGIN_DIR.'libs/adminmenu.php');
			}
			require_once(ZIJKART_PLUGIN_DIR.'libs/shortcodes.php');
		}

		public function updateOption(){
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('zij-kart/zij-kart.php')) {
			    $query = "SELECT opt.name, opt.value FROM `" . zijkart::$db->prefix . "zijkart_options` AS opt WHERE opt.for = 'default'";
			    $option = zijkart::$db->get_results($query);
			    foreach ($option as $o) {
			        self::$option[$o->name] = $o->value;
			    }
			}
		}

		public static function taskhandler(){
			$zktask = zijkartRequest::getValue('zktask',null);
			if($zktask !== null){
				if(is_admin()){
					$controller = 'page';
				}else{
					$controller = 'zkm';
				}
				$controller = zijkartRequest::getValue($controller);
				$controller = str_replace('zk_', '', $controller);
				$controller = self::getController($controller);
				$controller->$zktask();
			}
		}

		public static function include_controller($controller){
			require_once(ZIJKART_PLUGIN_DIR."controllers/$controller.php");
			$controller = "zk".$controller."_controller";
			$controller = new $controller();
			$controller->init();
		}

		public static function getModel($model){
			require_once(ZIJKART_PLUGIN_DIR."models/$model.php");
			$model = "zk".$model."_model";
			$model = new $model();
			return $model;
		}

		public static function getController($controller){
			require_once(ZIJKART_PLUGIN_DIR."controllers/$controller.php");
			$controller = "zk".$controller."_controller";
			$controller = new $controller();
			return $controller;
		}

		public static function addCSS(){
			if (is_admin()) {
			    wp_register_style('zijkart-admincss', ZIJKART_PLUGIN_URL.'includes/css/admincss.css');
			    wp_enqueue_style('zijkart-admincss');
			} else {
			    wp_register_style('zijkart-style', ZIJKART_PLUGIN_URL . 'includes/css/style.css');
			    wp_enqueue_style('zijkart-style');
			}
		}

		public static function addJS(){
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
			if(is_admin()){
				wp_enqueue_script( 'zijkart-commonadmin.js', ZIJKART_PLUGIN_URL.'includes/js/commonadmin.js', array(), '1.0.0', true );
			}else{
				wp_enqueue_script( 'zijkart-common.js', ZIJKART_PLUGIN_URL.'includes/js/common.js', array(), '1.0.0', true );
				// Localize the script with new data
				$translation_array = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'qtyerror' => __('Please set how much quantity you need','zijkart'),
					'areyousure' => __('Are you sure?','zijkart'),
				);
				wp_localize_script( 'zijkart-common.js', 'zijkart', $translation_array );
			}
		}

		public static function add_d($key,$value){
			self::$d[$key] = $value;
		}

		public static function get_d($key = null){
			$return  = null;
			if ($key == null){
				$return = self::$d;
			}elseif(self::$d[$key]){
				$return = self::$d[$key];
			}
			return $return;
		}

		public static function isset_d($key){
			return (isset(self::$d[$key]) && self::$d[$key] != '') ? true : false;
		}

		public static function getOption($name){
			return (isset(self::$option[$name])) ? self::$option[$name] : false;
		}

		public static function makeDir($path){
            mkdir($path, 0755);
            $file = $path . '/index.html';
            $handler = fopen($file, 'w') or die(__('Cannot open file', 'zijkart'));
            fclose($handler);
		}

		public static function getPageid() {
			$pageid = self::get_d('pageid');
		    if($pageid != null){
		        return $pageid;
		    }else{
		        $pageid = zijkart::getOption('defaultpageid');
		        return $pageid;
		    }
		}

		public static function setPageID($id) {
		    self::add_d('pageid',$id);
		    return;
		}		

	    function zijkart_ajaxcall() {
	        $model = zijkartRequest::getValue('zkm');
	        $zkajaxtask = zijkartRequest::getValue('zkajaxtask');
	        $result = zijkart::getModel($model)->$zkajaxtask();
	        echo $result;
	        die();
	    }

	    public static function getUID(){
			if(is_user_logged_in()){
				return get_current_user_id();
			}else{
				return 0;
			}
		}

		public static function loadTemplate($view,$layout){			
			$template = locate_template("zijkart/{$view}-{$layout}.php");
			if($template){
				include_once($template);
			}else{
				$template = locate_template("zijkart-{$view}-{$layout}.php");
				if($template){
					include_once($template);
				}else{
					require_once(ZIJKART_PLUGIN_DIR."views/$view/$layout.php");				
				}
			}
		}

		public static function parseID($id){
			if(is_numeric($id)) return $id;
			$array = explode('-', $id);
			$id = $array[count($array) -1];
			return $id;
		}

	    public static function pagination($total) {
	        $page = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
	        self::$pageLimit = zijkart::getOption('page_size'); // records per page
	        self::$pageOffset = ( $page - 1 ) * self::$pageLimit;
	        $pageNumber = ceil($total / self::$pageLimit);
	        $pageNumber = ($pageNumber > 0) ? ceil($pageNumber) : floor($pageNumber);
	        $result = paginate_links(array(
	            'base' => add_query_arg('pagenum', '%#%'),
	            'format' => '',
	            'prev_next' => false,
	            'total' => $pageNumber,
	            'current' => $page,
	            'add_args' => false,
	        ));
	        return $result;
	    }

	}

	add_action('init', 'zijkart_init_session', 1);

	function zijkart_init_session() {
	    if (!session_id())
	        session_start();
	}

?>

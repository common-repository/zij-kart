<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class zkoption_model extends zkModel{

		public $id = '';
		public $name = '';
		public $value = '';
		public $for = '';

		function __construct(){
			parent::__construct(zijkart::$db->prefix.'zijkart_options','id');
		}

		public function getData($layout){
			switch($layout){
				case "admin_options":
					$this->getAllOptions();
				break;
			}
		}

		private function getAllOptions(){
			$query = "SELECT name,value 
						FROM `".zijkart::$db->prefix."zijkart_options` ";
			$results = zijkart::$db->get_results($query);
			zijkart::add_d('options',$results);
		}

		public function save(){
			$data = zijkartRequest::getServerArray('POST');
			foreach($data AS $key => $value){
				$query = "UPDATE `".zijkart::$db->prefix."zijkart_options` SET value = '".$value."' WHERE name = '".$key."'";
				zijkart::$db->query($query);
			}
		}

		public function getOptionsByFor($for){
			$config = array();
			if(in_array('zijkart_options',zijkart::$db->tables)) {
				$query = "SELECT opt.name, opt.value FROM `".zijkart::$db->prefix."zijkart_options` AS opt WHERE opt.for = '".$for."'";
				$results = zijkart::$db->get_results($query);
				foreach($results AS $rs){
					$config[$rs->name] = $rs->value;
				}
				return $config;
			}
		}
	}
?>

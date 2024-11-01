<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class zkModel{

	public $primarykey = '';
	public $tablename = '';
	public $updaterecord = 0;
	public $fields = '';

	function __construct($tablename,$primarykey){
		$this->primarykey = $primarykey;
		$this->tablename = $tablename;
		$this->fields = get_object_vars($this);
		unset($this->fields['primarykey']);
		unset($this->fields['tablename']);
		unset($this->fields['fields']);
		unset($this->fields['updaterecord']);
	}

	function bind($data){
		if(isset($data[$this->primarykey]) && !empty($data[$this->primarykey])){
			$this->updaterecord = 1;
		}
		$columns = array();
		if($this->updaterecord == 1){
			foreach($data AS $key => $value){
				if(isset($this->fields[$key])){
					$columns[$key] = $value;
				}
			}
		}else{
			foreach($this->fields AS $key => $value){
				$columns[$key] = isset($data[$key]) ? $data[$key] : $value;
			}
		}
		$this->fields = $columns;
		return true;
	}

	function store(){
		if($this->updaterecord == 0){
			zijkart::$db->replace($this->tablename,$this->fields);
			if(zijkart::$db->last_error == ''){
				$this->{$this->primarykey} = zijkart::$db->insert_id;				
			}else{
				return false;
			}
		}else{
			zijkart::$db->update($this->tablename,$this->fields,array($this->primarykey => $this->fields[$this->primarykey]));
			if(zijkart::$db->last_error == ''){
				$this->{$this->primarykey} = $this->fields[$this->primarykey];				
			}else{
				return false;
			}
		}
		return true;
	}
}

?>

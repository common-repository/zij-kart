<?php
	/**
	 * @package Zij-Kart
	 * @version 1.1
	 */
	/*
	Plugin Name: Zij Kart
	Plugin URI: http://zijsoft.com
	Description: Zij kart provide the easy and best solution for the online showroom. You can categorized your product. You can easily manage your product category, brand and products with different color options while uploading images for your product.
	Author: Shoaib Rehmat
	Version: 1.1
	Author URI: http://zijsoft.com/
	*/

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	if(!defined('ZIJKART_VERSION')) define('ZIJKART_VERSION', '1.0');
	if(!defined('ZIJKART_PLUGIN_URL')) define('ZIJKART_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	if(!defined('ZIJKART_PLUGIN_DIR')) define('ZIJKART_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

	require_once('libs/app.php');

	register_activation_hook(__FILE__, array('zijkart','activate'));
	register_deactivation_hook(__FILE__, array('zijkart','deactivate'));
	add_action('init',array('zijkart','taskhandler'));

	$app = new zijkart();
	$app->init();
?>

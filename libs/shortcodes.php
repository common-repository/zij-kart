<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
    add_shortcode('zijkart_products', 'zijkart_products_shortcode');

    function zijkart_products_shortcode($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'category' => '',
            'brand' => '',
        );
        $shortcodes_args = shortcode_atts($defaults, $raw_args);
        zijkart::add_d('shortcodes_args',$shortcodes_args);
        zijkart::addCSS();
        zijkart::addJS();
        $pageid = get_the_ID();
        zijkart::setPageID($pageid);
        $layout = zijkartRequest::getLayout('zkl','products');
        if($layout == 'products'){
            zijkart::getModel('product')->getProductsForListing();
            zijkart::loadTemplate('product','products');
        }else{
            $controller = zijkartRequest::getValue('zkm');
            zijkart::include_controller($controller);
        }
        $content .= ob_get_clean();
        return $content;
    }

    add_shortcode('zijkart_categories', 'zijkart_categories_shortcode');

    function zijkart_categories_shortcode($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'limit' => '',
        );
        $shortcodes_args = shortcode_atts($defaults, $raw_args);
        zijkart::add_d('shortcodes_args',$shortcodes_args);
        zijkart::addCSS();
        zijkart::addJS();
        $pageid = get_the_ID();
        zijkart::setPageID($pageid);
        $layout = zijkartRequest::getLayout('zkl','categories');
        if($layout == 'categories'){
            zijkart::getModel('category')->getCategoriesForListing();
            zijkart::loadTemplate('category','categories');
        }else{
            $controller = zijkartRequest::getValue('zkm');
            zijkart::include_controller($controller);
        }
        $content .= ob_get_clean();
        return $content;
    }

    add_shortcode('zijkart_brands', 'zijkart_brands_shortcode');

    function zijkart_brands_shortcode($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'limit' => '',
        );
        $shortcodes_args = shortcode_atts($defaults, $raw_args);
        zijkart::add_d('shortcodes_args',$shortcodes_args);
        zijkart::addCSS();
        zijkart::addJS();
        $pageid = get_the_ID();
        zijkart::setPageID($pageid);
        $layout = zijkartRequest::getLayout('zkl','brands');
        if($layout == 'brands'){
            zijkart::getModel('brand')->getBrandsForListing();
            zijkart::loadTemplate('brand','brands');
        }else{
            $controller = zijkartRequest::getValue('zkm');
            zijkart::include_controller($controller);
        }
        $content .= ob_get_clean();
        return $content;
    }

    add_shortcode('zijkart_cart', 'zijkart_cart_shortcode');

    function zijkart_cart_shortcode($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'limit' => '',
        );
        $shortcodes_args = shortcode_atts($defaults, $raw_args);
        zijkart::add_d('shortcodes_args',$shortcodes_args);
        zijkart::addCSS();
        zijkart::addJS();
        $pageid = get_the_ID();
        zijkart::setPageID($pageid);
        $layout = zijkartRequest::getLayout('zkl','mycart');
        if($layout == 'mycart'){
            zijkart::getModel('cart')->getMyCart();
            zijkart::loadTemplate('cart','mycart');
        }else{
            $controller = zijkartRequest::getValue('zkm');
            zijkart::include_controller($controller);
        }
        $content .= ob_get_clean();
        return $content;
    }

?>
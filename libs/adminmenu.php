<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	add_action('admin_menu', 'zk_add_adminmenu');

	function zk_add_adminmenu(){
        add_menu_page(__('Dashboard', 'zijkart'), // Page title
                __('ZijKart Dashboard', 'zijkart'), // menu title
                'zijkart_manage', // capability
                'zk_zijkart', //menu slug
                'zk_adminPage', // function name
                plugins_url('zijkart/includes/images/admin_menu_icon.png')
        );
        add_submenu_page('zk_zijkart', // parent slug
                __('Category', 'zijkart'), // Page title
                __('Category', 'zijkart'), // menu title
                'zijkart_manage', // capability
                'zk_category', //menu slug
                'zk_adminPage' // function name
        );
        if(zijkart::getOption('brandhide') == 1){
            add_submenu_page('zk_zijkart', // parent slug
                    __('Brands', 'zijkart'), // Page title
                    __('Brands', 'zijkart'), // menu title
                    'zijkart_manage', // capability
                    'zk_brand', //menu slug
                    'zk_adminPage' // function name
            );
        }
        add_submenu_page('zk_zijkart', // parent slug
                __('Products', 'zijkart'), // Page title
                __('Products', 'zijkart'), // menu title
                'zijkart_manage', // capability
                'zk_product', //menu slug
                'zk_adminPage' // function name
        );
        add_submenu_page('zk_zijkart', // parent slug
                __('Options', 'zijkart'), // Page title
                __('Options', 'zijkart'), // menu title
                'zijkart_manage', // capability
                'zk_option', //menu slug
                'zk_adminPage' // function name
        );
        add_submenu_page('zk_zijkart', // parent slug
                __('Purchase history', 'zijkart'), // Page title
                __('Purchase history', 'zijkart'), // menu title
                'zijkart_manage', // capability
                'zk_purchasehistory', //menu slug
                'zk_adminPage' // function name
        );
	}

    function zk_adminPage() {
        zijkart::addCSS();
        zijkart::addJS();
        $page = zijkartRequest::getValue('page');
        $page = str_replace('zk_','',$page);
        zijkart::include_controller($page);
    }

?>

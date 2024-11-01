<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Hooks for the seo
add_filter('redirect_canonical', 'zijkart_redirectcanonical', 10, 2);
add_action('init', 'zijkart_rewritetag', 10, 0);
add_action('init', 'zijkart_addrules', 10, 0);
add_filter('wp_insert_post_data', 'zijkart_insertpostdata', '99', 2);
add_action('save_post', 'zijkart_savepost');
add_action('customize_save_after', 'zijkart_customizersaveafter');
add_action('parse_request', 'zijkart_parserequestdebug');

function zijkart_redirectcanonical($redirect_url, $requested_url) {

    global $wp_rewrite;
    // Abort if not using pretty permalinks, is a feed, or not an archive for the post type 'book'
    if (!$wp_rewrite->using_permalinks() || is_feed())
        return $redirect_url;

    // Get the original query parts
    $redirect = @parse_url($requested_url);
    $original = $redirect_url;
    if (!isset($redirect['query']))
        $redirect['query'] = '';

    // If is year/month/day - append year
    if (is_year() || is_month() || is_day()) {
        $year = get_query_var('year');
        $redirect['query'] = remove_query_arg('year', $redirect['query']);
        $redirect_url = user_trailingslashit(get_post_type_archive_link('book')) . $year;
    }

    // If is month/day - append month
    if (is_month() || is_day()) {
        $month = zeroise(intval(get_query_var('monthnum')), 2);
        $redirect['query'] = remove_query_arg('monthnum', $redirect['query']);
        $redirect_url .= '/' . $month;
    }

    // If is day - append day
    if (is_day()) {
        $day = zeroise(intval(get_query_var('day')), 2);
        $redirect['query'] = remove_query_arg('day', $redirect['query']);
        $redirect_url .= '/' . $day;
    }

    // If is page_id
    if (get_query_var('page_id')) {
        $page_id = get_query_var('page_id');
        $redirect['query'] = remove_query_arg('page_id', $redirect['query']);
        $redirect_url = user_trailingslashit(get_page_link($page_id));
    }
    // If is zkm
    if (get_query_var('zkm')) {
        $zkm = get_query_var('zkm');
        $redirect['query'] = remove_query_arg('zkm', $redirect['query']);
        $query = 'SELECT value FROM `' . zijkart::$db->prefix . 'zijkart_options` WHERE name = "system_slug"';
        $system_slug = zijkart::$db->get_var($query);
        $lastcharactor = substr($redirect_url, -1);
        if($lastcharactor != '/'){
            $redirect_url .= '/';
        }
        $redirect_url .= $system_slug;
    }
    // If is zkl
    if (get_query_var('zkl')) {
        $zkl = get_query_var('zkl');
        $redirect['query'] = remove_query_arg('zkl', $redirect['query']);
        $redirect_url .= '/' . $zkl;
    }
    // If is zkid
    if (get_query_var('zkid')) {
        $zkid = get_query_var('zkid');
        $redirect['query'] = remove_query_arg('zkid', $redirect['query']);
        $redirect_url .= '/' . $zkid;
    }
    // If is zkbrand
    if (get_query_var('zkbrand')) {
        $zkbrand = get_query_var('zkbrand');
        $redirect['query'] = remove_query_arg('zkbrand', $redirect['query']);
        $redirect_url .= 'brand/' . $zkbrand;
    }
    // If is zkcategory
    if (get_query_var('zkcategory')) {
        $zkcategory = get_query_var('zkcategory');
        $redirect['query'] = remove_query_arg('zkcategory', $redirect['query']);
        $redirect_url .= 'category/' . $zkcategory;
    }

    //Search Variables
    switch ($zkl) {
        case 'products':
            if (get_query_var('zk-title')) {
                $zktitle = get_query_var('zk-title');
                $redirect_url .= '/zk-title/' . $zktitle;
            }
            break;
    }
    $redirect['query'] = remove_query_arg('zk-title', $redirect['query']);
    $redirect['query'] = remove_query_arg('zk-cat', $redirect['query']);
    // If paged, apppend pagination
    if (get_query_var('paged') > 0) {
        $paged = (int) get_query_var('paged');
        $redirect['query'] = remove_query_arg('paged', $redirect['query']);

        if ($paged > 1)
            $redirect_url .= user_trailingslashit("/page/$paged", 'paged');
    }
    if ($redirect_url == $original)
        return $original;
    // tack on any additional query vars
    $redirect['query'] = preg_replace('#^\??&*?#', '', $redirect['query']);
    if ($redirect_url && !empty($redirect['query'])) {
        parse_str($redirect['query'], $_parsed_query);
        $_parsed_query = array_map('rawurlencode', $_parsed_query);
        $redirect_url = add_query_arg($_parsed_query, $redirect_url);
    }

    return $redirect_url;
}

function zijkart_rewritetag() {
    add_rewrite_tag('%zkm%', '([^&]+)');
    add_rewrite_tag('%zkl%', '([^&]+)');
    add_rewrite_tag('%zkid%', '([^&]+)');
    add_rewrite_tag('%zkcategory%', '([^&]+)');
    add_rewrite_tag('%zkbrand%', '([^&]+)');
    // Search Variables
    add_rewrite_tag('%zk-title%', '([^&]+)');
    add_rewrite_tag('%zk-cat%', '([^&]+)');
}

function zijkart_addrules() {
    $frontpage_id = get_option('page_on_front');
    $query = 'SELECT value FROM `' . zijkart::$db->prefix . 'zijkart_options` WHERE name = "system_slug"';
    $system_slug = zijkart::$db->get_var($query);
    //viewproduct
    add_rewrite_rule('(.?.+?)/' . $system_slug . '/viewproduct/(.?.+?)/?$', 'index.php?pagename=$matches[1]&zkm=product&zkl=viewproduct&zkid=$matches[2]', 'top');
    add_rewrite_rule($system_slug . '/viewproduct/(.?.+?)/?$', 'index.php?page_id=' . $frontpage_id . '&zkm=product&zkl=viewproduct&zkid=$matches[1]', 'top');
    //mycart
    add_rewrite_rule('(.?.+?)/' . $system_slug . '/mycart/?$', 'index.php?pagename=$matches[1]&zkm=cart&zkl=mycart', 'top');
    add_rewrite_rule($system_slug . '/mycart/?$', 'index.php?page_id=' . $frontpage_id . '&zkm=cart&zkl=mycart', 'top');
    //products
    add_rewrite_rule('(.?.+?)/' . $system_slug . '/products/?$', 'index.php?pagename=$matches[1]&zkm=product&zkl=products', 'top');
    add_rewrite_rule($system_slug . '/products/?$', 'index.php?page_id=' . $frontpage_id . '&zkm=product&zkl=products', 'top');
    //products by brand and category
    add_rewrite_rule('(.?.+?)/' . $system_slug . '/products/brand/(.?.+?)/category/(.?.+?)?$', 'index.php?pagename=$matches[1]&zkm=product&zkl=products&zkbrand=$matches[2]&zkcategory=$matches[3]', 'top');
    add_rewrite_rule($system_slug . '/products/brand/(.?.+?)/category/(.?.+?)?$', 'index.php?page_id=' . $frontpage_id . '&zkm=product&zkl=products&zkbrand=$matches[1]&zkcategory=$matches[2]', 'top');
    //products by brand
    add_rewrite_rule('(.?.+?)/' . $system_slug . '/products/brand/(.?.+?)?$', 'index.php?pagename=$matches[1]&zkm=product&zkl=products&zkbrand=$matches[2]', 'top');
    add_rewrite_rule($system_slug . '/products/brand/(.?.+?)?$', 'index.php?page_id=' . $frontpage_id . '&zkm=product&zkl=products&zkbrand=$matches[1]', 'top');
    //products by category
    add_rewrite_rule('(.?.+?)/' . $system_slug . '/products/category/(.?.+?)?$', 'index.php?pagename=$matches[1]&zkm=product&zkl=products&zkcategory=$matches[2]', 'top');
    add_rewrite_rule($system_slug . '/products/category/(.?.+?)?$', 'index.php?page_id=' . $frontpage_id . '&zkm=product&zkl=products&zkcategory=$matches[1]', 'top');
}

function zijkart_insertpostdata($data, $postarr) {
    // do something with the post data
    $query = 'SELECT value FROM `' . zijkart::$db->prefix . 'zijkart_options` WHERE name = "system_slug"';
    $system_slug = zijkart::$db->get_var($query);
    if ($data['post_name'] == $system_slug) {
        $data['post_name'] = $system_slug . '1';
    }
    return $data;
}

function zijkart_savepost($post_id) {
    update_option('rewrite_rules', '');
}

function zijkart_customizersaveafter() {
    update_option('rewrite_rules', '');
}

function zijkart_parserequestdebug($q) {
    // echo '<pre>';print_r($q->query_vars);echo '</pre>';
}

?>

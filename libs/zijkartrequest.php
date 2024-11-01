<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class zijkartRequest {

        //getting filter variables value
        public static function getFilterValue($var_name, $method = 'post'){
            $value = self::getValue($var_name,null,$method);
            $formsearch = self::getValue('submit', $method);
            if ($formsearch == __('Search','zijkart')) {
                $_SESSION['zk_search'][$var_name] = $value;
            }else{
                if (self::getValue('pagenum', null, 'get') != null) {
                    $value = (isset($_SESSION['zk_search'][$var_name]) && $_SESSION['zk_search'][$var_name] != '') ? $_SESSION['zk_search'][$var_name] : null;
                }else{
                    unset($_SESSION['zk_search'][$var_name]);
                }
            }
            return $value;
        }

        //gettting variable value
        public static function getValue($var_name, $defaultvalue = null, $method = null, $typecast = null) {
            $val = null;
            if($method !== null) {
                $method = strtolower($method);
                switch ($method) {
                    case 'post': $val = isset($_POST[$var_name]) ? $_POST[$var_name] : null; break;
                    case 'get': $val = (isset($_GET[$var_name])) ? $_GET[$var_name] : null; break;
                }
            } else {
                if(isset($_GET[$var_name])) $val = $_GET[$var_name];
                elseif(isset($_POST[$var_name])) $val = $_POST[$var_name];
                elseif(get_query_var($var_name)) $val = get_query_var($var_name);
                elseif (zijkart::isset_d($var_name) === true) $val = zijkart::get_d($var_name);
            }
            if ($typecast != null) {
                $typecast = strtolower($typecast);
                switch ($typecast) {
                    case "int": $val = (int) $val; break;
                    case "string": $val = (string) $val; break;
                }
            }
            $val = ($val == null) ? $defaultvalue : $val;
            return $val;
        }

        // whole server array
        public static function getServerArray($method) {
            $array = null;
            if ($method != null) {
                $method = strtolower($method);
                switch ($method) {
                    case 'post': $array = $_POST; break;
                    case 'get': $array = $_GET; break;
                }
            }
            return $array;
        }

        // get the layout name
        static function getLayout($layout, $defaultvalue, $method = null) {
            $layoutname = $defaultvalue;
            if ($method != null) {
                $method = strtolower($method);
                switch ($method) {
                    case 'post': $layoutname = $_POST[$layout]; break;
                    case 'get': $layoutname = $_GET[$layout]; break;
                }
            } else {
                if (isset($_POST[$layout])) $layoutname = $_POST[$layout];
                elseif (isset($_GET[$layout])) $layoutname = $_GET[$layout];
                elseif (get_query_var($layout)) $layoutname = get_query_var($layout);
                elseif (zijkart::isset_d($layout) === true) $layoutname = zijkart::get_d($layout);
            }
            return (is_admin()) ? 'admin_' . $layoutname : $layoutname;
        }

    }

?>
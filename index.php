<?php
/**
 * Plugin name: Skydrop Woocommerce
 * Plugin URI: http://github.com/skydropx/plugin-woocommerce
 * Description: Add skydrop local deliveries to woocommerce
 * Version: 1.0.0
 * Author: Arnoldo Rodriguez
 * Author URI: http://github.com/acolin
 * License: GPL2
 */

// Logger function to write to debug.log file
function logger($message) {
    if ( WP_DEBUG === true ) {
        if ( is_array($message) || is_object($message) ) {
            error_log( print_r($message, true) );
        } else {
            error_log( $message );
        }
    }
}

require_once dirname(__FILE__).'/lib/Skydrop/vendor/autoload.php';
require_once dirname(__FILE__).'/helpers/ShopRules.php';
require_once dirname(__FILE__).'/helpers/ShopFilters.php';
require_once dirname(__FILE__).'/helpers/ShopModifiers.php';
require_once dirname(__FILE__).'/helpers/SkydropConfigs.php';
require_once dirname(__FILE__).'/helpers/ShippingRateBuilder.php';
require_once dirname(__FILE__).'/helpers/OrderBuilder.php';
require_once dirname(__FILE__).'/helpers/OrderCreator.php';
require_once dirname(__FILE__).'/helpers/ProductsTags.php';
require_once dirname(__FILE__).'/skydrop_shipping_method.php';
require_once dirname(__FILE__).'/skydrop_hooks.php';

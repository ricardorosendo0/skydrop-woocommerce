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
add_action('woocommerce_shipping_init', 'skydrop_shipping_method_init');

function skydrop_shipping_method_init() {
  if (!class_exists('WC_Skydrop_Shipping_Method')) {
    class WC_Skydrop_Shipping_Method extends WC_Shipping_Method {
      /**
       * Constructor for your shipping class
       *
       * @access public
       * @return void
       */
      public function __construct() {
        $this->id = 'skydrop_shipping_method';
        $this->title = __( 'skydrop same day' );
        $this->method_description = __( 'Local deliveries as fast as 30 mins' );
        $this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
        $this->init();
      }

      /**
       * Init your settings
       *
       * @access public
       * @return void
       */
      function init(){
        // Load the settings API
        $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
        $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

        // Save settings in admin if you have any defined
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
      }

      /**
       * calculate_shipping function.
       *
       * @access public
       * @param mixed $package
       * @return void
       */
      public function calculate_shipping($package = array()) {
          foreach($rates_json['rates'] as $rate) {
              $rate = array(
                  'id' => "{$rate['service_code']}-{$rate['vehicle_type']}",
                  'label' => $rate['service_name'],
                  'cost' => "{$rate['total_price']}",
                  'taxes' => false
              );

              $this->add_rate($rate);
          }
      }
    }
  }
}

add_filter('woocommerce_shipping_methods', 'add_skydrop_shipping_method');

function add_skydrop_shipping_method($methods) {
  $methods['skydrop_shipping_method'] = 'WC_Skydrop_Shipping_Method';
  return $methods;
}

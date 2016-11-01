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
        $this->method_title = __( 'Skydrop Shipping Method', 'woocommerce' );
        $this->method_description = __( 'Entregas locales expres' );

        // Load the settings API
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->enabled = $this->get_option('enabled');
        $this->title   = $this->get_option( 'title' );

        // Save settings in admin if you have any defined
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
      }

      public function init_form_fields() {
          $this->form_fields = array(
              'enabled' => array(
                  'title' => __('Enable/Disable', 'woocommerce'),
                  'type' => 'checkbox',
                  'label' =>  __('Enable Skydrop Carrier', 'woocommerce'),
                  'default' => 'no',
              ),
              'api_key' => array(
                  'title' => __('API Key', 'woocommerce'),
                  'type' => 'text',
              ),
              'working_days' => array(
                  'title' => __('Working Days', 'woocommerce'),
                  'type' => 'multiselect',
                  'options' => array(
                      '0' => 'Sunday',
                      '1' => 'Monday',
                      '2' => 'Tuesday',
                      '3' => 'Wednesday',
                      '4' => 'Thursday',
                      '5' => 'Friday',
                      '6' => 'Saturday',
                  ),
              ),
              'opening_time' => array(
                  'title' => __('Opening Time', 'woocommerce'),
                  'type' => 'text',
                  'default' => '10:00',
              ),
              'closing_time' => array(
                  'title' => __('Closing Time', 'woocommerce'),
                  'type' => 'text',
                  'default' => '22:00',
              ),
              'vehicle_type' => array(
                  'title' => __('Vehicle Type', 'woocommerce'),
                  'type' => 'select',
                  'options' => array(
                      'car' => 'Car',
                  ),
              ),
              'service_type' => array(
                  'title' => __('Service Types', 'woocommerce'),
                  'type' => 'multiselect',
                  'options' => array(
                      'EExps' => 'Express',
                      'Hoy' => 'Same Day',
                      'next_day' => 'Next Day',
                  ),
              ),
          );
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

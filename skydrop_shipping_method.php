<?php

add_action('woocommerce_shipping_init', 'skydrop_shipping_method_init');

function skydrop_shipping_method_init() {
  if (!class_exists('WC_Skydrop_Shipping_Method')) {
    require_once dirname(__FILE__).'/lib/Skydrop/vendor/autoload.php';
    require_once dirname(__FILE__).'/helpers/ShippingRateBuilder.php';
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
          'env' => array(
            'title' => __('Environment', 'woocommerce'),
            'type' => 'select',
            'options' => array(
              'staging' => 'Staging',
              'production' => 'Production',
            ),
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

          'address1' => array(
            'title' => __('Address 1', 'woocommerce'),
            'type' => 'text',
            'default' => '',
          ),
          'address2' => array(
            'title' => __('Address 2', 'woocommerce'),
            'type' => 'text',
            'default' => '',
          ),
          'city' => array(
            'title' => __('City', 'woocommerce'),
            'type' => 'text',
            'default' => '',
          ),
          'state' => array(
            'title' => __('State', 'woocommerce'),
            'type' => 'text',
            'default' => '',
          ),
          'zip_code' => array(
            'title' => __('Zip Code', 'woocommerce'),
            'type' => 'text',
            'default' => '',
          ),
          'country' => array(
            'title' => __('Country', 'woocommerce'),
            'type' => 'text',
            'default' => '',
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
          try {
              $helper = new \ShippingRateBuilder($this, $package['destination'], []);
              $builder = $helper->builder;
              $searcher = new \Skydrop\ShippingRate\Search($helper->builder);
              $rates = $searcher->call();
          } catch (\Exception $e) {
              logger($e);
              $rates = [];
          }
          foreach($rates as $rate) {
              $code = json_decode(urldecode($rate->service_code));
              $_rate = array(
                  'id' => "skydrop_{$code->service_code}",
                  'label' => "{$rate->service_name}",
                  'cost' => "{$rate->total_price}",
                  'meta_data' => $code,
                  'taxes' => false
              );
              $this->add_rate($_rate);
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
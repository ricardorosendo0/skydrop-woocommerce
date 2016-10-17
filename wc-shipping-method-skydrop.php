<?php
/**
 * Plugin name: Skydrop Shipping Method
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
      public function calculate_shipping($package) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://54.191.139.107/api/v2/shipping_rates",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\n           \"rate\": {\n               \"origin\": {\n                   \"country\": \"MX\",\n                   \"postal_code\": \"64898\",\n                   \"province\": \"NL\",\n                   \"city\": \"Monterrey\",\n                   \"name\": null,\n                   \"address1\": \"hda. cataluña 4941\",\n                   \"address2\": \"residencial la hacienda\",\n                   \"address3\": null,\n                   \"phone\": null,\n                   \"fax\": null,\n                   \"address_type\": null,\n                   \"company_name\": null\n               },\n               \"destination\": {\n                   \"country\": \"MX\",\n                   \"postal_code\": \"66231\",\n                   \"province\": \"NL\",\n                   \"city\": \"San Pedro Garza García\",\n                   \"name\": \"Jason Normore\",\n                   \"address1\": \"Rio guadalquivir 422A\",\n                   \"address2\": \"del valle\",\n                   \"address3\": null,\n                   \"phone\": \"7097433959\",\n                   \"fax\": null,\n                   \"address_type\": null,\n                   \"company_name\": null\n               },\n               \"items\": [\n                   {\n                       \"name\": \"My Product 3\",\n                       \"sku\": null,\n                       \"quantity\": 1,\n                       \"grams\": 1000,\n                       \"price\": 2000,\n                       \"vendor\": \"TestVendor\",\n                       \"requires_shipping\": true,\n                       \"taxable\": true,\n                       \"fulfillment_service\": \"manual\"\n                   }\n               ],\n               \"currency\": \"CAD\"\n           }\n        }",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 02b7a7ab-91ee-6294-7bd6-5fa53757930b"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $rates_json = json_decode($response, true);
        }

        foreach($rates_json['rates'] as $rate) {
          $rate = array(
            'id' => "{$rate['service_code']}-{$rate['vehicle_type']}",
            'label' => $rate['service_name'],
            'cost' => "{$rate['total_price']}",
            'taxes' => false
          );

          $this->add_rate($rate);
        }

          //$rate = array(
            //'id' => $this->id,
            //'label' => $this->title,
            //'cost' => '99.00',
            //'taxes' => false
          //);

          //$this->add_rate($rate);
      }
    }
  }
}

add_filter('woocommerce_shipping_methods', 'add_skydrop_shipping_method');

function add_skydrop_shipping_method($methods) {
  $methods['skydrop_shipping_method'] = 'WC_Skydrop_Shipping_Method';
  return $methods;
}

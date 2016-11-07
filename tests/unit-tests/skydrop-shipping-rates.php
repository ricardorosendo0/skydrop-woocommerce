// <?php
//
// class WC_Skydrop_Shipping_Rates extends WC_Unit_Test_Case {
//     public function test_skydrop_shipping_rates()
//     {
//         logger('-------------------------------------------------------------');
//         skydrop_shipping_method_init();
//         $method = WC_Skydrop_Shipping_Method::getInstance();
//         update_option('woocommerce_skydrop_shipping_method_settings', serialize([
//             'api_key' => 'abcdefghijk',
//             'env' => 'staging',
//             'phone' =>  '12345678',
//             'address1' =>  'Rio Guadalquivir 422-A',
//             'address2' =>  'Del Valle',
//             'city' =>  'San Pedro Garza Garcia',
//             'lat' =>  '25.658517',
//             'lng' =>  '-100.357239',
//         ]));
//         $method->get_option('api_key');
//         // $order = $this->create_order();
//         // do_action( 'woocommerce_checkout_order_processed', $order->id, [] );
//         logger('-------------------------------------------------------------');
//     }
//
// 	public function create_order() {
//
// 		// Create product
// 		$product = WC_Helper_Product::create_simple_product();
// 		WC_Helper_Shipping::create_simple_flat_rate();
//
// 		$order_data = array(
// 			'status'        => 'processing',
// 			'customer_id'   => 1,
// 			'customer_note' => '',
// 			'total'         => '',
// 		);
//
// 		$_SERVER['REMOTE_ADDR'] = '127.0.0.1'; // Required, else wc_create_order throws an exception
// 		$order 					= wc_create_order( $order_data );
//
// 		// Add order products
// 		$item_id = $order->add_product( $product, 4 );
//
// 		// Set billing address
// 		$billing_address = array(
// 			'first_name' => 'Jeroen',
// 			'last_name'  => 'Sormani',
// 			'company'    => 'WooCompany',
// 			'address_1'  => 'Rio Rosas 422',
// 			'address_2'  => 'Del Valle',
// 			'postcode'   => '66220',
// 			'city'       => 'San Pedro Garza Garcia',
// 			'state'      => 'Nuevo Leon',
// 			'country'    => 'MX',
// 			'email'      => 'admin@example.org',
// 			'phone'      => '555-32123',
// 		);
// 		$order->set_address( $billing_address, 'billing' );
//
//         $order->order_shipping = 0;
// 		// Add shipping costs
// 		$shipping_taxes = WC_Tax::calc_shipping_tax( '89.53', WC_Tax::get_shipping_tax_rates() );
//         $shipping_rate = new WC_Shipping_Rate(
//             'skydrop_Hoy',
//             'Skydrop - Mismo Dia, te llega antes de las 10:00 pm',
//             '89.53',
//             $shipping_taxes,
//             'skydrop_shipping_method'
//         );
//         $shipping_rate->add_meta_data('service_code' , 'Hoy');
//         $shipping_rate->add_meta_data('vehicle_type' , 'car');
//         $shipping_rate->add_meta_data('schedule_date', '2016-11-07');
//         $shipping_rate->add_meta_data('starting_hour', '09:00');
//         $shipping_rate->add_meta_data('ending_hour'  , '15:00');
//         $order->add_shipping( $shipping_rate );
//
// 		// Set payment gateway
// 		$payment_gateways = WC()->payment_gateways->payment_gateways();
// 		$order->set_payment_method( $payment_gateways['cod'] );
//
// 		// Set totals
// 		$order->set_total( 10, 'shipping' );
// 		$order->set_total( 0, 'cart_discount' );
// 		$order->set_total( 0, 'cart_discount_tax' );
// 		$order->set_total( 0, 'tax' );
// 		$order->set_total( 0, 'shipping_tax' );
// 		$order->set_total( 40, 'total' ); // 4 x $10 simple helper product
//
// 		return wc_get_order( $order->id );
// 	}
// }

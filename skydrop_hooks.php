<?php

function skydrop_shipping_methods() {
    return [
        'skydrop_Hoy',
        'skydrop_next_day',
        'skydrop_EExps',
    ];
}

add_action('woocommerce_payment_complete', 'skydrop_payment_complete', 10, 1);

function skydrop_payment_complete($order_id) {
    logger('-----------------------------------------------------------------');
    logger('skydrop_payment_complete');
    $order = new WC_Order( $order_id );
    return $order_id;
}

// Hook runs on processing order.
add_action(
    'woocommerce_checkout_order_processed',
    'skydrop_checkout_order_processed',
    1,
    1
);

function skydrop_checkout_order_processed($order_id) {
    logger('-----------------------------------------------------------------');
    logger('skydrop_checkout_order_processed');


    $order = new WC_Order( $order_id );
    $helper = new \OrderCreator(null);
    $helper->createOrder($order);

    return $order_id;
}

// Hook runs before processing order.
add_action('woocommerce_new_order', 'skydrop_new_order', 1, 1);

function skydrop_new_order($order_id) {
    return $order_id;
}

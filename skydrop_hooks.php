<?php

add_action('woocommerce_payment_complete', 'skydrop_payment_complete', 10, 1);

function skydrop_payment_complete($order_id) {
    $order = new WC_Order( $order_id );
    $helper = new \OrderCreator();
    logger($helper->createOrder($order).', Order: #'.$order_id);

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
    $order = new WC_Order( $order_id );
    $helper = new \OrderCreator();
    logger($helper->createOrder($order).', Order: #'.$order_id);

    return $order_id;
}

// Hook runs before processing order.
add_action('woocommerce_new_order', 'skydrop_new_order', 1, 1);

function skydrop_new_order($order_id) {
    return $order_id;
}

add_action(
    'woocommerce_order_status_changed',
    'skydrop_order_status_changed',
    1,
    3
);

function skydrop_order_status_changed($order_id, $old_status, $new_status) {
    $order = new WC_Order( $order_id );

    if ($new_status == 'processing') {
        $helper = new \OrderCreator();
        logger($helper->createOrder($order).', Order: #'.$order_id);
    }

    return $order_id;
}

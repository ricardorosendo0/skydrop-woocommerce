<?php

function add_action($action, $functions)
{
}

function add_filter($action, $functions)
{
}

function get_the_terms($product_id, $str)
{
    return array((object)["name" => "Skydrop"]);
}

function __($string, $plugin = "")
{
}

function get_post_meta($order_id, $attr, $unique)
{
    return false;
}

function update_post_meta($order_id, $attr, $value)
{
    var_dump($value);
}

function get_bloginfo($attr)
{
    return 'test attr';
}

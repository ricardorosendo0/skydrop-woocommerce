<?php

class WC_Shipping_Method
{
    public function init_settings()
    {
    }

    public function get_option($option)
    {
        switch($option) {
        case 'vehicle_type':
            return 'car';
        case 'service_type':
            return ['Hoy'];
        case 'product_tag_rule':
            return 'all';
        case 'product_tag_name':
            return 'skydrop';
        default:
            return '';
        }
    }
}

<?php

class WC_Shipping_Method
{
    public function init_settings()
    {
    }

    public function get_option($option)
    {
        switch($option) {
        case 'api_key':
            return 'abcdefghijk';
        case 'env':
            return 'staging';
        case 'working_days':
            return [1,2,3,4,5];
        case 'opening_time':
            return '10:00';
        case 'closing_time':
            return '22:00';
        case 'vehicle_type':
            return 'car';
        case 'service_type':
            return ['Hoy'];
        case 'product_tag_rule':
            return 'all';
        case 'product_tag_name':
            return 'skydrop';
        case 'address1':
            return 'Rio Guadalquivir 422-A';
        case 'address2':
            return 'Del Valle';
        case 'phone':
            return '12345678';
        case 'city':
            return 'San Pedro Garza Garcia';
        case 'lat':
            return '25.658517';
        case 'lng':
            return '-100.357239';
        default:
            return '';
        }
    }
}

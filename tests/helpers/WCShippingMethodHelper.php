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
            break;
        case 'service_type':
            return ['Hoy'];
            break;
        default:
            return '';
            break;
        }
    }
}

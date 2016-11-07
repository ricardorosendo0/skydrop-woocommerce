<?php

class WC_Order
{
    public $id = 73;
    public $payment_method = 'cod';
    public $order_total = 140.0;
    public $status = 'processing';

    public function get_status()
    {
        return $this->status;
    }

    public function get_address()
    {
        return array(
            'first_name' => 'Yaser',
            'last_name' => 'Almasri',
            'company' => 'Verde Monarca',
            'address_1' => 'Notre Dame 115-A',
            'address_2' => 'Valle de San Ángel',
            'city' => 'San Pedro Garza García',
            'state' => 'Nuevo Leon',
            'postcode' => '66290',
            'country' => 'MX',
            'email' => 'test@masys.co',
            'phone' => '018180114330'
        );
    }

    public function get_items($str)
    {
        return array(
            122 => array(
                'name' => 'Skydrop - Express, te llega antes de las 17:13 PM',
                'type' => 'shipping',
                'item_meta' => array(
                    'method_id' => array('skydrop_EExps'),
                    'cost' => array(77.71),
                    'taxes' => array('a:0:{}'),
                    'service_code' => array('EExps'),
                    'vehicle_type' => array('car'),
                    'schedule_date' => array('2016-11-07'),
                    'starting_hour' => array('16:39'),
                    'ending_hour' => array('17:13'),

                ),
                'method_id' => 'skydrop_EExps',
                'cost' => 77.71,
                'taxes' => 'a:0:{}',
                'service_code' => 'EExps',
                'vehicle_type' => 'car',
                'schedule_date' => '2016-11-07',
                'starting_hour' => '16:39',
                'ending_hour' => '17:13'
            )
        );
    }
}

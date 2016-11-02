<?php

class OrderBuilder
{
    private $args;

    public function getOrderBuilder($args = array())
    {
        $this->args = $args;
        $builder = new Skydrop\Order\OrderBuilder(
            [
                'pickup'   => $this->_getPickup(),
                'delivery' => $this->_getDelivery(),
                'service'  => $this->_getService(),
                'package'  => $this->_getPakcage()
            ]
        );
        return $builder;
    }

    private function _getPickup()
    {
        return new \Skydrop\Order\Address([
            'name'         => get_bloginfo('name'),
            'email'        => get_bloginfo('admin_email'),
            'telephone'    => $this->args['module']->get_option('phone'),
            "municipality" => $this->args['module']->get_option('city'),
            "streetNameAndNumber" => $this->args['module']->get_option('address1'),
            "neighborhood" => $this->args['module']->get_option('address2')
        ]);
    }

    private function _getDelivery()
    {
        $address = $this->args['shippingAddress'];
        return new \Skydrop\Order\Address([
            'name'         => $address['first_name'].' '.$address['last_name'],
            'email'        => $address['email'],
            'streetNameAndNumber' => $address['address_1'],
            'municipality' => $address['city'],
            'neighborhood' => $address['address_2'],
            'telephone'    => $address['phone']
        ]);
    }

    private function _getService()
    {
        $service = $this->args['serviceCode'];
        return new \Skydrop\Order\Service([
            'serviceCode'  => $service['service_code'],
            'vehicleType'  => $service['vehicle_type'],
            'scheduleDate' => $service['schedule_date'],
            'startingHour' => $service['starting_hour'],
            'endingHour'   => $service['ending_hour']
        ]);
    }

    private function _getPakcage()
    {
        $payment = $this->args['payment'];
        if ($payment['method'] == 'cod') {
            return new \Skydrop\Order\Package([
                'codAmount' => $payment['amount']
            ]);
        }
        return array();
    }
}

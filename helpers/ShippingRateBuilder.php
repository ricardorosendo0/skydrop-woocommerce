<?php

class ShippingRateBuilder
{
    public function __construct($module, $destination, $items)
    {
        $this->module = $module;
        $this->destination = $destination;
        $this->builder = new Skydrop\ShippingRate\ShippingRateBuilder(
            [
                'origin'      => $this->_getOrigin(),
                'destination' => $this->_getDestination(),
                'items'       => $items
            ]
        );
    }

    private function _getOrigin()
    {
        return new \Skydrop\ShippingRate\Address(
            [
                "country"     => $this->module->get_option('country'),
                "postal_code" => $this->module->get_option('zip_code'),
                "province"    => $this->module->get_option('state'),
                "city"        => $this->module->get_option('city'),
                "address1"    => $this->module->get_option('address1'),
                "address2"    => $this->module->get_option('address2'),
            ]
        );
    }

    private function _getDestination()
    {
        return new \Skydrop\ShippingRate\Address(
            [
                'address1'    => $this->destination['address'],
                'address2'    => $this->destination['address_2'],
                'city'        => $this->destination['city'],
                'postal_code' => $this->destination['postcode'],
            ]
        );
    }
}

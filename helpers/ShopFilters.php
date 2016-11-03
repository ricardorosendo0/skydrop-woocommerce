<?php

class ShopFilters
{
    public static function defaults()
    {
        $module = WC_Skydrop_Shipping_Method::getInstance();
        $filters = [];
        $vehicleType = $module->get_option('vehicle_type');
        if (!empty($vehicleType)) {
            $filter = (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\VehicleType',
                'options' => [ 'vehicleTypes' => [$vehicleType] ]
            );
            $filters[] = $filter;
        }
        $onePerService = $module->get_option('service_type');
        if (!empty($onePerService)) {
            $filter = (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\OnePerService',
                'options' => [ 'serviceTypes' => $onePerService ]
            );
            $filters[] = $filter;
        }
        return $filters
    }
}

<?php

class SkydropConfigs
{
    public static function setDefaultConfigs()
    {
        $module = WC_Skydrop_Shipping_Method::getInstance();
        \Skydrop\Configs::setApiKey($module->get_option('api_key'));
        \Skydrop\Configs::setEnv($module->get_option('env'));

        \Skydrop\Configs::setWorkingDays($module->get_option('working_days'));
        $open = explode(':', $module->get_option('opening_time'));
        \Skydrop\Configs::setOpeningTime(
            ['hour' => $open[0], 'min' => $open[1]]);
        $close = explode(':', $module->get_option('closing_time'));
        \Skydrop\Configs::setClosingTime(
            ['hour' => $close[0], 'min' => $close[1]]);
    }

    public static function setRules()
    {
        $module = WC_Skydrop_Shipping_Method::getInstance();
        $ruleName = $module->get_option('product_tag_rule');
        if (empty($ruleName)) {
            return;
        }
        $tagName = $module->get_option('product_tag_name');
        $rules = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Rule\\ProductsWithTag',
                'options' => [
                    'rule'  => $ruleName,
                    'tagId' => $tagName
                ]
            )
        ];
        \Skydrop\Configs::setRules($rules);
    }

    public static function setFilters()
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
        \Skydrop\Configs::setFilters($filters);
    }

    public static function setModifiers()
    {
        $module = WC_Skydrop_Shipping_Method::getInstance();
    }
}

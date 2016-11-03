<?php

class SkydropConfigs
{
    public static function setDefault()
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
        \Skydrop\Configs::setRules(\ShopRules::defaults());
    }

    public static function setFilters()
    {
        \Skydrop\Configs::setFilters(\ShopFilters::defaults());
    }

    public static function setModifiers()
    {
        // \Skydrop\Configs::setFilters(\ShopModifiers::defaults());
    }

    public static function setup()
    {
        self::setDefault();
        self::setRules();
        self::setFilters();
        self::setModifiers();
    }
}

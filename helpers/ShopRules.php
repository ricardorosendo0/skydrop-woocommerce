<?php

class ShopRules
{
    public static function defaults()
    {
        $module = WC_Skydrop_Shipping_Method::getInstance();
        $ruleName = $module->get_option('product_tag_rule');
        if (empty($ruleName)) {
            return [];
        }
        $tagName = $module->get_option('product_tag_name');
        return [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Rule\\ProductsWithTag',
                'options' => [
                    'rule'  => $ruleName,
                    'tagId' => $tagName
                ]
            )
        ];
    }
}

<?php
use PHPUnit\Framework\TestCase;

class ShopRulesTest extends TestCase
{

    public function testReturnRulesArray()
    {
        $rules = ShopRules::defaults();
        $expected = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Rule\\ProductsWithTag',
                'options' => [
                    'rule'  => 'all',
                    'tagId' => 'skydrop'
                ]
            )
        ];
        $this->assertEquals($rules, $expected);
    }
}

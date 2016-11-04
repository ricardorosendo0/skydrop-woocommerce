<?php
use PHPUnit\Framework\TestCase;

class ShopFiltersTest extends TestCase
{
    public function testDefaults()
    {
        $filters = ShopFilters::defaults();
        $expected = [
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\VehicleType',
                'options' => [ 'vehicleTypes' => ['car'] ]
            ),
            (object)array(
                'klass' => '\\Skydrop\\ShippingRate\\Filter\\OnePerService',
                'options' => [ 'serviceTypes' => ['Hoy'] ]
            ),
        ];
        $this->assertEquals($filters, $expected);
    }
}

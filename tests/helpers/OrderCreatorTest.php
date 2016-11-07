<?php
use PHPUnit\Framework\TestCase;

class OrderCreatorTest extends TestCase
{
    public function testCreateOrder()
    {
        \VCR\VCR::configure()->setCassettePath(getcwd().'/tests/VCR');
        \VCR\VCR::turnOn();
        \VCR\VCR::insertCassette('create_order_success');
        $order = new WC_Order();
        $creator = new OrderCreator();
        $result = $creator->create($order);

        $this->assertEquals('Order created successfuly!', $result);
        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }
}

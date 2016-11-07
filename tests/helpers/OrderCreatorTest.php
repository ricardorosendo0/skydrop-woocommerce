<?php
use PHPUnit\Framework\TestCase;

class OrderCreatorTest extends TestCase
{
    public function testCreateOrderSuccessfuly()
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

    public function testCreateOrderBankPending()
    {
        \VCR\VCR::configure()->setCassettePath(getcwd().'/tests/VCR');
        \VCR\VCR::turnOn();
        \VCR\VCR::insertCassette('create_order_bank_pending');
        $order = new WC_Order();
        $order->payment_method = 'bank';
        $order->status = 'pending';
        $creator = new OrderCreator();
        $result = $creator->create($order);

        $this->assertEquals('Order not Cash On Delivery or in Processing status!', $result);
        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }

    public function testCreateOrderBankProcessing()
    {
        \VCR\VCR::configure()->setCassettePath(getcwd().'/tests/VCR');
        \VCR\VCR::turnOn();
        \VCR\VCR::insertCassette('create_order_bank_processing');
        $order = new WC_Order();
        $order->payment_method = 'bank';
        $creator = new OrderCreator();
        $result = $creator->create($order);

        $this->assertEquals('Order created successfuly!', $result);
        \VCR\VCR::eject();
        \VCR\VCR::turnOff();
    }
}

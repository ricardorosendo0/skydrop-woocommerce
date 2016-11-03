<?php

class OrderCreator
{
    private $order;
    private $address;
    private $service_code;
    private $error;

    public function create($order)
    {
        $this->order = $order;

        if (!$this->orderEligibaleForSkydrop()) {
            return $this->error;
        }

        $this->address = $this->order->get_address();

        SkydropConfigs::setDefault();
        try {
            $this->createOrder();
            return 'Order created successfuly!';
        } catch (\Exception $e) {
            logger($e);
            \Skydrop\Configs::notifyErrbit($e, $builder->toHash());
            \Skydrop\Configs::notifySlack(json_encode($builder->toHash()));
            return 'Error, Order not created!';
        }
    }

    private function createOrder()
    {
        $builder = $this->getOrderBuilder();
        $skydropOrder = new \Skydrop\API\Order();
        $response = $skydropOrder->create($builder->toHash());
        update_post_meta(
            $this->order->id, 'skydrop_tracking_url', $response->tracking_url
        );
    }

    private function getOrderBuilder()
    {
        $builder = new \OrderBuilder();
        return $builder->getOrderBuilder([
            'shippingAddress' => $this->address,
            'serviceCode' => $this->service_code,
            'payment' => [
                'method' => $this->order->payment_method,
                'amount' => $this->order->order_total
            ],
        ]);
    }

    private function setServiceCode()
    {
        $str = 'skydrop_';
        $shipping_items = $this->order->get_items( 'shipping' );
        foreach($shipping_items as $key => $val) {
            if (substr($val['method_id'], 0, strlen($str)) == $str) {
                $this->service_code = $val;
                return;
            }
        }
        $this->service_code = false;
    }

    private function orderEligibaleForSkydrop()
    {
        // if order has tracking url should not create new order
        if (get_post_meta($this->order->id, 'skydrop_tracking_url', true)) {
            $this->error = 'Order Already created in Skydrop!';
            return;
        }

        if (
            $this->order->payment_method != 'cod'
            && $this->order->get_status() != 'processing'
        ) {
            $this->error = 'Order not Cash On Delivery or in Processing status!';
            return;
        }

        $this->setServiceCode();
        if (!$this->service_code) {
            $this->error = 'Shipping method not Skydrop!';
            return;
        }
        return true;
    }
}

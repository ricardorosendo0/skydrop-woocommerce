<?php

class OrderCreator
{
    private $order;
    private $address;
    private $service_code;

    public function createOrder($order)
    {
        $this->order = $order;

        // if order has tracking url should not create new order
        if (get_post_meta($this->order->id, 'skydrop_tracking_url', true)) {
            return 'Order Already created in Skydrop!';
        }

        if (
            $this->order->payment_method != 'cod'
            && $this->order->get_status() != 'processing'
        ) {
            return 'Order not Cash On Delivery or in Processing status!';
        }

        $this->setServiceCode();
        if (!$this->service_code) {
            return;
        }

        $this->address = $this->order->get_address();

        SkydropConfigs::setDefaultConfigs();
        try {
            $builder = $this->getOrderBuilder();
            $skydropOrder = new \Skydrop\API\Order();
            $response = $skydropOrder->create($builder->toHash());
            update_post_meta(
                $this->order->id,
                'skydrop_tracking_url',
                $response->tracking_url
            );
            return 'Order created successfuly!';
        } catch (\Exception $e) {
            logger($e);
            \Skydrop\Configs::notifyErrbit($e, $builder->toHash());
            \Skydrop\Configs::notifySlack(json_encode($builder->toHash()));
        }
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
}

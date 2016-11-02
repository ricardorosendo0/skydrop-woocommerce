<?php

class OrderCreator
{
    private $module;
    private $order;
    private $address;
    private $service_code;

    public function __construct()
    {
        $this->module = new WC_Skydrop_Shipping_Method();
    }

    public function createOrder($order)
    {
        $this->order = $order;

        // if order has tracking url should not create new order
        if (get_post_meta($order_id, 'skydrop_tracking_url', true)) {
            return;
        }

        if ($this->order->payment_method != 'cod') {
            return false;
        }

        $this->setServiceCode();
        if (!$this->service_code) {
            return;
        }

        $this->address = $this->order->get_address();

        \Skydrop\Configs::setApiKey($this->module->get_option('api_key'));
        \Skydrop\Configs::setEnv($this->module->get_option('env'));
        try {
            $builder = $this->getOrderBuilder();
            $skydropOrder = new \Skydrop\API\Order();
            $response = $skydropOrder->create($builder->toHash());
            update_post_meta(
                $this->order->id,
                'skydrop_tracking_url',
                $response->tracking_url
            );
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
            'module' => $this->module,
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
        $shipping_items = $this->order->get_items( 'shipping' );
        foreach($shipping_items as $key => $val) {
            if (in_array($val['method_id'], skydrop_shipping_methods())) {
                $this->service_code = $val;
                return;
            }
        }
        $this->service_code = false;
    }
}

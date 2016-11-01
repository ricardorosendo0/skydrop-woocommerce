<?php
require_once(dirname(__FILE__).'/../lib/Skydrop/vendor/autoload.php');
require_once(dirname(__FILE__).'/../Helpers/OrderBuilder.php');
require_once(dirname(__FILE__).'/../classes/SkydropServiceType.php');

class OrderCreation
{
    private $module;
    private $order;
    private $address;
    private $serviceCode;

    public function __construct($module)
    {
		$this->context = Context::getContext();
        $this->module = $module;
        $this->id_cart = (int)$this->context->cart->id;
    }

    public function createOrder($params)
    {
        if (
            isset($params['newOrderStatus'])
            && !$params['newOrderStatus']->paid
        ) {
            return;
        }

        $this->order = new Order($params['id_order']);
        if (!empty($this->order->getWsShippingNumber())) {
            return;
        }

        $this->setServiceCode();
        if (!$this->serviceCode) {
            return;
        }

        $this->address = new Address($this->order->id_address_delivery);

        \Skydrop\Configs::setApiKey(Configuration::get('SKYDROP_CA_API_KEY'));
        \Skydrop\Configs::setEnv(Configuration::get('SKYDROP_CA_ENV'));
        try {
            $builder = $this->getOrderBuilder();
            $skydropOrder = new \Skydrop\API\Order();
            $response = $skydropOrder->create($builder->toHash());
            $this->order->setWsShippingNumber($response->tracking_url);
            \SkydropServiceType::deleteServiceTypesByCartId($this->id_cart);
        } catch (\Exception $e) {
            $this->module->logger($e);
            \Skydrop\Configs::notifyErrbit($e, $builder->toHash());
            \Skydrop\Configs::notifySlack(json_encode($builder->toHash()));
        }
    }

    private function getOrderBuilder()
    {
        $builder = new \OrderBuilder();
        return $builder->getOrderBuilder([
            'shippingAddress' => $this->address,
            'serviceCode' => $this->serviceCode,
            'payment' => [
                'method' => $this->order->module,
                'amount' => $this->order->total_paid
            ],
        ]);
    }

    private function setServiceCode()
    {
        $codes = $this->getCodes();
        $carrierId = $this->order->id_carrier;
        if ($carrierId == Configuration::get('SKYDROP_CA_SAME_DAY')) {
            $this->serviceCode = $codes['Hoy'];
        } else if ($carrierId == Configuration::get('SKYDROP_CA_NEXT_DAY')) {
            $this->serviceCode = $codes['next_day'];
        } else if ($carrierId == Configuration::get('SKYDROP_CA_EEXPS')) {
            $this->serviceCode = $codes['EExps'];
        } else {
            $this->serviceCode = false;
        }
    }

    private function getCodes()
    {
        $results = \SkydropServiceType::getServiceTypesByCartId($this->id_cart);
        $codes = [];
        foreach($results as $row) {
            $code = json_decode(urldecode($row->service_code), true);
            $codes[$row->service_type] = $code;
        }
        return $codes;
    }
}

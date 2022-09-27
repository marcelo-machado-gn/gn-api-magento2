<?php

namespace Gerencianet\Magento2\Block\Order\Payment;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;

class Info extends Template {

  /** @var OrderRepositoryInterface */
  protected $_orderFactory;

  public function __construct(
    Template\Context $context,
    OrderRepositoryInterface $orderRepositoryInterface,
    array $data = null
  ) {
    parent::__construct($context, $data);
    $this->_orderRepositoryInterface = $orderRepositoryInterface;
  }

  public function getPaymentMethod() {
    $order_id = $this->getRequest()->getParam('order_id');
    $order = $this->_orderRepositoryInterface->get($order_id);
    $payment = $order->getPayment();
    return $payment->getMethod();
  }

  public function getPaymentInfo() {
    $order_id = $this->getRequest()->getParam('order_id');
    $order = $this->_orderRepositoryInterface->get($order_id);
    if ($payment = $order->getPayment()) {
      $paymentMethod = $payment->getMethod();
      switch ($paymentMethod) {
        case 'gerencianet_boleto':
          return array(
            'tipo' => 'Boleto',
            'url' => $order->getGerencianetUrlBoleto(),
            'texto' => 'Clique aqui para visualizar seu boleto.',
            'linha-digitavel' => $order->getGerencianetCodigoDeBarras()
          );
          break;
        case 'gerencianet_pix':
          return array(
            'tipo' => 'Pix',
            'url' => $order->getGerencianetQrcodePix(),
            'texto' => 'Clique aqui para ver seu QRCode.',
            'chavepix' => $order->getGerencianetChavePix()
          );
          break;
      }
    }
    return false;
  }
}

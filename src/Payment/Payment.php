<?php
/**
 * Payment.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    jack <linjue@wilead.com>
 * @copyright 2007-16/1/13 WIZ TECHNOLOGY
 * @link      http://wizmacau.com
 * @link      http://jacklam.it
 * @link      https://github.com/lamjack
 * @version
 */

namespace Wiz\Wechat\Payment;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Util\Str;
use Util\XML;
use Wiz\Wechat\Core\Helper;
use Wiz\Wechat\Core\Http;
use Wiz\Wechat\Exception\ApiException;

/**
 * Class Payment
 * @package Wiz\Wechat\Payment
 */
class Payment
{
    const API_PREPARE_ORDER = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var array
     */
    protected $paymentConfigs;

    /**
     * Payment constructor.
     *
     * @param string $appId
     * @param array  $paymentConfigs
     */
    public function __construct($appId, array $paymentConfigs)
    {
        $this->appId = $appId;
        $this->paymentConfigs = $paymentConfigs;
    }

    /**
     * 统一下单
     *
     * @param Order $order
     *
     * @return array|\SimpleXMLElement
     */
    public function prepare(Order $order)
    {
        if ($order->valid()) {
            $order->set('appid', $this->appId);
            $order->set('mch_id', $this->paymentConfigs['mch_id']);
            $order->set('nonce_str', Str::randomStr(32));
            $signData = array_filter($order->all());
            $order->set('sign', Helper::sign($signData, $this->paymentConfigs['key']));

            $response = Http::getInstance()->getCurl()->post(API::UNIFIED_ORDER, ['xml' => $order->getXML()], ['xml' => true]);
            $result = XML::parse($response['data']);

            if ($result['return_code'] === 'FAIL')
                throw new ApiException($result['return_msg']);

            var_dump($result);
        }
    }

    /**
     * 获得微信JSAPI支付参数
     *
     * @param $unifiedOrderResult
     *
     * @return array
     */
    public function getJSApiParameters($unifiedOrderResult)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $appId = $accessor->getValue($unifiedOrderResult, '[appid]');
        $prepayId = $accessor->getValue($unifiedOrderResult, '[prepay_id]');
        $signData = [
            'appId' => $appId,
            'timeStamp' => (string)time(),
            'nonceStr' => Str::randomStr(16),
            'package' => sprintf('prepay_id=%s', $prepayId),
            'signType' => 'MD5'
        ];
        $sign = Helper::sign($signData, $this->paymentConfigs['key']);

        return [
            'timestamp' => $signData['timeStamp'],
            'nonceStr' => $signData['nonceStr'],
            'package' => $signData['package'],
            'signType' => $signData['signType'],
            'paySign' => $sign
        ];
    }
}
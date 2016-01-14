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
     * @param array $paymentConfigs
     */
    public function __construct($appId, array $paymentConfigs)
    {
        $this->appId = $appId;
        $this->paymentConfigs = $paymentConfigs;
    }

    /**
     * @param array $orderAttrs
     *
     * @return Order
     */
    public function createOrder(array $orderAttrs)
    {
        $autoAttrs = [
            'appid' => $this->appId,
            'mch_id' => $this->paymentConfigs['mch_id'],
            'nonce_str' => Str::randomStr(16)
        ];
        $orderAttrs = $autoAttrs + $orderAttrs;
        $orderAttrs = array_filter($orderAttrs);

        // 生成签名
        ksort($orderAttrs, SORT_STRING);
        $signArr = [];
        foreach ($orderAttrs as $k => $v) {
            $signArr[] = sprintf('%s=%s', $k, $v);
        }
        $signArr[] = 'key=' . $this->paymentConfigs['key'];
        $signStr = implode('&', $signArr);
        $orderAttrs['sign'] = strtoupper(md5($signStr));
        return new Order($orderAttrs);
    }

    /**
     * @param Order $order
     *
     * @return array|\SimpleXMLElement
     */
    public function prepare(Order $order)
    {
        $attr = array_filter($order->getAttributes());
        $xml = XML::build($attr);
        $response = Http::getInstance()->getCurl()->post(self::API_PREPARE_ORDER, ['xml' => $xml], ['xml' => true]);
        $data = XML::parse($response['data']);
        if ($data['return_code'] == 'SUCCESS') {
            return $data;
        }
        throw new ApiException($data['return_msg']);
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
            'timeStamp' => time(),
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
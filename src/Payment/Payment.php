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
     * @param Order $order
     *
     * @return array|\SimpleXMLElement
     */
    public function prepare(Order $order)
    {
        
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
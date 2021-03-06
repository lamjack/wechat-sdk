<?php
/**
 * API.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    jack <linjue@wilead.com>
 * @copyright 2007-16/3/3 WIZ TECHNOLOGY
 * @link      http://wizmacau.com
 * @link      http://jacklam.it
 * @link      https://github.com/lamjack
 * @version
 */

namespace Wiz\Wechat\Payment;

/**
 * 微支付接口
 *
 * Class API
 * @package Wiz\Wechat\Payment
 */
abstract class API
{
    /**
     *
     */
    const UNIFIED_ORDER = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
}
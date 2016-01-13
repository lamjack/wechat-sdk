<?php
/**
 * PaymentServiceProvider.php
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
namespace Wiz\Wechat\ServiceProviders;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Wiz\Wechat\Payment\Payment;

/**
 * Class PaymentServiceProvider
 * @package Wiz\Wechat\ServiceProviders
 */
class PaymentServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['payment'] = function ($pimple) {
            return new Payment($pimple['configs']['app_id'], $pimple['configs']['payment']);
        };
    }
}
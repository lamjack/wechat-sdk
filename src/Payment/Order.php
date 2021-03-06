<?php
/**
 * Order.php
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
use Wiz\Wechat\Core\Attribute;

/**
 * Class Order
 * @package Wiz\Wechat\Payment
 */
class Order extends Attribute
{
    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $processor = new Processor();
        $configs = $processor->processConfiguration(new OrderConfiguration(), ['order' => $this->all()]);
        $this->reset($configs);
        return true;
    }
}
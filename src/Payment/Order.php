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

class Order
{
    /**
     * @var array
     */
    protected $attributes;

    /**
     * Order constructor.
     *
     * @param array $attributes
     */
    private function __construct(array $attributes)
    {
        $processor = new Processor();
        $processedConfiguration = $processor->processConfiguration(new OrderConfiguration(), ['order' => $attributes]);
        $this->attributes = $processedConfiguration;
    }
}
<?php
/**
 * OrderConfiguration.php
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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class OrderConfiguration
 * @package Wiz\Wechat\Payment
 */
class OrderConfiguration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('order');

        $rootNode
            ->children()
                ->scalarNode('appid')
                    ->isRequired()
                ->end()
                ->scalarNode('mch_id')
                    ->isRequired()
                ->end()
                ->scalarNode('device_info')
                    ->defaultNull()
                ->end()
                ->scalarNode('nonce_str')
                    ->isRequired()
                ->end()
                ->scalarNode('sign')
                    ->isRequired()
                ->end()
                ->scalarNode('body')
                    ->isRequired()
                ->end()
                ->scalarNode('detail')
                    ->defaultNull()
                ->end()
                ->scalarNode('attach')
                    ->defaultNull()
                ->end()
                ->scalarNode('out_trade_no')
                    ->isRequired()
                ->end()
                ->enumNode('fee_type')
                    ->values(array('CNY'))
                    ->defaultValue('CNY')
                ->end()
                ->integerNode('total_fee')
                    ->isRequired()
                ->end()
                ->scalarNode('spbill_create_ip')
                    ->isRequired()
                ->end()
                ->scalarNode('time_start')
                    ->defaultNull()
                ->end()
                ->scalarNode('time_expire')
                    ->defaultNull()
                ->end()
                ->scalarNode('goods_tag')
                    ->defaultNull()
                ->end()
                ->scalarNode('notify_url')
                    ->isRequired()
                ->end()
                ->enumNode('trade_type')
                    ->values(array('JSAPI', 'NATIVE', 'APP'))
                    ->isRequired()
                ->end()
                ->scalarNode('product_id')
                    ->defaultNull()
                ->end()
                ->scalarNode('limit_pay')
                    ->values(array('no_credit'))
                    ->defaultNull()
                ->end()
                ->scalarNode('openid')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
<?php
/**
 * Configuration.php
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

namespace Wiz\Wechat\Core;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Wiz\Wechat\Core
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wechat');

        $this->addBasicNode($rootNode);
        $this->addLogNode($rootNode);
        $this->addPaymentNode($rootNode);
        $this->addOauthNode($rootNode);

        return $treeBuilder;
    }

    /**
     * 账号基本信息
     *
     * @param ArrayNodeDefinition $node
     */
    protected function addBasicNode(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('app_id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('secret')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('token')
                    ->defaultNull()
                ->end()
                ->scalarNode('aes_key')
                    ->defaultNull()
                ->end()
                ->booleanNode('debug')
                    ->defaultFalse()
                ->end()
            ->end();
    }

    protected function addOauthNode(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('oauth')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('scopes')
                            ->values(array('snsapi_base', 'snsapi_userinfo'))
                            ->defaultValue('snsapi_base')
                        ->end()
                        ->scalarNode('redirect_url')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * 日志配置
     *
     * @param ArrayNodeDefinition $node
     */
    protected function addLogNode(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('log')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('level')
                        ->values(array('debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'))
                        ->defaultValue('debug')
                    ->end()
                    ->scalarNode('file')
                        ->isRequired()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * 微信支付配置
     *
     * @param ArrayNodeDefinition $node
     */
    protected function addPaymentNode(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('payment')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('mch_id')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('key')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('cert_path')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('key_path')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
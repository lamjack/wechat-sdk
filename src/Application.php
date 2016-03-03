<?php
/**
 * Application.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    jack <linjue@wilead.com>
 * @copyright 2007-2016 WIZ TECHNOLOGY
 * @link      http://wizmacau.com
 * @link      http://jacklam.it
 * @link      https://github.com/lamjack
 * @version
 */

namespace Wiz\Wechat;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Wiz\Wechat\Core\AccessToken;
use Wiz\Wechat\Core\Cache\CacheInterface;
use Wiz\Wechat\Core\Configuration;
use Wiz\Wechat\Payment\Payment;
use Wiz\Wechat\Card\Card;

/**
 * Class Application
 * @package Wiz\Wechat
 */
class Application
{
    private $container;

    /**
     * Application constructor
     *
     * @param array          $configs
     * @param CacheInterface $cache
     */
    public function __construct(array $configs, CacheInterface $cache)
    {
        $this->container = new ContainerBuilder();

        $processor = new Processor();
        $processedConfiguration = $processor->processConfiguration(new Configuration(), ['wechat' => $configs]);

        $this->container->setParameter('configs', $processedConfiguration);
        $this->container->set('cache', $cache);
        $this->container->set('request', Request::createFromGlobals());

        $this->registerBase();
        $this->registerProviders();
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * 注册基础服务
     */
    private function registerBase()
    {
        $configs = $this->container->getParameter('configs');
        /** @var CacheInterface $cache */
        $cache = $this->get('cache');

        // 注册 AccessToken
        $this->container->set('access_token', new AccessToken($configs['app_id'], $configs['secret'], $cache));
    }

    /**
     * 注册微信模块
     */
    private function registerProviders()
    {
        $configs = $this->container->getParameter('configs');

        // Server
        $this->container->setDefinition('server', new Definition(Server\Server::class, [
            new Reference('request')
        ]));

        // OAuth
        $this->container->setDefinition('oauth', new Definition(OAuth\OAuth::class, [
            $configs['app_id'],
            $configs['secret'],
            $configs['oauth'],
            new Reference('request'),
            new Reference('cache')
        ]));

        // 微信支付
        $this->container->setDefinition('payment', new Definition(Payment::class, [
            $configs['app_id'],
            $configs['payment']
        ]));

        // 卡卷
        $this->container->setDefinition('card', new Definition(Card::class, [
            new Reference('access_token')
        ]));
    }
}
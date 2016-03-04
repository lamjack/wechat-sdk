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

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Wiz\Wechat\Core\Cache\CacheInterface;
use Wiz\Wechat\Core\Cache\FilesystemCache;
use Wiz\Wechat\Core\Configuration;
use Wiz\Wechat\Core\Log;
use Wiz\Wechat\Core\Token\Token;
use Wiz\Wechat\Core\Token\TokenInterface;
use Wiz\Wechat\Payment\Payment;
use Wiz\Wechat\Card\Card;

/**
 * Class Application
 * @package Wiz\Wechat
 */
class Application
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * Application constructor.
     *
     * @param array               $configs
     * @param TokenInterface|null $token
     * @param CacheInterface|null $cache
     */
    public function __construct(array $configs, TokenInterface $token = null, CacheInterface $cache = null)
    {
        $this->container = new ContainerBuilder();

        $processor = new Processor();
        $processedConfiguration = $processor->processConfiguration(new Configuration(), ['wechat' => $configs]);

        $this->container->setParameter('configs', $processedConfiguration);
        $this->container->set('request', Request::createFromGlobals());

        if (null === $cache)
            $cache = new FilesystemCache(sys_get_temp_dir());

        if (null === $token)
            $token = new Token($configs['app_id'], $configs['secret'], $cache);

        $this->container->set('cache', $cache);
        $this->container->set('token', $token);
        $this->container->set('request', Request::createFromGlobals());

        $this->register();
        $this->initializeLogger();
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * 注册微信模块
     */
    private function register()
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

        // JSSDK
        $this->container->setDefinition('jssdk', new Definition(JSSDK\JSSDK::class, [
            $configs['app_id'],
            new Reference('request'),
            new Reference('token')
        ]));

        // 微信支付
        $this->container->setDefinition('payment', new Definition(Payment::class, [
            $configs['app_id'],
            $configs['payment'],
            new Reference('request')
        ]));

        // 卡卷
        $this->container->setDefinition('card', new Definition(Card::class, [
            new Reference('token')
        ]));
    }

    private function initializeLogger()
    {
        $configs = $this->container->getParameter('configs');
        $logger = new Logger('WizWechat');

        if (!$configs['debug'] || defined('PHPUNIT_RUNNING')) {
            $logger->pushHandler(new NullHandler());
        } elseif (array_key_exists('file', $configs['log'])) {
            $logger->pushHandler(new StreamHandler($configs['log']['file'], $configs['log']['level']));
        }

        Log::setLogger($logger);
    }
}
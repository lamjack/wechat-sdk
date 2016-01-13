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

use Pimple\Container;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpFoundation\Request;
use Wiz\Wechat\Core\AccessToken;
use Wiz\Wechat\Core\Cache\CacheInterface;
use Wiz\Wechat\Core\Configuration;
use Wiz\Wechat\ServiceProviders as Providers;

/**
 * Class Application
 * @package Wiz\Wechat
 */
class Application extends Container
{
    /**
     * @var array
     */
    protected $providers = [
        Providers\PaymentServiceProvider::class
    ];

    /**
     * Application constructor
     *
     * @param array $configs
     * @param CacheInterface $cache
     */
    public function __construct(array $configs, CacheInterface $cache)
    {
        parent::__construct();

        $processor = new Processor();
        $processedConfiguration = $processor->processConfiguration(new Configuration(), ['wechat' => $configs]);

        $this['configs'] = $processedConfiguration;
        $this['cache'] = function () use ($cache) {
            return $cache;
        };

        $this->registerBase();
        $this->registerProviders();
    }

    /**
     * Register base module
     */
    private function registerBase()
    {
        $this['request'] = function () {
            return Request::createFromGlobals();
        };

        $this['access_token'] = function () {
            return new AccessToken($this['configs']['app_id'], $this['configs']['secret'], $this['cache']);
        };
    }

    /**
     * Register service providers
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }
}
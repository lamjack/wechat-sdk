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
use Wiz\Wechat\Core\AccessToken;
use Wiz\Wechat\Core\Cache\CacheInterface;
use Wiz\Wechat\Core\Cache\FilesystemCache;
use Wiz\Wechat\Core\Configuration;

/**
 * Class Application
 * @package Wiz\Wechat
 */
class Application
{
    /**
     * 配置参数
     *
     * @var array
     */
    private $configs;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * Application constructor
     *
     * @param array $configs
     * @param CacheInterface|null $cache
     */
    public function __construct(array $configs, CacheInterface $cache = null)
    {
        $processor = new Processor();

        $processedConfiguration = $processor->processConfiguration(new Configuration(), ['wechat' => $configs]);

        $this->configs = $processedConfiguration;
        $this->cache = $cache;
        $this->accessToken = new AccessToken($this->configs['app_id'], $this->configs['secret'], $this->getCache());
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @param bool $forceRefresh 强制刷新
     *
     * @return string
     */
    public function getAccessToken($forceRefresh = false)
    {
        return $this->accessToken->getToken($forceRefresh);
    }

    /**
     * @return CacheInterface
     */
    protected function getCache()
    {
        if (null === $this->cache)
            $this->cache = new FilesystemCache(sys_get_temp_dir());

        return $this->cache;
    }
}
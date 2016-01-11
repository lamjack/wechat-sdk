<?php
/**
 * AccessToken.php
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
namespace Wiz\Wechat\Core;

use Wiz\Wechat\Core\Cache\CacheInterface;
use Wiz\Wechat\Core\Cache\FilesystemCache;

/**
 * Class AccessToken
 * @package Wiz\Wechat\Core
 */
class AccessToken
{
    /**
     * @var string
     */
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/token';

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $prefix = 'wiz_wechat.core.access_token.';

    /**
     * @var CacheInterface|null
     */
    private $cache;

    /**
     * AccessToken constructor.
     *
     * @param string $appId
     * @param string $secret
     * @param CacheInterface|null $cache
     */
    public function __construct($appId, $secret, CacheInterface $cache = null)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->cache = $cache;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function getToken($forceRefresh = false)
    {
        $key = $this->prefix . $this->appId;
        $token = $this->getCache()->fetch($key);

        if ($forceRefresh || !$token) {
            $result = $this->getTokenFromServer();
            $this->getCache()->save($key, $result['access_token'], $result['expires_in'] - 120);
            $token = $result['access_token'];
        }

        return $token;
    }

    /**
     * @return array
     */
    protected function getTokenFromServer()
    {
        $params = [
            'appid' => $this->appId,
            'secret' => $this->secret,
            'grant_type' => 'client_credential',
        ];

        return Http::getInstance()->get(self::API_TOKEN_GET, $params);
    }

    /**
     * @return CacheInterface
     */
    protected function getCache()
    {
        if (null === $this->cache) {
            $this->cache = new FilesystemCache(sys_get_temp_dir());
        }
        return $this->cache;
    }
}
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
     * @var CacheInterface
     */
    private $cache;

    /**
     * AccessToken constructor.
     *
     * @param string $appId
     * @param string $secret
     * @param CacheInterface $cache
     */
    public function __construct($appId, $secret, CacheInterface $cache)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->cache = $cache;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function getToken($forceRefresh = false)
    {
        $key = $this->cache->getPrefix() . ':access_token:' . $this->appId;
        $token = $this->cache->fetch($key);

        if ($forceRefresh || !$token) {
            $result = $this->getTokenFromServer();
            $this->cache->save($key, $result['access_token'], $result['expires_in'] - 120);
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
}
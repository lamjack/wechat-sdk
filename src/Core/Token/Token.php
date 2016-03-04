<?php
/**
 * Token.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    jack <linjue@wilead.com>
 * @copyright 2007-16/3/4 WIZ TECHNOLOGY
 * @link      http://wizmacau.com
 * @link      http://jacklam.it
 * @link      https://github.com/lamjack
 * @version
 */

namespace Wiz\Wechat\Core\Token;

use Wiz\Wechat\Core\Cache\CacheInterface;
use Wiz\Wechat\Core\Helper;
use Wiz\Wechat\Core\Http;

/**
 * Class Token
 * @package Wiz\Wechat\Token
 */
class Token implements TokenInterface
{
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/token';
    const GET_TICKET = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    private $prefix;

    /**
     * Token constructor.
     *
     * @param string         $appId
     * @param string         $secret
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
    public function getAccessToken($forceRefresh = false)
    {
        $key = $this->keyCreater('access_token', $this->appId);
        $token = $this->cache->fetch($key);

        if ($forceRefresh || !$token) {
            $result = $this->getTokenFromServer();
            $this->cache->save($key, $result['access_token'], (int)$result['expires_in'] - 120);
            $token = $result['access_token'];
        }

        return $token;
    }

    /**
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function getJsapiTicket($forceRefresh = false)
    {
        $key = $this->keyCreater('jsapi_ticket', $this->appId);
        $ticket = $this->cache->fetch($key);

        if ($forceRefresh || !$ticket) {
            $result = $this->getTicketFromServer();
            $this->cache->save($key, $result['ticket'], (int)$result['expires_in'] - 120);
            $ticket = $result['ticket'];
        }

        return $ticket;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return array
     */
    protected function getTokenFromServer()
    {
        $url = Helper::addQueryParameters(self::API_TOKEN_GET, [
            'appid' => $this->appId,
            'secret' => $this->secret,
            'grant_type' => 'client_credential',
        ]);

        return Http::getInstance()->get($url);
    }

    /**
     * @return array
     */
    protected function getTicketFromServer()
    {
        $url = Helper::addQueryParameters(self::GET_TICKET, [
            'access_token' => $this->getAccessToken(),
            'type' => 'jsapi'
        ]);

        return Http::getInstance()->get($url);
    }

    /**
     * @param string $name
     * @param string $identify
     *
     * @return string
     */
    protected function keyCreater($name, $identify)
    {
        $part = [];

        if (!is_null($this->prefix) && strlen($this->prefix) > 0) {
            $part[] = $this->prefix;
        }

        $part[] = $name;
        $part[] = $identify;

        return implode(':', $part);
    }
}
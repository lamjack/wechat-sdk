<?php
/**
 * Http.php
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

use Network\Curl;
use Util\Json;
use Wiz\Wechat\Exception\ApiException;

/**
 * Class Http
 * @package Wiz\Wechat\Core
 */
class Http
{
    /**
     * @var self
     */
    private static $_instance;

    /**
     * @var Curl
     */
    private $curl;

    /**
     * Http constructor.
     */
    private function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Http();
        }

        return self::$_instance;
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return array
     */
    public function get($url, array $params = array())
    {
        $response = $this->curl->get($url, $params, ['json' => true]);
        return $this->parseJSON($response['data']);
    }

    /**
     * @return Curl
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param string $json
     *
     * @return array
     */
    protected function parseJSON($json)
    {
        $data = Json::decode($json, true);
        if (array_key_exists('errcode', $data) && $data['errcode'] != 0)
            throw new ApiException($data['errmsg'], $data['errcode']);
        else
            return $data;
    }

    /**
     * 防止用户克隆实例
     */
    public function __clone()
    {
        throw new \RuntimeException('Clone is not allowed.' . E_USER_ERROR);
    }
}
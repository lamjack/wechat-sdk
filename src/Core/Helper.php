<?php
/**
 * Helper.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    jack <linjue@wilead.com>
 * @copyright 2007-16/1/14 WIZ TECHNOLOGY
 * @link      http://wizmacau.com
 * @link      http://jacklam.it
 * @link      https://github.com/lamjack
 * @version
 */

namespace Wiz\Wechat\Core;

/**
 * Class Helper
 * @package Wiz\Wechat\Core
 */
abstract class Helper
{
    /**
     * 获取签名
     *
     * @param array       $data
     * @param string|null $key
     *
     * @return string
     */
    static public function sign(array $data, $key = null)
    {
        ksort($data, SORT_STRING);

        $signArr = [];
        foreach ($data as $k => $v) {
            $signArr[] = sprintf('%s=%s', $k, $v);
        }

        if (!is_null($key))
            $signArr[] = 'key=' . $key;

        $signStr = implode('&', $signArr);

        return strtoupper(md5($signStr));
    }

    /**
     * @param array $data
     *
     * @return string
     */
    static public function jssdkSign(array $data)
    {
        ksort($data, SORT_STRING);

        $signArr = [];
        foreach ($data as $k => $v) {
            $signArr[] = sprintf('%s=%s', $k, $v);
        }

        $signStr = implode('&', $signArr);

        return sha1($signStr);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    static public function httpBuildQuery(array $params)
    {
        $arr = [];
        foreach ($params as $k => $v) {
            array_push($arr, sprintf('%s=%s', $k, $v));
        }
        return implode($arr, '&');
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return string
     */
    static public function addQueryParameters($url, array $params)
    {
        $parsedUrl = parse_url($url);
        if (!array_key_exists('path', $parsedUrl))
            $url .= '/';
        $separator = (!array_key_exists('query', $parsedUrl)) ? '?' : '&';
        return $url .= $separator . self::httpBuildQuery($params);
    }
}
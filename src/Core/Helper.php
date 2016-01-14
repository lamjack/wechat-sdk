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
     * @param array $data
     * @param string $key
     *
     * @return string
     */
    static public function sign(array $data, $key)
    {
        ksort($data, SORT_STRING);
        $signArr = [];
        foreach ($data as $k => $v) {
            $signArr[] = sprintf('%s=%s', $k, $v);
        }
        $signArr[] = 'key=' . $key;
        $signStr = implode('&', $signArr);

        return strtoupper(md5($signStr));
    }
}
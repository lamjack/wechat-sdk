<?php
/**
 * CacheInterface.php
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

namespace Wiz\Wechat\Core\Cache;

/**
 * Interface CacheInterface
 * @package Wiz\Wechat\Core\Cache
 */
interface CacheInterface
{
    /**
     * 获取 prefix
     *
     * @return string
     */
    public function getPrefix();

    /**
     * 设置 prefix
     *
     * @param string $prefix
     */
    public function setPrefix($prefix);

    /**
     * 檢索
     *
     * @param string $key
     *
     * @return string
     */
    public function fetch($key);

    /**
     * 保存
     *
     * @param string   $key
     * @param string   $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function save($key, $value, $ttl = null);
}
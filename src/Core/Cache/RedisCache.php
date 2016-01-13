<?php
/**
 * RedisCache.php
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
 * Class RedisCache
 * @package Wiz\Wechat\Core\Cache
 */
class RedisCache implements CacheInterface
{
    use CacheTrait;

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * RedisCache constructor.
     *
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        return $this->redis->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, $value, $ttl)
    {
        $this->redis->setex($key, $ttl, $value);
    }
}
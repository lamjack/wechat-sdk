<?php
/**
 * FilesystemCache.php
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
 * Class FilesystemCache
 * @package Wiz\Wechat\Core\Cache
 */
class FilesystemCache implements CacheInterface
{
    /**
     * @var string
     */
    protected $dir;

    /**
     * FilesystemCache constructor.
     *
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, $value, $ttl)
    {

    }
}
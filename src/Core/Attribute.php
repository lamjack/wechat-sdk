<?php
/**
 * Attribute.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    jack <linjue@wilead.com>
 * @copyright 2007-16/3/3 WIZ TECHNOLOGY
 * @link      http://wizmacau.com
 * @link      http://jacklam.it
 * @link      https://github.com/lamjack
 * @version
 */

namespace Wiz\Wechat\Core;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Util\XML;

/**
 * Class Attribute
 * @package Wiz\Wechat\Core
 */
abstract class Attribute implements ParameterBagInterface
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * Attribute constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * 验证当前数据
     *
     * @return boolean
     */
    abstract public function valid();

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->attributes = [];
    }

    /**
     * @param array $attributes
     */
    public function reset(array $attributes)
    {
        $this->clear();
        $this->attributes = $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function add(array $parameters)
    {
        foreach ($parameters as $k => $v) {
            $this->attributes[$k] = $v;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if ($this->has($name))
            return $this->attributes[$name];
        else
            return null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resolveValue($value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function escapeValue($value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function unescapeValue($value)
    {
    }

    /**
     * @return string
     */
    public function getXML()
    {
        return XML::build($this->all());
    }
}
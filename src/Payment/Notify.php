<?php
/**
 * Notify.php
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

namespace Wiz\Wechat\Payment;

use Symfony\Component\HttpFoundation\Request;
use Util\XML;
use Wiz\Wechat\Core\Attribute;
use Wiz\Wechat\Core\Helper;
use Wiz\Wechat\Exception\FaultException;

/**
 * Class Notify
 * @package Wiz\Wechat\Payment
 */
class Notify extends Attribute
{
    /**
     * @var string
     */
    private $key;

    /**
     * Notify constructor.
     *
     * @param Request $request
     * @param string  $key
     *
     * @throws FaultException
     */
    public function __construct(Request $request, $key)
    {
        parent::__construct([]);

        try {
            $xml = XML::parse($request->getContent());
        } catch (\Exception $e) {
            throw new FaultException($e->getMessage());
        }

        $this->add($xml);
        $this->key = $key;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        $copy = clone $this;
        $sign = Helper::sign($copy->except('sign')->all(), $this->key);
        return ($sign === $this->get('sign'));
    }
}
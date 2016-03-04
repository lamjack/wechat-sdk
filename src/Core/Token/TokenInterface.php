<?php
/**
 * TokenInterface.php
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

/**
 * Interface TokenInterface
 * @package Wiz\Wechat\Token
 */
interface TokenInterface
{
    /**
     * @param boolean $forceRefresh
     *
     * @return string
     */
    public function getAccessToken($forceRefresh = false);

    /**
     * @param boolean $forceRefresh
     *
     * @return string
     */
    public function getJsapiTicket($forceRefresh = false);
}
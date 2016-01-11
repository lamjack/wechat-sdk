<?php
/**
 * AbstractAPI.php
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

/**
 * Class AbstractAPI
 * @package Wiz\Wechat\Core
 */
abstract class AbstractAPI
{
    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * AbstractAPI constructor.
     *
     * @param AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }
}
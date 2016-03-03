<?php
/**
 * API.php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    jack <linjue@wilead.com>
 * @copyright 2007-16/3/2 WIZ TECHNOLOGY
 * @link      http://wizmacau.com
 * @link      http://jacklam.it
 * @link      https://github.com/lamjack
 * @version
 */

namespace Wiz\Wechat\OAuth;

/**
 * Class API
 * @package Wiz\Wechat\OAuth
 */
abstract class API
{
    /**
     * @var string
     */
    const AUTHORIZE = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    /**
     * @var string
     */
    const ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    /**
     * @var string
     */
    const USER_INFO = 'https://api.weixin.qq.com/sns/userinfo';
}
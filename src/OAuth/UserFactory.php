<?php
/**
 * UserFactory.php
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

namespace Wiz\Wechat\OAuth;

use Wiz\Wechat\Core\Helper;
use Wiz\Wechat\Core\Http;
use Wiz\Wechat\Exception\FaultException;

/**
 * Class UserFactory
 * @package Wiz\Wechat\OAuth
 */
class UserFactory
{
    /**
     * @param array $data
     *
     * @return User
     * @throws FaultException
     */
    public static function creator(array $data)
    {
        $unionId = array_key_exists('unionid', $data) ? $data['unionid'] : null;
        $user = new User($data['openid'], $unionId);

        switch ($data['scope']) {
            case OAuth::SNSAPI_BASE:
                break;
            case OAuth::SNSAPI_USERINFO:
                $params = [
                    'access_token' => $data['access_token'],
                    'openid' => $data['openid'],
                    'lang' => 'zh_CN'
                ];
                $json = Http::getInstance()->get(API::USER_INFO . '?' . Helper::httpBuildQuery($params));
                $user->setNickname($json['nickname']);
                $user->setSex($json['sex']);
                $user->setProvince($json['province']);
                $user->setCity($json['city']);
                $user->setCountry($json['country']);
                $user->setHeadImgUrl($json['headimgurl']);
                $user->setPrivilege($json['privilege']);
                break;
            default:
                throw new FaultException(sprintf('Unknow oauth scope %s', $data['scope']));
        }

        return $user;
    }
}
<?php
/**
 * User.php
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

class User
{
    /**
     * @var string
     */
    private $openId;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $sex;

    /**
     * @var string
     */
    private $province;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $headImgUrl;

    /**
     * @var array
     */
    private $privilege = [];

    /**
     * @var string
     */
    private $unionId;

    /**
     * User constructor.
     *
     * @param string $openId
     * @param string $unionId
     */
    public function __construct($openId, $unionId = null)
    {
        $this->openId = $openId;
        $this->unionId = $unionId;
    }

    /**
     * @return string
     */
    public function getOpenId()
    {
        return $this->openId;
    }

    /**
     * @return string
     */
    public function getUnionId()
    {
        return $this->unionId;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getHeadImgUrl()
    {
        return $this->headImgUrl;
    }

    /**
     * @param string $headImgUrl
     */
    public function setHeadImgUrl($headImgUrl)
    {
        $this->headImgUrl = $headImgUrl;
    }

    /**
     * @return array
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @param array $privilege
     */
    public function setPrivilege(array $privilege)
    {
        $this->privilege = $privilege;
    }
}
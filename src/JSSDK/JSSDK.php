<?php
/**
 * JSSDK.php
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
namespace Wiz\Wechat\JSSDK;

use Symfony\Component\HttpFoundation\Request;
use Util\Json;
use Util\Str;
use Wiz\Wechat\Core\Helper;
use Wiz\Wechat\Core\Token\TokenInterface;

/**
 * Class JSSDK
 * @package Wiz\Wechat\JSSDK
 */
class JSSDK
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * @var string
     */
    private $url;

    /**
     * JSSDK constructor.
     *
     * @param string         $appId
     * @param Request        $request
     * @param TokenInterface $token
     */
    public function __construct($appId, Request $request, TokenInterface $token)
    {
        $this->appId = $appId;
        $this->request = $request;
        $this->token = $token;
    }

    /**
     * @param array $apis
     * @param bool  $debug
     * @param bool  $json
     *
     * @return array|string
     */
    public function config(array $apis, $debug = false, $json = false)
    {
        $configs = [
            'debug' => $debug,
            'appId' => $this->appId,
            'timestamp' => strval(time()),
            'nonceStr' => Str::randomStr(16),
            'jsApiList' => $apis
        ];

        $signArr = [
            'noncestr' => $configs['nonceStr'],
            'jsapi_ticket' => $this->token->getJsapiTicket(),
            'timestamp' => $configs['timestamp'],
            'url' => $this->getUrl()
        ];

        $configs['signature'] = Helper::jssdkSign($signArr);

        return $json ? Json::encode($configs) : $configs;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return !is_null($this->url) ? $this->url : $this->request->getUri();
    }
}
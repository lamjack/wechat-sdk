<?php
/**
 * OAuth.php
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

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Wiz\Wechat\Core\Cache\CacheInterface;
use Wiz\Wechat\Core\Helper;
use Wiz\Wechat\Core\Http;

class OAuth
{
    /**
     * @var string
     */
    const SNSAPI_BASE = 'snsapi_base';

    /**
     * @var string
     */
    const SNSAPI_USERINFO = 'snsapi_userinfo';

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var array
     */
    private $configs;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * 授权类型
     *
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $state;

    /**
     * OAuth constructor.
     *
     * @param string         $appId
     * @param string         $secret
     * @param array          $configs
     * @param Request        $request
     * @param CacheInterface $cache
     */
    public function __construct($appId, $secret, array $configs, Request $request, CacheInterface $cache)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->configs = $configs;
        $this->request = $request;
        $this->cache = $cache;
        $this->scope = $this->configs['scopes'];
    }

    /**
     * 设置Scope
     *
     * @param string $scope
     *
     * @return $this
     */
    public function scope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * 设置State
     *
     * @param string $state
     *
     * @return $this
     */
    public function state($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return RedirectResponse
     */
    public function redirect()
    {
        $params = [
            'appid' => $this->appId,
            'redirect_uri' => urlencode(Helper::addQueryParameters(
                $this->configs['redirect_url'],
                ['target' => $this->request->getUri()]
            )),
            'response_type' => 'code',
            'scope' => $this->scope
        ];

        if (null !== $this->state)
            $params['state'] = $this->state;

        $url = API::AUTHORIZE . '?' . Helper::httpBuildQuery($params) . '#wechat_redirect';

        return new RedirectResponse($url);
    }

    /**
     * @return null|User
     * @throws \Wiz\Wechat\Exception\FaultException
     */
    public function user()
    {
        // 微信回调时候的处理
        if ($this->request->query->has('code')) {
            $params = [
                'appid' => $this->appId,
                'secret' => $this->secret,
                'code' => $this->request->query->get('code'),
                'grant_type' => 'authorization_code'
            ];
            $result = Http::getInstance()->get(API::ACCESS_TOKEN . '?' . Helper::httpBuildQuery($params));
            return UserFactory::creator($result);
        }

        return null;
    }
}
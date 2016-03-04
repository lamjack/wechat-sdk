<?php
/**
 * Notice.php
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
namespace Wiz\Wechat\Notice;

use Wiz\Wechat\Core\Token\TokenInterface;

/**
 * Class Notice
 * @package Wiz\Wechat\Notice
 */
class Notice
{
    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * Notice constructor.
     *
     * @param TokenInterface $token
     */
    public function __construct(TokenInterface $token)
    {
        $this->token = $token;
    }
}
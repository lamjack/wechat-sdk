<?php
/**
 * Author: jack<linjue@wilead.com>
 * Date: 15/10/20
 */
namespace Wiz\Wechat;

/**
 * Wechat SDK Interface
 *
 * Interface SDKInterface
 * @package Wiz\Wechat
 */
interface SDKInterface
{
    /**
     * Get Wechat AccessToken
     *
     * @return string
     */
    public function getAccessToken();
}
<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\Oauth2\Model;

interface ScopeInterface extends ModelInterface
{
    /**
     * Get scope
     *
     * @return string
     */
    public function getScope();
}
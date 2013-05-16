<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\OAuth2\Extension\GrantType;

use Pantarei\OAuth2\Extension\GrantType;
use Silex\Application;

/**
 * Authorization code grant type implementation.
 *
 * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
 *
 * @author Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 */
class AuthorizationCodeGrantType extends GrantType
{
  /**
   * REQUIRED. Value MUST be set to "authorization_code".
   *
   * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
   */
  private $grant_type = 'authorization_code';

  /**
   * REQUIRED. The authorization code received from the
   * authorization server.
   *
   * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
   */
  private $code = '';

  /**
   * REQUIRED, if the "redirect_uri" parameter was included in the
   * authorization request as described in Section 4.1.1, and their
   * values MUST be identical.
   *
   * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
   */
  private $redirect_uri;

  /**
   * REQUIRED, if the client is not authenticating with the
   * authorization server as described in Section 3.2.1.
   *
   * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
   */
  private $cilentId;

  public function setCode($code)
  {
    $this->code = $code;
    return $this;
  }

  public function getCode()
  {
    return $this->code;
  }

  public function setRedirectUri($redirect_uri)
  {
    $this->redirect_uri = $redirect_uri;
    return $this;
  }

  public function getRedirectUri()
  {
    return $this->redirect_uri;
  }

  public function setClientId($client_id)
  {
    $this->client_id = $client_id;
    return $this;
  }

  public function getClientId()
  {
    return $this->client_id;
  }

  public function buildType($query, $filtered_query)
  {
    // Validate and set client_id.
    if ($this->app['oauth2.param.check.client_id']($query, $filtered_query)) {
      $this->setClientId($query['client_id']);
    }

    // Validate and set redirect_uri. NOTE: redirect_uri is not required if
    // already established via other channels.
    $query = $this->app['oauth2.param.fetch.redirect_uri']($query);
    if ($this->app['oauth2.param.check.redirect_uri']($query, $filtered_query)) {
      $this->setRedirectUri($query['redirect_uri']);
    }

    // Validate and set code.
    if ($this->app['oauth2.param.check.code']($query, $filtered_query)) {
      $this->setCode($filtered_query['code']);
    }
  }

  public function getParent()
  {
    return 'grant_type';
  }

  public function getName()
  {
    return $this->grant_type;
  }
}
<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\OAuth2\GrantType;

use Pantarei\OAuth2\GrantType\GrantTypeInterface;
use Pantarei\OAuth2\Util\ParameterUtils;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authorization code grant type implementation.
 *
 * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
 *
 * @author Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 */
class AuthorizationCodeGrantType implements GrantTypeInterface
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
    private $redirect_uri = '';

    /**
     * REQUIRED, if the client is not authenticating with the
     * authorization server as described in Section 3.2.1.
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4.1.3
     */
    private $cilent_id = '';

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

    public function __construct(Request $request, Application $app)
    {
        // Validate and set client_id.
        if ($client_id = ParameterUtils::checkClientId($request, $app['oauth2.model_manager.client'])) {
            $this->setClientId($client_id);
        }

        // Validate and set redirect_uri.
        if ($redirect_uri = ParameterUtils::checkRedirectUri($request, $app['oauth2.model_manager.client'])) {
            $this->setRedirectUri($redirect_uri);
        }

        // Validate and set code.
        if ($code = ParameterUtils::checkCode($request, $app['oauth2.model_manager.code'])) {
            $this->setCode($code);
        }
    }

    public static function create(Request $request, Application $app)
    {
        return new static($request, $app);
    }

    public function getResponse(Request $request, Application $app)
    {
        $response = $app['oauth2.token_type.default']::create($request, $app);
        return $response->getResponse($request, $app);
    }
}

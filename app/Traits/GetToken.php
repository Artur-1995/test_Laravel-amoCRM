<?php

namespace App\Traits;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Models\Token;
use App\Services\ConfigService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use League\OAuth2\Client\Token\AccessToken;

trait GetToken
{
    public $accessToken;
    public $apiClient;
    public $uri;

    public function __construct()
    {
        $this->apiClient = parent::getApiClient();
    }
    public function getToken(): AccessToken
    {
        if (isset($_GET['referer'])) {
            $this->apiClient->setAccountBaseDomain($_GET['referer']);
        }

        try {
            $this->accessToken = Token::get()->first();

            if (
                isset($this->accessToken)
                && isset($this->accessToken['access_token'])
                && isset($this->accessToken['refresh_token'])
                && isset($this->accessToken['expires'])
                && isset($this->accessToken['base_domain'])
                && time() < $this->accessToken['expires']
            ) {
                $this->accessToken =  new AccessToken([
                    'access_token' => $this->accessToken['access_token'],
                    'refresh_token' => $this->accessToken['refresh_token'],
                    'expires' => $this->accessToken['expires'],
                    'base_domain' => $this->accessToken['base_domain'],
                ]);
            }
        } catch (Exception $e) {
            Log::info('error_Invalid access token', [$e->getCode() => $e->getMessage()]);
        }

        if (empty($this->accessToken instanceof AccessToken)) {
            $this->uri = Cache::get('uri');
            $this->tokenHandler();
        }

        return $this->accessToken;
    }
    public function saveToken($data)
    {
        try {
            Token::updateOrCreate(['base_domain' => $data['base_domain']], $data);
        } catch (Exception $e) {
            Log::info('save_error', [$e->getCode() => $e->getMessage()]);
        }
    }

    public function tokenHandler()
    {
        /**
         * Ловим обратный код
         */

        try {
            if (isset($_GET['code'])) {
                $code = $_GET['code'];
                $this->accessToken = $this->apiClient->getOAuthClient()->getAccessTokenByCode($code);

                if (!$this->accessToken->hasExpired()) {
                    $this->saveToken([
                        'access_token' => $this->accessToken->getToken(),
                        'refresh_token' => $this->accessToken->getRefreshToken(),
                        'expires' => $this->accessToken->getExpires(),
                        'base_domain' => $this->apiClient->getAccountBaseDomain(),
                    ]);
                }
                $uri = Cache::get('uri');

                return redirect()->route($uri);
            }
        } catch (AmoCRMoAuthApiException $e) {
            Log::info('error', [$e->getCode() => $e->getMessage()]);
        }

        return $this->getTokenHandler();
    }


    public function getTokenHandler()
    {
        if (!isset($_GET['code'])) {
            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth2state'] = $state;
            if (isset($_GET['button'])) {
                echo $this->apiClient->getOAuthClient()->getOAuthButton(
                    [
                        'title' => 'Установить интеграцию',
                        'compact' => true,
                        'class_name' => 'className',
                        'color' => 'default',
                        'error_callback' => 'handleOauthError',
                        'state' => $state,
                    ]
                );
                die;
            } else {
                $uri = Route::currentRouteName() ?? 'home';
                Cache::forever('uri', $uri);

                $authorizationUrl = $this->apiClient->getOAuthClient()->getAuthorizeUrl([
                    'state' => $state,
                    'mode' => 'post_message',
                ]);
                header('Location: ' . $authorizationUrl);
                die;
            }
        } elseif (!isset($_GET['from_widget']) && (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state']))) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        }
    }
}

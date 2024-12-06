<?php

namespace App\Traits;

use AmoCRM\Client\AmoCRMApiClient;
use App\Services\ConfigService;
use App\Traits\GetToken;
use Illuminate\Support\Facades\Cache;
use League\OAuth2\Client\Token\AccessToken;

trait AmoCRM
{
    use GetToken;

    public function __construct(ConfigService $config)
    {
        $this->apiClient = new AmoCRMApiClient($config->clientId, $config->clientSecret, $config->redirectUri);
        $this->accessToken = $this->getAccessToken();
        $accountDomain = $this->apiClient->getOAuthClient()->getAccountDomainByRefreshToken($this->accessToken)->domain;

        $this->apiClient->setAccessToken($this->accessToken)
            ->setAccountBaseDomain($accountDomain)
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) {
                    $this->saveToken(
                        [
                            'access_token' => $this->accessToken->getToken(),
                            'refresh_token' => $this->accessToken->getRefreshToken(),
                            'expires' => $this->accessToken->getExpires(),
                            'base_domain' => $baseDomain,
                        ]
                    );
                }
            );
    }

    public function getApiClient(): AmoCRMApiClient
    {
        return $this->apiClient;
    }

    public function setApiClient(ConfigService $config): AmoCRMApiClient
    {
        $this->apiClient = new AmoCRMApiClient($config->clientId, $config->clientSecret, $config->redirectUri);

        return $this->apiClient;
    }

    public function getAccessToken(): AccessToken
    {
        return $this->getToken();
    }


    public function RedirectUri()
    {
        Cache::forget('uri');

        return redirect()->route($this->uri);
    }
}

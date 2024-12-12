<?php

namespace App\Traits;

use Exception;
use AmoCRM\Client\AmoCRMApiClient;
use League\OAuth2\Client\Token\AccessToken;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;

/**
 * Класс для подключения клиента AmoCRM
 *
 * @param AccessToken $accessToken    
 * @param AmoCRMApiClient $apiClient
 *
 * @throws Exception
 * @throws AmoCRMoAuthApiException ошибка при получении токена доступа
 */
trait GetClientAmoCRMTrait
{
    /**
     * Подключение клиента AmoCRM
     *
     * @param string $accountDomain Домен пользователя
     * @return AmoCRMApiClient $this->apiClient Клиента для работы с сервисом AmoCRM
     */
    public function getApiClient(string $accountDomain): AmoCRMApiClient
    {
        return $this->apiClient->setAccessToken($this->accessToken)
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
}

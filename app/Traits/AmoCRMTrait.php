<?php

namespace App\Traits;

use Exception;
use App\Services\ConfigService;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;

/**
 * Класс предоставляет клиента для работы с сервисом AmoCRM
 *
 * @param AmoCRMApiClient $apiClient
 *
 * @throws Exception
 * @throws AmoCRMoAuthApiException ошибка при получении токена доступа
 */
trait AmoCRMTrait
{
    use GetClientAmoCRMTrait, GetTokenTrait;

    /**
     * Клиент доступа к сервису AmoCRM
     * @var AmoCRMApiClient $apiClient
     */
    public $apiClient;

    /**
     * Метод нициализация клиента для работы с сервисом AmoCRM
     *
     * @param ConfigService $config конфигурации для подключения к интеграции
     * @throws Exception Невалидный токен
     */
    public function __construct(ConfigService $config)
    {
        $this->apiClient = new AmoCRMApiClient($config->clientId, $config->clientSecret, $config->redirectUri);
        $this->getToken();
        $accountDomain = $this->apiClient->getOAuthClient()->getAccountDomainByRefreshToken($this->accessToken)->domain;
        $this->apiClient = $this->getApiClient($accountDomain);
    }
}

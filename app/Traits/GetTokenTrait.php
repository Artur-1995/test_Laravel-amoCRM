<?php

namespace App\Traits;

use Exception;
use App\Models\Token;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use League\OAuth2\Client\Token\AccessToken;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;

/**
 * Трайт для получения токенов доступа
 *
 * Имеет методы для получения токенов доступа к аккаунту AmoCRM
 * из базы или по стандарту Auth2.0 через интеграцию
 *
 * @param AccessToken accessToken    
 * @param string uri
 *
 * @throws Exception
 * @throws AmoCRMoAuthApiException ошибка при получении токена доступа
 */
trait GetTokenTrait
{
    /**
     * Токена доступа
     * @var AccessToken $accessToken
     */
    public $accessToken;

    /**
     * Название запрашиваемого роута
     * @var string $uri
     */
    public $uri = 'home';

    /**
     * Метод для получения токенов доступа
     *
     * Метод обращается к БД, проверяет наличие и актуальность токена, при 
     * отсутствии отправляет запрос к сервису AmoCRM для получения 
     * нового токена и обрабатыват ответ
     *
     * @throws Exception Токен доступа не получен
     * @return AccessToken $this->accessToken
     */
    public function getToken(): AccessToken
    {
        try {
            if (isset($_GET['referer'])) {
                $this->apiClient->setAccountBaseDomain($_GET['referer']);
            }

            $this->getTokenFromBase();

            if (empty($this->accessToken instanceof AccessToken)) {
                $this->uri = Cache::get('uri');
                if (isset($_GET['code'])) {
                    $this->tokenHandler();
                } elseif (!isset($_GET['code'])) {
                    $this->getTokenRequest();
                } elseif (!isset($_GET['from_widget']) && (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state']))) {
                    unset($_SESSION['oauth2state']);
                    exit('Invalid state');
                }
            }
        } catch (Exception $e) {
            Log::info('error_access_token', [$e->getCode() => $e->getMessage()]);
        }

        return $this->accessToken;
    }

    /**
     * Метод для получения токенов из БД
     *
     * Метод обращается к БД, проверяет наличие и актуальность токена
     *
     * @throws Exception Невалидный токен
     * @return void
     */
    public function getTokenFromBase(): void
    {
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
            Log::info('error_Invalid_access_token', [$e->getCode() => $e->getMessage()]);
        }
    }

    /**
     * Метод сохранения токенов в БД
     * 
     * @param array $data массив с данными токена
     * @throws Exception Ошибка при сохранении
     * @return void Токен сохранен в базе
     */
    public function saveToken($data): void
    {
        try {
            Token::updateOrCreate(['base_domain' => $data['base_domain']], $data);
        } catch (Exception $e) {
            Log::info('save_error', [$e->getCode() => $e->getMessage()]);
        }
    }

    /**
     * Метод обработки ответа от сервиса AmoCRM
     *
     * Метод обрабатывает GET параметров
     * из ответа сервиса AmoCRM, получает токен доступа и сохраняет в БД
     * 
     * @return void
     */
    public function tokenHandler(): void
    {
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
            }
        } catch (AmoCRMoAuthApiException $e) {
            Log::info('error', [$e->getCode() => $e->getMessage()]);
        }
    }

    /**
     * Метод отправки запроса к сервис AmoCRM
     *
     * Метод отправляет запрос к сервис AmoCRM для получения ответа от интеграции
     * с данными для получения токена доступа
     *
     * @return void 
     */
    public function getTokenRequest(): void
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
                Cache::forever('uri', Route::currentRouteName());
                $authorizationUrl = $this->apiClient->getOAuthClient()->getAuthorizeUrl([
                    'state' => $state,
                    'mode' => 'post_message',
                ]);
                header('Location: ' . $authorizationUrl);
                die;
            }
        }
    }
}

<?php

namespace Aivanov\SbisPHP;

use Aivanov\SbisPHP\Exceptions\SbisExceptions;

use Aivanov\SbisPHP\SbisRequests;


class SbisClient
{

    use SbisRequests;

    private $login;
    private $password;
    private $token = '';
    private $s3sid;


    const SBIS_LOGIN = 'SBIS_LOGIN';
    const SBIS_PASSWORD = 'SBIS_PASSWORD';

    const SBIS_URL_AUTH = 'https://online.sbis.ru/auth/service/';

    const SBIS_URL = 'https://online.sbis.ru/service/?srv=1';


    /**
     * @throws SbisExceptions
     * @throws \Exception
     */
    public function __construct(string $login, string $password)
    {

        $this->login = $login ?? $_ENV[self::SBIS_LOGIN];
        $this->validateLogin();

        $this->password = $password ?? $_ENV[self::SBIS_PASSWORD];
        $this->validatePassword();

        $this->sbisAuth();

    }


    /**
     * @throws SbisExceptions
     */
    private function validateLogin()
    {
        if (!$this->login || !is_string($this->login)) {
            throw SbisExceptions::loginNotProvided(self::SBIS_LOGIN);
        }
    }


    /**
     * @throws SbisExceptions
     */
    private function validatePassword()
    {
        if (!$this->password || !is_string($this->password)) {
            throw SbisExceptions::passwordNotProvided(self::SBIS_PASSWORD);
        }
    }


    /**
     * @throws \Exception
     */
    private function sbisAuth()
    {

        $authResult = SbisRequests::authRequest('POST', [
            'jsonrpc' => '2.0',
            'method' => 'СБИС.Аутентифицировать',
            'params' => [
                'Параметр' => [
                    'Логин' => $this->login,
                    'Пароль' => $this->password,
                ]
            ],
            'id' => 0
        ], self::SBIS_URL_AUTH, $this->token);


        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $authResult, $matches);
        $cookies = array();
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        $this->s3sid = $cookies['s3sid-online-daab'];

        preg_match_all('/^{\s*([^;]*)/mi', $authResult, $matchess);

        $ma = json_decode($matchess[0][0]);

        if (!empty($ma->error)) {
            throw SbisExceptions::authError($ma->error->message);
        }

        $this->token = $ma->result;
    }


    public function documents(): SbisDocuments
    {
        return new SbisDocuments(self::SBIS_URL, $this->token);
    }
}
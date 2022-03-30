<?php

namespace Aivanov\SbisPHP;

use Aivanov\SbisPHP\Exceptions\SbisExceptions;

trait SbisRequests
{


    /**
     * <code>
     *
     * </code>
     * @param string $method
     * @param array $params
     * @return string
     */
    static function authRequest(string $method, array $params, string $url, string $token) : string
    {
        $curl = curl_init();

        json_encode($params);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8',
                'X-SBISSessionID: '.$token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }


    /**
     * @throws SbisExceptions
     */
    static function request(string $method, array $params, string $url, string $token) : object
    {
        $curl = curl_init();

        json_encode($params);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8',
                'X-SBISSessionID: '.$token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response);
        if (!empty($result->error)){
            throw SbisExceptions::sbisError(!empty($result->error->details) ? $result->error->details : $result->error);
        }

        return $result;
    }


    /**
     * @throws SbisExceptions
     */
    static function fileRequest(string $method, string $url, string $token) : string
    {
        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8',
                'X-SBISSessionID: '.$token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($response);
        if (!empty($result->error)){
            throw SbisExceptions::sbisError(!empty($result->error->details) ? $result->error->details : $result->error);
        }

        return $response;
    }
}
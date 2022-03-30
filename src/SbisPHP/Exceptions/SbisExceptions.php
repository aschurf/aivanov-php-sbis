<?php

namespace Aivanov\SbisPhp\Exceptions;

use Exception;

class SbisExceptions extends Exception
{

    public static function loginNotProvided($loginEnvName): self
    {
        return new static('Required "LOGIN" not supplied in config and could not find fallback environment variable ' . $loginEnvName . '');
    }

    public static function passwordNotProvided($passwordEnvName): self
    {
        return new static('Required "PASSWORD" not supplied in config and could not find fallback environment variable ' . $passwordEnvName . '');
    }

    public static function authError(string $errorMessage){
        return new static('Auth in SBIS error: '.$errorMessage);
    }

    public static function sbisError(string $errorMessage){
        return new static('SBIS ERROR: '.$errorMessage);
    }
}
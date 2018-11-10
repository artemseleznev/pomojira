<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

use chobie\Jira\Api;
use chobie\Jira\Api\Authentication\Basic;

class Helper
{
    private static $_api;

    public static function getApi()
    {
        if (is_null(self::$_api)) {
            $credentials = explode(';', file_get_contents('credentials'));
            list($login, $password) = $credentials;

            self::$_api = new Api(
                'https://hq.tutu.ru',
                new Basic($login, $password)
            );
        }

        return self::$_api;
    }
}
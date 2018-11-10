<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

namespace Pomojira;

use Jira_Api;
use Jira_Api_Authentication_Basic;

class Helper
{
    private static $_api;

    public static function getApi()
    {
        if (is_null(self::$_api)) {
            $credentials = explode(';', file_get_contents('credentials'));
            list($login, $password) = $credentials;

            self::$_api = new Jira_Api(
                'https://hq.tutu.ru',
                new Jira_Api_Authentication_Basic($login, $password)
            );
        }

        return self::$_api;
    }
}
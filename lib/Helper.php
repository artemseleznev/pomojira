<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

namespace Pomojira;

use Jira_Api;
use Jira_Api_Authentication_Basic;
use Dotenv\Dotenv;

class Helper
{
    private static $_api;

    public static function getApi()
    {
        if (is_null(self::$_api)) {
            list($login, $password) = [getenv('JIRA_USER'), getenv('JIRA_PASSWORD')];

            self::$_api = new Jira_Api(
                getenv('JIRA_HOST'),
                new Jira_Api_Authentication_Basic($login, $password)
            );
        }

        return self::$_api;
    }

    public static function initDotenv()
    {
        $dotenv = new Dotenv(__DIR__.DIRECTORY_SEPARATOR.'..');
        $dotenv->load();
    }
}
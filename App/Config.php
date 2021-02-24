<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';
//    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'personalbudget';
//    const DB_NAME = 'budzetka_homebudget';
    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';
//    const DB_USER = 'budzetka_HomeBudgetAdmin';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';
//    const DB_PASSWORD = '5y2GDSj6b6cfWEwg';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    const SECRET_KEY = 'secret';

    //const MAILGUN_API_KEY = 'YOUR-MAILGUN-API-key';
    //const MAILGUN_DOMAIN = 'YOUR-MAILGUN-DOMAIN';
}

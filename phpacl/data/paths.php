<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 23/12/17
 * Time: 19:10
 */

define('PATH_ROOT', realpath(__DIR__ . '/../'));
define('PATH_APP', realpath(__DIR__ . '/../App'));
define('PATH_BASE', realpath(PATH_APP . '/Base'));
define('PATH_CORE', realpath(PATH_APP . '/Core'));
define('PATH_VIEWS', realpath(__DIR__ . '/../Views'));
define('PATH_PUBLIC', realpath(PATH_ROOT . '/../public_html/acl/'));


define('PATH_DATA', realpath(PATH_ROOT . '/data'));
define('PATH_SETTINGS', realpath(PATH_ROOT . '/data/settings'));

define('PATH_UPLOADS', PATH_PUBLIC . "/uploads/");

/* PATHS DELS FITXERS A INCLUIR */
if (\Data\Config::PRODUCTION_MODE)
    define('PATH_FILE_SETTINGS', PATH_SETTINGS . "/settings.xml");
else
    define('PATH_FILE_SETTINGS', PATH_SETTINGS . "/settings.xml");

define('PATH_DATABASE_CONFIG', PATH_SETTINGS . "/database_config.json");
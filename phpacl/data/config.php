<?php

namespace Data {
    class Config
    {
        const DEBUG = true;

        // if the aplication is on production mode
        const PRODUCTION_MODE = false;

        const REMEMBER =
            [
                'cookie_name' => 'pmqesoxiw318374csb',
                'cookie_expiry' => 604800  //One week, feel free to make it longer
            ];

        const SESSION =
            [
                'name' => 'user',
                'token_name' => 'token',
            ];

    }

    if (!config::DEBUG) {
        ini_set('display_errors', '0');     # don't show any errors...
        error_reporting(E_ALL | E_STRICT);  # ...but do log them

    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }


    date_default_timezone_set('Etc/UTC');
}
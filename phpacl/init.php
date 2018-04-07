<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 23/12/17
 * Time: 18:34
 */

use App\config;
use PhpGene\Messages;
use System\App;


require_once __DIR__ . "/App/Core/Config.php";
require_once __DIR__ . "/data/links.php";
require_once __DIR__ . "/data/paths.php";
require_once __DIR__ . "/data/lang.php";
require_once __DIR__ . "/data/database.php";
require_once __DIR__ . "/data/Constants.php";
require_once PATH_DATA . '/lang.php';

require_once __DIR__ . "/vendor/autoload.php";

require_once __DIR__ . '/App/Base/PhpGene/load.php';
require_once __DIR__ . '/App/Base/Database/AccesBD.php';

Messages::setDebugMode(\Data\Config::DEBUG);

// allways load all system models
foreach (glob(PATH_CORE . "/System/Models/*.php") as $filename) {
    require_once $filename;
}

$app = new App();
if ($app->getStatus()->site_offline == 1 && !$app->getUser()->isAdmin()) {
//    \General\Redirect::to(LINK_BASE_OFFLINE); TODO: redirect to offline page
}

foreach (glob(PATH_CORE . "/System/*.php") as $filename) {
    if ($filename != PATH_CORE . "/System/Controller.php")
        require_once $filename;
}

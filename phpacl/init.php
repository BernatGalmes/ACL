<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 23/12/17
 * Time: 18:34
 */

use PhpGene\Messages;
use PHPACL\App;


function require_files($path_folder){
    foreach (glob($path_folder . "/*.php") as $filename) {
        require_once $filename;
    }
}

# load vendor
require_once __DIR__ . "/vendor/autoload.php";

# load constants files
require_files(__DIR__ . "/data/");

$paths = array(
    PATH_BASE,
    PATH_CORE . "/Models",
    PATH_APP
);
//echo get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths);
//set_include_path(get_include_path() . PATH_SEPARATOR . PATH_APP);
//print get_include_path();



// load base folders files
foreach (glob(PATH_BASE . "/*/*.php") as $filename) {
    require_once $filename;
}

# load app base files
require_files(PATH_BASE);

require_once PATH_CORE . "/Config.php";
require_once PATH_CORE . "/Models/User.php";
require_files(PATH_CORE . "/Models/");
require_files(PATH_APP . "/");

Messages::setDebugMode(\Data\Config::DEBUG);


$app = new App();
if ($app->getStatus()->site_offline == 1 && !$app->getUser()->isAdmin()) {
//    \General\Redirect::to(LINK_BASE_OFFLINE); TODO: redirect to offline page
}

foreach (glob(PATH_CORE . "/System/*.php") as $filename) {
    if ($filename != PATH_CORE . "/System/Controller.php")
        require_once $filename;
}

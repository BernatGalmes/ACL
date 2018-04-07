<?php
session_start();
require_once __DIR__ . '/../../phpacl/init.php';

$klein = new \Klein\Klein();


/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
$toHome = function ($request, $response, $service){
    $response->redirect("/eaudit/index.php");
};

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
$toIndex = function ($request, $response, $service){
    $response->redirect($request->uri() . "index.php");

};

/**
 * @param int $code
 * @param \Klein\Klein $router
 */
$klein->onHttpError(function ($code, $router) {
    if ($code == 404) {

        $router->response()->body(
            "<h1>BAD LINK</h1>"
        );
    } elseif ($code == 405) {
        $router->response()->body(
            'You can\'t do that!'
        );
    }elseif ($code >= 400 && $code < 500) {
        $router->response()->body(
            'Oh no, a bad error happened that caused a '. $code
        );
    } elseif ($code >= 500 && $code <= 599) {
        error_log('uhhh, something bad happened');
    }
});

foreach (glob(PATH_CONTROLLERS . "/*.php") as $filename) {
    require_once $filename;
}

$klein->respond("GET", "/eaudit/", $toHome);
$klein->respond("GET", "/eaudit/*", $toHome);
$klein->respond("GET", "/eaudit/centros/", $toIndex);
$klein->dispatch();
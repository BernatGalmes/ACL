<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 12/05/18
 * Time: 22:05
 */

use Respect\Validation\Validator as v;
/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @throws \Exception
 */
$controller_main = function ($request, $response, $service){
    if (!(new \PHPACL\User_logged())->isLoggedIn()){
        $service->render(PATH_APP . "/Main/Views/login.php", [
            'user' => new \PHPACL\User_logged()
        ]);
    }else{
        $service->render(PATH_APP . "/Main/Views/index.php");
    }
};

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @throws \Exception
 */
$controller_login = function ($request, $response, $service){
    echo "hello!!!";
    $user = new \PHPACL\User_logged();
    if (!$user->isLoggedIn()) {
        if (v::stringType()->validate($request->username) &&
            v::stringType()->validate($request->password)
        ){
            $user = new \PHPACL\User_logged();
            $login = $user->login($request->username, trim($request->password));
            if (!$login) {
                $user->getMessages()->debug(\BD\AccesBD::getInstance()->abd_darreraConsulta());
                $user->getMessages()->debug(\BD\AccesBD::getInstance()->abd_darrerError());
                $user->getMessages()->posa_error('Login fallido, por favor compruebe sus credenciales.');
                $service->render(PATH_APP . "/Main/Views/login.php", [
                    'user' => $user
                ]);
                return;
            }
        }
    }

    $response->redirect(LINK_APP)->send();


};

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @throws \Exception
 */
$controller_forgot_pass = function ($request, $response, $service){

};

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @throws \Exception
 */
$controller_logout = function ($request, $response, $service){
    (new \PHPACL\User_logged())->logout();
    $response->redirect("/acl");
};



$klein->respond("GET", "/acl/", $controller_main);
$klein->respond("GET", "/acl/logout", $controller_logout);
$klein->respond("POST", "/acl/login", $controller_login);
$klein->respond("GET", "/acl/forgot_password", $controller_forgot_pass);
$klein->respond("GET", "/acl/*", $controller_main);

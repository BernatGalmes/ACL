<?php
namespace PHPACL;

/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/02/18
 * Time: 10:44
 */

use BD\AccesBD;
use Core\Controllers\Pages;
use Klein\Klein;
use PhpGene\Messages;


/** @var Klein $klein */
$klein->with('/acl/system', function () use ($klein) {

    foreach (glob( __DIR__ . "/Controllers/*.php") as $filename) {
        require_once $filename;
    }


    $klein->respond(['POST', 'GET'], '/', Pages::get('dashboard'));
    $klein->respond(['POST', 'GET'], '/permissions/[addrole|remrole|delete|edit|create:action]?/[*:id_perm]?', Pages::get('permissions_list'));

    $klein->respond(['POST', 'GET'], '/account', function ($request, $response, $service){
        $service->render(Config::VIEW_FILE_ACCOUNT);
    });
});


/** @var Klein $klein */
$klein->with('/acl/system/users', function () use ($klein) {

    /**
     * Controller upload user logo
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @throws \Exception
     */
    $controller_upload_logo = function ($request, $response, $service){
        $files = $request->files();
        if ($files->exists("logo")) {
            $upload_logo = $files->get("logo");;
            $tmp_name_logo =  $upload_logo["tmp_name"];
            if ($upload_logo['error'] == 0) { // if no error

                // get image
                $image = imagecreatefromstring(
                    file_get_contents($tmp_name_logo)
                );

                list($ancho, $alto) = getimagesize($tmp_name_logo);
                list($nuevo_ancho, $nuevo_alto) = getimagesize(PATH_PUBLIC . "/recursos/imatges/logo.png");

                // resize image
                $im_logo = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
                imagecopyresized($im_logo, $image, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);

                // save image
                $res = imagepng($im_logo, PATH_PUBLIC . "/logos/" . $request->id_user . ".png");
                if (!$res) {
                    echo "error uploading file";
                }
            }
        }
        $user = new User($request->id_user);
        render_user_edit($service, $user, $user->getMessages());
    };

    $klein->respond(['POST', 'GET'], '/', Pages::get('users_list'));

    $klein->respond(['POST', 'GET'], '/[i:id_user]/[remove|update|centro:action]?', Pages::get('users_edit'));
    $klein->respond(['POST', 'GET'], '/[i:id_user]/upload_logo', $controller_upload_logo);

});



/** @var Klein $klein */
$klein->with('/acl/system/roles', function () use ($klein) {

    /**
     * Controller all actions and views related with Roles
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     */
    $controller_roles_create = function ($request, $response, $service){
        $role = role_create($request, $response, $service);
        if ($role->exists()){
            role_page($role, $request, $response, $service);
        }else{
            role_list(new Messages(), $request, $response, $service);
        }
    };

    $klein->respond(['POST', 'GET'], '/', Pages::get('roles_list'));
    $klein->respond(['POST', 'GET'], '/[delete|edit|addPermissions|remPermissions:action]?/[i:id_role]?', Pages::get('roles_edit'));
    $klein->respond(['POST', 'GET'], '/create', $controller_roles_create);
});


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


/**
 * @param \PhpGene\Messages $msgs
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
function permission_page ($id_perm, $msgs, $request, $response, $service){
    $service->render(Config::VIEW_FILE_PERMISSION,
        [
            'id_perm' => $id_perm,
            'msgs' => $msgs
        ]
    );
}

/**
 * @param \PhpGene\Messages $msgs
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
function permission_list ($msgs, $request, $response, $service){
    $aBD = AccesBD::getInstance();
    $permissions = $aBD->getPermissions();
    $service->render(Config::VIEW_FILE_PERMISSIONS,
        [
            'permissions' => $permissions,
            'perm' => new \PHPACL\Permission(),
            'msgs' => $msgs
        ]
    );
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return \PhpGene\Messages
 */
function permission_create ($request, $response, $service){
    $role = new Permission(
        [
            "tag"=> $request->tag,
            "description" => $request->description,
        ]);
    $msgs =$role->getMessages();
    if($role->insert()){
        $msgs->posa_ok("Permiso añadido correctamente");
//        permission_page($role->getID(), $msgs, $request, $response, $service);

    }else{
        $msgs->posa_error("El Permiso no se pudo añadir");
    }
    return $msgs;
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return \PhpGene\Messages
 */
function permission_edit ($request, $response, $service){
    $perm = new \PHPACL\Permission($request->id_perm);
    $perm->setData([
            "tag"=> $request->tag,
            "description" => $request->description,
        ]);
    $msgs =$perm->getMessages();
    if($perm->update()){
        $msgs->posa_ok("Permiso actualizado correctamente.");

    }else{
        $msgs->posa_error("El Permiso no se pudo actualizar.");
    }
    return $msgs;
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return \PhpGene\Messages
 */
function permission_delete ($request, $response, $service){
    // TODO: perform deletion
    return new \PhpGene\Messages();
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return \PhpGene\Messages
 */

function permission_addroles ($request, $response, $service){
    $roles_add = $request->roles_add;
    if (empty($roles_add)){
        return new \PhpGene\Messages();
    }
    $perm = new \PHPACL\Permission($request->id_perm);
    $perm->addRoles($roles_add);
    return $perm->getMessages();

}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return \PhpGene\Messages
 */
function permission_remroles ($request, $response, $service){
    $roles_rem = $request->roles_rem;
    if (empty($roles_rem)){
        return new \PhpGene\Messages();
    }
    $perm = new \PHPACL\Permission($request->id_perm);
    $perm->remRoles($roles_rem);
    return $perm->getMessages();
}

/** @var Klein $klein */
$klein->with('/acl/system', function () use ($klein) {

//    echo __DIR__ . "/Controllers/*.php";
//    Messages::debugVar(glob( __DIR__ . "/Controllers/*.php"));
//    exit();
    foreach (glob( __DIR__ . "/Controllers/*.php") as $filename) {
        require_once $filename;
    }



    /**
     * Controller all actions and views related with Roles
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     */
    $controller_permissions = function ($request, $response, $service){
        if (!\PHPACL\App::get()->isSuperUserSession()){
            $response->redirect("/eaudit");
            return;
        }
        // if bad request
        if(in_array($request->action, ['edit', 'delete', 'addrole', "remrole"]) &&
            empty($request->id_perm)){
            $response->redirect("/eaudit/permissions");//TODO:
            return;
        }
        switch ($request->action){
            case "create":
                $msgs = permission_create ($request, $response, $service);
                break;
            case "edit":
                $msgs = permission_edit ($request, $response, $service);
                break;
            case "delete":
                $msgs = permission_delete ($request, $response, $service);
                break;
            case "addrole":
                $msgs = permission_addroles ($request, $response, $service);
                break;
            case "remrole":
                $msgs = permission_remroles ($request, $response, $service);
                break;
            default:
                $msgs = new \PhpGene\Messages();
                break;
        }
        if(!empty($request->id_perm)){
            permission_page($request->id_perm, $msgs, $request, $response, $service);
        }else {
            permission_list($msgs, $request, $response, $service);
        }
    };


    $klein->respond(['POST', 'GET'], '/', Pages::get('dashboard'));
    $klein->respond(['POST', 'GET'], '/permissions/[addrole|remrole|delete|edit|create:action]?/[*:id_perm]?', $controller_permissions);

//    $klein->respond("GET", "/users", Pages::get('users_list'));
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


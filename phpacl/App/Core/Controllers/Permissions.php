<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 8/07/18
 * Time: 19:12
 */


namespace Core\Controllers;

use BD\AccesBD;
use PHPACL\Config;
use PHPACL\Permission;
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
            'perm' => new Permission(),
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
    $perm = new Permission($request->id_perm);
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
    return new Messages();
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
        return new Messages();
    }
    $perm = new Permission($request->id_perm);
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
        return new Messages();
    }
    $perm = new Permission($request->id_perm);
    $perm->remRoles($roles_rem);
    return $perm->getMessages();
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/04/18
 * Time: 12:26
 */
namespace PHPACL;
use App\database_item;
use BD\ABD_system;
use Klein\Klein;
use PhpGene\Messages;


/**
 * @param Role $role
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
function role_page ($role, $request, $response, $service){
    $service->render(Config::VIEW_FILE_ROLE,
        [
            'role' => $role
        ]
    );
}

/**
 * @param \PhpGene\Messages $msgs
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
function role_list ($msgs, $request, $response, $service){
    $aBD = ABD_system::getInstance();
    $roles = $aBD->getRoles();
    $service->render(Config::VIEW_FILE_ROLES,
        [
            'roles' => $roles,
            'msgs' => $msgs
        ]
    );
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return Role
 */
function role_create ($request, $response, $service){
    $role = new Role(["name"=> $request->name]);
    $msgs =$role->getMessages();
    if($role->insert()){
        $msgs->posa_ok("Rol añadido correctamente");

    }else{
        $msgs->posa_error("El rol no se pudo añadir");
    }
    return $role;
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return Role
 */
function role_edit ($request, $response, $service){

}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return Role
 */
function role_delete ($request, $response, $service){
    $role = new Role($request->id_role);
    if($role->remove()){
        $role= new Role();
        $msgs = $role->getMessages();
        $msgs->posa_ok("Role eliminado correctamente");
        return $role;
    }
    $msgs = $role->getMessages();
    $msgs->posa_error("Error eliminando rol");
    return $role;
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return Role
 */
function role_addPermissions ($request, $response, $service){
    $role = new Role($request->id_role);
    $perms_add = $request->roles_add;
    if (empty($perms_add)){
        return $role;
    }

    foreach ($perms_add as $id_perm){
        $db_item = new database_item([
            "id_role" => $role->getID(),
            "id_permission" => $id_perm
        ], \BD_main::TAULA_ROLE_PERMISIONS);
        $db_item->insert();
        $role->getMessages()->merge($db_item->getMessages());
    }
    return $role;
}

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 * @return Role
 */
function role_remPermissions ($request, $response, $service){
    $role = new Role($request->id_role);

    $perms_rem = $request->roles_rem;
    if (empty($perms_rem)){
        return $role;
    }
    $aBD = ABD_system::getInstance();
    foreach ($perms_rem as $id_perm){
        $aBD->unrelateRolePermission($role->getID(), $id_perm);
    }
    return $role;
}

/** @var Klein $klein */
$klein->with('/roles', function () use ($klein) {

    /**
     * Controller all actions and views related with Roles
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     */
    $controller_roles = function ($request, $response, $service){
        if(!empty($request->id_role)){
            switch ($request->action){
                case "edit":
                    $role = role_edit($request, $response, $service);
                    break;
                case "delete":
                    $role = role_delete($request, $response, $service);
                    break;
                case "addPermissions":
                    $role = role_addPermissions($request, $response, $service);
                    break;
                case "remPermissions":
                    $role = role_remPermissions($request, $response, $service);
                    break;
                default:
                    try {
                        $role = new Role($request->id_role);
                    }catch (\Exception $e){
                        $role = new Role();
                    }
                    break;
            }

            if ($role->exists()){
                role_page($role, $request, $response, $service);
            }else{
                role_list($role->getMessages(), $request, $response, $service);
            }

        }else {
            role_list(new Messages(), $request, $response, $service);
        }
    };

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


    // permissions
    $klein->respond(['POST', 'GET'], '/', function ($request, $response, $service){
        role_list(new Messages(), $request, $response, $service);
    });
    $klein->respond(['POST', 'GET'], '/[delete|edit|addPermissions|remPermissions:action]?/[i:id_role]?', $controller_roles);
    $klein->respond(['POST', 'GET'], '/create', $controller_roles_create);
});

<?php
namespace PHPACL;

/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/02/18
 * Time: 10:44
 */

use BD\AccesBD;
use Klein\Klein;

function initSystem(){
//    require_once PATH_CORE . '/BD/ABD_system.php';
//    foreach (glob(PATH_CORE . "/System/Models/*.php") as $filename) {
//        require_once $filename;
//    }
//    foreach (glob(PATH_CORE . "/System/*.php") as $filename) {
//        require_once $filename;
//    }
}

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
$klein->with('/eaudit/system', function () use ($klein) {
    initSystem();

    foreach (glob(PATH_CORE . "/System/Controllers/*.php") as $filename) {
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

    /**
     * Controller dashboard
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     */
    $controller_dashboard = function ($request, $response, $service){
        if (!(new \PHPACL\User_logged())->hasPermission('sys_dashboard')){
            $response->redirect("/eaudit");
            return;
        }

//        $response->redirect("/eaudit/system/index.php");
    };

    /**
     * Controller dashboard
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @throws \Exception
     */
    $controller_list_companies = function ($request, $response, $service){
        $msgs = new \PhpGene\Messages();

        $comp = new \PHPACL\Companyia();
        if ($request->action == \App\database_item::ACTION_INSERT){
            $comp->setAttr('name', $request->name);
            if ($comp->insert()){
                $response->redirect('/eaudit/system/company/' . $comp->getID());
                return;
            }else{
                $msgs->posa_error("Error insertando compañia.");
            }
        }

       render_company_list($request, $response, $service, $msgs, $comp);
    };

    /**
     * Controller company page
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @return bool
     * @throws \Exception
     */
    $controller_company = function ($request, $response, $service){
//        if (!\System\App::get()->isSuperUserSession()){
//            $response->redirect("/eaudit");
//            return;
//        }
        $comp = new \PHPACL\Companyia($request->id_comp);
        $msgs = $comp->getMessages();
        if (!empty($request->action)){

            $comp->setAttr('name', $request->name);

            switch ($request->action){
                // update item
                case \PhpGene\database_item::ACTION_UPDATE:
                    if ($comp->update()){
                        $msgs->posa_ok("Item updated correctly.");
                    }else{
                        $msgs->posa_error("Error updating item.");
                    }
                    break;

                // remove item
                case \PhpGene\database_item::ACTION_REMOVE:
                    if ($comp->remove()){
                        $msgs->posa_ok("Item removed successfully.");
                        render_company_list($request, $response, $service, $msgs);
                        return true;

                    }else{
                        $msgs->posa_error("Error deleting item.");
                    }
                    break;

                default:
                    return false;
            }
        }
        $service->render(Config::VIEW_FILE_COMPANY,
            [
                'company' => $comp,
                'msgs' => $msgs
            ]
        );
        return true;
    };
    $klein->respond(['POST', 'GET'], '/', $controller_dashboard);


    $klein->respond(['POST', 'GET'], '/permissions/[addrole|remrole|delete|edit|create:action]?/[*:id_perm]?', $controller_permissions);

    // companies
    $klein->respond(['POST', 'GET'], '/company/[i:id_comp]/[remove|update:action]?', $controller_company);
    $klein->respond(['POST', 'GET'], '/companies/[insert:action]?', $controller_list_companies);
    $klein->respond(['POST', 'GET'], '/account', function ($request, $response, $service){
        $service->render(Config::VIEW_FILE_ACCOUNT);
    });
});


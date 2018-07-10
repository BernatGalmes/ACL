<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/03/18
 * Time: 19:43
 */

namespace Core\Controllers;


use BD\AccesBD;
use PHPACL\Config;
use function PHPACL\render_user_edit;
use PHPACL\Role;
use function PHPACL\role_addPermissions;
use function PHPACL\role_delete;
use function PHPACL\role_edit;
use function PHPACL\role_list;
use function PHPACL\role_page;
use function PHPACL\role_remPermissions;
use PHPACL\User_logged;
use PhpGene\Messages;

class Pages
{
    static $c_pages = [];

    static function get($page){
        return self::$c_pages[$page];
    }
}



/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['users_list'] =
    function ($request, $response, $service){
        if (!(new User_logged())->hasPermission("sys_config")){
            echo '<div class="row">
                    <div class="col-md-12">
                    <h1>No tienes permiso para ver estos documentos</h1>
                    </div>
                  </div>';
            return;
        }
        // TODO: place users list view controller

        $aBD = AccesBD::getInstance();
        $permOps = $aBD->abd_consultaTaula(\BD_main::TAULA_ROLES);

        $msgs = new Messages();

//        try {
//            $userForm = Users::FormCreate();

            $users = $aBD->getUsers();
//
//        } catch (\Exception $e) {
//            require_once PATH_HTML_HEAD . '/main.php';
//            $aBD = ABD_system::getInstance();
//            $msgs->debug($aBD->abd_darreraConsulta());
//            $msgs->debug($aBD->abd_darrerError());
//            $msgs->debug($e->getMessage() . 'in File: ' . $e->getFile());
//            $msgs->debug('Line: ' . $e->getLine());
//            $msgs->showMessages();
//            include PATH_INCLUDES . '/footer.php';
//            die();
//        }


//        $newPasswd = User::randomPassword();


        $service->render(PATH_CORE . "/Views/users_list.php",
            [
                'users' => $users,
                'permOps' => $permOps,
//                'centros' => $centros
            ]
        );
    };



/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['users_edit'] =
    function ($request, $response, $service){
        if (!(new User_logged())->hasPermission("sys_config")){
            echo '<div class="row">
                    <div class="col-md-12">
                    <h1>No tienes permiso para ver estos documentos</h1>
                    </div>
                  </div>';
            return;
        }
        try{
            $user = new \PHPACL\User($request->id_user);
        }catch (\Exception $e){
            echo "the requested user doesn't exists";
            return false;
        }
        $msgs = $user->getMessages();
        if (!empty($request->action)){

            $user->setAttr('username', $request->username);
            $user->setAttr('email', $request->email);
            $user->setAttr('fname', $request->fname);
            $user->setAttr('lname', $request->lname);
            $user->setAttr('permission_id', $request->permission_id);

            switch ($request->action){
                // update item
                case \PhpGene\database_item::ACTION_UPDATE:
                    if ($user->update()){
                        $msgs->posa_ok("Item updated correctly.");
                    }else{
                        $msgs->posa_error("Error updating item.");
                    }
                    break;

                // remove item
                case \PhpGene\database_item::ACTION_REMOVE:
                    if ($user->remove()){
                        $msgs->posa_ok("Item removed successfully.");
                        $response->redirect("/eaudit/system/users/");
                        return true;

                    }else{
                        $msgs->posa_error("Error deleting item.");
                    }
                    break;

                case 'centro':
                    if (isset($request->asocCentro)){
                        if ($user->associateCentre($request->id_centre_asoc)){
                            $msgs->posa_ok("centro associado correctamente");
                        }else{
                            $msgs->posa_error("Error asociando centro");
                        }
                    }elseif (!empty($request->desasocCentro)) {
                        $user->unassociateCentre($request->desasocCentro);
                    }
                    break;
                default:
                    return false;
            }
        }

        render_user_edit($service, $user, $msgs);
        return true;
    };
/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['dashboard'] =
    function ($request, $response, $service){
        if (!(new User_logged())->hasPermission("sys_dashboard")){
            echo '<div class="row">
                    <div class="col-md-12">
                    <h1>No tienes permiso para ver estos documentos</h1>
                    </div>
                  </div>';
            return;
        }

//        System::delete_user_online(); //Deletes sessions older than 24 hours
//
//        //Find users who have logged in in X amount of time.
//
        $aBD = AccesBD::getInstance();
        $data = $aBD->getSystemData();
//
//        if (!empty($_POST['settings'])) {
//
//            //sitio offline
//            $site_offline = Input::get('site_offline');
//            Messages::debugVar($site_offline);
//            $app->setOffline($site_offline);
//
//            //track guests
//            $track_guest = Input::get('track_guest');
//            $app->setTrack_guests($track_guest);
//
//            Redirect::to($_SERVER['PHP_SELF']);
//        }
//
//
//        if (isset($_POST['backupbd'])) {
//            $file_bu = $aBD->backup();
//            echo "Copia efectuada correctamente.<br>  <a href='" . LINK_BACKUPS . $file_bu . "'>Descargar</a>.<br>\n";
//        }

        $service->render(PATH_CORE . "/Views/dashboard.php",
            [
                'data' => $data,
//                'form_taskslist' => $form_tasksList,
//                'centros' => $centros
            ]
        );
    };

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['tasksList_task'] =
    function ($request, $response, $service){
        if (!(new User_logged())->hasPermission("docs_tasks_list")){
            echo '<div class="row">
                    <div class="col-md-12">
                    <h1>No tienes permiso para ver estos documentos</h1>
                    </div>
                  </div>';
            return;
        }

    };

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['permissions_list'] =
    function ($request, $response, $service){
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

/*
 *
 * ROLES PAGES
 *
 */

/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['roles_list'] =
    function ($request, $response, $service){
        if (!(new User_logged())->hasPermission("sys_config")){
            echo '<div class="row">
                    <div class="col-md-12">
                    <h1>No tienes permiso para ver estos documentos</h1>
                    </div>
                  </div>';
            return;
        }
        // TODO: place ROLES list view controller

        role_list(new Messages(), $request, $response, $service);
    };



/**
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['roles_edit'] =
    function ($request, $response, $service){
        if (!(new User_logged())->hasPermission("sys_config")){
            echo '<div class="row">
                    <div class="col-md-12">
                    <h1>No tienes permiso para ver estos documentos</h1>
                    </div>
                  </div>';
            return;
        }
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
 * @param \Klein\Request $request
 * @param \Klein\Response $response
 * @param \Klein\ServiceProvider $service
 */
Pages::$c_pages['mail_config'] =
    function ($request, $response, $service){
        if (!(new User_logged())->hasPermission("sys_config")){
            echo '<div class="row">
                    <div class="col-md-12">
                    <h1>No tienes permiso para ver estos documentos</h1>
                    </div>
                  </div>';
            return;
        }

        // TODO: implement
        echo "<h1>Not implemented yet</h1>";
    };


<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/03/18
 * Time: 19:43
 */

namespace Core\Controllers;


use BD\AccesBD;
use function PHPACL\render_user_edit;
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

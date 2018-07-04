<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 2/04/18
 * Time: 13:29
 */
namespace PHPACL;

use BD\ABD_system;
use Core\Controllers\Pages;
use Klein\Klein;

function render_user_edit($service, $user, $msgs){
    $aBD = ABD_system::getInstance();
    $service->render(Config::VIEW_FILE_EDIT_USER,
        [
            'user' => $user,
            'roles' => $aBD->abd_consultaTaula(\BD_main::TAULA_ROLES),
            'centros' => $Allcentros = $aBD->getClients(),
            'msgs' => $msgs
        ]
    );
}

/** @var Klein $klein */
$klein->with('/users', function () use ($klein) {

    /**
     * Controller company page
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @return bool
     * @throws \Exception
     */
    $controller_edit_user = function ($request, $response, $service){
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

    $klein->respond(['POST', 'GET'], '/[i:id_user]/[remove|update|centro:action]?', $controller_edit_user);
    $klein->respond(['POST', 'GET'], '/[i:id_user]/upload_logo', $controller_upload_logo);

});
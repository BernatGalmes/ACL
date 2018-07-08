<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 2/04/18
 * Time: 13:29
 */
namespace PHPACL;

use BD\ABD_system;
use BD\AccesBD;
use Core\Controllers\Pages;
use Klein\Klein;

function render_user_edit($service, $user, $msgs){
    $aBD = AccesBD::getInstance();
    $service->render(Config::VIEW_FILE_EDIT_USER,
        [
            'user' => $user,
            'roles' => $aBD->abd_consultaTaula(\BD_main::TAULA_ROLES),
//            'centros' => $Allcentros = $aBD->getClients(),
            'msgs' => $msgs
        ]
    );
}

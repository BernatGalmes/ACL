<?php
namespace General;

use PhpGene\Input;
use PHPACL\Config;
use PHPACL\Pages;
use PHPACL\Role;


$role = $this->role;
$permissionId = $this->role->getID();
$validation = new Validate();

//Forms posted
if (Input::exists()) {
    if (isset($_POST['role-update'])) {
        if ($role->getAttr('name') != $_POST['name']) {
            $role->updateName($validation, Input::get('name'));
        }
    } elseif (isset($_POST['role-updatePages'])) {
        $pagesAdd = $_POST['addPage'];
        $pagesRemove = $_POST['removePage'];
        $role->updatePages($pagesAdd, $pagesRemove);
    }

    try {
        $role = new Role($permissionId);
    } catch (\Exception $e) {
        Redirect::to(LINK_SYSTEM_PERMISSIONS);
        return;
    }
}
//
//if (isset($_GET['del'])) {//delete current role
//    if ($role->remove()) {
//        Redirect::to(LINK_SYSTEM_PERMISSIONS);
//
//    } else {
//        $validation->addError("no se puede eliminar este rol");
//    }
//
//}


//Retrieve list of accessible pages
$pagePermissions = $role->pages();

//Retrieve list of users with membership
$permissionUsers = $role->users();

//Fetch all
$pages = new Pages();
$pageData = $pages->checkDatabase();

require_once PATH_INCLUDES . '/System/header.php';
?>
<div class="page-header">
    <div class="row">
        <h1 align="center">Gestión del rol: <b><?= $role->getAttr('name') ?></b></h1>
    </div>
</div>

<!-- Page Heading -->
<div class="row">
    <div class="col-xs-12">
        <div id="form-errors">
            <?= $validation->getMissatges()->html() ?>
            <?= $role->getMessages()->html() ?>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="row">
                    <div class="col-md-12 well">
                        <form name='role-update' action='<?= $_SERVER['PHP_SELF'] ?>?id=<?= $permissionId ?>' method='post'>
                            <div class="form-group">
                                <label for="id-role">ID:</label>
                                <p id="id-role"> <?= $role->getAttr('id') ?></p>
                            </div>
                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" class="form-control" id="name" name='name'
                                       value='<?= $role->getAttr('name') ?>'>
                            </div>
                            <button name="role-update" type="submit" class="btn btn-primary">Actualiza</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 well">
                        <a href="<?= Config::LINK_ROLES ?>delete/<?=$role->getID()?>" id="delete-user"
                           class="btn btn-danger pull-left"><i class="fa fa-trash fa-3x"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 well">
                        <a href="<?= Config::LINK_ROLES ?>" id="#"
                           class="btn btn-success pull-left">
                            <i class="fa fa-angle-left fa-3x"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Roles sin el permiso: </h3>
                    </div>
                    <div class="col-md-6">
                        <h3>Roles con el permiso: </h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <form action="/eaudit/system/roles/addPermissions/<?=$role->getID()?>"
                              id="form-addRole" method="post" >
                            <div class="form-group">
                                <label for="sel1">Select list:</label>
                                <select name="roles_add[]" multiple class="form-control" id="sel1" style="overflow: scroll;height: 400px">
                                    <?php
                                    //Display users with this role
                                    foreach ($role->getNonePermissions() as $perm):
                                        ?><option value="<?=$perm->getID()?>">
                                        <i class="fa fa-user" aria-hidden="true"></i> <?= $perm->getAttr('tag') . "/(" .$perm->getAttr('description')?>)
                                        </option><?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" form="form-addRole" class="btn btn-default">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                        <button type="submit" form="form-remRole" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                    </div>
                    <div class="col-md-5">
                        <form action="/eaudit/system/roles/remPermissions/<?=$role->getID()?>"
                              id="form-remRole" method="post">
                            <div class="form-group">
                                <label for="sel1">Select list:</label>
                                <select name="roles_rem[]" multiple class="form-control" id="sel1" style="overflow: scroll;height: 400px">
                                    <?php
                                    // Display users with this role
                                    foreach ($role->getPermissions() as $perm):
                                        ?><option value="<?=$perm->getID()?>">
                                        <i class="fa fa-user" aria-hidden="true"></i> <?= $perm->getAttr('tag') . "/(" .$perm->getAttr('description')?>)
                                        </option><?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        Los usuarios de este rol tienen los siguientes permisos
                        <ul>
                            <?php
                            foreach ($role->getPermissions() as $perm):
                                ?>
                                <li>
                                 <?= $perm->getAttr('description')?>
                                </li><?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h3>Miembros: </h3>
                        <div style="overflow: scroll;height: 700px">
                            <?php
                            // Display users with this role
                            foreach ($permissionUsers as $user):
                                ?><p><i class="fa fa-user" aria-hidden="true"></i> <?= $user['username'] ?></p><?php
                            endforeach;
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h3>Accesos del permiso</h3>
                        <form name="role-updatePages">
                            <div style="overflow: scroll;height: 700px">
                                <p><strong>Páginas públicas:</strong>
                                    <?php
                                    //List public pages
                                    foreach ($pageData as $v1) {
                                        if ($v1['private'] != 1) {
                                            echo "<br>" . $v1['page'];
                                        }
                                    }
                                    ?>
                                </p>
                                <p><br><strong>
                                        Remove Access From This Level:</strong>
                                    <?php
                                    //Display list of pages with this access level
                                    $page_ids = [];
                                    foreach ($pagePermissions as $pp) {
                                        $page_ids[] = $pp['page_id'];
                                    }
                                    foreach ($pageData as $v1) {
                                        if (in_array($v1['id'], $page_ids)) { ?>
                                            <br><input type='checkbox' name='removePage[]' id='removePage[]'
                                                       value='<?= $v1['id'] ?>'> <?= $v1['page'] ?>
                                        <?php }
                                    } ?>
                                </p>
                                <p><br>
                                    <strong>Add Access To This Level:</strong>
                                    <?php
                                    foreach ($pageData as $v1):
                                        if (!in_array($v1['id'], $page_ids) && $v1['private'] == 1): ?>
                                            <br><input type='checkbox' name='addPage[]' id='addPage[]'
                                                       value='<?= $v1['id'] ?>'> <?= $v1['page'] ?>
                                        <?php endif;
                                    endforeach; ?>
                                </p>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualiza páginas</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once PATH_INCLUDES . '/System/footer.php'; // the final html footer copyright row + the external js calls
?>

<?php require_once PATH_INCLUDES . '/html_footer.php'; // currently just the closing /body and /html
?>

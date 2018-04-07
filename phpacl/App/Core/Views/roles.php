<?php

namespace General;

//
////Delete a permission level from the DB
//function deletePermission($permission)
//{
//    global $errors;
//    $i = 0;
//    foreach ($permission as $id) {
//        $p = new Role($id);
//        $p->remove();
//        $i++;
//    }
//    return $i;
//}

//$validation = new Validate();
//PHP Goes Here!

//Missatges::debugVar($_POST);
//Forms posted
//if (!empty($_POST)) {
//    //Create new permission level
//    if (isset($_POST['addPermission'])) {
//        $permission = Input::get('name');
//        $fields = array('name' => $permission);
//        //NEW Validations
//        $validation->check($_POST, array(
//            'name' => array(
//                'display' => 'Role Name',
//                'required' => true,
//                'unique' => \BD_main::TAULA_ROLS,
//                'min' => 1,
//                'max' => 25
//            )
//        ));
//        if ($validation->passed()) {
//            $aBD = AccesBD::getInstance()->getService();
//            $aBD->insert(\BD_main::TAULA_ROLS, $fields);// TODO: THIS MIGHT NOT WORK
//            echo "Role Updated";
//        }
//    } /*elseif (isset($_POST['deletePermission'])) {
//        //Delete permission levels
//        if (!empty($_POST['delete'])) {
//            $deletions = $_POST['delete'];
//            if ($deletion_count = deletePermission($deletions)) {
//                $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
//            }
//        }
//    }*/
//}

require_once PATH_INCLUDES . '/System/header.php';
?>
<div class="page-header">
    <div class="row">
        <h1 align="center">Gestión de los permisos de la aplicación: </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-offset-3 col-sm-6">
        <div id="form-errors">
            <?= $this->msgs->html() ?>
        </div>

        <!-- Main Center Column -->
        <form id="form-addPermission" name="addPermission" class="form-inline" action="/eaudit/system/roles/create"
              method='post'>
            <div class="form-group">
                <label for="namePermission">Nombre nuevo rol:</label>
                <input type="text" name='name' class="form-control" id="namePermission">
            </div>
            <button name='addPermission' type="submit" class="btn btn-success">Crea</button>
        </form>


        <table class='table table-striped table-hover table-bordered'>
            <thead>
            <tr>
                <th>Rol:</th>
            </tr>
            </thead>
            <tbody>
            <?php
            //List each permission level
            foreach ($this->roles as $role): ?>
                <tr>
                    <td>
                        <a href='/eaudit/system/roles/<?= $role->getID() ?>'><?= $role->getAttr('name') ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <br><br>
        <!-- End of main content section -->
    </div>
</div>

<!-- /.row -->

<!-- footers -->
<?php require_once PATH_INCLUDES . '/System/footer.php'; // the final html footer copyright row + the external js calls
?>

<!-- Place any per-page javascript here -->
<!--<script src="../users/js/search.js" charset="utf-8"></script>-->

<?php require_once PATH_INCLUDES . '/html_footer.php'; // currently just the closing /body and /html
?>

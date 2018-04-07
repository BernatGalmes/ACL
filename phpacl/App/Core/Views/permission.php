<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/02/18
 * Time: 12:48
 */

require_once PATH_INCLUDES . '/System/header.php';

$perm = new \System\Permission($this->id_perm);
?>
<div id="modal-edit-perm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div id="modal-edit-perm-content" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>
                    <span class="glyphicon glyphicon-lock"></span>
                    Editar permiso
                    <br>
                </h4>
            </div>
            <div class="modal-body">
                <?= $perm->getMessages()->html()?>
                <div class="form-horizontal">
                    <form action="/eaudit/system/permissions/edit/<?=$perm->getID()?>" class="form-create-perm" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <label for="tag">Tag: </label>
                            <input maxlength="20" name="tag" id="tag"  type="text" class="form-control"
                                   value="<?= $perm->getAttr('tag') ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción: </label>
                            <textarea class="form-control" rows="5" name="description"><?= $perm->getAttr('description') ?></textarea>
                        </div>
                        <input name="send" id="send" type="submit" class="btn btn-success"
                               value="Crea"/>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span
                        class="glyphicon glyphicon-remove"></span> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-header">
    <div class="row">
        <h1 align="center">Gestión del permiso: <b><?= $perm->getAttr('tag') ?></b></h1>
    </div>
</div>

<!-- Page Heading -->
<div class="row">
    <div class="col-xs-12">
        <div id="messages">
            <?= $this->msgs->html() ?>
        </div>
        <div class="row">
            <div class="col-md-3">
                <!-- Main Center Column -->
                <button class="btn btn-warning" data-toggle="modal" data-target="#modal-edit-perm">
                    <i class='fa fa-edit fa-3x' aria-hidden='true'></i>
                </button>

                <br>
                <a href="/eaudit/system/permissions/delete/<?= $perm->getID() ?>" id="delete-user"
                   class="btn btn-danger pull-left"><i class="fa fa-trash fa-3x"></i></a>
                <br><br>

                <a href="/eaudit/system/permissions" id="delete-user"
                   class="btn btn-success pull-left">
                    <i class="fa fa-angle-left fa-3x"></i></a>
            </div>
            <div class="col-md-9">
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
                        <form action="/eaudit/system/permissions/addrole/<?=$perm->getID()?>"
                              id="form-addRole" method="post" >
                            <div class="form-group">
                                <label for="sel1">Select list:</label>
                                <select name="roles_add[]" multiple class="form-control" id="sel1" style="overflow: scroll;height: 400px">
                                <?php
                                //Display users with this role
                                foreach ($perm->getNoneRoles() as $role):
                                    ?><option value="<?=$role->getID()?>">
                                    <i class="fa fa-user" aria-hidden="true"></i> <?= $role->getAttr('name') ?>
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
                        <form action="/eaudit/system/permissions/remrole/<?=$perm->getID()?>"
                              id="form-remRole" method="post">
                            <div class="form-group">
                                <label for="sel1">Select list:</label>
                                <select name="roles_rem[]" multiple class="form-control" id="sel1" style="overflow: scroll;height: 400px">
                                <?php
                                //Display users with this role
                                foreach ($perm->getRoles() as $role):
                                    ?><option value="<?=$role->getID()?>">
                                    <i class="fa fa-user" aria-hidden="true"></i> <?= $role->getAttr('name') ?>
                                    </option><?php
                                endforeach;
                                ?>
                                </select>
                            </div>
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

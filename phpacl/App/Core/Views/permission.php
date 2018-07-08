<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/02/18
 * Time: 12:48
 */

$perm = new \PHPACL\Permission($this->id_perm);
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Bernat Galmés Rubert">
    <link rel="shortcut icon" href="/acl/recursos/imatges/icon-pages.ico">
    <title>Bacter Control - eaudit</title>

    <link href="<?= LINK_CSS ?>acl-main.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/acl/recursos/fonts/custom/style.css">
    <style>
        body {
            background-color: white;
        }

        .link:hover {
            color: #696969;
        }

        .div-icon {
            margin: 10px 10px 10px 10px;
            text-align: center;
            min-height: 20px;
            padding: 19px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        }

        p.icon-text {
            padding: 25px 0px;
            text-align: center;
            height: 0px;
        }
    </style>

    <style>
        body{
            background-color: white;
        }
    </style>

</head>
<body>
<?php
include PATH_VIEWS . "/navigation.php";
?>
<div id="page-wrapper">
    <div class="container">

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
    </div>
</div>
<script src="<?= LINK_JS ?>acl-main.min.js"></script>
</body>
</html>
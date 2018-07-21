<?php
namespace System;

use PhpGene\Token;


$icon_user = "<i class='fa fa-user-plus' aria-hidden='true'></i>";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Bernat Galmés Rubert">
    <link rel="shortcut icon" href="/acl/recursos/imatges/icon-pages.ico">
    <title><?=\PHPACL\App::get()->getName()?></title>

    <link href="<?= LINK_CSS ?>acl-tables.min.css" rel="stylesheet">
</head>
<body>
<?php
include PATH_VIEWS . "/navigation.php";
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <?php
            include __DIR__ . "/support/sidebar.php";
            ?>
            <div class="container" id="main">
                <div class="row">
                    <div class="page-header">
                        <h1 align="center">Usuarios
                            <small>Gestiona los usuarios</small>
                        </h1>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-6">
                                    <div id="missatges">
                    <!--                    --><?//= Users::getMsgs()->html() ?>
                                    </div>
                                    <button type="button" class="btn btn-success btn-group-justified" id="btn_formNovaAccio"
                                            data-toggle="collapse" data-target="#form_accio"><?= $icon_user ?> Añadir usuario
                                    </button>
                                    <!-- Modal content-->
                                    <div class="well collapse" id="form_accio" role="dialog">
                                        <div class="row">
                                            <h3><?= $icon_user ?> Nuevo usuario</h3>
                                        </div>
                                        <div class="row">
                    <!--                        <form class="form-signup" action="admin_users.php" method="POST" id="form-newUser">-->
                    <!--                            <h3 class="form-signin-heading"> Añadir:-->
                    <!--                                <select id="select-role" name="permissions">-->
                    <!--                                    <option value='0'>Seleciona un rol ...</option>-->
                    <!--                                    --><?php
                    //                                    foreach ($this->permOps as $permOp): ?>
                    <!--                                        <option value='--><?//= $permOp['id'] ?><!--'>--><?//= $permOp['name'] ?><!--</option>--><?php
                    //                                    endforeach; ?>
                    <!--                                </select>-->
                    <!--                            </h3>-->
                    <!--                            <div class="form-group">-->
                    <!--                                <div class="row">-->
                    <!--                                    <div class="col-md-4">-->
                    <!--                                        <input class="form-control" type="text" name="username" id="username"-->
                    <!--                                               placeholder="Username" value="--><?//= User::authomaticUsername() ?><!--"-->
                    <!--                                               readonly="readonly">-->
                    <!--                                    </div>-->
                    <!--                                    <div class="col-md-4">-->
                    <!--                                        <input class="form-control" type="password" name="password" id="password"-->
                    <!--                                               placeholder="Password" required aria-describedby="passwordhelp"-->
                    <!--                                               value="--><?//= $newPasswd ?><!--" readonly="readonly">-->
                    <!--                                    </div>-->
                    <!--                                    <div class="col-md-4">-->
                    <!--                                        <input type="password" id="confirm" name="confirm" class="form-control"-->
                    <!--                                               placeholder="Confirm Password" value="--><?//= $newPasswd ?><!--" required-->
                    <!--                                               readonly="readonly">-->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                    <!--                                <hr>-->
                    <!--                                <div class="row">-->
                    <!--                                    <div class="col-md-4">-->
                    <!--                                        <input class="form-control" type="text" name="email" id="email"-->
                    <!--                                               placeholder="Email Address" value="--><?//= $userForm->getMail() ?><!--" required>-->
                    <!--                                    </div>-->
                    <!--                                    <div class="col-md-4">-->
                    <!--                                        <input type="text" class="form-control" id="fname" name="fname"-->
                    <!--                                               placeholder="First Name" value="--><?//= $userForm->getFname() ?><!--" required>-->
                    <!--                                    </div>-->
                    <!--                                    <div class="col-md-4">-->
                    <!--                                        <input type="text" class="form-control" id="lname" name="lname"-->
                    <!--                                               placeholder="Last Name" value="--><?//= $userForm->getLname() ?><!--" required>-->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                    <!--                            </div>-->
                    <!--                            <br/><br/>-->
                    <!--                            <input type="hidden" value="--><?//= Token::generate(); ?><!--" name="csrf">-->
                    <!--                            <input class='btn btn-primary' type='submit' name='addUser' value='Registrar'/>-->
                    <!--                        </form>-->
                                        </div>
                                    </div><!-- close well -->
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="table-users" class="table table-striped table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="th-text">Username</th>
                                            <th class="th-text">Email</th>
                                            <th class="th-text">First Name</th>
                                            <th class="th-text">Last Name</th>
                                            <th class="th-text">Permisos</th>
                                            <th class="th-text">Join Date</th>
                                            <th class="th-text">Último login</th>
                                            <th>Logins</th>
                                            <!--<th class='th-btn-static'>Delete</th>-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        //Cycle through users
                                        foreach ($this->users as $v1):
                                            ?>
                                            <tr class="row-user" data-idUser="<?= $v1['id'] ?>">
                                                <td class="td-text"><?= $v1['username'] ?></td>
                                                <td class="td-text"><?= $v1['email'] ?></td>
                                                <td class="td-text"><?= $v1['fname'] ?></td>
                                                <td class="td-text"><?= $v1['lname'] ?></td>
                                                <td class="td-text"><?= $v1['title'] ?></td>
                                                <td class="td-text" data-order="<?=strtotime($v1['join_date'])?>"><?= $v1['join_date'] ?></td>
                                                <td class="td-text" data-order="<?=strtotime($v1['last_login'])?>"><?= $v1['last_login'] ?></td>
                                                <td><?= $v1['logins'] ?></td>
                                            </tr>
                                            <?php
                                        endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= LINK_JS ?>acl-tables.min.js"></script>
<script>
$(document).ready(function () {
    $(".row-user").on('click', function (e) {
        var idUser = $(this).attr("data-idUser");
        window.location.href = "./" + idUser;
    });

    if ($("#missatges").html().trim() !== "") {
        $("#form_accio").addClass("in");
    }

    $("#form-newUser").on('submit', function () {
        var selectedRole = $("#select-role").val();
        console.log(selectedRole);
        if (selectedRole == 0) {
            console.log(selectedRole);
            alert("Debes seleccionar un rol");
            return false;
        }
    });
    $("#form_accio").on("hide.bs.collapse", function () {
        $("#btn_formNovaAccio").html("<i class='fa fa-user-plus' aria-hidden='true'></i> Añadir usuario");
    });
    $("#form_accio").on("show.bs.collapse", function () {
        $("#btn_formNovaAccio").html('<span class="glyphicon glyphicon-collapse-up"></span> Ocultar formulario');
    });

    var table = $('#table-users').DataTable({
        "autoWidth": false,
        "columns": [
            {"width": "10%"},//username
            {"width": "20%"},//mail
            {"width": "15%"},//fname
            {"width": "10%"},//lname
            {"width": "10%"},//rol
            {"width": "10%"},//jdate
            {"width": "10%"},//lastlogin
            {"width": "5%"} //logins
        ],
        "bLengthChange": false,
        "iDisplayLength": 20,
        "columnDefs": [{
            "orderable": false,
            "width": "5%"
        }]
    });
});
</script>
</body>
</html>


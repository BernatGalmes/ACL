<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 2/04/18
 * Time: 13:39
 */

$userRole = $this->user->getRole();

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Bernat Galmés Rubert">
    <link rel="shortcut icon" href="/acl/recursos/imatges/icon-pages.ico">
    <title><?=\PHPACL\App::get()->getName()?></title>

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

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <a href="./<?=$this->user->getID()?>/remove" id="delete-user" class="btn btn-danger pull-right">
            <i class="fa fa-trash fa-3x"></i>
        </a>
        <button id="btn-reset-passwd" class="btn btn-warning pull-right">
            <span class="fa-passwd-reset fa-stack">
              <i class="fa fa-undo fa-stack-2x"></i>
              <i class="fa fa-lock fa-stack-1x"></i>
            </span>
        </button>
        <h3>Información del usuario: </h3>
        <div class="panel panel-primary">
            <div class="panel-heading">ID usuario: <?= $this->user->getID() ?></div>
            <div class="panel-body">
                <label>Joined: </label> <?= $this->user->getJoinDate() ?><br/>
                <label>Last login: </label> <?= $this->user->getLastLogin() ?><br/>
                <label>Logins: </label> <?= $this->user->getLogins() ?><br/>
            </div>
        </div>

        <?php
        MostraMissatgesSessio();
        echo $this->msgs->html();
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">ID usuario: <?= $this->user->getID() ?></div>
            <div class="panel-body">
                <?php
                $this->user->getMessages()->showMessages();
                ?>

                <form class="form" name='user_data' action='./<?=$this->user->getID()?>/update' method='post'>
                    <div class="form-group">
                        <label>Username:</label>
                        <input class='form-control' type='text' name='username' value='<?= $this->user->getName() ?>'
                               required/>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input class='form-control' type='text' name='email' value='<?= $this->user->getMail() ?>'
                               required/>
                    </div>
                    <div class="form-group">
                        <label>Primer nombre:</label>
                        <input class='form-control' type='text' name='fname' value='<?= $this->user->getFname() ?>'
                               required/>
                    </div>
                    <div class="form-group">
                        <label>Segundo nombre:</label>
                        <input class='form-control' type='text' name='lname' value='<?= $this->user->getLname() ?>'
                               minlength="2" required/>
                    </div>
                    <div class="form-group">
                        <label class="form-signin-heading">Rol: </label>
                        <select name="permission_id">
                            <?php
                            foreach ($this->roles as $permOp):
                                if ($userRole->getID() == $permOp['id']) {
                                    ?>
                                    <option value='<?= $permOp['id'] ?>'
                                            selected='selected'><?= $permOp['name'] ?></option><?php
                                } else {
                                    ?>
                                    <option value='<?= $permOp['id'] ?>'><?= $permOp['name'] ?></option><?php
                                }
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class='btn btn-primary' name="updateUser" type='submit' value='Actualizar'/>
                        <a class='btn btn-warning' href="admin_users.php?id=<?= $this->user->getID() ?>">Cancelar</a><br>
                    </div>
                </form>

            </div>
        </div>
        <?php
        if ($this->user->hasLogo()):
        ?>
            <h3>Logo:</h3>
            <div class="panel panel-primary">
                <div class="panel-heading">Cambia el logo del usuario:</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div align="center" class="logoBacter">
                                <img src="<?=$this->user->getLinkLogo()?>" alt="BacterControl">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form action="/eaudit/system/users/<?=$this->user->getID()?>/upload_logo" method="post" enctype="multipart/form-data">
                                Select image to upload:
                                <input type="file" name="logo" id="logoToUpload">
                                <input type="submit" value="Upload Image" name="submit">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        endif;
        ?>

        <h3>Centros:</h3>
        <div class="panel panel-primary">
            <div class="panel-heading">Elimina asociacion con centro:</div>
            <div class="panel-body">
                <form class="form" name='user_centros' action='./<?=$this->user->getID()?>/centro' method='post'>
                    <h3>Desasociar centro:</h3>
                    <div class='row'>
                        <?php
//                        $centros = $this->user->getCentros();
//                        if ($centros):
//                            $i = 1;
//                            foreach ($centros as $centro):
//                                if ($i % 2 == 0) { ?>
<!--                                    <div class='row'>--><?php
//                                }
//                                ?>
<!--                                <div class="form-group col-sm-6">-->
<!--                                    <button type="submit" name="desasocCentro" id="desasocCentro" class="btn btn-danger"-->
<!--                                            value="--><?//= $centro->getID() ?><!--">--><?//= \Icon::DELETE_WHITE ?><!--</button>-->
<!--                                    <label>--><?//= $centro->getName() ?><!--</label>-->
<!--                                </div>-->
<!---->
<!--                                --><?php
//                                if ($i % 2 == 0) { ?>
<!--                                    </div>--><?php
//                                }
//                                $i++;
//                            endforeach;
//                        endif;
                        ?>
                    </div>
                    <h3>Asociar centro:</h3>
                    <div class="form-group">
                        <label>Centro:</label>
                        <select name="id_centre_asoc">
                            <option value="-1" selected='selected'>Selecciona un centro....</option>
                            <?php
                            foreach ($this->centros as $data_centro) {
                                ?>
                                <option value="<?= $data_centro['id'] ?>"><?= $data_centro['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <button type="submit" name="asocCentro" id="asocCentro" class="btn btn-success" value="">
                            Associar:
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class='row'>
            <div class="col-md-12">
                <a href="admin_users.php" class='btn btn-warning'>Volver</a>
            </div>
        </div>
    </div><!--/col-9-->
</div><!--/row-->
</div>
</div>
<script src="<?= LINK_JS ?>acl-main.min.js"></script>
<script>
    var idUser = <?= $this->user->getID() ?>;//TODO: get from html
    // $("#delete-user").on("click", function () {
    //     if (confirm('¿Esta seguro que desea eliminar el usuario?.')) {
    //         $.get('/eaudit/system/services/user_delete.php', {user_id: idUser}, function (results) {
    //             console.log(results);
    //             var res = JSON.parse(results);
    //
    //             if (res.status === 'success') {
    //                 alert("usuario eliminado correctamente");
    //                 window.location.href = "./admin_users.php";
    //             } else {
    //                 alert("no se ha podido eliminar el usuario");
    //             }
    //         });
    //     }
    // });

    $("#btn-reset-passwd").on("click", function () {
        if (confirm('¿Esta seguro que desea generar una nueva contraseña para el usuario?.')) {
            $.get('services/user_changeRandomPass.php', {user_id: idUser}, function (results) {
                console.log(results);
                var res = JSON.parse(results);
                //TODO: implement reset password function
                alert("not implemented");
                if (res.status === 'success') {
                    alert("La contraseña del usuario ha sido cambiada correctamente");
                } else {
                    alert("no se ha podido cambiar la contraseña del usuario");
                }
            });
        }
    });
</script>
</div>
</div>

</body>
</html>

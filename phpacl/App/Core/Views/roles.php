<?php

namespace General;

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
    <title>Bacter Control - eaudit</title>

    <link href="<?= LINK_CSS ?>acl-main.min.css" rel="stylesheet">
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
                    <!-- Page Heading -->
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
                                            <a href='<?=LINK_APP?>system/roles/<?= $role->getID() ?>'><?= $role->getAttr('name') ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>

                            <br><br>
                            <!-- End of main content section -->
                        </div>
                    </div>
                </div> <!-- /.wrapper -->
            </div>
        </div>
    </div>
</div>
<script src="<?= LINK_JS ?>acl-main.min.js"></script>
</body>
</html>
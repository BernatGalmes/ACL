<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/02/18
 * Time: 12:48
 */

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

<div id="modal-create-perm" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div id="modal-create-perm-content" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>
                    <span class="glyphicon glyphicon-lock"></span>
                    Nuevo permiso
                    <br>
                </h4>
            </div>
            <div class="modal-body">
                <?= $this->perm->getMessages()->html()?>
                <div class="form-horizontal">
                    <form action="/eaudit/system/permissions/create" class="form-create-perm" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <label for="tag">Tag: </label>
                            <input maxlength="20" name="tag" id="tag"  type="text" class="form-control"
                                   value="<?= $this->perm->getAttr('tag') ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción: </label>
                            <textarea class="form-control" rows="5" name="description"><?= $this->perm->getAttr('description') ?></textarea>
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

<!-- Page Heading -->
<div class="page-header">
    <h1 align="center">
        <span class="glyphicon glyphicon-lock"></span>Permisos
    </h1>
    <div id="missatges">
        <?= $this->msgs->html() ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <button type="button" class="btn btn-success btn-group-justified"
                        data-toggle="modal" data-target="#modal-create-perm">
                    <span class="glyphicon glyphicon-lock"></span>Añadir permiso
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <table id="table-permissions" class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th class="th-text">TAG</th>
                        <th class="th-text">Description</th>
                        <th class="th-text">creador</th>
                        <th class="th-text">ctime</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //Cycle through users
                    foreach ($this->permissions as $p):
                        ?>
                        <tr class="row-permission" data-idPerm="<?= $p->getID(); ?>">
                            <td class="td-text"><?= $p->getAttr("tag") ?></td>
                            <td class="td-text"><?= $p->getAttr("description") ?></td>
                            <td class="td-text"><?= $p->getAttr("creador") ?></td>
                            <td class="td-text" data-order="<?=strtotime($p->getAttr("ctime"))?>"><?= $p->getAttr("ctime") ?></td>
                        </tr>
                    <?php
                    endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- footers -->
</div> <!-- /.col -->
</div> <!-- /.row -->
</div> <!-- /.container -->
</div> <!-- /.wrapper -->
<!-- End of main content section -->
<script src="<?= LINK_JS ?>acl-main.min.js"></script>
<script>
    $(document).ready(function () {
        $(".row-permission").on('click', function (e) {
            var idUser = $(this).attr("data-idPerm");
            window.location.href = "./permissions/" + idUser;
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

        var table = $('#table-permissions').DataTable({
            "autoWidth": false,
            "columns": [
                {"width": "10%"},//tag
                {"width": "50%"},//description
                {"width": "10%"},//creador
                {"width": "10%"},//cdate
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
</div>
</div>

</body>
</html>


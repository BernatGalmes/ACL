<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/02/18
 * Time: 12:48
 */

require_once PATH_INCLUDES . '/System/header.php';
?>
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
<?php
require_once PATH_INCLUDES . '/System/footer.php'; // the final html footer copyright row + the external js calls
?>
<!-- Place any per-page javascript here -->
<script type="text/javascript" src="<?= LINK_JS ?>eaudit.js"></script>
<script type="text/javascript" src="<?= LINK_JS ?>datatables.min.js"></script>
<script type="text/javascript" src="<?= LINK_JS ?>users-tables.js"></script>
<script>
    $(document).ready(function () {
        $(".row-permission").on('click', function (e) {
            var idUser = $(this).attr("data-idPerm");
            window.location.href = "/eaudit/system/permissions/" + idUser;
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
<?php require_once PATH_INCLUDES . '/html_footer.php'; // currently just the closing /body and /html
?>

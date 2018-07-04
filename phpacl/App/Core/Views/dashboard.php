<?php
namespace General;

$data = $this->data;
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
        <h1 class="text-center">DashBoard BacterControl v.0</h1>

        <div class="row">
            <div class="col-md-12">
                <h2>Administraci&oacute;n sistema</h2>
                <!-- Users Panel -->
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Usuarios</strong></div>
                        <div class="panel-body text-center">
                            <div class="huge"><i class='fa fa-user fa-1x'></i> <?= $data['users'] ?></div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left"><a href="admin_users.php">Gestionar</a></span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div> <!-- /panel-footer -->
                    </div><!-- /panel -->
                </div><!-- /col -->

                <!-- Permissions Panel -->
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Permisos</strong></div>
                        <div class="panel-body text-center">
                            <div class="huge"><i class='fa fa-lock fa-1x'></i> <?= $data['levels'] ?></div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left"><a href="../../../eaudit/core/System/Views/roles.php">Gestionar</a></span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div> <!-- /panel-footer -->
                    </div><!-- /panel -->
                </div> <!-- /.col -->

                <!-- Pages Panel -->
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Paginas</strong></div>
                        <div class="panel-body  text-center">
                            <div class="huge"><i class='fa fa-file-text fa-1x'></i> <?= $data['pages'] ?></div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left"><a href="admin_pages.php">Gestionar</a></span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div> <!-- /panel-footer -->
                    </div><!-- /panel -->
                </div><!-- /col -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Email Settings Panel -->
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Configurar correo</strong></div>
                        <div class="panel-body text-center">
                            <div class="huge"><i class='fa fa-paper-plane fa-1x'></i> 9</div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left"><a href='email_settings.php'>Gestionar</a></span>
                            <span class="pull-right"><i class='fa fa-arrow-circle-right'></i></span>
                            <div class="clearfix"></div>
                        </div> <!-- /panel-footer -->
                    </div> <!-- /panel -->
                </div> <!-- /col -->
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Centros</strong></div>
                        <div class="panel-body text-center">
                            <div class="huge"><i class='fa fa-user fa-1x'></i> <?= $data['clients'] ?></div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left"><a href="<?= LINK_SYSTEM_CENTROS ?>">Gestionar</a></span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div> <!-- /panel-footer -->
                    </div><!-- /panel -->
                </div> <!-- /col -->
                <div class="col-xs-6 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Companyies</strong></div>
                        <div class="panel-body text-center">
                            <div class="huge"><i class='fa fa-user fa-1x'></i> <?= $data['companys'] ?></div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-left"><a href="<?= LINK_SYSTEM_ADMIN_COMPANIES ?>">Gestionar</a></span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div> <!-- /panel-footer -->
                    </div><!-- /panel -->
                </div><!-- /col -->
            </div>
        </div><!-- /.row -->
        <div class="row "> <!-- rows for Info Panels -->
            <div class="col-md-12">
                <h2>Info Panels</h2>
                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Todos los usuarios</strong> <span
                                    class="small">(Que han logueado)</span>
                        </div>
                        <div class="panel-body text-center">
                            <div class="row">
                                <div class="col-xs-3 "><h3><?= $data['usersHour'] ?></h3>
                                    <p>por hora</p></div>
                                <div class="col-xs-3"><h3><?= $data['usersToday'] ?></h3>
                                    <p>por d&iacute;a</p></div>
                                <div class="col-xs-3 "><h3><?= $data['usersWeek'] ?></h3>
                                    <p>por semana</p></div>
                                <div class="col-xs-3 "><h3><?= $data['usersMonth'] ?></h3>
                                    <p>por mes</p></div>
                            </div>
                        </div>
                    </div><!--/panel-->


                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Todos los visitantes</strong> <span class="small">(Que han logueado y que no)</span>
                        </div>
                        <div class="panel-body">
<!--                            --><?php //if ($app->getStatus()->track_guest == 1): ?>
<!--                                --><?//= "En los ultimos 30 minutos el numero de visitantes ha sido de: " . System::count_users() . "<br>"; ?>
<!--                            --><?php //else: ?>
<!--                                Seguimiento de usuarios parado! Cambia el estado de "Track Guests" en el apartado de opciones avanzadas.-->
<!--                            --><?php //endif; ?>
                        </div>
                    </div><!--/panel-->

                </div> <!-- /col -->

                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading"><strong>Usuarios logueados</strong> <span
                                    class="small">(ultimas 24h)</span></div>
                        <div class="panel-body">
                            <div class="uvistable table-responsive">
                                <table class="table">
<!--                                    --><?php //if ($app->getStatus()->track_guest == 1): ?>
<!--                                        <thead>-->
<!--                                        <tr>-->
<!--                                            <th>Nombre de usuario</th>-->
<!--                                            <th>IP</th>-->
<!--                                            <th>Ultima actividad</th>-->
<!--                                        </tr>-->
<!--                                        </thead>-->
<!--                                        <tbody>-->
<!---->
<!--                                        --><?php //foreach ($data['recentUsers'] as $v1):
//                                            //                                        continue;//TODO: remove
//                                            $user_id = $v1['user_id'];
//                                            $r_user = new User($user_id, true);
//                                            $username = $r_user->getAttr('username');//TODO: do with Usuario instance
//                                            $timestamp = date("Y-m-d H:i:s", $v1['timestamp']);
//                                            $ip = $v1['ip'];
//
//                                            if ($user_id == 0) {
//                                                $username = "guest";
//                                            }
//
//                                            if ($user_id == 0):?>
<!--                                                <tr>-->
<!--                                                    <td>--><?//= $username ?><!--</td>-->
<!--                                                    <td>--><?//= $ip ?><!--</td>-->
<!--                                                    <td>--><?//= $timestamp ?><!--</td>-->
<!--                                                </tr>-->
<!--                                            --><?php //else: ?>
<!--                                                <tr>-->
<!--                                                    <td>-->
<!--                                                        <a href="admin_user.php?id=--><?//= $user_id ?><!--">--><?//= $username ?><!--</a>-->
<!--                                                    </td>-->
<!--                                                    <td>--><?//= $ip ?><!--</td>-->
<!--                                                    <td>--><?//= $timestamp ?><!--</td>-->
<!--                                                </tr>-->
<!--                                            --><?php //endif; ?>
<!---->
<!--                                        --><?php //endforeach; ?>
<!---->
<!--                                        </tbody>-->
<!--                                    --><?php //else: ?>
<!--                                        Seguimiento de usuarios parado! Canvia el estado de "Track Guests" en el apartado de opciones avanzadas.-->
<!--                                    --><?php //endif; ?>
                                </table>
                            </div>
                        </div>
                    </div><!--/panel-->
                </div> <!-- /col2/2 -->
            </div>
        </div> <!-- /row -->


        <div class="row"> <!-- rows for Main Settings -->
            <div class="col-md-12">
                <div class="col-xs-12 col-md-6"> <!-- Site Settings Column -->
                    <form class="" action="index.php" name="settings" method="post">
                        <h2>Opciones avanzadas</h2>


                        <!-- Force Password Reset -->
                        <div class="form-group">
                            <label for="force_pr">Force Password Reset (disabled)</label>
<!--                            <select id="force_pr" class="form-control" name="force_pr" disabled>-->
<!--                                <option value="1" --><?php //if ($app->getStatus()->force_pr == 1) echo 'selected="selected"'; ?><!-- >-->
<!--                                    Yes-->
<!--                                </option>-->
<!--                                <option value="0" --><?php //if ($app->getStatus()->force_pr == 0) echo 'selected="selected"'; ?><!-- >No-->
<!--                                </option>-->
<!--                            </select>-->
                        </div>

                        <!-- Site Offline -->
                        <div class="form-group">
                            <label for="site_offline">Site Offline</label>
<!--                            <select id="site_offline" class="form-control" name="site_offline">-->
<!--                                <option value="1" --><?php //if ($app->getStatus()->site_offline == 1) echo 'selected="selected"'; ?><!-- >-->
<!--                                    Yes-->
<!--                                </option>-->
<!--                                <option value="0" --><?php //if ($app->getStatus()->site_offline == 0) echo 'selected="selected"'; ?><!-- >-->
<!--                                    No-->
<!--                                </option>-->
<!--                            </select>-->
                        </div>

                        <!-- Track Guests -->
                        <div class="form-group">
                            <label for="track_guest">Track Guests</label>
<!--                            <select id="track_guest" class="form-control" name="track_guest">-->
<!--                                <option value="1" --><?php //if ($app->getStatus()->track_guest == 1) echo 'selected="selected"'; ?><!-- >-->
<!--                                    Yes-->
<!--                                </option>-->
<!--                                <option value="0" --><?php //if ($app->getStatus()->track_guest == 0) echo 'selected="selected"'; ?><!-- >-->
<!--                                    No-->
<!--                                </option>-->
<!--                            </select>-->
                            <small>If your site gets a lot of traffic and starts to stumble, this is the first thing to turn
                                off.
                            </small>
                        </div>

                        <?php
                        /*<input type="hidden" name="csrf" value="<?=\Token::generate();?>" />*/
                        ?>
                        <p><input class='btn btn-primary' type='submit' name="settings" value='Save Site Settings'/></p>
                    </form>
                </div> <!-- /col1/2 -->

                <div class="col-xs-12 col-md-6"><!-- CSS Settings Column -->
                    <h2>BACKUP
                        <small>Sistema de copia de seguridad</small>
                    </h2>
                    <form id="form1" action="" method="post">
                        <input id="backupbd" name="backupbd" type="submit" value="Respaldar base de datos">
                    </form>
                </div> <!-- /col1/3 -->
            </div>
        </div> <!-- /row -->

        <script src="<?= LINK_JS ?>acl-main.min.js"></script>
        <!-- Place any per-page javascript here -->
        <script type="text/javascript">
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
            })
            ;
        </script>

        </div>
    </div>
</div>

</body>
</html>


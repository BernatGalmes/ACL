<?php
$user = new \PHPACL\User_logged();

$get_info_id = $user->getID();
$raw = date_parse($user->getAttr('join_date'));
$signupdate = $raw['month'] . "/" . $raw['day'] . "/" . $raw['year'];

require_once PATH_INCLUDES . '/header.php';
?>
<div id="page-wrapper">
    <div class="container">
        <div class="well">
            <div class="row">
                <div class="col-xs-12 col-md-3">
<!--                    <p><a href="../../../eaudit/core/System/Views/user_settings.php" class="btn btn-primary">Modificar información personal</a></p>-->
                </div>
                <div class="col-xs-12 col-md-9">
                    <h1><?= ucfirst($user->getAttr('username')) ?></h1>
                    <p><?= ucfirst($user->getAttr('fname')) . " " . ucfirst($user->getAttr('lname')) ?></p>
                    <p>Miembro desde:<?= $signupdate ?></p>
                    <p>Número de Logins: <?= $user->getAttr('logins') ?></p>
                </div>
            </div>
        </div>

    </div> <!-- /container -->

</div> <!-- /#page-wrapper -->

<!-- footers -->
<?php require_once PATH_INCLUDES . '/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once PATH_INCLUDES . '/html_footer.php'; // currently just the closing /body and /html ?>

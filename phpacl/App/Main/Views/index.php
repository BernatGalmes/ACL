<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 12/05/18
 * Time: 21:42
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

    <!-- Bootstrap Core CSS -->
    <!-- AKA Primary CSS -->
    <link href="<?= LINK_CSS ?>Bootstrap-3.3.6/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template CSS -->
    <!-- AKA Secondary CSS -->
    <link href="<?= LINK_CSS ?>bootstrap-notifications.min.css" rel="stylesheet">
    <link href="<?= LINK_CSS ?>eaudit.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

    <!-- Custom Fonts -->
    <link href="<?= LINK_FONTS ?>css/font-awesome.min.css" rel="stylesheet" type="text/css">
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

</head>
<body>
<?php
include PATH_VIEWS . "/navigation.php";
?>
<div id="page-wrapper">
    <div class="container">
        <div class="jumbotron">
            <h1 align="center">Titulo por defecto</h1>
        </div>
        <div class="row">
            <div class="col-md-2"></div>

            <?php if ((new \PHPACL\User_logged())->isAdmin()) { ?>
                <div class="link div-icon col-md-2" data-urlLink='./system/admin_users.php'>
                        <span class="icon-icon-system-users ea-icon-main">
                        <span class="path1"></span><span class="path2"></span><span
                                    class="path3"></span><span class="path4"></span><span
                                    class="path5"></span><span class="path6"></span><span
                                    class="path7"></span><span class="path8"></span><span
                                    class="path9"></span><span class="path10"></span><span
                                    class="path11"></span><span class="path12"></span><span
                                    class="path13"></span><span class="path14"></span><span
                                    class="path15"></span>
                        </span>
                    <p class="icon-text">Usuarios</p>
                </div>
            <?php } ?>
            <div class="link div-icon col-md-2" data-urlLink='.'>
                <span class="icon-icon-tasks ea-icon-main"></span>
                <p class="icon-text">Tareas</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>



        </div>
    </div>
</div>

<!-- footers -->
</div> <!-- /.col -->
</div> <!-- /.row -->
</div> <!-- /.container -->
</div> <!-- /.wrapper -->
<div class="container">
    <div class="row">
        <div class="col-sm-12 text-center">
            <footer><font color='white'><br>&copy; <?= GEN_COPYRIGHT ?></font></footer>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?= LINK_CSS ?>Bootstrap-3.3.6/js/bootstrap.min.js"></script>
<script src="<?= LINK_JS ?>eaudit.js"></script>
<script src="/eaudit/recursos/fonts/custom/eaudit-icons.js"></script>
</body>
</html>

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
            <div class="well col-xs-offset-4 col-xs-4">
                <div align="center" class="logoBacter">
                    <img src="/acl/recursos/imatges/logo.png" alt="PHPacl">
                </div>
                <div class="missatges"><?php
//                    echo $msgs->html();
                    ?>
                </div>
                <form name="login" class="form-signin" action="login.php" method="post">
                    <h2 class="form-signin-heading">Login: </h2>
                    <div class="form-group">
                        <label for="username"><span class="glyphicon glyphicon-user"></span> Nombre de
                            usuario o
                            e-mail</label>
                        <input class="form-control" type="text" name="username" id="username"
                               placeholder="Username/Email" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password"><span class="glyphicon glyphicon-eye-open"></span>
                            Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                               placeholder="Password" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="remember">
                            <input type="checkbox" name="remember" id="remember"> Recordar-me</label>
                    </div>

                    <input type="hidden" name="csrf" value="<?php //TODO: put token generator ?>">
                    <button class="submit  btn  btn-primary" type="submit"><i class="fa fa-sign-in"></i>
                        Login
                    </button>
                    <a class="pull-right" href='/acl/forgot_password'><i class="fa fa-wrench"></i>
                        Recuperar
                        contraseña</a><br><br>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- footers -->
<!-- footers -->


<!-- jQuery -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?= LINK_CSS ?>Bootstrap-3.3.6/js/bootstrap.min.js"></script>
<script src="<?= LINK_JS ?>eaudit.js"></script>

<!-- Place any per-page javascript here -->
</body>
</html>

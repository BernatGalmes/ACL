<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 5/05/18
 * Time: 22:16
 */
$user = new \PHPACL\User_logged();
?>
<nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header ">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" id="logoEmpresa" href=".">PHPACL</a>
        </div>
        <div class="collapse navbar-collapse navbar-top-menu-collapse navbar-right">
            <ul class="nav navbar-nav ">
                <?php
                if ($user->isLoggedIn()) { //anyone is logged in
                    ?>
                    <li><a href="/eaudit/system/account"><i
                                    class="fa fa-fw fa-user"></i> <?= $user->getAttr('username') ?></a>
                    </li> <!-- Common for Hamburger and Regular menus link -->
                    <li class="hidden-sm hidden-md hidden-lg"><a href="<?= LINK_APP ?>"><i
                                    class="fa fa-fw fa-home"></i> Home</a></li> <!-- Hamburger menu link -->
                    <li class="dropdown hidden-xs"><a class="dropdown-toggle" href="#" data-toggle="dropdown"><i
                                    class="fa fa-fw fa-cog"></i><b class="caret"></b></a> <!-- regular user menu -->
                        <ul class="dropdown-menu"> <!-- open tag for User dropdown menu -->
                            <li><a href="<?= LINK_APP ?>"><i class="fa fa-fw fa-home"></i> Home</a></li>
                            <!-- regular user menu link -->
                            <li><a href="<?= LINK_SYSTEM_ACCOUNT ?>"><i class="fa fa-fw fa-user"></i>
                                    Account</a></li> <!-- regular user menu link -->

                            <?php
                            if ($user->isAdmin()) {  //Links for permission level 2 (default admin)
                                ?>
                                <li class="divider"></li>

                                <li><a href="/acl/system/"><i class="fa fa-fw fa-cogs"></i> Admin Dashboard</a>
                                </li> <!-- regular Admin menu link -->
                                <?php
                            }
                            ?>
                            <li class="divider"></li>
                            <li><a href="/acl/logout"><i class="fa fa-fw fa-sign-out"></i> Desconectar</a></li>
                            <!-- regular Logout menu link -->
                        </ul> <!-- close tag for User dropdown menu -->
                    </li>

                    <li class="hidden-sm hidden-md hidden-lg"><a href="/acl/logout"><i
                                    class="fa fa-fw fa-sign-out"></i>
                            Desconectar</a></li> <!-- regular Hamburger logout menu link -->

                    <?php
                } else { // no one is logged in so display default items
                    ?>
                    <li>
                        <a href="/acl/login" class=""><i class="fa fa-sign-in"></i> Conecta-te</a>
                    </li>
                    <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"><i
                                    class="fa fa-life-ring"></i>Ayuda<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?= LINK_BASE_FORGOT_PASS ?>"><i class="fa fa-wrench"></i>
                                    Olvidaste la contraseña?</a></li>
<!--                            --><?php //if ($app->isEnabledMailAct()): ?>
<!--                                <li>-->
<!--                                    <a href="--><?//= LINK_BASE_VERIFY_RESEND ?><!--"><i class="fa fa-exclamation-triangle"></i>-->
<!--                                        Reenviar correo de activación</a>-->
<!--                                </li>-->
<!--                            --><?php //endif; ?>
                        </ul>
                    </li>
                    <?php
                } //end of conditional for menu display
                ?>
            </ul> <!-- End of UL for navigation link list -->
        </div> <!-- End of Div for right side navigation list -->
        <?php
        if ($user->isLoggedIn()) { //anyone is logged in
            ?>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <!-- Menu gestio App -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i>Principal<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            if ($user->isAdmin()) {
                                ?>
                                <li class="divider"></li>
                                <li>

                                </li>
                                <li class="divider"></li>
                                <li><a href="<?=LINK_APP?>system/users/"
                                       target="_self">Usuarios</a></li>
                                <?php
                            } ?>
                        </ul>
                    </li>
                    <!-- Menu ducuments -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-file-pdf-o"></i>1st Module<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">

                        </ul>
                    </li>
                    <!-- Menu gestio ducumental -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flask"></i>2th Module<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="" target="_self">Page 1</a></li>
                            <li class="divider"></li>
                            <li><a href="" target="_self">Page 2</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php
        } ?>
    </div> <!-- End of Div for navigation bar -->
</nav> <!-- End of Div for navigation bar styling -->

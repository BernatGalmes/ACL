<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 8/07/18
 * Time: 22:28
 */


$s_user = new \PHPACL\User_logged();
?>
<div id="sidebar-system" class="nav-side-menu">
    <div class="brand">Brand Logo</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
    <div class="menu-list">
        <ul id="menu-content" class="menu-content collapse out">
            <?php
            if ($s_user->hasPermission('sys_dashboard')):
                ?>
                <li id="menu-dashBoard">
                    <a href="<?=LINK_APP?>system/index.php">
                        <i class="fa fa-globe fa-lg"></i> DashBoard
                    </a>
                </li>
            <?php endif;
            if ($s_user->hasPermission('sys_users_list')):
                ?>
                <li id="menu-users">
                    <a href="<?=LINK_APP?>system/admin_users.php">
                        <i class="fa fa-users fa-lg"></i> Gestionar usuarios
                    </a>
                </li>
            <?php endif; ?>
            <li data-toggle="collapse" data-target="#menu-config" class="collapsed">
                <a href="#"><i class="fa fa-dashboard fa-lg"></i> Configuración <span
                        class="arrow"></span></a>
            </li>
            <ul class="sub-menu collapse" id="menu-config">
            <?php
            $link_page = LINK_APP . "system/email_settings.php";
            if ($s_user->hasPermission('sys_mail')):
                ?>
                <li id="menu-mailSettings" class="link" data-urlLink="<?= $link_page ?>">Correo</li>
            <?php endif;
            $link_page = LINK_APP . "system/roles";
            if ($s_user->hasPermission("sys_man_perm")):
                ?>
                <li id="menu-roles" class="link" data-urlLink="<?= $link_page ?>">Roles</li>
                <li id="menu-permissions" class="link" data-urlLink="<?=LINK_APP?>system/permissions">Permisos</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 23/12/17
 * Time: 18:26
 */

namespace App;
define('LINK_APP', '/acl/');
define('LINK_JS', LINK_APP . 'recursos/js/');
define('LINK_CSS', LINK_APP . 'recursos/css/');
define('LINK_FONTS', LINK_APP . 'recursos/fonts/');
define('LINK_SCRIPTS', LINK_APP . 'recursos/services');
define('LINK_SERVICES', LINK_APP . 'recursos/ws/');
define('LINK_IMAGES', LINK_APP . 'recursos/imatges/');
define('LINK_UPLOADS', LINK_APP . 'uploads/');
define('LINK_BACKUPS', LINK_APP . 'backup/');
define('LINK_PAGE_ERROR', ''); //TODO: create page and put link
define('LINK_JS_JQUERY', LINK_JS . 'jquery.min.js');

define('LINK_PAGE_FORGOT_PASS', LINK_APP . 'forgot_password.php');
define('LINK_PAGE_FORGOT_PASS_RESET', LINK_APP . 'forgot_password_reset.php');

/* Base links */
define('LINK_BASE_EMAIL_TEST', LINK_APP . 'email_test.php');
define('LINK_BASE_FORBIDDEN', LINK_APP . 'forbidden.php');
define('LINK_BASE_FORGOT_PASS', LINK_APP . 'forgot_password.php');
define('LINK_BASE_FORGOT_PASS_RESET', LINK_APP . 'forgot_password_reset.php');
define('LINK_BASE_LOGIN', LINK_APP . 'login.php');
define('LINK_BASE_LOGOUT', LINK_APP . 'logout.php');
define('LINK_BASE_OFFLINE', LINK_APP . 'offline.html');
define('LINK_BASE_VERIFY', LINK_APP . 'verify.php');
define('LINK_BASE_VERIFY_RESEND', LINK_APP . 'verify_resend.php');

/* System Module */
define('LINK_SYSTEM', LINK_APP . 'system/');
define('LINK_SYSTEM_ACCOUNT', LINK_SYSTEM . 'account');
define('LINK_SYSTEM_ADMIN_PERMISSION', LINK_SYSTEM . 'role.php');
define('LINK_SYSTEM_ADMIN_USER', LINK_SYSTEM . 'admin_user.php');
define('LINK_SYSTEM_ADMIN_USERS', LINK_SYSTEM . 'admin_users.php');
define('LINK_SYSTEM_EMAIL_SETTINGS', LINK_SYSTEM . 'email_settings.php');
define('LINK_SYSTEM_PERMISSIONS', LINK_SYSTEM . 'roles.php');
define('LINK_SYSTEM_USER', LINK_SYSTEM . 'user_settings.php');

define('LINK_SYSTEM_CENTROS', LINK_APP . 'centros/');
define('LINK_SYSTEM_CENTROS_ADMIN', LINK_APP . 'centros/gestionar.php');

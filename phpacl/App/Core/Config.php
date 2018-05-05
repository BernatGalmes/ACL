<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 1/04/18
 * Time: 19:53
 */

namespace PHPACL;


class Config extends \Data\Config
{
    CONST PATH_MODULE = PATH_CORE . "/System/";
    const PATH_VIEWS = self::PATH_MODULE . "Views/";
    const PATH_MODELS = self::PATH_MODULE . "Models/";

    const VIEW_FILE_PERMISSION = self::PATH_VIEWS . "permission.php";
    const VIEW_FILE_PERMISSIONS = self::PATH_VIEWS . "permissions.php";

    const VIEW_FILE_ROLE = self::PATH_VIEWS . "role.php";
    const VIEW_FILE_ROLES = self::PATH_VIEWS . "roles.php";

    const VIEW_FILE_COMPANY = self::PATH_VIEWS . "company.php";
    const VIEW_FILE_COMPANIES = self::PATH_VIEWS . "list_companies.php";

    const VIEW_FILE_EDIT_USER = self::PATH_VIEWS . "user_edit.php";

    const VIEW_FILE_ACCOUNT = self::PATH_VIEWS . "account.php";

    const LINK_ROLES = LINK_APP . "system/roles/";
}
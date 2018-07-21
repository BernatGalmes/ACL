<?php

namespace BD;
use PHPACL\Permission;
use PHPACL\Role;

require_once __DIR__ . '/bd.php';

/**
 * Class AccesBD amb les funcions generiques de la base de dades
 * @package App
 */
class AccesBD extends BD
{

    function __construct()
    {//variables configuracio(config.php)
        parent::__construct();
    }

    /**
     * @return AccesBD dbObject
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new AccesBD();
        }
        return self::$_instance;
    }


    public function getSystemData()
    {
        $date = date("Y-m-d H:i:s");
        $hour = date("Y-m-d H:i:s", strtotime("-1 hour", strtotime($date)));
        $today = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($date)));
        $week = date("Y-m-d H:i:s", strtotime("-1 week", strtotime($date)));
        $month = date("Y-m-d H:i:s", strtotime("-1 month", strtotime($date)));
        $last24 = time() - 86400;
        $data = [];
        $data['recentUsers'] = $this->db->rawQuery("SELECT * FROM users_online WHERE timestamp > ? ORDER BY timestamp DESC", array($last24));
        $data['recentUsersCount'] = $this->db->count;
        $usersHour = $this->db->rawQuery("SELECT * FROM users WHERE last_login > ?", array($hour));
        $data['usersHour'] = $this->db->count;
        $usersToday = $this->db->rawQuery("SELECT * FROM users WHERE last_login > ?", array($today));
        $data['usersToday'] = $this->db->count;
        $usersWeek = $this->db->rawQuery("SELECT username FROM users WHERE last_login > ?", array($week));
        $data['usersWeek'] = $this->db->count;
        $usersMonth = $this->db->rawQuery("SELECT username FROM users WHERE last_login > ?", array($month));
        $data['usersMonth'] = $this->db->count;
        $levels = $this->db->rawQuery("SELECT * FROM main_roles");
        $data['levels'] = $this->db->count;
        $users = $this->db->rawQuery("SELECT * FROM users");
        $data['users'] = $this->db->count;

        return $data;
    }


    //Retrieve information for all users
    public function getUsers()
    {
        return $this->db->rawQuery(
            "SELECT 
		      id, username, email, fname, lname, title,
		      DATE_FORMAT(join_date,\"%d-%m-%Y\") as join_date, 
		      DATE_FORMAT(last_login,\"%d-%m-%Y\") as last_login, 
		      logins 
		    FROM users");
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function getUser($field, $value)
    {
        try {
            return $this->abd_getItemWhere(\BD_main::TAULA_USUARIS, $field . '="' . $value . '"');
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param $data
     * @param $id_user
     * @return bool
     */
    public function updateUser($data, $id_user)
    {
        try {
            $this->abd_updateItem(\BD_main::TAULA_USUARIS, $id_user, $data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRoles()
    {
        $rolesData =  $this->query("SELECT * FROM " . \BD_main::TAULA_ROLES);
        $res = [];
        foreach ($rolesData as $r){
            $res[] = new Role($r);
        }
        return $res;
    }


    public function getPermissions()
    {
        $rolesData =  $this->abd_consultaTaula(\BD_main::TAULA_PERMISSIONS);
        $res = [];
        foreach ($rolesData as $r){
            $res[] = new Permission($r);
        }
        return $res;
    }

    public function updateRole($roleId, $data)
    {
        try {
            $this->abd_updateItem("permissions", $roleId, $data);
        } catch (\Exception $e) {
            echo $this->abd_darrerError();
        }
    }

    public function getRolesPermission($id_permission){
        $roles_data = $this->db->rawQuery(
            "SELECT main_roles.* FROM main_roles  
                     INNER JOIN main_rolepermissions 
                     ON main_roles.id = main_rolepermissions.id_role 
                  WHERE main_rolepermissions.id_permission = ?", [$id_permission]);
        $roles = [];
        foreach ($roles_data as $rData){
            $roles[] = new Role($rData);
        }
        return $roles;
    }

    //TODO: assert that query is correct
    public function getNoneRolesPermission($id_permission){
        $roles_data = $this->db->rawQuery(
            "SELECT main_roles.* FROM 
                    main_roles LEFT JOIN main_rolepermissions 
                     ON (main_roles.id = main_rolepermissions.id_role AND  main_rolepermissions.id_permission = ?)
                  WHERE main_rolepermissions.id IS NULL", [$id_permission]);
        $roles = [];
        foreach ($roles_data as $rData){
            $roles[] = new Role($rData);
        }
        return $roles;
    }

    public function getPermissionsRole($id_role){
        $perms_data = $this->db->rawQuery(
            "SELECT main_permissions.* FROM main_permissions  
                     INNER JOIN main_rolepermissions 
                     ON main_permissions.tag = main_rolepermissions.id_permission 
                  WHERE main_rolepermissions.id_role = ?", [$id_role]);
        $perms = [];
        foreach ($perms_data as $rData){
            $perms[] = new Permission($rData);
        }
        return $perms;
    }

    //TODO: assert that query is correct
    public function getNonePermissionsRole($id_role){
        $perms_data = $this->db->rawQuery(
            "SELECT main_permissions.* FROM 
                    main_permissions LEFT JOIN main_rolepermissions 
                     ON (main_permissions.tag = main_rolepermissions.id_permission AND  main_rolepermissions.id_role= ?)
                  WHERE main_rolepermissions.id IS NULL", [$id_role]);
        $perms = [];
        foreach ($perms_data as $rData){
            $perms[] = new Permission($rData);
        }
        return $perms;
    }

    public function unrelateRolePermission($id_role, $id_perm){
        return $this->db->rawQuery(
            "DELETE FROM " . \BD_main::TAULA_ROLE_PERMISIONS .
            " WHERE id_role = ? AND id_permission = ?",
            [$id_role, $id_perm]);

    }

    public function roleHasPermission($id_role, $tag){
        $res = $this->db->rawQuery(
            "SELECT * FROM main_rolepermissions 
                    WHERE id_permission=? AND id_role=?", [$tag, $id_role]);
        return !empty($res);
    }
}

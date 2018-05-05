<?php

namespace BD;
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
        $pages = $this->db->rawQuery("SELECT * FROM main_uris");
        $data['pages'] = $this->db->count;
        $users = $this->db->rawQuery("SELECT * FROM users");
        $data['users'] = $this->db->count;
        $clients = $this->db->rawQuery("SELECT * FROM main_clients");
        $data['clients'] = $this->db->count;
        $companys = $this->db->rawQuery("SELECT * FROM main_companys");
        $data['companys'] = $this->db->count;

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
     * If is admin user logged get all companies inside database, otherwhise get false
     * @return array|bool
     * @throws \Exception
     */
    public function getCompanies()
    {
        if (!(new User_logged())->isAdmin()) {
            return false;
        }

        $this->db->orderBy('name', 'ASC');
        return $this->db->get(\BD_main::TAULA_COMPANYIES, null, "*");
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

    public function unRelateCenterUser($id_center, $id_user)
    {
        return $this->db->rawQuery(
            "DELETE FROM " . \BD_main::TAULA_CLIENTSUSER .
            " WHERE id_client= ? AND id_user= ?", [$id_center, $id_user]);
    }

    public function relateCenterUser($id_centro, $id_user){
        $item = new database_item([
            "id_client" => $id_centro,
            "id_user" => $id_user
        ], \BD_main::TAULA_CLIENTSUSER);
        return $item->insert();
    }

    public function unRelateCenterCompany($id_center, $id_company)
    {
        return $this->db->rawQuery(
            "DELETE FROM " . \BD_main::TAULA_CLIENTCOMPANYIA .
            " WHERE id_company = ? AND id_client = ?", [$id_company, $id_center]);
    }


    public function getCentersUser($id_user)
    {
        $id_centros = $this->abd_consultaTaula(\BD_main::TAULA_CLIENTSUSER,
            '*', 'id_user=' . $id_user);

        $centros = [];
        foreach ($id_centros as $data_centro) {
            $centros[$data_centro['id_client']] = new Centro($data_centro['id_client']);
        }
        return $centros;
    }

    public function getUsersCenter($id_center)
    {
        $dataUsuarios = $this->abd_consultaTaula(\BD_main::TAULA_CLIENTSUSER,
            '*', 'id_client=' . $id_center);

        $users = [];
        foreach ($dataUsuarios as $data_user) {
            $users[$data_user['id_user']] = new User($data_user['id_user']);
        }
        return $users;

    }

    public function getPage($id)
    {
        return $this->abd_getItem(\BD_main::TAULA_PAGES, $id, 'id, page, private');
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

    /**
     * @param $id_center
     * @return Companyia[]
     */
    public function getCompaniesCenter($id_center)
    {
        $dataComp = $this->abd_consultaTaula(\BD_main::TAULA_CLIENTCOMPANYIA,
            '*', 'id_client=' . $id_center);

        $companies = [];
        foreach ($dataComp as $data_comp) {
            $companies[$data_comp['id_company']] = new Companyia($data_comp['id_company']);
        }
        return $companies;
    }

    public function getIdCentersCompany($id_comp)
    {
        $dataComp = $this->abd_consultaTaula(\BD_main::TAULA_CLIENTCOMPANYIA,
            'id_client', 'id_company=' . $id_comp);

        return array_column($dataComp, 'id_client');
    }

    /**
     * @param $pagesAdd
     * @param $roleId
     * @return bool
     */
    public function addPagesRole($pagesAdd, $roleId)
    {
        $data = [];
        foreach ($pagesAdd as $id_page) {
            $data[] = ["permission_id" => $roleId, "page_id" => $id_page];
        }

        $ids = $this->db->insertMulti('permission_page_matches', $data);
        return $ids;
    }

    public function deletePagesRole($pagesRemove, $roleId)
    {

        foreach ($pagesRemove as $id_page) {
            $this->db->where("permission_id = " . $roleId . " AND page_id = " . $id_page);
            $this->db->delete('permission_page_matches');
        }

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

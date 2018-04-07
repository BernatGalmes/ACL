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

    /**
     * @return array|bool devuelve una tabla con todos los clientes
     */
    public function getClients()
    {
        global $app;
        if ($app->getUser()->isAdmin()) {
            return $this->abd_consultaTaulaOrdenada(\BD_main::TAULA_CLIENTS, '*', 'name');
        } else {
            return $this->getClientsUser();
        }
    }

    private function getClientsUser()
    {
        global $app;
        $id_user = $app->getUser()->getID();

        $ssql =
            "SELECT 
						main_clients.id as id, 
						main_clients.name as name,
						main_clients.admin as admin
					FROM 
						main_clients INNER JOIN main_clientuser
						ON main_clients.id = main_clientuser.id_client
					WHERE 
						main_clientuser.id_user = ?";

        $dades = $this->db->rawQuery($ssql, array($id_user));

        return $dades;
    }


}

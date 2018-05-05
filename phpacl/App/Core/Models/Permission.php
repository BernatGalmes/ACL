<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 4/02/18
 * Time: 12:54
 */

namespace PHPACL;


use App\database_item;
use BD\ABD_system;
use BD\AccesBD;
use PhpGene\Messages;

class Permission extends database_item
{
    /**
     * Permission constructor.
     * @param $item
     * @throws \Exception
     */
    function __construct($item=null)
    {
        parent::__construct($item, \BD_main::TAULA_PERMISSIONS);
        if(is_string($item)) {
            if (!$this->__build_from_db($item)) {
                throw new \Exception("item not found in database");
            }
        }
    }

    /**
     * Idem del pare sense registrar el nou id a $_data
     * @return bool
     * @throws
     */
    public function insert()
    {

        try {
            $aBD = AccesBD::getInstance();
            if ($aBD->hasColumn($this->_db_table, 'creador')) {
                // get identifier of current user logged
                $this->_data['creador'] = (new User_logged())->getID();
            }

            if ($aBD->hasColumn($this->_db_table, 'cdate'))
                $this->_data['cdate'] = cdate();

            if ($this->insert_validation()) {
                $aBD->abd_insertItem($this->_db_table, $this->_data);
                return true;
            }
        } catch (\Exception $e) {
            $this->getMessages()->debug($e->getMessage());
            $this->getMessages()->debug($aBD->abd_darrerError());
        }

        return false;
    }

    /**
     * Get the identifier of the item
     * @return int|string Id of the item
     */
    public function getID()
    {
        return $this->getAttr('tag');
    }

    function getRoles(){
        return ABD_system::getInstance()->getRolesPermission($this->getID());
    }

    function getNoneRoles(){
        return ABD_system::getInstance()->getNoneRolesPermission($this->getID());
    }

    function addRoles($list_id_roles){
        foreach ($list_id_roles as $id_role){
            $db_item = new database_item([
                "id_role" => $id_role,
                "id_permission" => $this->getID()
            ], \BD_main::TAULA_ROLE_PERMISIONS);
            $db_item->insert();
            $this->getMessages()->merge($db_item->getMessages());
        }
    }

    function remRoles($list_id_roles){
        $aBD = ABD_system::getInstance();
        foreach ($list_id_roles as $id_role){
            $aBD->unrelateRolePermission($id_role, $this->getID());
        }
    }
}
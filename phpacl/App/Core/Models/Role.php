<?php
/**
 * Created by IntelliJ IDEA.
 * User: bernat
 * Date: 2/07/17
 * Time: 12:11
 */

namespace PHPACL;


use BD\ABD_system;
use General\Validate;
use App\database_item;
use PhpGene\Messages;

/**
 * Class Role Define un rol de un grupo de usuarios. ie.: Admin
 * @package system
 */
class Role extends database_item
{

    /**
     * Role constructor.
     * @param $item
     * @throws \Exception item not found
     */
    function __construct($item=null)
    {
        parent::__construct($item, \BD_main::TAULA_ROLES);
    }

    /**
     * @param $validator Validate
     * @param $newName string
     */
    function updateName($validator, $newName)
    {
        $fields = array('name' => $newName);
        //NEW Validations
        $validator->check($_POST, array(
            'name' => array(
                'display' => 'Role Name',
                'required' => true,
                'unique' => 'permissions',
                'min' => 1,
                'max' => 25
            )
        ));
        if ($validator->passed()) {
            $aBD = ABD_system::getInstance();
            $aBD->updateRole($this->getID(), $fields);
        }
    }

    protected function insert_validation()
    {
        $validator = new Validate();
        $validator->check($_POST, array(
            'name' => array(
                'display' => 'Role Name',
                'required' => true,
                'unique' => $this->_db_table,
                'min' => 1,
                'max' => 25
            )
        ));
        if (!$validator->passed()){
            $msgs = $this->getMessages();
            $msgs->merge($validator->getMissatges());
            return false;
        }

        return true;
    }

    /**
     * @param $pagesAdd int[] list of pages ids
     * @param $pagesRemove int[] list of pages ids
     */
    function updatePages($pagesAdd, $pagesRemove)
    {
        $aBD = ABD_system::getInstance();

        $aBD->addPagesRole($pagesAdd, $this->getID());
        $aBD->deletePagesRole($pagesRemove, $this->getID());
    }

    public function pages()
    {
        $db = ABD_system::getInstance();

        return $db->query("SELECT 
            m.id as id, m.page_id as page_id, p.page as page, p.private as private
	      FROM main_roleuris AS m
	      INNER JOIN main_uris AS p ON m.page_id = p.id
	      WHERE m.permission_id = ?", [$this->getAttr('id')]);

    }


    //Retrieve list of users who have a permission level

    /**
     * Delete the role from database, only if it is not admin role and haven't got any user
     * @return bool
     */
    public function remove()
    {
        // si hi ha usuaris amb el rol o es admin retorna false
        if (in_array($this->getAttr('name'), User_logged::PRIVILEGED_ROLES)
            || !empty($this->users())) {
            return false;
        }

        return parent::remove();
    }

    function users()
    {
        $db = ABD_system::getInstance();
        return $db->query("SELECT id, username FROM users WHERE permission_id = ?", array($this->getAttr('id')));
    }

    function getPermissions(){
        return ABD_system::getInstance()->getPermissionsRole($this->getID());
    }

    function getNonePermissions(){
        return ABD_system::getInstance()->getNonePermissionsRole($this->getID());
    }
}
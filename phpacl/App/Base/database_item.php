<?php
/**
 * Created by IntelliJ IDEA.
 * User: bern
 * Date: 24/12/17
 * Time: 10:31
 */

namespace App;


use BD\AccesBD;
use PHPACL\User;
use PHPACL\User_logged;

class database_item extends \PhpGene\database_item
{
    /**
     * Remove the item in the database
     * @return bool
     */
    public function remove()
    {
        $aBD = AccesBD::getInstance();
        try {
            return $aBD->abd_deleteItem($this->_db_table, $this->getID());
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * Update the database data with the current object data
     * @return bool
     */
    public function update()
    {
        $aBD = AccesBD::getInstance();
        try {
            if ($this->update_validation()) {
                return $aBD->abd_updateItem($this->_db_table, $this->getID(), $this->_data);
            }
        } catch (\Exception $e) {
            $this->getMessages()->debug($e->getMessage());
            $this->getMessages()->debug($aBD->abd_darrerError());
        }
        return false;
    }

    /**
     * @return bool
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
                $id = $aBD->abd_insertItem($this->_db_table, $this->_data);
                return $this->__build_from_db($id);
            }
        } catch (\Exception $e) {
            $this->getMessages()->debug($e->getMessage());
            $this->getMessages()->debug($aBD->abd_darrerError());
            $this->getMessages()->debug($aBD->abd_darreraConsulta());
        }

        return false;
    }

    protected function insert_validation(){
        return true;
    }

    protected function update_validation(){
        return true;
    }

    protected function __build_from_db($id)
    {
        $aBD = AccesBD::getInstance();
        try {
            $this->_data = $aBD->abd_getItem($this->_db_table, $id);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function exists(){
        $aBD = AccesBD::getInstance();
        try {
            $aBD->abd_getItem($this->_db_table, $this->getID());
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $user User
     * @return bool
     */
    public function isCreador($user)
    {
        return $this->_data['creador'] == $user->getID();
    }
}
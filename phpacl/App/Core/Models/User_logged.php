<?php

namespace PHPACL;

use BD\AccesBD;
use PhpGene\Session;

/**
 * Class User
 * Represents a user logged
 * @package System
 */
class User_logged extends User
{
    const PRIVILEGED_ROLES = ['Admin', "superuser"];

    private $_sessionName, $_isLoggedIn = false;

    /**
     * User_logged constructor.
     * @param null $user
     * @throws \Exception
     */
    public function __construct($user = null)
    {
        $this->_sessionName = config::SESSION['name'];

        // if no user especified then load from session
        if (!isset($user) && Session::exists($this->_sessionName)) {
            $user = Session::get($this->_sessionName);
            parent::__construct($user);

            if ($this->exists()) {
                $this->_isLoggedIn = true;
                $this->_role = $this->getRole();// user logged always have this loaded
            } else {
                $this->logout();
                die();
            }


        } else { // load especified user
            parent::__construct($user);
        }
    }

    /**
     * Perform a login into tha application.
     * Note: if a user was logged overwrite his session
     * @param null $identifier  id, username or mail of the user
     * @param null $password password without encrypt
     * @return bool
     */
    public function login($identifier=null, $password=null)
    {
        // login the current user
        if (!isset($identifier) && !isset($password) && $this->exists()) {
            Session::put($this->_sessionName, $this->getID());
            return false;
        }

        // check login data
        if (!$this->loginCheck($identifier, $password)) {
            return false;
        }

        Session::put($this->_sessionName, $this->getID());
        $aBD = AccesBD::getInstance();
        try {
            $aBD->query("UPDATE users SET last_login = ?, logins = logins + 1 WHERE id = ?",
                [date("Y-m-d H:i:s"), $this->getID()]);
        } catch (\Exception $e) {
            return false;
        }
        $this->_isLoggedIn = true;
        return true;
    }

    /**
     * Check login data
     * @param $identifier
     * @param $password
     * @return bool
     */
    private function loginCheck($identifier, $password)
    {
        // if especified user don't exist
        $user = $this->find($identifier);
        if (!$user) {
            return false;
        }

        // check password
        $this->_data['password'] = trim($this->_data['password']);
        $password = trim($password);
        if (!password_verify($password, $this->_data['password'])) {
            return false;
        }
        return true;
    }

    public function logout()
    {
        Session::delete($this->_sessionName);
        $this->_isLoggedIn = false;
    }


    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public function isAdmin()
    {
        if ($this->isLoggedIn()) {
            return in_array($this->getAttr('title'), self::PRIVILEGED_ROLES);
        }

        return false;
    }

    public function hasPermission($tag){
        if (!$this->_isLoggedIn){
            Redirect::to(LINK_APP);
        }

        global $app;
        if ($app->isSuperUserSession()){
            return true;
        }

        return parent::hasPermission($tag);
    }
}

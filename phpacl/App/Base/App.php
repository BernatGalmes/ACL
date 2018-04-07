<?php
/**
 * Created by IntelliJ IDEA.
 * User: bernat
 * Date: 15/07/17
 * Time: 21:08
 */

namespace System;

use General\Redirect;
use PhpGene\Messages;
use PhpGene\xml_data;

class App extends xml_data
{
    /**
     * @var User_logged
     */
    private $user;

    private $_mail;
    private $_status;

    private static $_instance;
    /**
     * App constructor. Init the app
     */
    function __construct()
    {
        parent::__construct(PATH_FILE_SETTINGS);
        $this->_status = $this->dom->website->status;
        $this->_mail = $this->dom->mail;

        // Get current user logged
        $this->user = new User_logged();
        //Check to see that user is verified
        if ($this->user->isLoggedIn()) {
            $currentPage = $this->page();

            // pagines permeses sense validar
            $pages_without_ver = ['verify.php', 'logout.php', 'verify_thankyou.php', 'verify_resend.php'];
            if ($this->user->getAttr('email_verified') == 0 && !in_array($currentPage, $pages_without_ver)) {
                Redirect::to('verify.php');
                die("user must be verified");
            }
        }

        if (!$this->user->isAdmin() && $this->_status->site_offline == 1) {
            Redirect::to(LINK_BASE_OFFLINE);
        }

        //if track_guest enabled AND there is a user logged in
        if ($this->_status->track_guest == 1 && $this->user->isLoggedIn()) {
            if ($this->user->isLoggedIn()) {
                $user_id = $this->user->getID();
            } else {
                $user_id = 0;
            }
            if (php_sapi_name() !== "cli")
                System::new_user_online($user_id);

        }
    }

    public static function get(){
        if (empty(self::$_instance)){
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    public function page()
    {
        $uri = $_SERVER['PHP_SELF'];
        $path = explode('/', $uri);
        $currentPage = end($path);
        return $currentPage;
    }

    public function setOffline($site_offline)
    {
        if ($this->_status->site_offline != $site_offline) {
            $this->_status->site_offline = $site_offline;
            $this->saveChanges();
        }
    }

    public function setTrack_guests($track_guest)
    {
        print_r($this->_status);
        print_r($track_guest);
        if ($this->_status->track_guest != $track_guest) {
            $this->_status->track_guest = $track_guest;
            $this->saveChanges();
        }
    }

    public function module()
    {
        if (php_sapi_name() === "cli"){ //script running from command line
            return "";
        }
        $uri = $_SERVER['PHP_SELF'];
        $path = explode('/', $uri);
        $currentFolder = $path[count($path)-2];
        return $currentFolder;
    }

    /**
     * @return User_logged
     */
    public function getUser()
    {
        return $this->user;
    }

    public function isSuperUserSession()
    {
        $l_user = $this->getUser();
        return $l_user->getRole()->getID() == (int)$this->dom->website->superuser;
    }
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @return boolean
     */
    public function isEnabledMailAct()
    {
        //Value of email_act used to determine whether to display the Resend Verification link
        return $this->_mail['email_act'];

    }

    public function setCliUser(){
        $this->user = new User_logged(1);
    }

}
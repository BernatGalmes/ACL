<?php

namespace PHPACL;

use App\database_item;
use BD\AccesBD;
use PhpGene\Messages;


class User extends database_item
{
    const DEFAULT_USERNAME_LENGTH = 7;
    const RANDOM_PASS_LENGTH = 8;

    private $_perms_status = [];

    /**
     * Name of user table in database
     */
    const TABLE = \BD_main::TAULA_USUARIS;

    /**
     * User validation config
     */
    const VALIDATION_NEW = [
        'username' => array(
            'display' => 'Username',
            'required' => true,
            'min' => 5,
            'max' => 35,
            'unique' => 'users',
        ),
        'fname' => array(
            'display' => 'First Name',
            'required' => true,
            'min' => 2,
            'max' => 35,
        ),
        'lname' => array(
            'display' => 'Last Name',
            'required' => true,
            'min' => 2,
            'max' => 35,
        ),
        'email' => array(
            'display' => 'Email',
            'required' => true,
            'valid_email' => true,
            'unique' => 'users',
        ),
        'password' => array(
            'display' => 'Password',
            'required' => true,
            'min' => 6,
            'max' => 25,
        ),
        'confirm' => array(
            'display' => 'Confirm Password',
            'required' => true,
            'matches' => 'password',
        ),
    ];

    /**
     * @var Role role of current user
     */
    protected $_role;

    /**
     * @var String password without hash
     */
    private $_password;


    /**
     * User constructor.
     * @param int | string | null $user user identifier or username
     * @throws \Exception if user is not in database
     */
    public function __construct($user = null)
    {
        parent::__construct(null, self::TABLE);
        $this->_msgs = new Messages();
        if (empty($user)) {
            return;

        } elseif (is_array($user)) {
            $this->_data = $user;

        } elseif (!$this->find($user)) {
            throw new \Exception("user not found");
        }
    }

    /**
     * @param int | string | bool $user user identifier or username
     * @return bool indicating if it has been successful
     */
    protected function find($user = false)
    {
        if (!$user) {
            return false;
        }

        if (is_numeric($user)) {
            $field = 'id';

        } elseif (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';

        } else {
            $field = 'username';

        }

        $aBD = AccesBD::getInstance();
        $data = $aBD->getUser($field, $user);
        if (!empty($data)) {
            $this->_data = $data;
        }
        return !empty($data);
    }

    /***
     *   getters and setters
     */


    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getJoinDate()
    {
        return $this->getAttr('join_date');
    }

    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getLastLogin()
    {
        return $this->getAttr('last_login');
    }

    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getLogins()
    {
        return $this->getAttr('logins');
    }

    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getFullName()
    {
        return $this->getFName() . ' ' . $this->getLName() . ' (' . $this->getName() . ')';
    }

    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getFname()
    {
        return $this->getAttr('fname');
    }

    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getLname()
    {
        return $this->getAttr('lname');
    }

    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getName()
    {
        return $this->getAttr('username');
    }

    /**
     * @deprecated use getAttr() insted
     * @return mixed|string
     */
    public function getMail()
    {
        return $this->getAttr('email');
    }

    public function hasLogo()
    {
        $aBD = AccesBD::getInstance();
        return
            $aBD->roleHasPermission($this->getAttr('id_role'), "docs_auds_create") ||
            $aBD->roleHasPermission($this->getAttr('id_role'), "docs_infs_create") ||
            $aBD->roleHasPermission($this->getAttr('id_role'), "docs_sams_create") ||
            $aBD->roleHasPermission($this->getAttr('id_role'), "docs_samaudi_create");
    }

    public function getLinkLogo()
    {
        if ($this->hasLogo()){
            if (file_exists(PATH_PUBLIC . "/logos/" . $this->getID() . ".png"))
                return LINK_APP . "logos/" . $this->getID() . ".png";
        }

        return LINK_APP . "recursos/imatges/logo.png";
    }

    public function getPathLogo()
    {
        if ($this->hasLogo()){
            if (file_exists(PATH_PUBLIC . "/logos/" . $this->getID() . ".png"))
                return PATH_PUBLIC . "/logos/" . $this->getID() . ".png";
        }

        return PATH_PUBLIC . "/recursos/imatges/logo.png";
    }
    public static function authomaticUsername()
    {
        $aBD = AccesBD::getInstance();
        $nextId = $aBD->nextID(\BD_main::TAULA_USUARIS);
        $cero = '0';

        $outuser = 'U';
        for ($i = strlen($nextId)+1; $i < self::DEFAULT_USERNAME_LENGTH; $i++) {
            $outuser .= $cero;
        }

        $outuser = $outuser . $nextId;
        return $outuser;
    }

    public static function randomPassword()
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, self::RANDOM_PASS_LENGTH);
    }

    /**
     * Check if the current user exists
     * @return bool
     */
    public function exists()
    {
        return !empty($this->_data);
    }

    /**
     * Elimina totes les dades de l'usuari en curs de la base de dades
     */
    public function remove()
    {
        return parent::remove();
        //TODO: decidir com gestionar les relacions amb documents i altres elements
    }

    public function getRole()
    {
        if (empty($this->_role))
            $this->_role = new Role($this->getAttr('id_role'));
        return $this->_role;
    }

    /**
     * Change user password
     * @param $newPass
     * @return true
     * @throws \Exception
     */
    public function updatePassword($newPass)
    {
        $this->_data['vericode'] = rand(100000, 999999);
        $this->_data['password'] = self::pass_hash($newPass);
        $this->_data['email_verified'] = true;

        return $this->update();
    }

    /************************************************/
    /************************************************/
    /****         Correo usuario nuevo          *******/
    /************************************************/
    /************************************************/


    public static function pass_hash($original_pass)
    {
        return password_hash($original_pass, PASSWORD_BCRYPT, array('cost' => 12));
    }

    /**
     * Code executed before insert indicating if is a correct object
     * @return bool
     * @throws \Exception
     */
    protected function insert_validation()
    {
        $validator = new Validate();

        try{
            $role = new Role($this->getAttr('id_role'));
        }catch(\Exception $e){
            $this->getMessages()->posa_error("<strong>Fallo!</strong> Rol incorrecto!");
            return false;
        }
        if (!$role->exists()){
            $this->getMessages()->posa_error("<strong>Fallo!</strong> debes seleccionar un rol para el usuario");
            return false;
        }

        $validator->check($this->_data, self::VALIDATION_NEW);
        if (!$validator->passed()) {
            $this->getMessages()->merge($validator->getMissatges());
            return false;
        }
        unset($this->_data['confirm']);
        if (!empty($this->_data['password'])) {
            $this->_password = $this->_data['password'];
            $this->_data['password'] = self::pass_hash($this->_data['password']);
        }

        $aBD = ABD_system::getInstance();
        $this->_data['vericode'] = rand(100000, 999999);
        $this->_data['join_date'] = date("Y-m-d H:i:s");
        $this->_data['title'] = $aBD->abd_getItem(\BD_main::TAULA_ROLES, $this->_data['id_role'], 'name')['name'];

        return true;
    }

    /**
     * Note: Check password and encrypt it before update
     * @return bool
     */
    protected function update_validation()
    {
        $camps_val = [];
        if (!empty($this->getAttr('username'))) {
            $camps_val['username'] = array(
                'display' => 'Username',
                'required' => true,
                'min' => 5,
                'max' => 35,
                'unique_update' => 'users,' . $this->getID(),
            );
        }
        if (!empty($this->getAttr('fname'))) {
            $camps_val['fname'] = array(
                'display' => 'First Name',
                'required' => true,
                'min' => 2,
                'max' => 35,
            );
        }

        if (!empty($this->getAttr('lname'))) {
            $camps_val['lname'] = array(
                'display' => 'Last Name',
                'required' => true,
                'min' => 2,
                'max' => 35,
            );
        }


        if (!empty($this->getAttr('email'))) {
            $camps_val['email'] = array(
                'display' => 'Email',
                'required' => true,
                'valid_email' => true,
                'unique_update' => 'users,' . $this->getID(),
            );
        }
        $this->_msgs = new Messages();
        $validator = new Validate();
        $validator->check($this->_data, $camps_val);
        if (!$validator->passed()) {
            $this->_msgs = $validator->getMissatges();
            return false;
        }

        if (isset($this->_data['id_role'])) {
            $aBD = AccesBD::getInstance();
            try {
                $this->_data['title'] = $aBD->abd_getItem(\BD_main::TAULA_ROLES, $this->_data['id_role'], 'name')['name'];
            } catch (\Exception $e) {
                return false;
            }
        }
        return true;
    }

    public function insert()
    {
        if (!parent::insert()) {
            return false;
        }

        // if user has been created send mail to the user
        if (!$this->sendMail_nuevoUsuario()) {
            $this->_msgs->posa_error("<strong>Fallo!</strong> no se ha podido enviar el correo al usuario, por lo tanto no se ha creado");
            $this->remove();
            return false;

        }
        $this->_msgs->posa_ok("Usuario creado de forma satisfactoria");
        return true;
    }

    /**
     * @throws \Exception
     */
    public function verify(){
        $this->_data['email_verified'] = 1;
        $this->update();
    }


    /************************************************/
    /************************************************/
    /****         Funcions estàtiques          *******/
    /************************************************/
    /************************************************/


    public function sendMail_nuevoUsuario()
    {
        try {
            $mail = new Mail();

            $mail->to($this->getAttr('email'), $this->getAttr('fname'));
            $mail->setSubject('Bacter Control - Nuevo usuario');
            $mail->body($this->mensajeNuevoUsuario());

            if (!$mail->send()) {
                $this->_msgs->posa_error(MSG_PASS_FORGOTTEN_ERROR_MAIL);
                $this->_msgs->debug($mail->ErrorInfo);
                return false;
            }
            return true;

        } catch (\Exception $e) {
            $this->_msgs->posa_error($e->getMessage()); //Boring error messages from anything else!
            return false;
        }
    }

    private function mensajeNuevoUsuario()
    {
        $params = array(
            'fname' => $this->getAttr('fname'),
            'email' => $this->getAttr('email'),
            'vericode' => $this->getAttr('vericode'),
            'username' => $this->getName(),
            'password' => $this->_password,
        );
        return Mail::mail_body(PATH_VIEWS . '/mails/_mail_newUser.php', $params);
    }

    //Check if a user ID exists in the DB

    public function sendMail_verificar()
    {
        $encoded_email = $this->getAttr('email');
        $subject = 'Verificación de correo';
        try {
            $mail = new Mail();

            $mail->to($encoded_email, $this->getAttr('fname'));
            $mail->setSubject($subject);
            $mail->body($this->mensajeVerificar());
            if (!$mail->send()) {
                $this->_msgs->posa_error(MSG_PASS_FORGOTTEN_ERROR_MAIL);
                $this->_msgs->debug($mail->ErrorInfo);
                return false;
            }
            return true;

        } catch (\Exception $e) {
            $this->_msgs->posa_error($e->getMessage()); //Boring error messages from anything else!
            return false;
        }
    }

    private function mensajeVerificar()
    {
        $params = array(
            'fname' => $this->getAttr('fname'),
            'email' => $this->getAttr('email'),
            'vericode' => $this->getAttr('vericode'),
        );
        return Mail::mail_body(PATH_VIEWS . '/mails/_email_verify.php', $params);
    }

    public function sendMail_forgotPassword()
    {
        $encoded_email = $this->getAttr('email');
        $options = array(
            'fname' => $this->getAttr('fname'),
            'email' => $encoded_email,
            'vericode' => $this->getAttr('vericode'),
        );
        $subject = 'Password Reset';
        $body = Mail::mail_body(PATH_VIEWS . '/mails/_email_template_forgot_password.php', $options);

        try {
            $mail = new Mail();

            $mail->to($encoded_email, $this->getAttr('fname'));
            $mail->setSubject($subject);
            $mail->body($body);
            if (!$mail->send()) {
                $this->_msgs->posa_error(MSG_PASS_FORGOTTEN_ERROR_MAIL);
                $this->_msgs->debug($mail->ErrorInfo);
                return false;
            }
            return true;

        } catch (\Exception $e) {
            $this->_msgs->posa_error($e->getMessage()); //Boring error messages from anything else!
            return false;
        }
    }

    public function sendMail_newRandomPassword($newPass)
    {
        $encoded_email = $this->getAttr('email');
        $options = array(
            'fname' => $this->getAttr('fname'),
            'username' => $this->getAttr('username'),
            'newPass' => $newPass,
            'email' => $encoded_email,
            'vericode' => $this->getAttr('vericode'),
        );
        $subject = 'Password Reset';
        $body = Mail::mail_body(PATH_VIEWS . '/mails/_email_template_newRandomPass.php', $options);

        try {
            $mail = new Mail();

            $mail->to($encoded_email, $this->getAttr('fname'));
            $mail->setSubject($subject);
            $mail->body($body);
            if (!$mail->send()) {
                $this->_msgs->posa_error(MSG_PASS_FORGOTTEN_ERROR_MAIL);
                $this->_msgs->debug($mail->ErrorInfo);
                return false;
            }
            return true;

        } catch (\Exception $e) {
            $this->_msgs->posa_error($e->getMessage());
            return false;
        }
    }

    public function hasPermission($tag){
        if(!isset($this->_perms_status[$tag])) {
            $aBD = AccesBD::getInstance();
            $this->_perms_status[$tag] = $aBD->roleHasPermission($this->_data['id_role'], $tag);
        }
        return $this->_perms_status[$tag];
    }
}

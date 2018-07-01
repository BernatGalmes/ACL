<?php

namespace PHPACL;

use BD\AccesBD;
use General\Controller;
use General\Redirect;
use General\Validate;
use PhpGene\Input;
use PhpGene\Messages;
use PhpGene\Token;

/**
 * Class Users Classe dedicada a gestionar tot el relacionat amb els usuaris.
 * @package System
 * @deprecated
 */
class Users extends Controller
{

    /**
     * Management of user creation of admin_users.php
     * @return User
     * @throws \Exception
     */
    public static function FormCreate()
    {
        unset($_POST['csrf']);
        if (empty($_POST['addUser']))
            return new User();


        unset($_POST["addUser"]);

        // retrieve input data
        $data = [
            'username' => Input::get('username'),
            'password' => Input::get('password'),
            'fname' => Input::get('fname'),
            'email' => Input::get('email'),
            'lname' => Input::get('lname'),
            'confirm' => Input::get('confirm'),
            'permission_id' => Input::get('permissions')
        ];

        $user = new User($data);
        $status = $user->insert();
        self::$msgs = $user->getMessages();

        // if has been an error inserting remove
        if (!$status) {
            if ($user->exists()) {
                $user->remove();
            }
            return $user;
        }

        $user = new User();
        return $user;
    }


    public static function Form($id_user = null)
    {
        try {
            if (isset($id_user)) {
                $usuario = new User ($id_user);

            } else {
                $usuario = new User ();

            }
            if (Input::exists('post')) {
                unset($_POST['csrf']);
                if (isset($_POST["updateUser"])) {
                    if (!isset($id_user)) {//en principi es imposible
                        return false;
                    }
                    unset($_POST["updateUser"]);
                    $user = new User(Input::getAllPost());
                    if(!$user->update()) {
                        Redirect::to(LINK_SYSTEM_ADMIN_USER . '?id=' . $id_user);
                    }

                }
            }
            return $usuario;
        } catch (\Exception $e) {
            self::getMsgs()->posa_error("No se pudo cargar el usuario");
        }

    }

    /**
     * @param $app App
     */
    public static function pageLogin($app)
    {
        self::$msgs = new Messages();
        $user = $app->getUser();

        if ($user->isLoggedIn()) {
            Redirect::to(LINK_APP);
        }

        if (!Input::exists()) {
            return;
        }
        $token = Input::get('csrf');
        if (!Token::check($token)) {
            Redirect::to($_SERVER['PHP_SELF']);
        }

        $validate = new Validate();
        $validation = $validate->check($_POST,
            array(
                'username' =>
                    array(
                        'display' => 'Username',
                        'required' => true
                    ),
                'password' =>
                    array(
                        'display' => 'Password',
                        'required' => true)
            )
        );

        if ($validation->passed()) {//Log user in
            $remember = (Input::get('remember') === 'on') ? true : false;
            $user = new User_logged();
            $login = $user->login(Input::get('username'), trim(Input::get('password')), $remember);
            if ($login) {
                Redirect::to(LINK_APP);
            } else {
                self::$msgs->debug(AccesBD::getInstance()->abd_darreraConsulta());
                self::$msgs->debug(AccesBD::getInstance()->abd_darrerError());
                self::$msgs->posa_error('Login fallido, por favor compruebe sus credenciales.');
            }

        } else {
            self::$msgs = $validate->getMissatges();
        }
    }

    /**
     * @param App $app
     * @return User
     */
    public function pageSettings($app)
    {
        $session_user = $app->getUser();
        $data = [];
        if ($session_user->getAttr('username') != $_POST['username']) {
            $data['username'] = Input::get("username");

        }

        //Update first name
        if ($session_user->getAttr('fname') != $_POST['fname']) {
            $data['fname'] = Input::get("fname");

        }

        //Update last name

        if ($session_user->getAttr('lname') != $_POST['lname']) {
            $data['lname'] = Input::get("lname");

        }

        //Update email
        if ($session_user->getAttr('email') != $_POST['email']) {
            $data['email'] = Input::get("email");

//            if ((new MailSettings())->getSettings()['email_act'] = 1) {
//                $fields['email_verified'] = 0;
//            }

        }
        $msgs = new Messages();
        if (!empty($_POST['password'])) {
            if (!password_verify(Input::get('old'), $session_user->getAttr('password'))) {
                $msgs->posa_error('El password antiguo introducido és incorrecto');

            } else {
                $passValidator = new Validate();
                $passValidator->check($_POST, array(
                    'old' => array(
                        'display' => 'Old Password',
                        'required' => true,
                    ),
                    'password' => array(
                        'display' => 'New Password',
                        'required' => true,
                        'min' => 6,
                    ),
                    'confirm' => array(
                        'display' => 'Confirm New Password',
                        'required' => true,
                        'matches' => 'password',
                    ),
                ));

                $msgs->merge($passValidator->getMissatges());
                if ($passValidator->passed()) {
                    $data['password'] = User::pass_hash(Input::get('password'));
                }
            }
        }
        if (empty($data)) {
            return new User($session_user->getData());
        }

        $user = new User($data);
        $user->update();

        return $user;

    }
}

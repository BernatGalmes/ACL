<?php
/**
 * Created by IntelliJ IDEA.
 * User: bernat
 * Date: 1/04/17
 * Time: 11:08
 */

namespace System;


use BD\AccesBD;
use PhpGene\Input;

class System
{
    /**
     * @param $mail MailSettings
     */
    public static function page_mailSettings($mail)
    {
        if (!empty($_POST)) {

            $mail->updateSMTP(Input::get('smtp_server'));
            $mail->updateWebSite(Input::get('website_name'));
            $mail->updateSmtp_port(Input::get('smtp_port'));
            $mail->updateMailLogin(Input::get('email_login'));
            $mail->updateMailPass(Input::get('email_pass'));
            $mail->updateFromName(Input::get('from_name'));
            $mail->updateFromMail(Input::get('from_email'));
            $mail->updateTransport(Input::get('transport'));
            $mail->updateVerifyUrl(Input::get('verify_url'));
            $mail->updateEmail_act(Input::get('email_act'));

            $mail->saveChanges();
        }
    }


    public static function delete_user_online()
    {
        $db = AccesBD::getInstance();
        $timeout = 86400; //30 minutes - This can be changed
        $timestamp = time();
        $delete = $db->query("DELETE FROM users_online WHERE timestamp < ($timestamp - $timeout)");
    }


    public static function count_users()
    {
        $db = AccesBD::getInstance();
        $timestamp = time();
        $timeout = 1800; //30 minutes - This can be changed
        $selectAll = $db->query("SELECT * FROM users_online WHERE timestamp > ($timestamp-$timeout)");
        return count($selectAll);
    }


    public static function new_user_online($user_id)
    {
        $db = AccesBD::getInstance();
        $ip = self::ipCheck();
        $timestamp = time();
        $checkUserId = $db->query("SELECT * FROM users_online WHERE user_id = ?", array($user_id));
        $countUserId = count($checkUserId);

        if ($countUserId == 0) {
            $fields = array('timestamp' => $timestamp, 'ip' => $ip, 'user_id' => $user_id);
            $db->abd_insertItem('users_online', $fields);
        } else {
            if ($user_id == 0) {
                $fields = array('timestamp' => $timestamp, 'ip' => $ip, 'user_id' => $user_id);
                $checkQ = $db->query("SELECT id FROM users_online WHERE user_id = 0 AND ip = ?", array($ip));
                if (count($checkQ) == 0) {
                    $db->abd_insertItem('users_online', $fields);
                } else {
                    $to_update = $checkQ[0];
                    $db->abd_updateItem('users_online', $to_update['id'], $fields);
                }
                $to_update = $checkQ[0];
                $db->abd_updateItem('users_online', $to_update['id'], $fields);
            } else {
                $fields = array('timestamp' => $timestamp, 'ip' => $ip, 'user_id' => $user_id);
                $checkQ = $db->query("SELECT id FROM users_online WHERE user_id = ?", array($user_id));
                $to_update = $checkQ[0];
                $db->abd_updateItem('users_online', $to_update['id'], $fields);
            }
        }
    }


    private static function ipCheck()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: bernat
 * Date: 31/03/17
 * Time: 21:55
 */

namespace PHPACL;

use PhpGene\xml_data;

class MailSettings extends xml_data
{

    const XML_FILE = PATH_FILE_SETTINGS;

    private $mail_node;
    private $smtp_server;

    function __construct()
    {
        parent::__construct(self::XML_FILE);
        $this->mail_node = $this->dom->mail;

    }

    public function updateWebSite($newVal)
    {
        if ($this->mail_node->websiteName != $newVal) {
            $this->mail_node->websiteName = $newVal;
        }
    }

    public function updateSMTP($newVal)
    {
        if ($this->mail_node->smtp_server != $newVal) {
            $this->mail_node->smtp_server = $newVal;
        }
    }

    public function updateSmtp_port($newVal)
    {
        if ($this->mail_node->smtp_port != $newVal) {
            $this->mail_node->smtp_port = $newVal;
        }
    }

    public function updateMailLogin($newVal)
    {
        if ($this->mail_node->username != $newVal) {
            $this->mail_node->username = $newVal;
        }
    }

    public function updateMailPass($newVal)
    {
        if ($this->mail_node->password != $newVal) {
            $this->mail_node->password = $newVal;
        }
    }

    public function updateFromName($newVal)
    {
        if ($this->mail_node->from_name != $newVal) {
            $this->mail_node->from_name = $newVal;
        }
    }

    public function updateFromMail($newVal)
    {
        if ($this->mail_node->from_mail != $newVal) {
            $this->mail_node->from_mail = $newVal;
        }
    }

    public function updateTransport($newVal)
    {
        if ($this->mail_node->transport != $newVal) {
            $this->mail_node->transport = $newVal;
        }
    }

    public function updateVerifyUrl($newVal)
    {
        if ($this->mail_node->verify_url != $newVal) {
            $this->mail_node->verify_url = $newVal;
        }
    }

    public function updateEmail_act($newVal)
    {
        if ($this->mail_node->email_act != $newVal) {
            $this->mail_node->email_act = $newVal;
        }
    }


    public function getSettings()
    {
        return $this->dom->mail;
    }

}


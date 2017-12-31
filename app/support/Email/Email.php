<?php
// +----------------------------------------------------------------------
// | Email.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Support\Email;

use Phalcon\Config;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected static $_instance;

    protected $email;

    protected $password;

    protected $name;

    protected $host;

    protected $port;

    protected $target;

    protected $subject;

    protected $body;

    private function __construct()
    {
        /** @var Config $config */
        $config = di('app')->email;
        $validator = new EmailValidator();
        if ($validator->validate($config->toArray())->valid()) {
            throw new EmailException($validator->getErrorMessage());
        }

        $this->email = $config->email;
        $this->password = $config->password;
        $this->name = $config->name;
        $this->host = $config->host;
        $this->port = $config->port;
    }

    public static function getInstance()
    {
        if (isset(static::$_instance) && static::$_instance instanceof Email) {
            return static::$_instance;
        }
        return static::$_instance = new static();
    }

    public function addTarget($email, $name = null)
    {
        if (!isset($name)) {
            $name = $email;
        }

        $this->target[] = ['email' => $email, 'name' => $name];
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @desc   发送邮件
     * @author limx
     * @param       $subject 邮件标题
     * @param       $body    邮件内容
     * @param array $target  接收人邮箱
     */
    public function send($subject = null, $body = null)
    {
        if (isset($subject)) {
            $this->subject = $subject;
        }

        if (isset($body)) {
            $this->body = $body;
        }

        $mail = new PHPMailer();

        $mail->SMTPDebug = 0;                                   // Enable verbose debug output

        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->Host = $this->host;                              // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = $this->email;                         // SMTP username
        $mail->Password = $this->password;                      // SMTP password
        $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $this->port;                              // TCP port to connect to

        $mail->setFrom($this->email, $this->name);
        foreach ($this->target as $item) {
            $mail->addAddress($item['email'], $item['name']);   // Add a recipient
        }

        $mail->isHTML(true);                              // Set email format to HTML

        $mail->Subject = $this->subject;
        $mail->Body = $this->body;

        if (!$mail->send()) {
            throw new EmailException('Mailer Error: ' . $mail->ErrorInfo);
        }
        // 发送成功，清空发送列表=
        $this->afterEmailSend();
        return true;
    }

    protected function afterEmailSend()
    {
        $this->target = null;
        $this->subject = null;
        $this->body = null;
    }

}
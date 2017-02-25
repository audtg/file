<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '\PHPMailer\PHPMailerAutoload.php';
class MyMailer
    extends \PHPMailer
{
    public $username;
    public $href;
    public $date;
    public function sendMail()
    {
        date_default_timezone_set('Etc/UTC');
        $this->isSMTP();
        $this->SMTPDebug = 0;
        $this->Debugoutput = 'html';
        $this->Host = 'smtp.mail.ru';
        $this->Port = 465;
        $this->SMTPSecure = 'ssl';
        $this->SMTPAuth = true;
        $this->Username = 'tosendmail@mail.ru';
        $this->Password = 'Sending_10';
        $this->setFrom('tosendmail@mail.ru');
//        $this->Subject = 'Change password';
//        $this->msgHTML('<!DOCTYPE html>
//<html>
//<head lang="en">
//    <meta charset="UTF-8">
//    <title></title>
//</head>
//<body>
//    <h4>Здравствуйте, ' . $this->username . '!</h4>
//    <h4>Ваша ссылка для смены пароля: ' . $this->href . '</h5>
//    <h5>Ссылка создана ' . $this->date . '. Срок действия ссылки 20 минут.</h6>
//</body>
//</html>');
        return $this->send();
    }
}
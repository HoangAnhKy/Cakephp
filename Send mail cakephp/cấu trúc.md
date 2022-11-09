# Cài đặt phpmailer

```sh
    composer require phpmailer/phpmailer
```

# Tạo component để gửi mail

```php
<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require ROOT . '/vendor/phpmailer/phpmailer/src/Exception.php';
require ROOT . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require ROOT . '/vendor/phpmailer/phpmailer/src/SMTP.php';

class EmailComponent extends Component
{
    public function send_mail($to, $subject, $message)
    {
        $sender = "admin@gmail.com";
        $header = "X-Mailer: PHP/" . phpversion() . "Return-Path: $sender";

        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = env('HOST_GMAIL', 'smtp.gmail.com');
        $mail->SMTPAuth = true;
        $mail->Username = 'miecute0509@gmail.com';
        $mail->Password = 'jvpnyoffuvkdjeiw';
        $mail->SMTPSecure = 'TLS';
        $mail->Port = 587;


        $mail->SMTPOptions = array(
            'tsl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ),

            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ),

        );


        $mail->From = $sender;
        $mail->FromName = "From Me";

        $mail->AddAddress($to);
        $mail->isHTML(true);
        $mail->CreateHeader($header);
        $mail->Subject = $subject;
        $mail->Body = '<b> ' . nl2br($message) . '</b>';
        $mail->AltBody = nl2br($message);

        if (!$mail->Send()) {
            return array('error' => true, 'message' => 'Mailer Error: ' . $mail->ErrorInfo);
        } else {
            return array('error' => true, 'message' => 'Message Send!');
        }
    }
}

?>

```

# Tạo controller để điều hướng dữ liệu

```php
public function initialize()
    {
        $this->c_email = $this->loadComponent('Email');
    }
 public function sendmail()
    {
        $to = 'hoanganhkyltt@gmail.com';
        $subject = 'hi bro!';
        $message = 'hello you';
        try{
            $mail = $this->c_email->send_mail($to, $subject, $message);
        }catch (\Exception $e){
            echo 'can not send! ';
        }
        exit;
    }
```

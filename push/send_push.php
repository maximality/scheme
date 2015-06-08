<?php
date_default_timezone_set('Europe/Moscow');


//$push->setRootCertificationAuthority('entrust_root_certification_authority.pem');

require_once("classes/Login.php");
$login = new Login();

if ($login->isUserLoggedIn() == true) {
    require_once 'ApnsPHP/Autoload.php';
    $push = new ApnsPHP_Push(
        ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
        'vpi_apns_development.pem'
    );
    $push->setProviderCertificatePassphrase('maximka23');
    $messageText = $_POST['text'];

    $push->setWriteInterval(10 * 1000);

    $push->connect();

    include_once('../dbconfig.php');

    $STH = $DBH->prepare('SELECT user_apns_token FROM users WHERE user_apns_token IS NOT NULL');
    $STH->execute();
    $result = $STH->fetchAll();

    foreach ($result as $value) {
        $message = new ApnsPHP_Message($value['user_apns_token']);
        $message->setText($messageText);
        $message->setBadge(1);
        $push->add($message);
    }

    $push->send();

    $push->disconnect();

    echo 'true';
}
else
    echo 'false';

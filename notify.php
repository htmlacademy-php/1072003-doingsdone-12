<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once('vendor/autoload.php');
require_once('helpers.php');
require_once('functions.php');
require_once('connection.php');

/**
 * @var mysqli $con
 */

$login = 'keks@phpdemo.ru';
$pass = 'htmlacademy';
$dsn = 'smtp://4234:32434@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login';
$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);

$users = get_users_today_tasks($con);

$recipients = [];

foreach ($users as $user) {
    $recipients[$user['id']]['name'] = $user['name'];
    $recipients[$user['id']]['email'] = $user['email'];
    $recipients[$user['id']]['tasks'][] = [
        'title' => $user['title'],
        'deadline' => $user['dt_completion']
    ];
}

foreach ($recipients as $recipient) {
    $message = (new Email())
        ->subject("Уведомление от сервиса «Дела в порядке»")
        ->from('keks@phpdemo.ru')
        ->to($recipient['email']);

    $messageContent = "Уважаемый {$recipient['name']}! </br>";

    foreach ($recipient['tasks'] as $task) {
        $task['deadline'] = date('d.m.Y');
        $messageContent .= "У вас запланирована задача: {$task['title']} на {$task['deadline']} </br>";
    }

    $message->html($messageContent);

    try {
        $mailer->send($message);
        print("Рассылка для {$recipient['name']} успешно отправлена");
    } catch (Exception $e) {
        print("Не удалось отправить рассылку для {$recipient['name']}");
    }
}

<?php 

include 'config/mailconf.php';

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom("mail@budgetary.site", "Mail");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("kevinvong@rocketmail.com", "Kevin Vong");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid($sendgrid_api_key);
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}

?>
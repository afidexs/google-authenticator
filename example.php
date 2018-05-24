<?php

include "vendor/autoload.php";

if (isset($argv[1])) {
    $secretKey = $argv[1];
    echo "Secret passed in as argument!\n";
    echo "Your secret is ".$secretKey."\n";
} else {
    $secretFactory = new \Dolondro\GoogleAuthenticator\SecretFactory();
    $secret = $secretFactory->create("MyAwesomeWebCo", "Dolondro");
    $secretKey = $secret->getSecretKey();

    $qrImageGenerator = new \Dolondro\GoogleAuthenticator\QrImageGenerator\EndroidQrImageGenerator();
    //$qrImageGenerator = new \Dolondro\GoogleAuthenticator\QrImageGenerator\GoogleQrImageGenerator();

    echo "Your secret is: ".$secretKey."\n";
    file_put_contents(__DIR__."/example.html", "<img src='".$qrImageGenerator->generateUri($secret)."'>'");
    echo "Visit this URL: 'file://".__DIR__."/example.html' to view an image of your secret, and add it to your google authenticator app\n";
}

$googleAuthenticator = new \Dolondro\GoogleAuthenticator\GoogleAuthenticator();

while (true) {
    echo "Enter the code that has been given in the app:\n";
    $handle = fopen("php://stdin", "r");
    while (($code = trim(fgets($handle))) === "") {
        echo "Response required\n";
    }

    if ($googleAuthenticator->authenticate($secretKey, $code)) {
        echo "This code was valid!\n";
    } else {
        echo "This code was invalid =[\n";
    }

    echo "\n";
}

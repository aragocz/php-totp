<?php
    include "base32lib.php";
    $name = rawurlencode("");
    $label = rawurlencode("");
    $issuer = rawurlencode("");
    $length = 20;
    $secret = base32_encode(bin2hex(openssl_random_pseudo_bytes($length)));

    $uri = "otpauth://totp/$label:$name?secret=$secret&issuer=$issuer&algorithm=SHA1&digits=6&period=30";
    echo "URI: $uri\nSecret: $secret"
?>
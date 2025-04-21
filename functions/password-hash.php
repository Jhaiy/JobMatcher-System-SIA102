<?php 
    define('ENCRYPTION_KEY', 'the_elephant_in_the_room');
    define('ENCRYPTION_METHOD', 'AES-256-CBC');
    define('ENCRYPTION_IV', '1823761845747657');

    function encryption($string) {
        $string = (string) $string;
        $key = hash('sha256', ENCRYPTION_KEY);
        $initializationVector = ENCRYPTION_IV;
        return base64_encode(openssl_encrypt($string, ENCRYPTION_METHOD, $key, 0, $initializationVector));
    }

    function decryption($string) {
        $key = hash('sha256', ENCRYPTION_KEY);
        $initializationVector = ENCRYPTION_IV;
        return openssl_decrypt(base64_decode($string), ENCRYPTION_METHOD, $key, 0, $initializationVector);
    }
?>
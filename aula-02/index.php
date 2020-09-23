<?php

$keys = scandir('./chaves', 1);
$messages = scandir('./mensagens', 1);

foreach ($messages as $message_file_name) {
    $message_decrypted = null;
    $message = file_get_contents("./mensagens/{$message_file_name}");

    foreach ($keys as $key_file_name) {
        $encrypted = base64_decode($message);
        $key = file_get_contents("./chaves/{$key_file_name}");
        if (!$message_decrypted && $key && $encrypted) {
            if (openssl_private_decrypt($encrypted, $decrypted, $key)) {
                $message_decrypted = $decrypted;
                $file = fopen("./mensagens-descriptografadas/{$message_file_name}", 'w');
                fwrite($file, "{$message_decrypted} \n");
                fclose($file);
            }
        }
    }
}

echo 'Mensagens descriptografadas!';
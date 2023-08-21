<?php
\app\Application::singleton()->setConfig('application', [
    'hmac_secret' => 'da23e79ea4c4b38e19714f9a069940d789634baf', # hexdump -vn20 -e'5/4 "%08X" 1 "\n"' /dev/urandom | tr '[:upper:]' '[:lower:]'
    'development' => true,
    'encryption_key' => 'Please replace this text with anything', 
    'version' => '20230821',
]);

<?php

execute();

function execute()
{
    $names = get_names();
    for ($key = 0; array_key_exists($key, $names); $key++) {
        create_file($key, $names);
    }

    echo 'Pronto!';
}


function create_file($key, $names)
{
    $file_name = get_file_name($key);
    $origin = get_origin($key, $names);
    $message = get_message($key, $names);
    $destination = get_destination($key, $names);
    $hash = get_hash($message);

    $previous_message = get_message($key - 1, $names);
    $previous_hash = get_hash($previous_message) ? get_hash($previous_message) : 'Vazio';

    $file = fopen($file_name, 'w');
    fwrite($file, "Origem: {$origin} \n");
    fwrite($file, "Destino: {$destination} \n");
    fwrite($file, "Mensagem: {$message} \n");
    fwrite($file, "Hash: {$hash} \n");
    fwrite($file, "Hash anterior: {$previous_hash} \n");
    fclose($file);

    validate($key, $previous_hash);
}

function validate($key, $hash)
{
    if (($key - 1) >= 0) {
        $file_name = get_file_name($key - 1);
        $lines_array = file($file_name);

        $search_string = 'Hash: ';

        foreach ($lines_array as $line) {
            if (strpos($line, $search_string) !== false) {
                $previous_hash = str_replace($search_string, '', $line);
                $key = $key + 1;
                if (trim($hash) == trim($previous_hash)) {
                    echo "bloco_$key.txt - VALIDATED \n";
                } else {
                    echo "bloco_$key.txt - FAIL \n";
                }
            }
        }
    }
}

function get_names()
{
    return [
        'Chase',
        'Franklin',
        'Huynh',
        'England',
        'Lugo',
        'Rodrigues',
        'Betts',
        'Cummings',
        'Irwin',
        'Nixon',
        'Higgins',
        'Cook',
        'Ross',
        'Eaton',
        'Fountain'
    ];
}

function get_origin($key, $names)
{
    return array_key_exists($key, $names) ? $names[$key] : $key;
}

function get_destination($key, $names)
{
    return array_key_exists($key + 1, $names) ? $names[$key + 1] : $names[0];
}

function get_file_name($key)
{
    $block_number = $key + 1;
    return "./blocos/bloco_{$block_number}.txt";
}

function get_message($key, $names)
{
    if (array_key_exists($key, $names) && array_key_exists($key + 1, $names)) {
        return $message = "Ola {$names[$key + 1]}. Meu nome é {$names[$key]}.";
    }

    if (array_key_exists($key, $names)) {
        return $message = "Ola {$names[0]}. Meu nome é {$names[$key]}.";
    }
}

function get_hash($message)
{
    if ($message) {
        return hash('sha256', $message);
    }
}
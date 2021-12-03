<?php
function dd (...$vars) {
    foreach ($vars as $var) {
        print_r($var);
        echo PHP_EOL;
    }
    exit;
}

function base_path ($path = false) {
    if (!$path) {
        return BASE_PATH;
    }
    return BASE_PATH . "/$path";
}

<?php
require_once "vendor/autoload.php";
define("BASE_PATH", __DIR__);

// $generator->saveAs(__DIR__ . "/generated/" . 1 . ".jpg");
if (!isset($argv[1])) {
    dd("Error: Please provide path");
}
$path = $argv[1];
$lang = isset($argv[2]) ? $argv[2] : "en";
$dir  = pathinfo($path, PATHINFO_DIRNAME);
if (!file_exists($dir)) {
    dd("Error: directory $dir not found");
}
$generator = new \Gogabidzia\RandomQuote\Generator($lang);
$generator->saveAs($path);

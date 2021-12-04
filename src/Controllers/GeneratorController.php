<?php


namespace Gogabidzia\RandomQuote\Controllers;


use Gogabidzia\RandomQuote\Generator;

class GeneratorController {
    function __construct () {

    }

    public function index () {
        include base_path('views/index.php');
    }

    public function image () {
        $generator = new Generator();
        $photo     = $generator->generate();
        header("Content-Type", "image/jpeg");
        echo $photo->response();
    }
}

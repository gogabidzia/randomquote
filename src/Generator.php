<?php

namespace Gogabidzia\RandomQuote;

use GuzzleHttp\Client;
use Intervention\Image\ImageManager;
use Intervention\Image\Imagick\Font;
use Intervention\Image\Imagick\Shapes\RectangleShape;

class Generator {
    public $lang;

    function __construct ($lang = "en") {
        if (!in_array($lang, ['en', 'ka'])) {
            dd('Unsupported language');
        }
        $this->lang = $lang;
    }

    public function saveAs ($path) {
        $image = $this->generate($path);
        $image->save($path);
    }

    public function generate ($path) {
        $quote = $this->getRandomQuote();
        $photo = $this->getRandomPhoto();
        $name  = $this->getRandomName();
        $photo = $photo->resizeCanvas(1100, 1400, 'top-center', false, "000000");
        $photo->rectangle(0, 0, 1100, 38, function (RectangleShape $shape) {
            $shape->background("000000");
        });
        $photo->text($quote, 548, 1128, function (Font $font) {
            $font->file(base_path("assets/font/Anton-Regular.ttf"));
            $font->size(50);
            $font->color("FFFFFF");
            $font->align("center");
        });
        $photo->text($name, 1050, 1350, function (Font $font) {
            $font->file(base_path("assets/font/Nunito-SemiBoldItalic.ttf"));
            $font->size(40);
            $font->color("f6e58d");
            $font->align("right");
        });
        return $photo;
    }

    private function getRandomQuote () {
        $sessionId = $this->_get("https://inspirobot.me/api", [
            'getSessionID' => "1",
        ]);
        $res       = $this->_get("http://inspirobot.me/api", [
            'generateFlow' => "1",
            'sessionID'    => $sessionId,
        ]);
        $data      = json_decode($res, true);
        $q         = $this->filterFirst($data['data'], 'type', 'quote');
        if (strlen($q['text']) > 100 || strpos($q['text'], '[') !== false) {
            return $this->getRandomQuote();
        }
        $quoted = $q['text'];
        return $this->escapeQuote($quoted);
    }

    protected function getRandomPhoto () {
        $content = file_get_contents("https://thispersondoesnotexist.com/image");
        $manager = $this->getImageManager();
        return $manager
            ->make($content);
    }

    protected function getRandomName () {
        $res = json_decode($this->_get("https://randomuser.me/api", []), true);
        return $res['results'][0]['name']['first'] . " " . $res['results'][0]['name']['last'];
    }

    private function _get ($path, $data = []) {
        $qs       = http_build_query($data);
        $client   = new Client();
        $response = $client->get($path . "?" . $qs);
        return $response->getBody()->getContents();
    }

    private function filterFirst ($array, $key, $value) {
        foreach ($array as $item) {
            if ($item[$key] == $value) {
                return $item;
            }
        }
        return null;
    }

    private function getImageManager () {
        return new ImageManager(['driver' => 'imagick']);
    }

    private function escapeQuote ($q) {
        $withoutNewLine = join("", explode("\n", $q));
        $exploded       = explode(' ', $withoutNewLine);
        $len            = 0;
        $arr            = [];
        $index          = 0;
        foreach ($exploded as $item) {
            if (!isset($arr[$index])) {
                $arr[$index] = [];
            }
            $len           += strlen($item);
            $arr[$index][] = $item;
            if ($len > 35) {
                $index++;
                $len = 0;
            }
        }
        foreach ($arr as $key => $items) {
            $arr[$key] = join(' ', $items);
        }
        return join("\n", $arr);
    }
}

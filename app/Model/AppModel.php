<?php

declare(strict_types=1);

namespace App\Model;
use RuntimeException;
use Flight;

class AppModel
{
    // protected function getJsonDataContent(string $name): object
    // {
    //     $path = Flight::get("data.content.path") . "{$name}.json";

    //     if (!is_readable($path)) {
    //         throw new RuntimeException(
    //             "Content file not found or unreadable: {$name}.json"
    //         );
    //     }

    //     $json = file_get_contents($path);

    //     if (!json_validate($json)) {
    //         throw new RuntimeException(
    //             "Content file must contain a JSON object: {$name}.json"
    //         );
    //     }

    //     $content = json_decode(
    //         $json,
    //         false,
    //         512,
    //         JSON_THROW_ON_ERROR
    //     );

    //     return $content;
    // }
}

<?php

namespace AOP\App;

class File
{
    public static function get($file)
    {
        $streamContext = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        return file_get_contents($file, false, $streamContext);
    }

    public static function exists($file)
    {
        return file_exists($file);
    }

    public static function getJsonDecoded($file)
    {
        return json_decode(static::get($file));
    }
}

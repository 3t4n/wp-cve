<?php

namespace FluentSupport\Framework\Database\Orm\Casts;

use FluentSupport\Framework\Foundation\App;
use FluentSupport\Framework\Support\Collection;
use FluentSupport\Framework\Database\Orm\Castable;
use FluentSupport\Framework\Database\Orm\CastsAttributes;

class AsEncryptedCollection implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                if (isset($attributes[$key])) {
                    return new Collection(json_decode(App::make('encrypter')->decryptString($attributes[$key]), true));
                }

                return null;
            }

            public function set($model, $key, $value, $attributes)
            {
                if (! is_null($value)) {
                    return [$key => App::make('encrypter')->encryptString(json_encode($value))];
                }

                return null;
            }
        };
    }
}

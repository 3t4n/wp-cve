<?php

namespace Modular\SDK\Support;

use Modular\SDK\ModularClient;
use Modular\SDK\Objects\BaseObject;
use Modular\SDK\Objects\BaseObjectFactory;
use function Modular\ConnectorDependencies\collect;

class ApiHelper
{
    /**
     * @var ModularClient|null
     */
    public static ModularClient $sdk;

    /**
     * @var ApiHelper
     */
    private static ApiHelper $instance;

    /**
     * @var
     */
    private static $data;

    /**
     * @var array
     */
    private static $included;

    /**
     * @param ModularClient $sdk
     * @return string
     */
    public static function setSdk(ModularClient $sdk): ApiHelper
    {
        self::$sdk = $sdk;

        return self::getInstance();
    }

    /**
     * @param $data
     * @return ApiHelper
     */
    public static function setResponse($data): ApiHelper
    {
        self::$data = $data->data ?? $data;
        self::$included = collect($data->included ?? []);

        return self::getInstance();
    }

    /**
     * @return BaseObject[]|BaseObject
     * @throws \Exception
     */
    public static function parser()
    {
        $data = self::$data;

        return self::convertToObject($data);
    }

    /**
     * @return static
     */
    private static function getInstance(): ApiHelper
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param $item
     * @return mixed
     * @throws \Exception
     */
    private static function convertToObject($item)
    {
        if (is_array($item)) {
            $item = collect($item)->map(function ($item) {
                return self::transformIntoObject($item);
            });
        } else {
            $item = self::transformIntoObject($item);
        }

        return $item;
    }

    /**
     * @param $item
     * @return mixed
     * @throws \Exception
     */
    private static function transformIntoObject($item)
    {
        $type = self::getType($item);

        $class = BaseObjectFactory::getObjectClass($type);

        if (!$class) {
            // TODO Custom Exceptions
            throw new \Exception('Undefined object class for: ' . $type);
        }

        $attrs = BaseObjectFactory::parseAttributes($item);
        $relations = self::mapRelations($item->relationships ?? new \stdClass());

        /**
         * @var BaseObject $class
         */
        $class = new $class;
        $class->setSdk(self::$sdk);
        $class->setAttributes($attrs);
        $class->setRelations($relations);

        return $class;
    }

    /**
     * @param $object
     * @return null|string
     */
    private static function getType($object)
    {
        if (isset($object->type)) {
            return $object->type;
        } else if (isset($object->expires_in)) {
            return 'oauth_token';
        }

        return null;
    }

    /**
     * @param $relations
     * @return mixed
     */
    private static function mapRelations($relations)
    {
        $relations = (array)$relations;

        foreach ($relations as $name => $data) {
            $data = $data->data;

            if (!is_array($data)) {
                $relations[$name] = self::findObject($data);
            } else {
                $relations[$name] = collect($data)
                    ->map(function ($item) {
                        return self::findObject($item);
                    });
            }
        }

        return $relations;
    }

    /**
     * @param $data
     * @return string
     */
    private static function findObject($data)
    {
        $data = self::$included->filter(function ($o) use ($data) {
            return $o->type === $data->type && $o->id === $data->id;
        })->first();

        return self::convertToObject($data);
    }
}

<?php

namespace Modular\SDK\Objects;

use Modular\ConnectorDependencies\Carbon\CarbonInterface;
use Modular\ConnectorDependencies\Illuminate\Contracts\Support\Arrayable;
use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\Casts\Attribute;
use Modular\ConnectorDependencies\Illuminate\Support\Carbon;
use Modular\ConnectorDependencies\Illuminate\Support\Collection as BaseCollection;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Date;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use Modular\SDK\ModularClient;

class BaseObject extends BaseObjectFactory implements \JsonSerializable, \ArrayAccess
{
    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = true;

    /**
     * The cache of the mutated attributes for each class.
     *
     * @var array
     */
    protected static $mutatorCache = [];

    /**
     * The cache of the "Attribute" return type marked mutated attributes for each class.
     *
     * @var array
     */
    protected static $attributeMutatorCache = [];

    /**
     * The built-in, primitive cast types supported by Eloquent.
     *
     * @var string[]
     */
    protected static $primitiveCastTypes = [
        'array',
        'bool',
        'boolean',
        'collection',
        'date',
        'datetime',
        'decimal',
        'double',
        'float',
        'int',
        'integer',
        'json',
        'object',
        'real',
        'string',
        'timestamp',
    ];

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * The loaded relationships for the model.
     *
     * @var array
     */
    protected array $relations = [];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [];

    /**
     * @var ModularClient
     */
    protected ModularClient $sdk;

    /**
     * @param ModularClient $sdk
     * @return $this
     */
    public function setSdk(ModularClient $sdk)
    {
        $this->sdk = $sdk;

        return $this;
    }

    /**
     * @param \stdClass $values
     * @return $this
     */
    public function setAttributes(\stdClass $values)
    {
        $this->attributes = $this->parseAttribute((array)$values);

        return $this;
    }

    /**
     * @param array $attrs
     * @return array
     */
    public function parseAttribute(array $attrs)
    {
        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependent upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        foreach ($attrs as $key => $value) {
            if ($this->hasCast($key)) {
                $attrs[$key] = $this->castAttribute($key, $value);
            }
        }

        return $attrs;
    }

    /**
     * @param $relations
     * @return $this
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param string $key
     * @param array|string|null $types
     * @return bool
     */
    public function hasCast($key, $types = null)
    {
        if (array_key_exists($key, $this->getCasts())) {
            return $types ? in_array($this->getCastType($key), (array)$types, true) : true;
        }

        return false;
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        return $this->casts;
    }

    /**
     * Get the type of cast for a model attribute.
     *
     * @param string $key
     * @return string
     */
    protected function getCastType($key)
    {
        return trim(strtolower($this->getCasts()[$key]));
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        $castType = $this->getCastType($key);

        if (is_null($value) && in_array($castType, static::$primitiveCastTypes)) {
            return $value;
        }

        switch ($castType) {
            case 'int':
            case 'integer':
                return (int)$value;
            case 'real':
            case 'float':
            case 'double':
                return $this->fromFloat($value);
            case 'decimal':
                return $this->asDecimal($value, explode(':', $this->getCasts()[$key], 2)[1]);
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                return (bool)$value;
            case 'object':
                return $this->fromJson($value, true);
            case 'array':
            case 'json':
                return $this->fromJson($value);
            case 'collection':
                return new BaseCollection($this->fromJson($value));
            case 'date':
                return $this->asDate($value);
            case 'datetime':
            case 'custom_datetime':
                return $this->asDateTime($value);
            case 'immutable_date':
                return $this->asDate($value)->toImmutable();
            case 'immutable_custom_datetime':
            case 'immutable_datetime':
                return $this->asDateTime($value)->toImmutable();
            case 'timestamp':
                return $this->asTimestamp($value);
        }

        return $value;
    }

    /** Decode the given float.
     *
     * @param mixed $value
     * @return mixed
     */
    public function fromFloat($value)
    {
        switch ((string)$value) {
            case 'Infinity':
                return INF;
            case '-Infinity':
                return -INF;
            case 'NaN':
                return NAN;
            default:
                return (float)$value;
        }
    }

    /**
     * Return a decimal as string.
     *
     * @param float $value
     * @param int $decimals
     * @return string
     */
    protected function asDecimal($value, $decimals)
    {
        return number_format($value, $decimals, '.', '');
    }

    /**
     * Decode the given JSON back into an array or object.
     *
     * @param string $value
     * @param bool $asObject
     * @return mixed
     */
    public function fromJson($value, $asObject = false)
    {
        return json_decode($value, !$asObject);
    }

    /**
     * Return a timestamp as DateTime object with time set to 00:00:00.
     *
     * @param mixed $value
     * @return \Illuminate\Support\Carbon
     */
    protected function asDate($value)
    {
        return $this->asDateTime($value)->startOfDay();
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param mixed $value
     * @return \Illuminate\Support\Carbon
     */
    protected function asDateTime($value)
    {
        // If this value is already a Carbon instance, we shall just return it as is.
        // This prevents us having to re-instantiate a Carbon instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof CarbonInterface) {
            return Date::instance($value);
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof \DateTimeInterface) {
            return Date::parse(
                $value->format('Y-m-d H:i:s.u'), $value->getTimezone()
            );
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return Date::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Carbon instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting Carbonized conversion.
        if ($this->isStandardDateFormat($value)) {
            return Date::instance(Carbon::createFromFormat('Y-m-d', $value)->startOfDay());
        }

        $format = $this->getDateFormat();

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the Carbon object
        // that is returned back out to the developers after we convert it here.
        try {
            $date = Date::createFromFormat($format, $value);
        } catch (\InvalidArgumentException $e) {
            $date = false;
        }

        return $date ?: Date::parse($value);
    }

    /**
     * Determine if the given value is a standard date format.
     *
     * @param string $value
     * @return bool
     */
    protected function isStandardDateFormat($value)
    {
        return preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value);
    }

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Return a timestamp as unix timestamp.
     *
     * @param mixed $value
     * @return int
     */
    protected function asTimestamp($value)
    {
        return $this->asDateTime($value)->getTimestamp();
    }

    /**
     * Convert a DateTime to a storable string.
     *
     * @param mixed $value
     * @return string|null
     */
    public function fromDateTime($value)
    {
        return empty($value) ? $value : $this->asDateTime($value)->format(
            $this->getDateFormat()
        );
    }

    /**
     * Get magic attribute
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->attributes[$name] ?? ($this->relations[$name] ?? null);
    }

    /**
     * Set magic attribute
     *
     * @param string $property
     * @param $value
     */
    public function __set(string $property, $value)
    {
        if (array_key_exists($property, $this->attributes)) {
            $this->attributes[$property] = $value;
        }
    }

    /**
     * @return mixed
     */
    public function save()
    {
        $alias = $this->getObjectAlias(get_class($this));

        if (isset($this->attributes['id'])) {
            return $this->sdk->{$alias}->update($this->attributes['id'], $this->attributes);
        }

        return $this->sdk->{$alias}->create($this->attributes);
    }

    /**
     * Transform to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Transform to array
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->attributesToArray();
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = $this->attributes;

        $attributes = $this->addMutatedAttributesToArray(
            $attributes, $this->getMutatedAttributes()
        );

        // Here we will grab all of the appended, calculated attributes to this model
        // as these attributes are not really in the attributes array, but are run
        // when we need to array or JSON the model for convenience to the coder.
        foreach ($this->getArrayableAppends() as $key) {
            $attributes[$key] = $this->mutateAttributeForArray($key, null);
        }

        return $attributes;
    }

    /**
     * Add the mutated attributes to the attributes array.
     *
     * @param array $attributes
     * @param array $mutatedAttributes
     * @return array
     */
    protected function addMutatedAttributesToArray(array $attributes, array $mutatedAttributes)
    {
        foreach ($mutatedAttributes as $key) {
            // We want to spin through all the mutated attributes for this model and call
            // the mutator for the attribute. We cache off every mutated attributes so
            // we don't have to constantly check on attributes that actually change.
            if (!array_key_exists($key, $attributes)) {
                continue;
            }

            // Next, we will call the mutator for this attribute so that we can get these
            // mutated attribute's actual values. After we finish mutating each of the
            // attributes we will return this final array of the mutated attributes.
            $attributes[$key] = $this->mutateAttributeForArray(
                $key, $attributes[$key]
            );
        }

        return $attributes;
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function mutateAttributeForArray($key, $value)
    {
        $value = $this->mutateAttribute($key, $value);

        return $value instanceof Arrayable ? $value->toArray() : $value;
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * Get the mutated attributes for a given instance.
     *
     * @return array
     */
    public function getMutatedAttributes()
    {
        $class = static::class;

        if (!isset(static::$mutatorCache[$class])) {
            static::cacheMutatedAttributes($class);
        }

        return static::$mutatorCache[$class];
    }

    /**
     * Extract and cache all the mutated attributes of a class.
     *
     * @param string $class
     * @return void
     */
    public static function cacheMutatedAttributes($class)
    {
        static::$attributeMutatorCache[$class] =
            collect($attributeMutatorMethods = static::getAttributeMarkedMutatorMethods($class))
                ->mapWithKeys(function ($match) {
                    return [lcfirst(static::$snakeAttributes ? Str::snake($match) : $match) => true];
                })->all();

        static::$mutatorCache[$class] = collect(static::getMutatorMethods($class))
            ->merge($attributeMutatorMethods)
            ->map(function ($match) {
                return lcfirst(static::$snakeAttributes ? Str::snake($match) : $match);
            })->all();
    }

    /**
     * Get all of the "Attribute" return typed attribute mutator methods.
     *
     * @param mixed $class
     * @return array
     */
    protected static function getAttributeMarkedMutatorMethods($class)
    {
        $instance = is_object($class) ? $class : new $class;

        return collect((new \ReflectionClass($instance))->getMethods())->filter(function ($method) use ($instance) {
            $returnType = $method->getReturnType();

            if ($returnType &&
                $returnType instanceof \ReflectionNamedType &&
                $returnType->getName() === Attribute::class) {
                $method->setAccessible(true);

                if (is_callable($method->invoke($instance)->get)) {
                    return true;
                }
            }

            return false;
        })->map->name->values()->all();
    }

    /**
     * Get all of the attribute mutator methods.
     *
     * @param mixed $class
     * @return array
     */
    protected static function getMutatorMethods($class)
    {
        preg_match_all('/(?<=^|;)get([^;]+?)Attribute(;|$)/', implode(';', get_class_methods($class)), $matches);

        return $matches[1];
    }

    /**
     * Get all of the appendable values that are arrayable.
     *
     * @return array
     */
    protected function getArrayableAppends()
    {
        if (!count($this->appends)) {
            return [];
        }

        return $this->appends;
    }

    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Encode the given value as JSON.
     *
     * @param mixed $value
     * @return string
     */
    protected function asJson($value)
    {
        return json_encode($value);
    }
}

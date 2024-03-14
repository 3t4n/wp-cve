<?php

namespace FluentSupport\App\Models;

use FluentSupport\App\Models\Traits\AgentTrait;

class Agent extends Person
{
    use AgentTrait;

    protected static $type = 'agent';

    protected $searchable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'address_line_1',
        'address_line_2',
        'country'
    ];

    /**
     * @return array
     */
    public function getSearchableFields(){
        return $this->searchable;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->person_type = static::$type;
            $model->hash = md5(time().wp_generate_uuid4());
        });

        static::addGlobalScope(function ($builder) {
            $builder->where('person_type', 'agent');
        });
    }


    /**
     * One2Many: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function tickets()
    {
        $foreign_key = self::$type . '_id';

        $class = __NAMESPACE__ . '\Ticket';

        return $this->hasMany(
            $class, $foreign_key, 'id'
        );
    }

}

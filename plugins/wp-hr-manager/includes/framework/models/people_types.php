<?php
namespace WPHR\HR_MANAGER\Framework\Models;

use WPHR\HR_MANAGER\Framework\Model;

class PeopleTypes extends Model {
    protected $table      = 'wphr_people_types';
    public $timestamps    = false;
    protected $fillable   = [ 'name' ];

    /**
     * Filter types by name
     *
     * @param  object  $query
     * @param  string  $name
     *
     * @return object
     */
    public function scopeName( $query, $name ) {
        return $query->where( 'name', '=', $name );
    }
}

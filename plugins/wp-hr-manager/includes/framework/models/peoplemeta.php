<?php
namespace WPHR\HR_MANAGER\Framework\Models;

use WPHR\HR_MANAGER\Framework\Model;

class Peoplemeta extends Model {
    protected $primaryKey = 'meta_id';
    protected $table      = 'wphr_peoplemeta';
    public $timestamps    = false;
    protected $fillable   = [ 'meta_key', 'meta_value' ];

    /**
     * Available CRM Meta
     *
     * @since 1.1.7
     *
     * @param object $query
     *
     * @return object Query Builder
     */
    public function scopeAvailableMeta( $query ) {
        $meta_keys = wphr_crm_get_contact_meta_fields();

        return $query->whereIn( 'meta_key', $meta_keys );
    }
}

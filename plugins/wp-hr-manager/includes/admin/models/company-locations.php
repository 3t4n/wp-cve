<?php
namespace WPHR\HR_MANAGER\Admin\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Company_Locations
 *
 * @package WPHR\HR_MANAGER\Admin\Models
 */
class Company_Locations extends Model {
    protected $table = 'wphr_company_locations';
    protected $fillable = [ 'name', 'address_1', 'address_2', 'city', 'state', 'zip', 'country', 'office_start_time', 'office_end_time', 'office_working_hours', 'office_timezone', 'office_financial_year_start', 'office_financial_day_start' ];
//    public $timestamps = false;


}



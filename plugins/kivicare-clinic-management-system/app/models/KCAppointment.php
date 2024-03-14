<?php


namespace App\models;

use App\baseClasses\KCBase;
use App\baseClasses\KCModel;

class KCAppointment extends KCModel {

	public function __construct()
	{
		parent::__construct('appointments');
	}

	public static function getDoctorAppointments($doctor_id) {
		return collect(( new self() )->get_by(['doctor_id' =>(int)$doctor_id]));
	}


    public static function appointmentPermissionUserWise($appointment){
        $appointment_detail = (new KCAppointment())->get_by(['id' => (int)$appointment],'=',true);
        $kcbase = (new KCBase());

        $login_user_role = $kcbase->getLoginUserRole();
        $permission = false;
        switch ($login_user_role){

            case $kcbase->getReceptionistRole():
                $clinic_id = kcGetClinicIdOfReceptionist();
                if(!empty($appointment_detail->clinic_id) && (int)$appointment_detail->clinic_id === $clinic_id ){
                    $permission = true;
                }
                break;
            case $kcbase->getClinicAdminRole():
                $clinic_id = kcGetClinicIdOfClinicAdmin();
                if(!empty($appointment_detail->clinic_id) && (int)$appointment_detail->clinic_id === $clinic_id ){
                    $permission = true;
                }
                break;
            case 'administrator':
                $permission = true;
                break;
            case $kcbase->getDoctorRole():
                if(!empty($appointment_detail->doctor_id) && (int)$appointment_detail->doctor_id === get_current_user_id() ){
                    $permission = true;
                }
                break;
            case $kcbase->getPatientRole():
                if(!empty($appointment_detail->patient_id) && (int)$appointment_detail->patient_id === get_current_user_id() ){
                    $permission = true;
                }
                break;
        }
        return $permission;
    }
}
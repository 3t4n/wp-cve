<?php


namespace App\models;

use App\baseClasses\KCModel;
class KCCustomFormData extends KCModel
{
    public function __construct()
    {
        parent::__construct('custom_form_data');
    }

    /**
     * Determine user permissions for a custom form based on module type.
     *
     * @param int $form_id   The ID of the custom form.
     * @param int $module_id The ID of the module.
     *
     * @return bool|array|null User permissions or false if not found.
     */
    public function customFormUserWisePermission($form_id, $module_id){
        // Get the form data based on form_id.
        $form_data = (new KCCustomForm())->get_by(['id' => (int)$form_id], '=', true);

        // Check if form_data or module_type is empty.
        if (empty($form_data) || empty($form_data->module_type)) {
            return false;
        }

        // Create objects for each module type.
        $kcAppointment = new KCAppointment();
        $kcPatientEncounter = new KCPatientEncounter();
        $kcUser = new KCUser();

        switch ($form_data->module_type) {
            case 'appointment_module':
                return $kcAppointment->appointmentPermissionUserWise($module_id);
            case 'patient_encounter_module':
                return $kcPatientEncounter->encounterPermissionUserWise($module_id);
            case 'doctor_module':
                return $kcUser->doctorPermissionUserWise($module_id);
            case 'patient_module':
                return $kcUser->patientPermissionUserWise($module_id);
            default:
                return false;
        }
    }

}
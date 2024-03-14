jQuery(document).ready(function($){

    $(document).on('click','#cgRegistrationSearchReset',function () {

        $('#cgUsersManagement').click();

    });

    $(document).on('click','#cg_create_user_data_csv_submit',function () {

        //e.preventDefault();// no prevent default here!!!!

        var $cgUsersManagementForm = $('#cgUsersManagementForm');
        $cgUsersManagementForm.removeClass('cg_load_backend_submit');
        $cgUsersManagementForm.find('#cg_create_user_data_csv_new_export').removeAttr('disabled');
        $cgUsersManagementForm.find('#cgRegistrationSearchSubmit').addClass('is_users_export').click();

        setTimeout(function () {
            $cgUsersManagementForm.find('#cg_create_user_data_csv_new_export').attr('disabled','disabled');
            $cgUsersManagementForm.addClass('cg_load_backend_submit');
            $(this).removeClass('cg_disabled_no_pointer_events');
        },100)

    });

    $(document).on('click','#cg_input_image_upload_file_to_delete_button',function () {
        $(this).remove();
        $('#cg_input_image_upload_file_preview').remove();
        $('#cg_input_image_upload_file_to_delete_wp_id').prop('disabled',false);
        $('#cg_profile_image_removed').removeClass('cg_hide');
    });

});
<script type="text/javascript">
    function custom_login_change() {
        var checked = jQuery('#http_basic_auth_settings\\[custom_login\\]').is(':checked');
        if ( checked ) {
            jQuery('#http_basic_auth_settings\\[login\\]').closest('tr').prev().show();
            jQuery('#http_basic_auth_settings\\[login\\]').closest('tr').show();
            jQuery('#http_basic_auth_settings\\[password\\]').closest('tr').show();
            jQuery('#http_basic_auth_settings\\[login\\]').prop('required',true);
            jQuery('#http_basic_auth_settings\\[password\\]').prop('required',true);
        }
        else {
            jQuery('#http_basic_auth_settings\\[login\\]').closest('tr').prev().hide();
            jQuery('#http_basic_auth_settings\\[login\\]').closest('tr').hide();
            jQuery('#http_basic_auth_settings\\[password\\]').closest('tr').hide();
            jQuery('#http_basic_auth_settings\\[login\\]').prop('required',false);
            jQuery('#http_basic_auth_settings\\[password\\]').prop('required',false);
        }
    }
	jQuery('#http_basic_auth_settings\\[password\\]').attr( 'autocomplete', 'new-password' );
	jQuery('#http_basic_auth_settings\\[custom_login\\]').change(function(){
	    custom_login_change();
    });
	jQuery(document).ready(function(){
        custom_login_change();
    });
</script>
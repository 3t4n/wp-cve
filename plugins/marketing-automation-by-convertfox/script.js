jQuery(document).ready(function($) {
   let identify_users = $('input[name="convertfox_settings[identify_users]"]');
   let identity_verify_users = $('input[name="convertfox_settings[identity_verify_users]"]');
   let identity_secret_key = $('input[name="convertfox_settings[identity_secret_key]"]');

   validateIdentifyUserFields();

   identify_users.on('change', function() {
      validateIdentifyUserFields();
   });

   identity_verify_users.on('change', function() {
      validateIdentifyUserFields();
   });

   function validateIdentifyUserFields() {
      if (identify_users.is(':checked')) {
         identity_verify_users.closest('tr').show();

         if (identity_verify_users.is(':checked')) {
            identity_secret_key.closest('tr').show();
         } else {
            identity_secret_key.closest('tr').hide();
         }
      } else {
         identity_verify_users.closest('tr').hide();
         identity_secret_key.closest('tr').hide();
      }
   }
});
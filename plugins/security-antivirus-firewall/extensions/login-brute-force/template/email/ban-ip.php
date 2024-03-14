<?php _e("Your website stay safe because S.A.F. - wpTools Security Report guard your website!", 'wptsaf_security'); ?>
<?php if(WPTSAF_PRO){ ?>
<?php _e("Make your website even more safe with S.A.F. Premium version http://wptools.co/security-antivirus-firewall/#pricing", 'wptsaf_security'); ?>
<?php } ?>
<?php _e("If you have some kind of problems or new features request feel free to contact us http://wptools.co/security-antivirus-firewall/#get-in-touch", 'wptsaf_security'); ?>

<?php _e("Brute Force Monitor", 'wptsaf_security'); ?>
-------------
<?php printf( __( 'Ip address %s was banned.', 'wptsaf_security' ), $ip ); ?>
<?php printf( __( 'User logins failed %s time during %s minutes.', 'wptsaf_security' ), $countFailedLogin,  $timeCounting ); ?>

============
<?php _e("How to turn off this notification?", 'wptsaf_security');?>
<?php _e("ust open admin section of the S.A.F.  plugin. Open Brute Force Monitor/Settings / First ban   and turn off Notify admin option.", 'wptsaf_security');?>
<?php
if(!function_exists('cg_create_google_options')){
    function cg_create_google_options($i){

        global $wpdb;
        $tablename_google_options = $wpdb->base_prefix . "$i"."contest_gal1ery_google_options";

        $selectSQLgoogleOptions = $wpdb->get_row( "SELECT * FROM $tablename_google_options WHERE GeneralID = '1'" );
        if(empty($selectSQLgoogleOptions)){

            $wpdb->query( $wpdb->prepare(
                "
					INSERT INTO $tablename_google_options
					( id, GeneralID,
					ClientId,ButtonTextOnLoad,
					ButtonStyle,BorderRadius,
					FeControlsStyle,TextBeforeGoogleSignInButton
					)
					VALUES (
					%s,%d,
					%s,%s,
					%s,%d,
					%s,%s
					)",
                '','1',
                '','Continue with Google',
                'bright',1,'white',''
            ) );

        }
        return $selectSQLgoogleOptions;
    }
}
if(!function_exists('cg_get_google_options')){
    function cg_get_google_options(){
        global $wpdb;

        $tablename_google_options = $wpdb->prefix . "contest_gal1ery_google_options";
        $selectSQLgoogleOptions = $wpdb->get_row( "SELECT * FROM $tablename_google_options WHERE GeneralID = '1'" );

        return $selectSQLgoogleOptions;
    }
}
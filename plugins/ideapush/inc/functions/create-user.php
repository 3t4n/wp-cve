<?php

// a common function to create a user
function idea_push_create_user_common($firstName,$lastName,$email,$password){
    
    
    $ipAddress = idea_push_get_ip_address();
    
    //for now we are going to force create the role
    remove_role( 'idea_push_guest' );
    add_role( 'idea_push_guest', 'IdeaPush Guest', array('read' => true, 'level_1' => true ));
        
    
    //sanitise and validate input
    $firstName = idea_push_sanitization_validation($firstName,'name'); 
    $lastName = idea_push_sanitization_validation($lastName,'name');
    $email = idea_push_sanitization_validation($email,'email');
    $password = idea_push_sanitization_validation($password,'name');
    
    if($firstName == false || $lastName == false || $email == false || $password == false){
        return 'The input is not valid';
    }
        
    $getUserCounter = idea_push_user_counter('user');
    
    $userdata = array(
        'user_login'  =>  strtolower($firstName).'-'.strtolower($lastName).'-'.$getUserCounter,
        'display_name'  =>  $firstName,
        'nickname'  =>  $firstName,
        'first_name'  =>  $firstName,
        'last_name'  =>  $lastName,
        'role'  =>  'idea_push_guest',
//        'role'  =>  'contributor',
        'description'  =>  'This user was created automatically by the IdeaPush plugin.',
        'user_email'  =>  $email,
        'user_pass'   =>  $password  // When creating an user, `user_pass` is expected.
    );

    $user_id = wp_insert_user($userdata);

    //if its an error, return false
    if(is_wp_error( $user_id ) ){
        return false;
    }

    //create session
    wp_set_auth_cookie($user_id,true);


    //now add ip meta
    add_user_meta($user_id, 'ip_address', $ipAddress); 
    
    return $user_id;

}






function idea_push_create_user(){
    
    $firstName = idea_push_sanitization_validation($_POST['firstName'],'name');
    $lastName = idea_push_sanitization_validation($_POST['lastName'],'name');
    $email = idea_push_sanitization_validation($_POST['email'],'email');
    $password = idea_push_sanitization_validation($_POST['password'],'name');
    
    if($firstName == false || $lastName == false || $email == false  || $password == false){
        wp_die(); 
    }
    
    
    echo idea_push_create_user_common($firstName,$lastName,$email,$password);
    
    wp_die();    
}

add_action( 'wp_ajax_create_user', 'idea_push_create_user' );
add_action( 'wp_ajax_nopriv_create_user', 'idea_push_create_user' );



?>
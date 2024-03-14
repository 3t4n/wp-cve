<?php

if ( ini_get( 'max_execution_time' ) <= 60 ) {
    ?>
    <div class="card shadow">
        <div class="card-body">
            <h5 class="card-title text-uppercase mb-4"><?php 
    echo  __( 'Warning', 'send-users-email' ) ;
    ?></h5>
            <div class="alert alert-warning" role="alert">
                <p><?php 
    echo  sprintf( __( 'Your PHP max execution time is %d seconds. Please consider increasing this limit if you are trying to send email to lots of users at once.', 'send-users-email' ), ini_get( 'max_execution_time' ) ) ;
    ?></p>
                <p><?php 
    echo  __( 'Consider sending email to users in batches. Email User feature allows you to filter users by ID range and can be used to send email in batches.', 'send-users-email' ) ;
    ?></p>
				<?php 
    ?>

            </div>
        </div>
    </div>
<?php 
}

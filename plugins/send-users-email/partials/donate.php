	<?php 

if ( rand( 1, 3 ) == 1 ) {
    ?>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-4 text-uppercase"><?php 
    echo  __( 'Help plugin development', 'send-users-email' ) ;
    ?></h5>
                <a href="https://www.buymeacoffee.com/smnbhattarai">
                    <img alt="donate"
                         src="https://img.buymeacoffee.com/button-api/?text=Donate&emoji=&slug=smnbhattarai&button_colour=FFDD00&font_colour=000000&font_family=Cookie&outline_colour=000000&coffee_colour=ffffff"/>
                </a>
            </div>
        </div>
	<?php 
} else {
    ?>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-4 text-uppercase"><?php 
    echo  __( 'Send Users Email PRO', 'send-users-email' ) ;
    ?></h5>
				<?php 
    echo  '<p class="card-text"><a class="btn btn-success" href="' . sue_fs()->get_upgrade_url() . '">' . __( 'Upgrade Now!', 'send-users-email' ) . '</a></p>' ;
    ?>
            </div>
        </div>
	<?php 
}

?>
	<?php 

if ( rand( 1, 3 ) == 1 ) {
    ?>
        <div class="card shadow">
            <div class="card-body">
                <p class="card-text text-uppercase" style="font-size: 1rem;">
                    <a style="text-decoration: none;" target="_blank"
                       href="https://wordpress.org/support/plugin/send-users-email/reviews/#new-post">
	                    <?php 
    echo  __( 'Show plugin some', 'send-users-email' ) ;
    ?> <span title="<?php 
    echo  __( 'Love', 'send-users-email' ) ;
    ?>"
                                               style="color: #ff0000; font-size: 1.3rem;">&hearts;</span> <?php 
    echo  __( 'by giving', 'send-users-email' ) ;
    ?>
                        <span title="<?php 
    echo  __( 'Five Star', 'send-users-email' ) ;
    ?>"
                              style="color: #ffb900; font-size: 1.3rem;">&starf;&starf;&starf;&starf;&starf;</span>
	                    <?php 
    echo  __( 'review', 'send-users-email' ) ;
    ?>.
                    </a>
                </p>
            </div>
        </div>
	<?php 
}

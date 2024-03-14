<?php 
	$tsseph_options = tsseph_init_new_vars($tsseph_options);
?>

<div class="wrap columns-2 dd-wrap tsseph">
    <div style="margin-bottom: 20px;">
		<img width="75" height="75" src="<?PHP echo plugins_url("../src/ePodaci-harok.png",__FILE__ );  ?>" style="vertical-align:middle;" alt="Slovenská pošta - ePodací hárok" itemprop="logo">
		<h1 style="display:inline-block;padding-left: 20px;"><?php _e( 'Slovenská pošta - ePodací hárok', 'spirit-eph' ) ?></h1>
	</div>	
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div id="side-info-column" class="inner-sidebar">

            <div class="postbox">
                <div class="inside">
                    <h3><?php _e('Autori', 'spirit-eph'); ?></h3>
                    <p><?php printf( wp_kses( __( 'Matej Podstrelenec & Ján Trgina <a href="%s" target="_blank" > thespirit.studio</a>.', 'spirit-eph'), array('a'=>array('href'=>array(), 'target' =>array() ))), esc_url("https://thespirit.studio")); ?></p>
                    <p><?php printf( wp_kses( __( 'Potrebujete pomôcť s WordPressom? <a href="%s" target="_blank" > Napíšte nám.</a>.', 'spirit-eph'), array('a'=>array('href'=>array(), 'target' =>array() ))), esc_url("mailto:matej.podstrelenec@gmail.com")); ?></p>
					<p></p>	
					<h3><?php _e('Nápad na vylepšenie', 'spirit-eph'); ?>?</h3>
                    <p><?php printf( wp_kses( __( 'Vaše postrehy nám <a href="%s" target="_blank" > prosím napíšte</a>. Zapracujeme ich.', 'spirit-eph'), array('a'=>array('href'=>array(), 'target' =>array() ))), esc_url("mailto:matej.podstrelenec@gmail.com")); ?></p>
					<h3><?php _e('Chcete podporiť vývoj', 'spirit-eph'); ?>?</h3>
                    <p><?php printf( wp_kses( __( 'Ak ste si plugin obľúbili, môžete jeho vývoj <a href="%s" target="_blank" > podporiť tu</a>.', 'spirit-eph'), array('a'=>array('href'=>array(), 'target' =>array() ))), esc_url("https://matejpodstrelenec.sk/podpora-vyvoja-pluginov/")); ?></p>
                </div>
            </div>
            <div class="postbox">
                <div class="inside">
                    <h3><?php _e('Bonus funkcie', 'spirit-eph'); ?></h3>
                    <p>Zakúpením bonusovej funkcionality podporíte ďalší vývoj pluginu 🙂</p>

                    <?php
                        echo tsseph_get_bonus($tsseph_bonus_options);
                    ?>

                </div>
            </div>            
        </div>        
        
        <div id="post-body">
            <div id="post-body-content">
                <form method="post" action="options.php">
                    <?php 
                        settings_fields( 'tsseph_settings_group' ); 
                        tsseph_get_settings($tsseph_options, $tsseph_bonus_options);
                    ?>
                    
                </form>
            </div>
        </div>
    </div>
</div>
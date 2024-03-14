<?php
defined( 'ABSPATH' ) or die();

class wl_companion_team_guardian
{
    
    public static function wl_companion_team_guardian_html() {
    ?>
    <div class="our-expert">
    <div class="container">
        <div class="text-center">
            <h2><?php echo get_theme_mod( 'guardian_team_title' ,'Our Expert Advisor' ); ?></h2>
        </div>
        <div class="row justify-content-center">
		<?php if ( ! empty ( get_theme_mod('guardian_team_data' ) ) ) { 
			$name_arr = unserialize(get_theme_mod( 'guardian_team_data'));
			foreach ( $name_arr as $key => $value ) { ?>
            <div class="col-lg-4 col-md-4 pb-30">
             <!-- Team Block -->
             <div class="team-block">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
						<?php if( ! empty ( $value['team_image'] ) ) { ?>
                        <div class="image">
                            <img src="<?php echo esc_url( $value['team_image'] ); ?>" alt="" />
                        </div>
						<?php } ?>
                        <div class="lower-content">
						<?php if ( ! empty ( $value['team_name'] ) ) { ?>
                            <h3> <?php echo $value['team_name']; ?> </h3>
						<?php } ?>
						<?php if ( ! empty ( $value['team_desc'] ) ) { ?>
                            <div class="designation1"><?php echo $value['team_desc']; ?> </div>
						<?php } ?>
                        </div>
                    </div>
                </div>
                <!-- Team Block -->
            </div>
		<?php } } else { ?>
		
		<?php $team_data = serialize( array(
            /*Repeater's first item*/
            array(
				'team_name' => 'Maria Rosi',
				'team_desc'      => 'Business Plann Expert',
				'team_image'       => get_template_directory_uri().'/images/team1.png' ,
				),
            /*Repeater's second item*/
            array(
				'team_name' => 'Maria Rosi',
				'team_desc'      => 'Business Plann Expert',
				'team_image'       => get_template_directory_uri().'/images/team2.png' ,
				),
            /*Repeater's third item*/
            array(
				'team_name' => 'Maria Rosi',
				'team_desc'      => 'Business Plann Expert',
				'team_image'       => get_template_directory_uri().'/images/team3.png' ,
				),
            ) );
			$name_arr = unserialize( $team_data );
			foreach ( $name_arr as $key => $value ) { ?>
            <div class="col-lg-4 col-md-4 pb-30">
             <!-- Team Block -->
             <div class="team-block">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
						<?php if( ! empty ( $value['team_image'] ) ) { ?>
                        <div class="image">
                            <img src="<?php echo esc_url( $value['team_image'] ); ?>" alt="" />
                        </div>
						<?php } ?>
                        <div class="lower-content">
						<?php if ( ! empty ( $value['team_name'] ) ) { ?>
                            <h3> <?php echo $value['team_name']; ?> </h3>
						<?php } ?>
						<?php if ( ! empty ( $value['team_desc'] ) ) { ?>
                            <div class="designation1"><?php echo $value['team_desc']; ?> </div>
						<?php } ?>
                        </div>
                    </div>
                </div>
                <!-- Team Block -->
            </div>
		<?php } } ?>
        </div>
    </div>
</div>
<?php } } ?>
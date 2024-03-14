<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the admin dashboard of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      1.1.1
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin/partials
 */

if ( 'dashboard' === $active_tab ) {
    $shortcodes = array( array(
        'code'  => 'card-oracle id=',
        'label' => __( 'Reading Shortcode', 'card-oracle' ),
    ), array(
        'code'  => 'card-oracle-daily id=',
        'label' => __( 'Daily Card Shortcode', 'card-oracle' ),
    ), array(
        'code'  => 'card-oracle-random id=',
        'label' => __( 'Random Card Shortcode', 'card-oracle' ),
    ) );
    ?>
	<div id="co_dashboard" class="card-oracle-dashboard-content">
		<div class="card-oracle-cards">
			<div class="card-oracle-card">
				<a class="stats-link" href="<?php 
    echo  esc_url( admin_url( 'edit.php?post_type=co_readings' ) ) ;
    ?>">
					<div class="card-oracle-card-header">
						<?php 
    esc_html_e( 'Readings', 'card-oracle' );
    ?>
					</div>
					<div class="card-oracle-card-body">
						<div class="count"><?php 
    echo  esc_html( $readings_text ) ;
    ?></div>
						<div class="card-oracle-icon-container dashicons dashicons-welcome-view-site"></div>
					</div>
				</a>
			</div>
			<div class="card-oracle-card">
				<a class="stats-link" href="<?php 
    echo  esc_url( admin_url( 'edit.php?post_type=co_positions' ) ) ;
    ?>">
					<div class="card-oracle-card-header">
						<?php 
    esc_html_e( 'Positions', 'card-oracle' );
    ?>
					</div>
					<div class="card-oracle-card-body">
						<div class="count"><?php 
    echo  esc_html( $positions_text ) ;
    ?></div>
						<div class="card-oracle-icon-container dashicons dashicons-editor-ol"></div>
					</div>
				</a>
			</div>
			<div class="card-oracle-card">
				<a class="stats-link" href="<?php 
    echo  esc_url( admin_url( 'edit.php?post_type=co_cards' ) ) ;
    ?>">
					<div class="card-oracle-card-header">
						<?php 
    esc_html_e( 'Cards', 'card-oracle' );
    ?>
					</div>
					<div class="card-oracle-card-body">
						<div class="count"><?php 
    echo  esc_html( $cards_text ) ;
    ?></div>
						<div class="card-oracle-icon-container dashicons dashicons-admin-page"></div>
					</div>
				</a>
			</div>
			<div class="card-oracle-card">
				<a class="stats-link" href="<?php 
    echo  esc_url( admin_url( 'edit.php?post_type=co_descriptions' ) ) ;
    ?>">
					<div class="card-oracle-card-header">
						<?php 
    esc_html_e( 'Descriptions', 'card-oracle' );
    ?>
					</div>
					<div class="card-oracle-card-body">
						<div class="count"><?php 
    echo  esc_html( $descriptions_text ) ;
    ?></div>
						<div class="card-oracle-icon-container dashicons dashicons-media-text"></div>
					</div>
				</a>
			</div>
		</div> <!-- Cards -->
		<?php 
    $reading_count = count( $readings );
    ?>
		<?php 
    
    if ( 0 < $reading_count ) {
        ?>
		<div class="card-oracle-dashboard">
			<span class="dashicons dashicons-chart-bar"></span>
			<h2><?php 
        esc_html_e( 'Reading Statistics', 'card-oracle' );
        ?></h2>
		</div>
		<div class="card-oracle-stats"> <!-- Statistics for each Reading -->
			<?php 
        for ( $i = 0 ;  $i < $reading_count ;  $i++ ) {
            //phpcs:ignore Generic.WhiteSpace.ScopeIndent
            $position_text = esc_html( sprintf(
                /* translators: %d is a number */
                _n(
                    '%d position',
                    '%d positions',
                    $reading_array[$i]->positions,
                    'card-oracle'
                ),
                number_format_i18n( $reading_array[$i]->positions )
            ) );
            $card_text = esc_html( sprintf(
                /* translators: %d is a number */
                _n(
                    '%d card',
                    '%d cards',
                    $reading_array[$i]->cards,
                    'card-oracle'
                ),
                number_format_i18n( $reading_array[$i]->cards )
            ) );
            $description_text = esc_html( sprintf(
                /* translators: %d is a number */
                _n(
                    '%d description',
                    '%d descriptions',
                    $reading_array[$i]->descriptions,
                    'card-oracle'
                ),
                number_format_i18n( $reading_array[$i]->descriptions )
            ) );
            $shortcode_name = sprintf( 'card-oracle-shortcodes-%d', $i );
            ?>
			<div class="card-oracle-stat">
				<div class="card-oracle-stat-header">
					<?php 
            echo  esc_html( $readings[$i]->post_title ) ;
            ?>
				</div>
				<div class="card-oracle-stat-body">
					<p><?php 
            echo  esc_html( $position_text ) ;
            ?></p>
					<p><?php 
            echo  esc_html( $card_text ) ;
            ?></p>
					<p><?php 
            echo  esc_html( $description_text ) ;
            ?></p>
					<?php 
            /* phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped */
            ?>
					<div class="card-oracle-modal-link">
						<a href="#card-oracle-open-modal-<?php 
            echo  esc_attr( $readings[$i]->ID ) ;
            ?>"><?php 
            esc_html_e( 'Reading Shortcodes', 'card-oracle' );
            ?></a>
					</div>
					<div id="card-oracle-open-modal-<?php 
            echo  esc_attr( $readings[$i]->ID ) ;
            ?>" class="card-oracle-modal-dialog">
						<div>
							<a href="#close" title="Close" class="card-oracle-modal-close">X</a>
							<h2>Tarot Card Oracle Shortcodes</h2>
							<?php 
            foreach ( $shortcodes as $shortcode ) {
                printf(
                    '<p class="card-oracle-shortcode-header">%1$s</p><input class="card-oracle-shortcode" id="copy%2$s size="24" value="[%3$s&quot;%2$s&quot;]"><button id="copy-action-btn" class="button" title="%4$s" value="[%3$s&quot;%2$s&quot;]"><img src="%5$s" alt="%6$s"></button>',
                    esc_html( $shortcode['label'] ),
                    esc_attr( $readings[$i]->ID ),
                    $shortcode['code'],
                    esc_attr__( 'Click to copy shortcode', 'card-oracle' ),
                    esc_url( CARD_ORACLE_CLIPPY ),
                    esc_attr__( 'Copy to clipboard', 'card-oracle' )
                );
            }
            ?>
						</div>
					</div>
					<?php 
            /* phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped */
            ?>
				</div>
			</div>
			<?php 
        }
        ?>
		</div> <!-- Statistics for each Reading -->
		<?php 
    }
    
    ?>
	</div> <!-- co_dashboard -->
<?php 
}

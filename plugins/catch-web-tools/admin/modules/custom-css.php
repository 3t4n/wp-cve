<?php
/**
 * @package Admin
 * @sub-package Admin Custom CSS Display
 */
 ?>
<?php include ( 'header.php' ); ?>
    <div id="customcss">
        <div class="content-wrapper">
            <div class="header">
                <h3><?php _e( 'Custom CSS', 'catch-web-tools' ); ?></h3>
            </div> <!-- .header -->
            <div class="content">
                <form method="post" action="options.php">
                	<?php settings_fields( 'custom-css-settings-group' ); ?>
    				<?php $settings	=	catchwebtools_get_options( 'catchwebtools_custom_css' ); ?>
    				<div class="option-container">
                        <h3 class="option-toggle option-active"><a href="#"><?php _e( 'Custom Css Settings', 'catch-web-tools' ); ?></a></h3>
                        <div class="option-content inside open">
                            <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php _e( 'Enter Custom Css', 'catch-web-tools' ); ?></th>
                                    <td>
                                    <?php
    								$text	=	( ! empty ( $settings ) ) ? esc_html( $settings ): '';
    								echo '<textarea cols="80" rows="7" name="catchwebtools_custom_css">' .$text. '</textarea>';
                                     echo '<p class="description">'. __( 'You can just add your Custom CSS and save, it will show up in the frontend head section. Leave it blank if it is not needed.', 'catch-web-tools' ) . '<p class="description">';
    								?>
                                    </td>
                                </tr>
                                 <tr>
                                    <th scope="row"><?php _e( 'CSS Tutorial from W3Schools.', 'catch-web-tools' ); ?></th>
                                    <td>
                                        <a class="button" href="<?php echo esc_url( __( 'http://www.w3schools.com/css/default.asp', 'catch-web-tools' ) ); ?>" title="<?php esc_attr_e( 'CSS Tutorial', 'catch-web-tools' ); ?>" target="_blank"><?php _e( 'Click Here to Read', 'catch-web-tools' );?></a>
                                    </td>
                                </tr>
                             </tbody>
                        </table>
                       		<?php submit_button('Save Changes'); ?>
                        </div>
                    </div>
                </form>
            </div><!-- .container -->
        </div><!-- .content-wrapper -->
    </div><!-- #customcss -->

<?php include ( 'main-footer.php' ); ?>

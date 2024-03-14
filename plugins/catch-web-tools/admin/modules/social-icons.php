<?php
/**
 * @package Admin
 * @sub-package Admin Social Icons Display
 */
?>
<?php include( 'header.php' ); ?>
	<div id="social">
		<div class="content-wrapper">
			<div class="header">
				<h3><?php _e( 'Social Icons', 'catch-web-tools' ); ?></h3>
			</div> <!-- .header -->
			<div class="content">
				<form method="post" action="options.php">
					<?php settings_fields( 'social-icons-group' ); ?>
					<?php $settings = catchwebtools_get_options( 'catchwebtools_social' ); ?>
					<div class="option-container">
						<h3 class="option-toggle option-active"><a href="#"><?php _e( 'Enable Social Icons Module', 'catch-web-tools' ); ?></a></h3>
						<div class="option-content inside open">
							<table class="form-table">
								<tbody>
									<tr>
										<th scope="row"><?php _e( 'Enable Social Icons Module', 'catch-web-tools' ); ?></th>

										<td>
											<?php
												$text = ( ! empty( $settings['status'] ) && $settings['status'] ) ? 'checked' : '';
												echo '<input type="checkbox" ' . $text . ' name="catchwebtools_social[status]" value="1"/>&nbsp;&nbsp;' . __( 'Check to Enable', 'catch-web-tools' );
												echo '<p class="description">'
											?>
											<ul>
												<li>
													<h4><?php _e( 'Shortcode', 'catch-web-tools' ); ?></h4>

													<?php _e( 'The shortcode', 'catch-web-tools' ); ?>
													<code>[catchthemes_social_icons]</code>
													<?php _e( '(in the Post/Page content) will enable Social Icons into the Page/Post.', 'catch-web-tools' ); ?>

													<h4><?php _e( 'Widget', 'catch-web-tools' ); ?></h4>

													<?php _e( 'Drag and drop CWT Social Icons Widget to any Sidebar for results.', 'catch-web-tools' ); ?>

													<h4><?php _e( 'In WordPress Template', 'catch-web-tools' ); ?></h4>

													<?php _e( 'If Catch Web Tools\' Social Icons is required in WordPress template, the following code can be used: ', 'catch-web-tools' ); ?>:

													<br/>

													<code>&lt;?php
														if ( function_exists( 'catchwebtools_social_icons' ) )
															catchwebtools_social_icons();
														?&gt;
													</code>

													<br/>

													<?php _e( 'OR', 'catch-web-tools' ); ?>

													<br/>

													<code>
														&lt;?php
															echo do_shortcode( '[catchthemes_social_icons]' );
														?&gt;
													</code>
												</li>
											</ul>
											<?php
											'</p>';
											?>
										</td>
									</tr>
								</tbody>
							</table>

							<?php submit_button( 'Save Changes' ); ?>
						</div>

						<h3 class="option-toggle"><a href="#"><?php _e( 'Social Icons Settings', 'catch-web-tools' ); ?></a></h3>
						<div class="option-content inside">
							<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><?php _e( 'Enable Social Icons Sidebar?', 'catch-web-tools' ); ?></th>
									<td>
										<a class="button" href="<?php echo admin_url( 'widgets.php' ); ?>" title="<?php esc_attr_e( ' Drag and Drop CWT Social Icons widget to any sidebar', 'catch-web-tools' ); ?>"><?php _e( ' Drag and Drop CWT Social Icons widget to any sidebar', 'catch-web-tools' ); ?></a>
									</td>
								</tr>

								<tr>
									<th scope="row"><?php _e( 'Social Icon Size (px)', 'catch-web-tools' ); ?></th>
									<td>
										<?php
											$text = ( ! empty( $settings['social_icon_size'] ) && $settings['social_icon_size'] != '' ) ? $settings['social_icon_size'] : '32';
											echo '<input type="number" id="catchwebtools_social_icon_size" name="catchwebtools_social[social_icon_size]" value="' . absint( $text ) . '"/>px';
										?>
									</td>
								</tr>

								<tr>
									<th scope="row"><?php _e( 'Enable Social Icon\'s Brand Color on', 'catch-web-tools' ); ?></th>

									<td>
										<?php
											echo '<select id="catchwebtools_social_icon_brand_color" name="catchwebtools_social[social_icon_brand_color]">';
												echo '<option value="" ' . selected( $settings['social_icon_brand_color'], 'icon', false ) . '>' . esc_html__( '--Select--', 'catch-web-tools' ) . '</option>';

												echo '<option value="hover"' . selected( $settings['social_icon_brand_color'], 'hover', false ) . '>' . esc_html__( 'hover', 'catch-web-tools' ) . '</option>';

												echo '<option value="hover-static"' . selected( $settings['social_icon_brand_color'], 'hover-static', false ) . '>' . esc_html__( 'hover and static', 'catch-web-tools' ) . '</option>';
											echo '</select>';
										?>
									</td>
								</tr>


								<tr id="catchwebtools_social_color_main">
									<th scope="row"><?php _e( 'Social Icon Color', 'catch-web-tools' ); ?></th>
									<td>
									<?php
									$text = ( ! empty( $settings['social_icon_color'] ) && $settings['social_icon_color'] != '' ) ? $settings['social_icon_color'] : '#504f4f';
									echo '<input type="text" id="catchwebtools_social_color" name="catchwebtools_social[social_icon_color]" value="' . esc_attr( $text ) . '" data-default-color="#504f4f" />';
									?>
									</td>
								</tr>

								<tr id="catchwebtools_social_color_hover_main">
									<th scope="row"><?php _e( 'Social Icon Hover Color', 'catch-web-tools' ); ?></th>
									<td>
									<?php
									$text = ( ! empty( $settings['social_icon_hover_color'] ) && $settings['social_icon_hover_color'] != '' ) ? $settings['social_icon_hover_color'] : '#504f4f';
									echo '<input type="text" id="catchwebtools_social_hover_color" name="catchwebtools_social[social_icon_hover_color]" value="' . esc_attr( $text ) . '" data-default-color="#504f4f" />';
									?>
									</td>
								</tr>

								<?php
									//Social Icon's Brand Color Options
									$social_icon_brand_color = $settings['social_icon_brand_color'];

									$class = ' class="catchwebtools-social';

								if ( 'hover' == $social_icon_brand_color ) {
									$class .= ' social-brand-hover';
								} elseif ( 'hover-static' == $social_icon_brand_color ) {
									$class .= ' social-brand-static';
								}
									$class .= '"';

									$catchwebtools_social_icons = catchwebtools_get_social_icons_list();

								foreach ( $catchwebtools_social_icons as $key => $value ) {
									?>
								<tr<?php echo $class; ?>>
									<th scope="row"><?php echo '<span class="genericon genericon-' . $key . '"></span>' . $value; ?></th>
									<td>
									<?php
										$text = ( ! empty( $settings[ $key ] ) && '' != $settings[ $key ] ) ? $settings[ $key ] : '';
										echo '<input type="text" size="80" name="catchwebtools_social[' . $key . ']" value="' . esc_attr( $text ) . '"/>';
									?>
									</td>
								</tr>
									<?php
								}
								?>
							 </tbody>
						</table>
							<?php submit_button( 'Save Changes' ); ?>
						</div>
					</div>
				</form>
			</div><!-- .content -->
		</div><!-- .content-wrapper -->
	</div><!-- #customcss -->

<?php include( 'main-footer.php' ); ?>

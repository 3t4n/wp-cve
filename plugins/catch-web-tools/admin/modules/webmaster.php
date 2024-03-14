<?php
/**
 * @package Admin
 * @sub-package Admin Webmaster Display
 */
 ?>

<?php include ( 'header.php' ); ?>
    <div id="webmaster">
        <div class="content-wrapper">
            <div class="header">
                <h3><?php _e( 'Webmaster Tools', 'catch-web-tools' ); ?></h3>
            </div> <!-- .header -->
            <div class="content">
                <form method="post" action="options.php">
                    <?php settings_fields( 'webmaster-tools-group' ); ?>

                    <?php $settings = catchwebtools_get_options( 'catchwebtools_webmaster' ); ?>

                    <div class="option-container">
                        <h3 class="option-toggle option-active"><a href="#"><?php esc_html_e( 'Enable Webmaster Module', 'catch-web-tools' ); ?></a></h3>

                        <div class="option-content inside open">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Enable Webmaster Module', 'catch-web-tools' ); ?></th>
                                        <td>
                                            <?php
                                            $text	=	( ! empty ( $settings['status'] ) && $settings['status'] ) ? 'checked' : '';
                                            echo '<input type="checkbox" ' .$text. ' name="catchwebtools_webmaster[status]" value="1"/>&nbsp;&nbsp;'. esc_html__( 'Check to Enable', 'catch-web-tools' );
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php submit_button('Save Changes'); ?>
                        </div>

                        <h3 class="option-toggle"><a href="#"><?php esc_html_e( 'Feed Redirect / Custom Feeds', 'catch-web-tools' ); ?></a></h3>

                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Enter your custom feed URL:', 'catch-web-tools' ); ?></th>
                                        <td>
                                            <?php
                                            $text   =   ( ! empty ( $settings['feed_uri'] ) ) ? $settings['feed_uri'] : '';
                                            ?>

                                            <input type="text" name="catchwebtools_webmaster[feed_uri]" id="catchwebtools_webmaster[feed_uri]" value="<?php echo esc_attr( $text ); ?>"  size="80"/>

                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Enter your custom comments feed URL:', 'catch-web-tools' ); ?></th>
                                        <td>
                                            <?php
                                            $text   =   ( ! empty ( $settings['comments_feed_uri'] ) ) ? $settings['comments_feed_uri'] : '';
                                            ?>

                                            <input type="text" name="catchwebtools_webmaster[comments_feed_uri]" id="catchwebtools_webmaster[comments_feed_uri]" value="<?php echo esc_attr( $text ); ?>"  size="80" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <p class="description"><?php printf( esc_html__( 'If your custom feed(s) are not handled by Feedblitz or Feedburner, do not use the redirect options.', 'catch-web-tools' ) ); ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php submit_button('Save Changes'); ?>
                        </div>

                        <h3 class="option-toggle"><a href="#"><?php esc_html_e( 'Header and Footer Scripts', 'catch-web-tools' ); ?></a></h3>

                        <div class="option-content inside">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <?php echo esc_html__( 'Enter scripts or code you would like output to', 'catch-web-tools' ) . '<code>wp_head()</code>:'?>
                                        </th>
                                        <td>
                                            <?php
                                            $text	=	( ! empty ( $settings['header'] ) ) ? $settings['header'] : '';
                                            echo '<textarea cols="80" rows="7" name="catchwebtools_webmaster[header]">' .esc_textarea( $text ). '</textarea>';
                                            echo '<p class="description">'. esc_html__( 'The', 'catch-web-tools' ) .'<code>wp_head()</code>'. esc_html__( 'hook executes immediately before the closing </head> tag in the document source.' , 'catch-web-tools' ) .'</p>';
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <?php echo esc_html__( 'Enter scripts or code you would like output to', 'catch-web-tools' ) . '<code>wp_footer()</code>:'?>
                                        </th>
                                        <td>
                                            <?php
                                            $text	=	( ! empty ( $settings['footer'] ) ) ? $settings['footer'] : '';
                                            echo '<textarea cols="80" rows="7" name="catchwebtools_webmaster[footer]">' . esc_html( $text ). '</textarea>';
                                            echo '<p class="description">'. esc_html__( 'The', 'catch-web-tools' ) .'<code>wp_footer()</code>'. esc_html__( 'hook executes immediately before the closing </body> tag in the document source.' , 'catch-web-tools' ) .'</p>';
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php submit_button('Save Changes'); ?>
                        </div>

                        <h3 class="option-toggle"><a href="#"><?php esc_html_e( 'Site Verification', 'catch-web-tools' ); ?></a></h3>

                        <div class="option-content inside">
                            <?php echo '<p class="description">'. esc_html__( 'You can use the boxes below to verify with different Webmaster Tools. If your site is already verified, you can skip this section. Enter the verify meta values for' , 'catch-web-tools' ) .':</p>';?>

                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <a target="_blank" href="https://www.google.com/webmasters/tools/dashboard?hl=en&amp;siteUrl='<?php echo  urlencode( get_bloginfo( 'url' ) )?>%2F"> <?php esc_html_e( 'Google Webmaster Tools', 'catch-web-tools' )?> </a>
                                        </th>
                                        <td>
                                            <?php
                                            $text	=	( ! empty ( $settings['google-site-verification'] ) ) ? $settings['google-site-verification'] : '';
                                            echo '<input type="text" size="80" name="catchwebtools_webmaster[google-site-verification]" value="' . esc_attr( $text ) . '" /><span>' . esc_html__( 'Enter your Google ID only', 'catch-web-tools' ) .'</span>' ;
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <a target="_blank" href="https://www.bing.com/webmaster/?rfp=1#/Dashboard/?url='<?php echo  str_replace( 'http://', '', get_bloginfo( 'url' ) )?>"><?php esc_html_e( 'Bing Webmaster Tools', 'catch-web-tools' )?></a>
                                        </th>
                                        <td>
                                            <?php
                                            $text	=	( ! empty ( $settings['msvalidate.01'] ) ) ? $settings['msvalidate.01'] : '';
                                            echo '<input type="text" size="80" name="catchwebtools_webmaster[msvalidate.01]" value="' . esc_attr( $text ) . '" /><span>'. esc_html__( 'Enter your Bing ID only', 'catch-web-tools' ) .'</span>' ;
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <a target="_blank" href="https://www.alexa.com/pro/subscription"><?php esc_html_e( 'Alexa Verification ID', 'catch-web-tools' )?></a>
                                        </th>
                                        <td>
                                            <?php
                                            $text	=	( ! empty ( $settings['alexaVerifyID'] ) ) ? $settings['alexaVerifyID'] : '';
                                            echo '<input type="text" size="80" name="catchwebtools_webmaster[alexaVerifyID]" value="' . esc_attr( $text ) . '" /><span>'. esc_html__( 'Enter your Alexa ID only', 'catch-web-tools' ) .'</span>' ;
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <a target="_blank" href="https://help.pinterest.com/en/articles/confirm-your-website"><?php esc_html_e( 'Pinterest Site Verification', 'catch-web-tools' )?></a>
                                        </th>
                                        <td>
                                            <?php
                                            $text   =   ( ! empty ( $settings['p:domain_verify'] ) ) ? $settings['p:domain_verify'] : '';
                                            echo '<input type="text" size="80" name="catchwebtools_webmaster[p:domain_verify]" value="' . esc_attr( $text ) . '" /><span>'. esc_html__( 'Enter your Pinterest Site Verification ID only', 'catch-web-tools' ) .'</span>' ;
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <a target="_blank" href="https://help.yandex.com/webmaster/service/rights.xml#how-to"><?php esc_html_e( 'Yandex Webmaster Tools', 'catch-web-tools' )?></a>
                                        </th>
                                        <td>
                                            <?php
                                            $text   =   ( ! empty ( $settings['yandexverify'] ) ) ? $settings['yandexverify'] : '';
                                            echo '<input type="text" size="80" name="catchwebtools_webmaster[yandexverify]" value="' . esc_attr( $text ) . '" /><span>'. esc_html__( 'Enter your Yandex Verification ID only', 'catch-web-tools' ) .'</span>' ;
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php submit_button('Save Changes'); ?>
                        </div>
                    </div>
                 </form>
            </div><!-- .content -->
        </div><!-- .content-wrapper -->
    </div><!-- #customcss -->

<?php include ( 'main-footer.php' ); ?>

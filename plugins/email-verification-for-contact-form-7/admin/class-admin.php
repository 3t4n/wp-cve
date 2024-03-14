<?php
if(!class_exists('evcf7_admin_functions'))
{
    class evcf7_admin_functions
    {
        public function __construct(){
            add_action( 'wpcf7_init', array( 'evcf7_admin_functions','evcf7_update_tag_generator_email' ), 36, 0 );     // form tag generator
        }

        static function evcf7_update_tag_generator_email(){
            if(class_exists('WPCF7_TagGenerator')){
                $tag_generator = WPCF7_TagGenerator::get_instance();
                $tag_generator->add( 'verification', __( 'verification', 'contact-form-7-mail-verification' ), array('evcf7_admin_functions','evcf7_tag_generator_email') );
                $tag_generator->add( 'verification-otp', __( 'verification otp', 'contact-form-7-mail-verification' ), array('evcf7_admin_functions','evcf7_tag_generator_email') );
            }
        }

        static function evcf7_tag_generator_email( $contact_form, $args = '' ){
            $args = wp_parse_args( $args, array() );
            if($args['id'] == 'verification') {
                $type = 'email';
            }
            if($args['id'] == 'verification-otp') {
                $type = 'text';
            } ?>
            <div class="control-box">
                <fieldset>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><?php echo esc_html( __( 'Field type', 'email-verification-for-contact-form-7' ) ); ?></th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <?php echo esc_html( __( 'Field type', 'email-verification-for-contact-form-7' ) ); ?></legend>
                                        <label><input type="checkbox" name="required" checked disabled readonly />
                                            <?php echo esc_html( __( 'Required field', 'email-verification-for-contact-form-7' ) ); ?></label>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'email-verification-for-contact-form-7' ) ); ?></label>
                                </th>
                                
                                <td>
                                    <?php if ( 'email' == $type ) : ?>
                                        <input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" value="<?php esc_attr_e('verification'); ?>" disabled />
                                    <?php elseif ( 'text' == $type ) : ?>
                                        <input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" value="<?php esc_attr_e('verification-otp'); ?>" disabled />
                                    <?php endif; ?>
                                </td>                                
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'email-verification-for-contact-form-7' ) ); ?></label>
                                </th>
                                <td>
                                    <input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><br />
                                    <label><input type="checkbox" name="placeholder" class="option" />
                                        <?php echo esc_html( __( 'Use this text as the placeholder of the field', 'email-verification-for-contact-form-7' ) ); ?></label>
                                </td>
                            </tr>

                            <?php if ( in_array( $type, array( 'text' ,'email' ) ) ) : ?>
                            <tr>
                                <th scope="row"><?php echo esc_html( __( 'Akismet', 'email-verification-for-contact-form-7' ) ); ?></th>
                                <td>
                                    <fieldset>
                                        <?php if ( 'text' == $type ) : ?>
                                            <label>
                                                <input type="checkbox" name="akismet:author" class="option" />
                                                <?php echo esc_html( __( "This field requires author's name", 'email-verification-for-contact-form-7' ) ); ?>
                                            </label>
                                        <?php elseif ( 'email' == $type ) : ?>
                                            <label>
                                                <input type="checkbox" name="akismet:author_email" class="option" />
                                                <?php echo esc_html( __( "This field requires author's email address", 'email-verification-for-contact-form-7' ) ); ?>
                                            </label>                                         
                                        <?php endif; ?>

                                    </fieldset>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <tr>
                                <th scope="row"><label
                                        for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'email-verification-for-contact-form-7' ) ); ?></label>
                                </th>
                                <td><input type="text" name="id" class="idvalue oneline option"
                                        id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
                            </tr>

                            <tr>
                                <th scope="row"><label
                                        for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'email-verification-for-contact-form-7' ) ); ?></label>
                                </th>
                                <td><input type="text" name="class" class="classvalue oneline option"
                                        id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>
            </div>

            <div class="insert-box">
                <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

                <div class="submitbox">
                    <input type="button" class="button button-primary insert-tag"
                        value="<?php echo esc_attr( __( 'Insert Tag', 'email-verification-for-contact-form-7' ) ); ?>" />
                </div>

                <br class="clear" />
                <p class="description mail-tag">
                    <label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>">
                    <?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'email-verification-for-contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?>
                    <input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label>
                </p>
            </div>
            <?php
        }
        
    }
    new evcf7_admin_functions();
}
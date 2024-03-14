<?php

// AFI action provider filter.
add_filter( 'adfoin_action_providers', 'adfoin_wordpress_actions', 10, 1 );

function adfoin_wordpress_actions( $actions ) {

    $actions['wordpress'] = array(
        'title' => __( 'WordPress', 'advanced-form-integration' ),
        'tasks' => array(
            'create_post' => __( 'Create New Post', 'advanced-form-integration' ),
            'create_user' => __( 'Create New User', 'advanced-form-integration' )
        )
    );

    return $actions;
}

add_action( 'adfoin_action_fields', 'adfoin_wordpress_action_fields' );

function adfoin_wordpress_action_fields() {
    ?>

    <script type="text/template" id="wordpress-action-template">
        <table class="form-table">
            <tr valign="top" v-if="action.task == 'create_post'">
                <th scope="row">
                    <?php esc_attr_e( 'Post Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_post'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Post Type', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[postTypeId]" v-model="fielddata.postTypeId">
                        <option value=""> <?php _e( 'Select Post Type...', 'advanced-form-integration' ); ?> </option>
                        <!-- <option v-for="(item, index) in fielddata.postTypes" :value="index" > {{item}}  </option> -->
                        <?php wp_dropdown_roles(); ?>
                    </select>
                    <div class="spinner" v-bind:class="{'is-active': postTypeLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_post'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Status', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[status]" v-model="fielddata.status">
                        <option value=""> <?php _e( 'Select Status...', 'advanced-form-integration' ); ?> </option>
                        <option value="publish"> <?php _e( 'Published', 'advanced-form-integration' ); ?> </option>
                        <option value="draft"> <?php _e( 'Draft', 'advanced-form-integration' ); ?> </option>
                        <option value="pending"> <?php _e( 'Pending', 'advanced-form-integration' ); ?> </option>
                        <option value="private"> <?php _e( 'Private', 'advanced-form-integration' ); ?> </option>
                        <option value="trash"> <?php _e( 'Trash', 'advanced-form-integration' ); ?> </option>                        
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_post'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Author', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.author" name="fieldData[author]">
                    <select @change="updateFieldValue('author')" v-model="author">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                    <p class="description"><?php _e( 'Username, email or User ID', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_post'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Title', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.title" name="fieldData[title]" required="required">
                    <select @change="updateFieldValue('title')" v-model="title">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                    <p class="description"><?php _e( 'Title of the post.', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_post'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Slug', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.slug" name="fieldData[slug]">
                    <select @change="updateFieldValue('slug')" v-model="slug">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_post'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Content', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <textarea class="regular-text" v-model="fielddata.content" name="fieldData[content]" rows="8"></textarea>
                    <select @change="updateFieldValue('content')" v-model="content">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_post'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Post Meta', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <textarea class="regular-text" v-model="fielddata.postMeta" name="fieldData[postMeta]" rows="4"></textarea>
                    <select @change="updateFieldValue('postMeta')" v-model="postMeta">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                    <p class="description"><?php _e( 'Accepts <code>meta_key:meta_value</code> pair. Use double pipe (||) between multiple meta key value pair.', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>

            <tr valign="top" v-if="action.task == 'create_user'">
                <th scope="row">
                    <?php esc_attr_e( 'User Fields', 'advanced-form-integration' ); ?>
                </th>
                <td scope="row">

                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Username', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.username" name="fieldData[username]" required="required">
                    <select @change="updateFieldValue('username')" v-model="username">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                    <p class="description"><?php _e( 'Login username', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Password', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.password" name="fieldData[password]">
                    <select @change="updateFieldValue('password')" v-model="password">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                    <p class="description"><?php _e( 'Auto-generated password will be used if left blank', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Role', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <select name="fieldData[role]" v-model="fielddata.role">
                        <option value=""> <?php _e( 'Select Role...', 'advanced-form-integration' ); ?> </option>
                        <?php wp_dropdown_roles(); ?>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Email', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.email" name="fieldData[email]" required="required">
                    <select @change="updateFieldValue('email')" v-model="email">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                    <!-- <p class="description"><?php _e( 'Email address of the user', 'advanced-form-integration' ); ?></p> -->
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'First Name', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.firstName" name="fieldData[firstName]">
                    <select @change="updateFieldValue('firstName')" v-model="firstName">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Last Name', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.lastName" name="fieldData[lastName]">
                    <select @change="updateFieldValue('lastName')" v-model="lastName">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'Website', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <input type="text" class="regular-text" v-model="fielddata.website" name="fieldData[website]">
                    <select @change="updateFieldValue('website')" v-model="website">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                </td>
            </tr>

            <tr valign="top" class="alternate" v-if="action.task == 'create_user'">
                <td scope="row-title">
                    <label for="tablecell">
                        <?php esc_attr_e( 'User Meta', 'advanced-form-integration' ); ?>
                    </label>
                </td>
                <td>
                    <textarea class="regular-text" v-model="fielddata.userMeta" name="fieldData[userMeta]" rows="4"></textarea>
                    <select @change="updateFieldValue('userMeta')" v-model="userMeta">
                        <option value=""><?php _e( 'Form Fields...', 'advanced-form-integration' ); ?></option>
                        <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                    </select>
                    <p class="description"><?php _e( 'Accepts <code>meta_key:meta_value</code> pair. Use double pipe (||) between multiple meta key value pair.', 'advanced-form-integration' ); ?></p>
                </td>
            </tr>
        </table>
    </script>

    <?php
}

add_action( 'wp_ajax_adfoin_get_wordpress_post_types', 'adfoin_get_wordpress_post_types', 10, 0 );
/*
 * Get wordpress post types
 */
function adfoin_get_wordpress_post_types() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'advanced-form-integration' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }

    $get_cpt_args = array(
        'public'   => true
    );

    $types      = get_post_types( $get_cpt_args, 'object' );
    $post_types = wp_list_pluck( $types, 'label', 'name' );

    wp_send_json_success( $post_types );
}

add_action( 'adfoin_wordpress_job_queue', 'adfoin_wordpress_job_queue', 10, 1 );

function adfoin_wordpress_job_queue( $data ) {
    adfoin_wordpress_send_data( $data['record'], $data['posted_data'] );
}

/*
 * Handles creating new WordPress post
 */
function adfoin_wordpress_send_data( $record, $posted_data ) {

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !adfoin_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data       = $record_data["field_data"];
    $task       = $record["task"];

    if( $task == "create_post" ) {

        $default_data = array(
            'post_title'    => '',
            'post_name'     => '',
            'post_type'     => 'post',
            'post_status'   => 'draft',
            'post_date'     => '',
            'post_author'   => '',
            'post_content'  => '',
            'post_excerpt'  => '',
            'post_parent'   => '',
            'menu_order'    => '0',
            'post_password' => '',
        );

        $submitted_data = array(
            'post_type'    => empty( $data["postTypeId"] ) ? "" : $data["postTypeId"],
            'post_status'  => empty( $data["status"] ) ? "" : $data["status"],
            'post_title'   => empty( $data["title"] ) ? "" : adfoin_get_parsed_values( $data["title"], $posted_data ),
            'post_name'    => empty( $data["slug"] ) ? "" : sanitize_title( adfoin_get_parsed_values( $data["slug"], $posted_data ) ),
            'post_content' => empty( $data["content"] ) ? "" : adfoin_get_parsed_values( $data["content"], $posted_data ),
        );

        $author = empty( $data["author"] ) ? "" : adfoin_get_parsed_values( $data["author"], $posted_data );

        if( $author ) {
            if ( is_numeric( $author ) ) {
                $submitted_data['post_author'] = absint( $author );
            } else {
                // get author by username or email
                $user = get_user_by( 'login', $author );
                if ( ! $user ) {
                    $user = get_user_by( 'email', $author );
                }
                if ( ! $user ) {
                    $user = get_user_by( 'slug', $author );
                }
                if ( ! empty( $user ) ) {
                    $submitted_data['post_author'] = absint( $user->ID );
                }
            }
        }

        $post_data = wp_parse_args( $submitted_data, $default_data );
        $post_id   = wp_insert_post( $post_data );

        if( is_numeric( $post_id ) ) {
            $post_metas = empty( $data["postMeta"] ) ? '' : sanitize_text_field( $data["postMeta"] );
            $post_metas = explode( "||", $post_metas );
            $post_metas = array_filter( $post_metas );

            if( is_array( $post_metas ) && !empty( $post_metas ) ) {
                foreach( $post_metas as $post_meta ) {
                    if( false !== strpos( $post_meta, ":" ) ) {
                        list( $meta_key, $meta_value ) = explode(':', $post_meta, 2);
                        $meta_value = adfoin_get_parsed_values( trim( $meta_value ), $posted_data );

                        if( $meta_key && $meta_value ) {
                            update_post_meta( absint( $post_id ), $meta_key, $meta_value );
                        }
                    }
                }
            }
        }
    }

    if( $task == 'create_user' ) {
        $user_data = array(
            'user_login' => adfoin_get_parsed_values( $data['username'], $posted_data ),
            'user_email' => adfoin_get_parsed_values( $data['email'], $posted_data )
        );

        $role = $data['role'] ? $data['role'] : 'subscriber';

        $user_data['role'] = $role;

        if( $data['password'] ) {
            $user_data['user_pass'] = adfoin_get_parsed_values( $data['password'], $posted_data );
        } else{
            $user_data['user_pass'] = wp_generate_password( 24 );
        }

        if( $data['firstName'] ) {
            $user_data['first_name'] = adfoin_get_parsed_values( $data['firstName'], $posted_data );
        }

        if( $data['lastName'] ) {
            $user_data['last_name'] = adfoin_get_parsed_values( $data['lastName'], $posted_data );
        }

        if( $data['website'] ) {
            $user_data['user_url'] = adfoin_get_parsed_values( $data['website'], $posted_data );
        }

        $user_id = wp_insert_user( $user_data );

        if( $user_id && is_numeric( $user_id ) && $data['userMeta'] ) {

            $user_metas = empty( $data['userMeta'] ) ? '' : sanitize_text_field( $data['userMeta'] );
            $user_metas = explode( '||', $user_metas );

            if( is_array( $user_metas ) && !empty( $user_metas ) ) {
                foreach( $user_metas as $user_meta ) {
                    if( false !== strpos( $user_meta, ':' ) ) {
                        list( $meta_key, $meta_value ) = explode(':', $user_meta, 2);
                        $meta_value = adfoin_get_parsed_values( trim( $meta_value ), $posted_data );

                        if( $meta_key && $meta_value ) {
                            update_user_meta( absint( $user_id ), $meta_key, $meta_value );
                        }
                    }
                }
            }
        }
    }

    return;
}
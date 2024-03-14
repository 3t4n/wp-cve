<?php
defined('ABSPATH') OR die('This script cannot be accessed directly.');

/**
 * Description of schema-editor
 * Inspired by http://codex.wordpress.org/Function_Reference/add_meta_box#Class
 *
 * @author Mark van Berkel
 */
class SchemaEditor {

		private $Settings;
        private $PluginURL;

        /**
         * Hook into the appropriate actions when the class is constructed.
         */
        public function __construct($HunchSchemaPluginURL) {

				$this->Settings = get_option( 'schema_option_name' );
                $this->PluginURL = $HunchSchemaPluginURL;

                add_action( 'load-post.php', array( $this, 'hunch_schema_edit' ) );
                add_action( 'load-post-new.php', array( $this, 'hunch_schema_edit' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
                add_action( 'wp_ajax_HunchSchemaMarkupUpdate', array( $this, 'MarkupUpdate' ) );
        }

     /**
     * Register javascript for media upload (Publisher Logo)
     */
    public function admin_assets($hook) {
        
        if ( ! in_array($hook, array('post.php', 'post-new.php' ) ) ) {
            return;
        }

        // Javascript
        wp_enqueue_media(); 
        wp_enqueue_script('schema-admin-funcs', $this->PluginURL . 'js/schemaEditor.js', array('jquery'), '20171127');
        
    }
    
    public function hunch_schema_edit() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'ActionSavePost'));
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box($post_type) {
        $post_types = array('post', 'page');            //limit meta box to certain post types
		$post_types = apply_filters( 'hunch_schema_meta_box_post_types', $post_types, $post_type );

        if (in_array($post_type, $post_types)) {
            add_meta_box(
                'schema_meta_json_ld'
                , __('Schema App Structured Data', 'schema_textdomain')
                , array($this, 'render_meta_box_content')
                , $post_type
                , 'advanced'
                , 'high'
            );
        }
    }

    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content($post) {

        $PostType = get_post_type();
        $MarkupType = get_post_meta( $post->ID, '_HunchSchemaType', true );
        $MarkupCustom = get_post_meta( $post->ID, '_HunchSchemaMarkup', true );
        $MarkupDefault = '';

		$markup_final			= true;
		$global_markup			= true;
		$single_markup_disable	= get_post_meta( $post->ID, '_HunchSchemaDisableMarkup', true );
		$single_markup_enable	= get_post_meta( $post->ID, '_HunchSchemaEnableMarkup', true );

		if ( $PostType == 'page' && isset( $this->Settings['SchemaDefaultShowOnPage'] ) && $this->Settings['SchemaDefaultShowOnPage'] == 0 ) {
			$global_markup = false;
		}

		if ( $PostType == 'post' && isset( $this->Settings['SchemaDefaultShowOnPost'] ) && $this->Settings['SchemaDefaultShowOnPost'] == 0 ) {
			$global_markup = false;
		}

		if (  ( $global_markup && $single_markup_disable )  ||  ( ! $global_markup && ! $single_markup_enable )  ) {
			$markup_final = false;
		}

        // Add an nonce field so we can check for it later.
        wp_nonce_field('schema_inner_custom_box', 'schema_inner_custom_box_nonce');

        $schema_server = new SchemaServer();
        // Use get_post_meta to retrieve an existing value from the database.       
        $jsonLd = $schema_server->getResource(get_permalink($post->ID), true);
		$MarkupDefault = $jsonLd;

        if (empty($jsonLd)) {
            $schemaObj = HunchSchema_Thing::factory($PostType);
			$MarkupDefault = $schemaObj->getResource(TRUE);
            $jsonLd = $MarkupCustom ? $MarkupCustom : $MarkupDefault;
            $replacelink = $schema_server->createLink();
        } else {
            $editlink = $schema_server->updateLink();
        }
        $testlink = "https://search.google.com/test/rich-results?user_agent=2&url=" . urlencode( esc_url( get_permalink( $post->ID ) ) );

        // Display the form, using the current value.
        ?>
        <style>
        #schemapostlinks {
            display: inline;
            float: right;
        }
        #schemapostlinks button {
            margin: 0 1em;
        }
        </style>
        <?php _e('Post JSON-LD', 'schema_textdomain'); ?>
            <div id="schemapostlinks">
                <?php 
					if ( $markup_final )
					{
						if ( empty($replacelink) ) {
							echo '<button><a target="_blank" href="' . esc_url($editlink) . '">Edit</a></button>';
						} else {
							?>
							<?php if ( ! $MarkupCustom ): ?>
								<button><a target="_blank" href="<?php echo esc_url($replacelink); ?>">Use Different Schema.org Type</a></button>
								<button id="extendSchema" value="Extend Markup"><a target="_blank" href="#">Add to <?php echo esc_html($schemaObj->schemaType); ?> Default Markup</a></button>
							<?php endif; ?>
							<input type="hidden" id="resourceURI" value="<?php echo get_permalink($post->ID); ?>#<?php print esc_html($schemaObj->schemaType); ?>"/>
							<textarea id="resourceData" style="display:none"><?php echo esc_attr($jsonLd); ?></textarea> 
							<?php 
						}
					}
                ?>
                <button><a class="" target="_blank" href="<?php echo esc_url($testlink); ?>">Test with Google</a></button>
            </div>
        </label> 
        <p>
			<textarea id="MetaSchemaMarkupDefault" style="display: none;"><?php echo esc_attr( $MarkupDefault ); ?></textarea> 
			<div class="MetaSchemaMarkup" style="position: relative;">
				<div class="ErrorMessage" style="color: red;"></div>
				<textarea class="large-text metadesc" rows="6" id="schema_new_field" name="schema_new_field" data-id="<?php print esc_attr($post->ID); ?>" <?php print $MarkupCustom ? '' : 'disabled'; ?>><?php print $markup_final ? esc_attr($jsonLd) : ''; ?></textarea>
				<?php if ( $markup_final ): ?>
					<a class="Edit dashicons dashicons-edit" style="<?php print $MarkupCustom ? 'display: none;' : ''; ?> position: absolute; bottom: 10px; right: 32px;" href="#"></a>
					<a class="Delete dashicons dashicons-trash" style="<?php print $MarkupCustom ? '' : 'display: none;'; ?> position: absolute; bottom: 10px; right: 32px;" href="#"></a>
					<span class="Updating" style="display: none; position: absolute; top: 5px; right: 32px;">Saving...</span>
				<?php endif; ?>
			</div>
            <?php if ( isset( $schemaObj ) && $markup_final ) : ?>
				<strong>Note: </strong>
				<span id="MetaSchemaMarkupNote" style="color: grey"><em>
					<span class="custom" style="<?php print $MarkupCustom ? '' : 'display: none;'; ?>">This is custom markup.</span>
					<span class="default" style="<?php print $MarkupCustom ? 'display: none;' : ''; ?>">This is default markup. Extend this with Schema App Creator using Update linked above.</span>
				</em></span>
            <?php endif; ?>
        </p>


		<?php if ( ! empty( $this->Settings['Debug'] ) ) : ?>

			<h4>Debug</h4>

			<table class="widefat fixed wp-list-table">
				<tbody>

					<tr>
						<td style="width: 30%;">
							URL
						</td>
						<td>
							<a href="<?php print esc_url($schema_server->resource_url); ?>" target="_blank"><?php print esc_html($schema_server->resource_url); ?></a>
						</td>
					</tr>
					<tr>
						<td style="width: 30%;">
							Transient
						</td>
						<td>
							<?php print $schema_server->transient ? 'Yes' : 'No'; ?>
						</td>
					</tr>
					<tr>
						<td style="width: 30%;">
							Transient Id
						</td>
						<td>
							<?php print esc_html($schema_server->transient_id); ?>
						</td>
					</tr>
					<tr>
						<td style="width: 30%;">
							Data Sources
						</td>
						<td>
							<ul>
							<?php foreach ( $schema_server->data_sources as $data_source ) : ?>
								<li><a href="<?php print esc_url($data_source); ?>" target="_blank"><?php print esc_html($data_source); ?></a></li>
							<?php endforeach; ?>
							</ul>
						</td>
					</tr>

				</tbody>
			</table>

		<?php endif; ?>


        <h4>Markup Options</h4>

		<table class="widefat fixed wp-list-table">
			<tbody>

				<?php if ( $global_markup ) : ?>
					<tr>
						<td style="width: 30%;">
							<?php _e('Disable Schema markup', 'schema_textdomain'); ?>
						</td>
						<td>
							<input type="checkbox" name="HunchSchemaDisableMarkup" value="1" <?php $this->CheckSelected(1, $single_markup_disable, 'checkbox'); ?>>
						</td>
					</tr>
				<?php else : ?>
					<tr>
						<td style="width: 30%;">
							<?php _e('Enable Schema markup', 'schema_textdomain'); ?>
						</td>
						<td>
							<input type="checkbox" name="HunchSchemaEnableMarkup" value="1" <?php $this->CheckSelected(1, $single_markup_enable, 'checkbox'); ?>>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $PostType == 'page' ) : ?>
					<tr>
						<td>
							<?php _e('Select Type', 'schema_textdomain'); ?>
						</td>
						<td>
							<select name="HunchSchemaType">
								<option value="">Article</option>
								<option value="BlogPosting" <?php $this->CheckSelected('BlogPosting', $MarkupType); ?>>Blog Posting</option>
								<option value="LiveBlogPosting" <?php $this->CheckSelected('LiveBlogPosting', $MarkupType); ?>>&nbsp; Live Blog Posting</option>
								<option value="NewsArticle" <?php $this->CheckSelected('NewsArticle', $MarkupType); ?>>News Article</option>
								<option value="Report" <?php $this->CheckSelected('Report', $MarkupType); ?>>Report</option>
								<option value="ScholarlyArticle" <?php $this->CheckSelected('ScholarlyArticle', $MarkupType); ?>>Scholarly Article</option>
								<option value="MedicalScholarlyArticle" <?php $this->CheckSelected('MedicalScholarlyArticle', $MarkupType); ?>>&nbsp; Medical Scholarly Article</option>
								<option value="SocialMediaPosting" <?php $this->CheckSelected('SocialMediaPosting', $MarkupType); ?>>Social Media Posting</option>
								<option value="DiscussionForumPosting" <?php $this->CheckSelected('DiscussionForumPosting', $MarkupType); ?>>&nbsp; Discussion Forum Posting</option>
								<option value="TechArticle" <?php $this->CheckSelected('TechArticle', $MarkupType); ?>>Tech Article</option>
								<option value="APIReference" <?php $this->CheckSelected('APIReference', $MarkupType); ?>>&nbsp; API Reference</option>
							</select>
						</td>
					</tr>
				<?php endif; ?>

			</tbody>
		</table>

		<?php do_action( 'hunch_schema_meta_box', $post ); ?>

        <?php
    }

    public function ActionSavePost($PostId) {
        if (( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || wp_is_post_revision($PostId)) {
            return $PostId;
        }

        if (isset($_POST['schema_inner_custom_box_nonce'])) {
            if (isset($_POST['HunchSchemaDisableMarkup'])) {
                update_post_meta($PostId, '_HunchSchemaDisableMarkup', sanitize_text_field($_POST['HunchSchemaDisableMarkup']));
            } else {
                delete_post_meta($PostId, '_HunchSchemaDisableMarkup');
            }

            if (isset($_POST['HunchSchemaEnableMarkup'])) {
                update_post_meta($PostId, '_HunchSchemaEnableMarkup', sanitize_text_field($_POST['HunchSchemaEnableMarkup']));
            } else {
                delete_post_meta($PostId, '_HunchSchemaEnableMarkup');
            }

            if (isset($_POST['HunchSchemaType'])) {
                update_post_meta($PostId, '_HunchSchemaType', sanitize_text_field($_POST['HunchSchemaType']));
            } else {
                delete_post_meta($PostId, '_HunchSchemaType');
            }
        }

		// Delete schema data transient cache
		delete_transient( 'HunchSchema-Markup-' . md5( get_permalink( $PostId ) ) );
    }

    public function CheckSelected($SavedValue, $CurrentValue, $Type = 'select', $Display = true) {
        if (( is_array($SavedValue) && in_array($CurrentValue, $SavedValue) ) || ( $SavedValue == $CurrentValue )) {
            switch ($Type) {
                case 'select':
                    if ($Display) {
                        print 'selected="selected"';
                    } else {
                        return 'selected="selected"';
                    }
                    break;
                case 'radio':
                case 'checkbox':
                    if ($Display) {
                        print 'checked="checked"';
                    } else {
                        return 'checked="checked"';
                    }
                    break;
            }
        }
    }


	public function MarkupUpdate()
	{
		if ( $_POST['Id'] )
		{
			if ( ! empty( $_POST['Data'] ) )
			{
				json_decode( utf8_encode( stripslashes( $_POST['Data'] ) ) );

				if ( json_last_error() === JSON_ERROR_NONE )
				{
					update_post_meta( sanitize_key( $_POST['Id'] ), '_HunchSchemaMarkup', sanitize_textarea_field( $_POST['Data'] ) );

					print wp_json_encode( array( 'Status' => 'Ok' ) );
				}
				else
				{
					print wp_json_encode( array( 'Status' => 'Error', 'Message' => 'Invalid JSON format' ) );
				}
			}
			else
			{
        delete_post_meta( sanitize_key ($_POST['Id'] ), '_HunchSchemaMarkup' );
                                
				print wp_json_encode( array( 'Status' => 'Ok', 'Delete' => true ) );
			}
		}
		else
		{
			print wp_json_encode( array( 'Status' => 'Error', 'Message' => 'Required parameter missing' ) );
		}

		exit;
	}

}

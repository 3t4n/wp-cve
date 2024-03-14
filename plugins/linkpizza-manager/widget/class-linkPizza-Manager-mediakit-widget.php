<?php
/**
 * Created by PhpStorm.
 * User: arjanpronk
 * Date: 17/05/16
 * Time: 16:50
 */

define('PZZ_IMAGE_URL_VERSION_WHITE', '//pzz.io/resources/images/affiliate-program/bloggers-marktplaats.png.xhtml?ln=linkpizza');
define('PZZ_IMAGE_URL_VERSION_BLUE', '//pzz.io/resources/images/affiliate-program/bloggers-marktplaats-b.png.xhtml?ln=linkpizza');


class LinkPizza_Manager_MediaKit_Widget extends WP_Widget
{

	public function register_widget()
	{
		register_widget('LinkPizza_Manager_MediaKit_Widget');
	}

	/**
	 * Sets up the widgets name
	 *
	 * @return LinkPizza_Manager_MediaKit_Widget
	 * @since    1.0.0
	 *
	 */
	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'LinkPizza_Manager_MediaKit_Widget',
			'description' => __('Add a link to your MediaKit and earn commission for every referred advertiser and publisher', 'linkpizza-manager'),
		);
		parent::__construct('LinkPizza_Manager_MediaKit_Widget', __('LinkPizza MediaKit link', 'linkpizza-manager'), $widget_ops);

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance)
	{
		extract($args);
		if(!empty($instance['pzz_mediakit_version'])){
			echo $before_widget;
			?>

            <div style="display: block;">
                <a href="https://linkpizza.com/nl/mediakit/<?php $username = get_option( 'pzz_username' );if ( $username != false ) { echo $username;} else{ echo 'klaas.joosten';} ?>/<?php $website = get_option( 'pzz_website' );if ( $website != false ) { echo $website->host;} else{ echo 'linkpizza.com';} ?>?ref=<?php $userid = get_option( 'pzz_id' );if ( $userid != false ) { echo $userid;} else{ echo 13003;} ?>"
                   title="Klik hier om naar mijn MediaKit op de blogger MarktPlaats te gaan"
                   target="_blank" >
                    <img src="<?php if($instance['pzz_mediakit_version'] == 'white') {echo PZZ_IMAGE_URL_VERSION_WHITE;} else {echo PZZ_IMAGE_URL_VERSION_BLUE;};?>"
                         alt="Klik hier om naar mijn MediaKit op de blogger MarktPlaats te gaan" />
                </a>
            </div>
			<?php
			echo $after_widget;
		}
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form($instance)
	{
		if(!empty($instance['pzz_mediakit_version'])){
			$pzz_mediakit_version = $instance['pzz_mediakit_version'];
		} else {
		    $pzz_mediakit_version = 'white';
        }
		?>

        <p>
        <?php echo $pzz_mediakit_version ?>
            <label for="<?php echo $this->get_field_id('label'); ?>"><?php echo __('Style')?>
                <select class="widefat" id="<?php echo $this->get_field_id('pzz_mediakit_version');?>" name="<?php echo $this->get_field_name('pzz_mediakit_version'); ?>">
                    <option value="white" <?php selected($pzz_mediakit_version, 'white'); ?>>
                        <?php _e('White','linkpizza-manager') ?>
                    </option>
                    <option value="blue" <?php selected($pzz_mediakit_version, 'blue'); ?>>
	                    <?php _e('Blue','linkpizza-manager') ?>
                    </option>
                </select>
            </label>
        </p>

		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		if(!empty($new_instance['pzz_mediakit_version'])){
			$instance['pzz_mediakit_version'] = $new_instance['pzz_mediakit_version'];
		}
		return $instance;
	}

}

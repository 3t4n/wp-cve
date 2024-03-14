<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

class Install
{
	protected static $issue;
	protected static $ispro;

	public static function getIssue()
	{
			if (! is_null(self::$issue))
				return self::$issue;

			return false;

	}

 /** Check if all needed conditions are met. Sets issue variable if not.
 * @return Boolean
 */
	public static function verifyPlugin()
	{
		$not_fatal = true;

		$status = static::checkMaxButtons();
		if (! $status)
		{
				self::$issue = 'no-maxclass';
				return false; // prevent loading
		}

		$version = self::check_mb_version();
		if ( ! $version )
			self::$issue = 'mb-wrong-version';

			return true;
	}

	/** Check if current MaxButtons loaded is a PRO version or not */
	public static function isPro()
	{
			if (isset(static::$ispro))
				return static::$ispro;

			static::$ispro = false;
			if (defined('MAXBUTTONS_PRO_ROOT_FILE'))
			{
				$license = MB()->getClass('license');
				$license_activated = $license->is_activated();
				if ( ! $license_activated)
				{
					return false;
				}
				else {
						static::$ispro = true;
						return true;
				}
			}
			else {
				return false;
			}

	}

	/** Checks if maxbuttons plugin exists */
	protected static function checkMaxButtons()
	{
		if (! class_exists('MaxButtons\maxButtonsPlugin'))
		{
			add_action('admin_notices', array(__NAMESPACE__ . '\Install', 'noMaxButtons'));
			return false;
		}
		return true;
	}

	protected static function check_mb_version()
	{
		$result = version_compare( MAXBUTTONS_VERSION_NUM, MBSOCIAL_REQUIRED_MB );

		if ($result >= 0)
			return true;
		else
			return false;

	}

	public static function convertToOne($collection)
	{
			$collection_id = $collection->getID();

			$keys = array('style', 'layout');

			$styleData = $collection->get_meta('style');
			$styleData = $styleData['style'];
			$layoutData = $collection->get_meta('layout');
			$layoutData = $layoutData['layout'];

			$countData = $collection->get_meta('count');
			$countData = $countData['count'];

			if (! isset($styleData['style']))
				return; // escape

				$style = $styleData['style'];
				switch($style)
				{
				 	case 'round':
					case 'roundflip':
						$width = 55;
						$height = 55;
						$label = 16;
						$count = 16;
						$icon = 20;
						if ($style == 'roundflip')
							$effect = 'flip';
						else {
							$effect = 'transform';
						}
						$style = 'round';

					break;
					case 'square':
					case 'horizontal':
						$width = 45;
						$height = 45;
						//
						$label = 15;
						$icon = 20;
						if ($style == 'square')
						{
							$effect = 'transform';
							$count = 20;
						}
						else {
							$effect = 'stretch';
							$count = 16;
						}
							$style = 'square';
					break;

					case 'dropsquare':
					case 'liftsquare':
					case 'shiftsquare':
						 $width = 60;
						 $height = 65;
						 $label = 10;
						 $icon = 20;
						 $count = 10;
						 if ($style == 'dropsquare')
						 { $effect = 'drop' ; }
						 if ($style == 'liftsquare')
						 { $effect = 'lift'; }
						 if ($style == 'shiftsquare')
						 { $effect = 'shift'; }

						 $style = 'square';

					break;

				}

				$styleData['mbs-width']  = $width;
				$styleData['mbs-height'] = $height;
				$styleData['mbs-style'] = $style;

				$layoutData['font_label_size'] = $label;
				$layoutData['font_icon_size']  = $icon;

				$effectData = array(); // new!
				$effectData['effect_type'] = $effect;

				$countData['font_count_size'] = $count;

				unset($styleData['style']);

				$collection->update_meta($collection_id, 'style', $styleData);
				$collection->update_meta($collection_id, 'layout', $layoutData);
				$collection->update_meta($collection_id, 'effect', $effectData);
				$collection->update_meta($collection_id, 'count', $countData);

	}

	public static function noMaxButtons()
	{

		$action = 'install-plugin';
		$slug = 'maxbuttons';
		$url = wp_nonce_url(
			add_query_arg(
				array(
				    'action' => $action,
				    'plugin' => $slug
				),
				admin_url( 'update.php' )
			),
			$action.'_'.$slug
		);


		$message = sprintf( __("Wordpress Social Buttons requires MaxButtons plugin, which doesn't seem to be active or installed","maxbuttons"), PHP_VERSION);
		echo"<div class='error'> <h4>$message</h4>
				<p><a href='$url'>" . __('Install MaxButtons Now','mbsocial') . "</a></p>
			</div>";
		return;

	}



}

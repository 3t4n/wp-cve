<?php
use Tussendoor\OpenRDW\Config;

/**
 * Public-facing view for the plugin.
 */

$categories = array();
$allfields = $settings['allfields'];

if (isset($settings['checkedfields'])) {
	$checkedfields = $settings['checkedfields'];
} else {
	$checkedfields = null;
}


?>
<section id="<?php echo $args['widget_id']; ?>" class="widget open_rdw_kenteken_widget <?php echo $settings['class']; ?>">
	<h2><?php echo $settings['title']; ?></h2>
	<form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
		<p><input type="text" name="<?php echo $args['widget_id']; ?>" value="<?php if (isset($kenteken)) { echo $kenteken; } ?>" maxlength="8"></p>
		<p><input name="submit" type="submit" id="submit" value="<?php echo esc_html__('Search license', 'open-rdw-kenteken-voertuiginformatie'); ?>"></p>
	</form>
<?php

if (!isset($kentekeninfo) && isset($kenteken)) {
	echo '<p>' . sprintf(esc_html__('Helaas is er geen voertuiginformatie gevonden voor kenteken %s.', 'open-rdw-kenteken-voertuiginformatie'), $kenteken)  . '</p>';
} elseif (empty($checkedfields)) {
	echo '<p>' . esc_html__('Er zijn geen widgetinstellingen gevonden. Voeg deze toe of breng de websitebeheerder hiervan op de hoogte.', 'open-rdw-kenteken-voertuiginformatie') . '</p>';
} elseif (isset($kentekeninfo)) {
	echo '<table>';
	foreach ($checkedfields as $field) {
		$field = strtolower($field);

		if (!isset($kentekeninfo[$field]) || !isset($allfields[$field])) { continue; }
		$data = $kentekeninfo[$field];

		if ($data != '' && $data !== '0' && $data != 'Niet geregistreerd' && $data != 'N.v.t.') {

			if (!in_array($allfields[$field]['category'], $categories)) {

				$categories[] = $allfields[$field]['category'];
				echo '<tr class="open-rdw-head"><th colspan="2"><a>' . $allfields[$field]['category'] . '</a></th></tr>';

			}

			echo '<tr style="display: none;">';
			echo '<td>' . $allfields[$field]['label'] . '</td>';
			echo '<td>' . $kentekeninfo[$field] . '</td>';
			echo '</tr>';

		}

	}
	echo '</table>';
}

?>
</section>
<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2023 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

require_once( '../../../../../../wp-load.php' );
require_once( '../../../define.php' );
require_once( 'tinymce-dialog.functions.php' );

$site_url = get_option( 'siteurl' );
$admin_url = get_admin_url();

$pool = new Football_Pool_Pool( FOOTBALLPOOL_DEFAULT_SEASON );

//$suffix = FOOTBALLPOOL_LOCAL_MODE ? '' : '.min';
$suffix = '.min';
?>
<!DOCTYPE html>
<html lang="<?php bloginfo( 'language' ) ?>">
<head>
	<title>Football Pool Shortcodes</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="<?php echo FOOTBALLPOOL_PLUGIN_URL ?>assets/admin/admin<?php echo $suffix; ?>.js?ver=<?php echo FOOTBALLPOOL_DB_VERSION; ?>"></script>
	<script src="<?php echo FOOTBALLPOOL_PLUGIN_URL ?>assets/libs/chosen/chosen.jquery.min.js?ver=<?php echo FOOTBALLPOOL_DB_VERSION; ?>"></script>
	<script src="tinymce-dialog<?php echo $suffix; ?>.js?ver=<?php echo FOOTBALLPOOL_DB_VERSION; ?>"></script>
	
	<link rel="stylesheet" href="../../../../../../wp-includes/js/tinymce/skins/lightgray/skin.min.css">
	<link rel="stylesheet" href="tinymce-dialog.css">
</head>
<body>
<form class="shortcode-dialog">
	<div class="shortcode-selector mce-container">
		<div>
			<label for="shortcode" class="mce-label"><?php _e( 'Select a shortcode', 'football-pool' ); ?></label>
			<div>
				<select class="mce-select no-chosen" id="shortcode" onchange="FootballPoolTinyMCE.display_shortcode_options( jQuery( this ).val() )">
                    <option value="">-- <?php _e( 'Select a shortcode', 'football-pool' ); ?> --</option>
					<optgroup label="<?php _e( 'Pool', 'football-pool' ); ?>">
						<option value="fp-ranking"><?php _e( 'Ranking', 'football-pool' ); ?></option>
						<option value="fp-predictions"><?php _e( 'Predictions for match or question', 'football-pool' ); ?></option>
						<option value="fp-predictionform"><?php _e( 'Input form for predictions', 'football-pool' ); ?></option>
						<option value="fp-next-match-form"><?php _e( 'Input form for Next matches', 'football-pool' ); ?></option>
						<option value="fp-matches"><?php _e( 'Table of Matches', 'football-pool' ); ?></option>
						<option value="fp-next-matches"><?php _e( 'List of Next matches', 'football-pool' ); ?></option>
						<option value="fp-last-matches"><?php _e( 'List of Latest matches', 'football-pool' ); ?></option>
						<option value="fp-group"><?php _e( 'Group Table (standing)', 'football-pool' ); ?></option>
						<option value="fp-league-info"><?php _e( 'League info', 'football-pool' ); ?></option>
						<option value="fp-user-score"><?php _e( 'Score for a User', 'football-pool' ); ?></option>
						<option value="fp-user-ranking"><?php _e( 'Ranking for a User', 'football-pool' ); ?></option>
						<option value="fp-user-list"><?php _e( 'List the users in the pool', 'football-pool' ); ?></option>
						<option value="fp-match-scores"><?php _e( 'Scores for a set of Matches', 'football-pool' ); ?></option>
						<option value="fp-question-scores"><?php _e( 'Scores for a set of Questions', 'football-pool' ); ?></option>
					</optgroup>
					<optgroup label="<?php _e( 'Links', 'football-pool' ); ?>">
						<option value="fp-link"><?php _e( 'Link to page', 'football-pool' ); ?></option>
						<option value="fp-register"><?php _e( 'Link to registration', 'football-pool' ); ?></option>
					</optgroup>
					<optgroup label="<?php _e( 'Other', 'football-pool' ); ?>">
						<option value="fp-countdown"><?php _e( 'Countdown', 'football-pool' ); ?></option>
						<option value="fp-jokermultiplier"><?php _e( 'Value for', 'football-pool' ); ?> <?php _e( 'Joker multiplier', 'football-pool' ); ?></option>
						<option value="fp-money-in-the-pot"><?php _e( 'Amount of money in the pot', 'football-pool' ); ?></option>
						<option value="fp-last-calc-date"><?php _e( 'Last date a calculation was done', 'football-pool' ); ?></option>
						<option value="fp-fullpoints"><?php _e( 'Value for', 'football-pool' ); ?> <?php _e( 'Full points', 'football-pool' ); ?></option>
						<option value="fp-totopoints"><?php _e( 'Value for', 'football-pool' ); ?> <?php _e( 'Toto points', 'football-pool' ); ?></option>
						<option value="fp-goalpoints"><?php _e( 'Value for', 'football-pool' ); ?> <?php _e( 'Goal bonus', 'football-pool' ); ?></option>
						<option value="fp-diffpoints"><?php _e( 'Value for', 'football-pool' ); ?> <?php _e( 'Goal difference bonus', 'football-pool' ); ?></option>
						<option value="fp-plugin-option"><?php _e( 'Show Plugin Option', 'football-pool' ); ?></option>
					</optgroup>
				</select>
			</div>
		</div>
	</div>

	<div class="shortcode-options-panel mce-container" id="mce-set-parameters-header">
		<div>
			<h3><?php _e( 'Set parameters', 'football-pool' ); ?></h3>
		</div>
	</div>
	
	<!-- No parameters for shortcode -->
	<div id="no-shortcode-params" class="shortcode-options-panel mce-container">
		<div class="info">
			<?php _e( 'There are no parameters for this shortcode. Just add it.', 'football-pool' ); ?>
		</div>
	</div>
	
	<!-- fp-ranking -->
	<div id="fp-ranking" class="shortcode-options-panel mce-container">
		<?php 
		ranking_select_with_default( 'ranking' ); 
		league_select_with_default_and_user( 'ranking' );
		label_textbox( __( 'Number of players', 'football-pool' ), 'ranking-num', array( 'placeholder' => 5 ) );
		?>
		<!--<div>
			<label for="ranking-show-num-predictions"><?php _e( 'Show number of predictions?', 'football-pool' ); ?></label>
		</div>-->
		<?php date_now_postdate_custom_fieldset( 'ranking' ); ?>
	</div>
	
	<!-- fp-user-list -->
	<div id="fp-user-list" class="shortcode-options-panel mce-container">
		<?php
		league_select_with_default_and_user( 'user-list' );
		label_textbox( __( 'Number of players', 'football-pool' ), 'user-list-num', array( 'placeholder' => 5 ) );
		label_checkbox( __( 'Show latest registrations?', 'football-pool' ), 'user-list-latest' );
		?>
	</div>
	
	<!-- fp-predictions -->
	<div id="fp-predictions" class="shortcode-options-panel mce-container">
		<?php match_select( 'predictions' ); ?>
		<div>
			<label class="mce-label"></label>
			<div>
				<span class="info"><?php _e( 'and/or', 'football-pool' ); ?></span>
			</div>
		</div>
		<?php 
		question_select( 'predictions' );
		label_textbox( 
			__( 'Text', 'football-pool' ), 
			'predictions-text',
			array(
				'placeholder' => __( 'Text to display if there is nothing to show', 'football-pool' ),
				'style' => 'width: 100%'
			)
		);
		label_checkbox( __( 'Use querystring?', 'football-pool' ), 'predictions-use-querystring' );
		?>
	</div>
	
	<!-- fp-user-score -->
	<div id="fp-user-score" class="shortcode-options-panel mce-container">
		<?php
		user_select( 'user-score' );
		ranking_select_with_default( 'user-score' );
		label_textbox( __( 'Text', 'football-pool' ), 'user-score-text', array( 'placeholder' => 0 ) );
		date_now_postdate_custom_fieldset( 'user-score' );
		label_checkbox( __( 'Use querystring?', 'football-pool' ), 'user-score-use-querystring' );
		?>
	</div>
	
	<!-- fp-user-ranking -->
	<div id="fp-user-ranking" class="shortcode-options-panel mce-container">
		<?php
		user_select( 'user-ranking' );
		ranking_select_with_default( 'user-ranking' );
		label_checkbox( __( "Show rank in user's league?", 'football-pool' ), 'user-ranking-league-rank' );
		label_textbox( __( 'Text', 'football-pool' ), 'user-ranking-text' );
		date_now_postdate_custom_fieldset( 'user-ranking' );
		?>
	</div>
	
	<!-- fp-group -->
	<div id="fp-group" class="shortcode-options-panel mce-container">
		<div>
			<label class="mce-label" for="group-id"><?php _e( 'Select a group', 'football-pool' ); ?></label>
			<div>
				<select class="mce-select" id="group-id">
					<?php group_options(); ?>
				</select>
			</div>
		</div>
	</div>

	<!-- fp-predictionform -->
	<div id="fp-predictionform" class="shortcode-options-panel mce-container">
		<div class="info">
			<?php _e( 'Click a label to show the options.', 'football-pool' );?>
			<br>
			<?php _e( 'Use CTRL+click to select multiple values.', 'football-pool' );?>
		</div>
		<div>
			<label class="mce-label nofloat clickable" for="match-id" onclick="FootballPoolTinyMCE.toggle_select_row( this, 'predictionform' )">
				<?php _e( 'Select one or more matches', 'football-pool' ); ?>
			</label>
			<br>
			<select class="mce-select" id="match-id" style="height:100px; display:none;" multiple="multiple">
				<?php echo match_options(); ?>
			</select>
		</div>
		<div>
			<label class="mce-label nofloat clickable" for="matchtype-id" onclick="FootballPoolTinyMCE.toggle_select_row( this, 'predictionform' )">
				<?php _e( 'Select one or more match types', 'football-pool' ); ?>
			</label>
			<br>
			<select class="mce-select" id="matchtype-id" style="height:100px; display:none;" multiple="multiple">
				<?php
				$match_types = Football_Pool_Matches::get_match_types();
				foreach( $match_types as $match_type ) {
					printf( '<option value="%d">%s</option>', $match_type->id, $match_type->name );
				}
				?>
			</select>
		</div>
		<div>
			<label class="mce-label nofloat clickable" for="question-id" onclick="FootballPoolTinyMCE.toggle_select_row( this, 'predictionform' )">
				<?php _e( 'Select one or more questions', 'football-pool' ); ?>
			</label>
			<br>
			<select class="mce-select" id="question-id" style="height:100px; display:none;" multiple="multiple">
				<?php echo bonusquestion_options(); ?>
			</select>
		</div>
	</div>

	<!-- fp-next-match-form -->
	<div id="fp-next-match-form" class="shortcode-options-panel mce-container">
		<?php
		label_textbox(
				__( 'Number of matches', 'football-pool' ),
				'next-match-form-num',
				['placeholder', __( 'No maximum', 'football-pool' )]
		);
		?>
	</div>

	<!-- fp-matches -->
	<div id="fp-matches" class="shortcode-options-panel mce-container">
		<div class="info">
			<?php _e( 'Click a label to show the options.', 'football-pool' );?>
			<br>
			<?php _e( 'Use CTRL+click to select multiple values.', 'football-pool' );?>
		</div>
		<div>
			<label class="mce-label nofloat clickable" for="matches-match-id" onclick="FootballPoolTinyMCE.toggle_select_row( this, 'matches' )">
				<?php _e( 'Select one or more matches', 'football-pool' ); ?>
			</label>
			<br>
			<select class="mce-select" id="matches-match-id" style="height:100px; display:none;" multiple="multiple">
				<?php echo match_options(); ?>
			</select>
		</div>
		<div>
			<label class="mce-label nofloat clickable" for="matches-matchtype-id" onclick="FootballPoolTinyMCE.toggle_select_row( this, 'matches' )">
				<?php _e( 'Select one or more match types', 'football-pool' ); ?>
			</label>
			<br>
			<select class="mce-select" id="matches-matchtype-id" style="height:100px; display:none;" multiple="multiple">
				<?php
				$match_types = Football_Pool_Matches::get_match_types();
				foreach( $match_types as $match_type ) {
					printf( '<option value="%d">%s</option>', $match_type->id, $match_type->name );
				}
				?>
			</select>
		</div>
		<div>
			<label class="mce-label nofloat clickable" for="matches-group-id" onclick="FootballPoolTinyMCE.toggle_select_row( this, 'matches' )">
				<?php _e( 'Select a group', 'football-pool' ); ?>
			</label>
			<br>
			<select class="mce-select" id="matches-group-id" style="display:none;">
				<option value=""></option>
				<?php group_options(); ?>
			</select>
		</div>
	</div>

	<!-- fp-next-matches -->
	<div id="fp-next-matches" class="shortcode-options-panel mce-container">
		<?php matchtype_select( 'next-matches' ); ?>
		<div>
			<label class="mce-label" for="next-matches-group-id"><?php _e( 'Select a group', 'football-pool' ); ?></label>
			<div>
				<select class="mce-select" id="next-matches-group-id">
					<option value=""></option>
					<?php group_options(); ?>
				</select>
			</div>
		</div>
		<?php
		date_now_postdate_custom_fieldset( 'next-matches' );
		label_textbox( __( 'Number of matches', 'football-pool' ), 'next-matches-num', ['placeholder', 5] );
		?>
	</div>

	<!-- fp-last-matches -->
	<div id="fp-last-matches" class="shortcode-options-panel mce-container">
		<?php matchtype_select( 'last-matches' ); ?>
		<div>
			<label class="mce-label" for="last-matches-group-id"><?php _e( 'Select a group', 'football-pool' ); ?></label>
			<div>
				<select class="mce-select" id="last-matches-group-id">
					<option value=""></option>
					<?php group_options(); ?>
				</select>
			</div>
		</div>
		<?php
		date_now_postdate_custom_fieldset( 'last-matches' );
		label_textbox( __( 'Number of matches', 'football-pool' ), 'last-matches-num', ['placeholder', 5] );
		?>
	</div>

	<!-- fp-league-info -->
	<div id="fp-league-info" class="shortcode-options-panel mce-container">
		<?php league_select( 'league-info' ); ?>
		<div>
			<fieldset>
				<legend class="mce-label">
					<?php _e( 'Show this info', 'football-pool' ); ?>
				</legend>
				<div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="league-info-name" name="league-info-info" value="name" checked="checked">
							<?php _e( 'name', 'football-pool' ); ?>
						</label>
					</div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="league-info-points" name="league-info-info" value="points">
							<?php _e( 'points', 'football-pool' ); ?>
						</label>
					</div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="league-info-avgpoints" name="league-info-info" value="avgpoints" >
							<?php _e( 'average points', 'football-pool' ); ?>
						</label>
					</div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="league-info-wavgpoints" name="league-info-info" value="wavgpoints">
							<?php _e( 'weighted average points', 'football-pool' ); ?>
						</label>
					</div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="league-info-playernames" name="league-info-info" value="playernames">
							<?php _e( 'player names', 'football-pool' ); ?>
						</label>
					</div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="league-info-numplayers" name="league-info-info" value="numplayers">
							<?php _e( 'number of players', 'football-pool' ); ?>
						</label>
					</div>
				</div>
			</fieldset>
		</div>
		<?php
		ranking_select_with_default( 'league-info' );
		label_textbox( __( 'Format', 'football-pool' ), 'league-info-format', array( 'label_link' => '//php.net/manual/en/function.sprintf.php' ) );
		?>
	</div>
	
	<!-- fp-link -->
	<div id="fp-link" class="shortcode-options-panel mce-container">
		<div>
			<label class="mce-label" for="slug"><?php _e( 'Select a page', 'football-pool' ); ?></label>
			<div>
				<select id="slug">
					<?php
					$pages = Football_Pool::get_pages();
					foreach ( $pages as $page ) {
						printf( '<option value="%s">%s</option>', $page['slug'], __( $page['title'], 'football-pool' ) );
					}
					?>
				</select>
			</div>
		</div>
	</div>
	
	<!-- fp-register -->
	<div id="fp-register" class="shortcode-options-panel mce-container">
		<?php
		label_textbox( __( 'Link title', 'football-pool' ), 'link-title' );
		label_checkbox( __( 'New window?', 'football-pool' ), 'link-window' );
		?>
	</div>
	
	<!-- fp-countdown -->
	<div id="fp-countdown" class="shortcode-options-panel mce-container">
		<div>
			<fieldset>
				<legend><?php _e( 'Countdown to', 'football-pool' ); ?></legend>
				<div>
					<div>
						<label class="mce-label">
							<input type="radio" id="count-to-match" name="count_to" value="match" checked="checked" 
								onclick="FootballPoolAdmin.toggle_linked_options( '#count-match-row', '#count-date-row' )">
							<?php _e( 'Match', 'football-pool' ); ?>
						</label>
					</div>
					<div>
						<label class="mce-label">
							<input type="radio" id="count-to-date" name="count_to" value="date" 
								onclick="FootballPoolAdmin.toggle_linked_options( '#count-date-row', '#count-match-row' )">
							<?php _e( 'Date', 'football-pool' ); ?>
						</label>
					</div>
				</div>
			</fieldset>
		</div>
		<?php
		label_textbox(
			__( 'Date', 'football-pool' ),
			'count-date',
			array( 
				'label_link' => '//php.net/manual/en/function.date.php',
				'label_tooltip' => __( 'information about PHP\'s date format', 'football-pool' ),
				'placeholder' => 'Y-m-d H:i',
				'div_id' => 'count-date-row',
			)
		);
		?>
		<div id="count-match-row">
			<label class="mce-label" for="count-match"><?php _e( 'Match', 'football-pool' ); ?></label>
			<div>
				<select class="mce-select" id="count-match">
					<optgroup label="<?php _e( 'default', 'football-pool' ); ?>">
						<option value="0" selected="selected"><?php _e( 'first match', 'football-pool' ); ?></option>
					</optgroup>
					<optgroup label="<?php _e( 'other', 'football-pool' ); ?>">
						<option value="next"><?php _e( 'next match', 'football-pool' ); ?></option>
					</optgroup>
					<optgroup label="<?php _e( 'or choose a match', 'football-pool' ); ?>">
						<?php echo match_options(); ?>
					</optgroup>
				</select>
			</div>
		</div>
		<div id="count-texts">
			<label class="mce-label" for="text-1">
				<a target="_blank" href="<?php echo $admin_url; ?>admin.php?page=footballpool-help#shortcodes" 
					title="<?php _e( 'see help page for more info', 'football-pool' ); ?>">
						<?php _e( 'Texts for counter', 'football-pool' ); ?>
				</a>
			</label>
			<div>
				<label>
					<input type="checkbox" id="count-no-texts" value="1" onchange="FootballPoolTinyMCE.toggle_count_texts( this.id )">
					<?php _e( 'no texts', 'football-pool' ); ?>
				</label>
			</div>
			<div>
				<input class="mce-textbox" type="text" id="text-1" placeholder="<?php _e( 'before - time not passed', 'football-pool' ); ?>" 
					title="<?php _e( "Leave empty for the default texts. Don't forget spaces between a text and the timer.", 'football-pool' ); ?>" >
				<input class="mce-textbox" type="text" id="text-2" placeholder="<?php _e( 'after - time not passed', 'football-pool' ); ?>" 
					title="<?php _e( "Leave empty for the default texts. Don't forget spaces between a text and the timer.", 'football-pool' ); ?>">
				<br>
				<input class="mce-textbox" type="text" id="text-3" placeholder="<?php _e( 'before - time passed', 'football-pool' ); ?>" 
					title="<?php _e( "Leave empty for the default texts. Don't forget spaces between a text and the timer.", 'football-pool' ); ?>">
				<input class="mce-textbox" type="text" id="text-4" placeholder="<?php _e( 'after - time passed', 'football-pool' ); ?>" 
					title="<?php _e( "Leave empty for the default texts. Don't forget spaces between a text and the timer.", 'football-pool' ); ?>">
			</div>
		</div>
		<?php label_checkbox( __( 'Display inline', 'football-pool' ), 'count-inline' ); ?>
		<div>
			<label class="mce-label" for="count-format"><?php _e( 'Time format', 'football-pool' ); ?></label>
			<div>
				<select class="mce-select" id="count-format">
					<option value="2">
						<?php
							printf( '%s, %s, %s, %s'
									, __( 'days', 'football-pool' )
									, __( 'hours', 'football-pool' )
									, __( 'minutes', 'football-pool' )
									, __( 'seconds', 'football-pool' )
								);
						?>
					</option>
					<option value="4">
						<?php
							printf( '%s, %s, %s'
									, __( 'days', 'football-pool' )
									, __( 'hours', 'football-pool' )
									, __( 'minutes', 'football-pool' )
								);
						?>
					</option>
					<option value="3">
						<?php
							printf( '%s, %s, %s'
									, __( 'hours', 'football-pool' )
									, __( 'minutes', 'football-pool' )
									, __( 'seconds', 'football-pool' )
								);
						?>
					</option>
					<option value="5">
						<?php
							printf( '%s, %s'
									, __( 'hours', 'football-pool' )
									, __( 'minutes', 'football-pool' )
								);
						?>
					</option>
					<option value="1"><?php _e( 'only seconds', 'football-pool' ); ?></option>
				</select>
			</div>
		</div>
		<?php
		label_textbox(
			__( 'Format string', 'football-pool' ),
			'count-format-string',
			array( 
				'label_link' => $admin_url . 'admin.php?page=footballpool-help#shortcodes',
				'label_tooltip' => __( 'see help page for more info', 'football-pool' ),
				'placeholder' => '{d} {days}, {h} {hrs}, {m} {min}, {s} {sec}',
			)
		);
		?>
	</div>

	<!-- fp-match-scores -->
	<div id="fp-match-scores" class="shortcode-options-panel mce-container">
		<?php
		league_select_with_default( 'match-scores' );
		user_select_multiple( 'match-scores' );
		match_select_multiple( 'match-scores' );
		matchtype_select( 'match-scores' );
		label_select( 'Display', 'match-scores-display', array(
			'points' => __( 'Points scored', 'football-pool' ),
			'predictions' => __( 'Predictions', 'football-pool' ),
			'both' => __( 'Both', 'football-pool' ),
		) );
		label_checkbox( __( 'Hide zeroes?', 'football-pool' ), 'match-scores-hide-zeroes' );
		label_checkbox( __( 'Show total?', 'football-pool' ), 'match-scores-show-total' );
		label_checkbox( __( 'Use querystring?', 'football-pool' ), 'match-scores-use-querystring' );
		?>
	</div>

	<!-- fp-question-scores -->
	<div id="fp-question-scores" class="shortcode-options-panel mce-container">
		<?php
		league_select_with_default( 'question-scores' );
		user_select_multiple( 'question-scores' );
		question_select_multiple( 'question-scores' );
		label_checkbox( __( 'Hide zeroes?', 'football-pool' ), 'question-scores-hide-zeroes' );
		label_checkbox( __( 'Show total?', 'football-pool' ), 'question-scores-show-total' );
		label_checkbox( __( 'Use querystring?', 'football-pool' ), 'question-scores-use-querystring' );
		?>
	</div>

	<!-- fp-plugin-option -->
	<div id="fp-plugin-option" class="shortcode-options-panel mce-container">
		<?php
		label_textbox( __( 'Option key', 'football-pool' ), 'plugin-option-key' );
		label_textbox( __( 'Default value', 'football-pool' ), 'plugin-option-default' );
		// label_textbox( __( 'Type', 'football-pool' ), 'plugin-option-type', array( 'placeholder' => 'int or text (default)' ) );
		?>
		<div>
			<fieldset>
				<legend class="mce-label">
					<?php _e( 'Type', 'football-pool' ); ?>
				</legend>
				<div>
					<div>
						<label class="mce-label">
							<input type="radio" id="plugin-option-type-text" name="plugin-option-type" value="text">
							<?php _e( 'Text', 'football-pool' ); ?>
						</label>
					</div>
					<div>
						<label class="mce-label">
							<input type="radio" id="plugin-option-type-int" name="plugin-option-type" value="int">
							<?php _e( 'Numeric', 'football-pool' ); ?>
						</label>
					</div>
				</div>
			</fieldset>
		</div>
	</div>

	<!-- fp-money-in-the-pot -->
	<div id="fp-money-in-the-pot" class="shortcode-options-panel mce-container">
		<?php
		league_select_with_default_and_user( 'money-in-the-pot', true );
		label_textbox( __( 'Amount', 'football-pool' ), 'money-in-the-pot-amount', array( 'placeholder' => 0 ) );
		label_textbox( __( 'Format', 'football-pool' ), 'money-in-the-pot-format', array( 'label_link' => '//php.net/manual/en/function.sprintf.php' ) );
		?>
	</div>

	<!-- fp-last-calc-date -->
	<div id="fp-last-calc-date" class="shortcode-options-panel mce-container">
		<?php
		label_textbox( __( 'Format', 'football-pool' ), 'last-calc-date-format', array( 'label_link' => 'https://www.php.net/manual/en/datetime.format.php' ) );
		?>
	</div>

	<!-- fp-shortcode -->
	<div id="fp-shortcode" class="shortcode-options-panel mce-container">
		<div>
			<label class="mce-label">label</label>
			<div>
				input
			</div>
		</div>
	</div>
</form>
</body>
</html>
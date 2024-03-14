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

class Football_Pool_Admin_Help extends Football_Pool_Admin {
	public function __construct() {}
	
	private static function calc_score( $home, $away, $user_home, $user_away, $joker_multiplier ) {
		global $pool;
		$score = $pool->calc_score( $home, $away, $user_home, $user_away, 0, 0, 0 );
		
		return $joker_multiplier * $score['score'];
	}
	
	private static function echo_score( $home, $away, $user_home, $user_away, $joker_multiplier, $calculation ) {
		printf( '<span title="outcome via calc_score method = %s">%s</span>'
			, self::calc_score( $home, $away, $user_home, $user_away, $joker_multiplier )
			, $calculation
		);
	}
	
	public static function admin() {
		$img_dir = FOOTBALLPOOL_ASSETS_URL . 'admin/images/';
		$user_img_dir = FOOTBALLPOOL_UPLOAD_URL;
		
		$totopoints = Football_Pool_Utils::get_fp_option( 'totopoints', FOOTBALLPOOL_TOTOPOINTS, 'int' );
		$fullpoints = Football_Pool_Utils::get_fp_option( 'fullpoints', FOOTBALLPOOL_FULLPOINTS, 'int' );
		$goalpoints = Football_Pool_Utils::get_fp_option( 'goalpoints', FOOTBALLPOOL_GOALPOINTS, 'int' );
		$diffpoints = Football_Pool_Utils::get_fp_option( 'diffpoints', FOOTBALLPOOL_DIFFPOINTS, 'int' );
		$joker_multiplier = 1; // set this by default to 1 because we don't use it in our examples (yet)

		self::admin_header( __( 'Help', 'football-pool' ), '' );
		?>
		<div class="help-page">
			<h2 id="introduction">Introduction</h2>
			<p>
			The Football Pool plugin installs a fantasy sports pool in your WordPress blog. In the default configuration
				this plugin enables you to define matches between (football) teams and lets your blog visitors predict
				the outcomes of the matches. Players earn points for correct predictions and the best player wins the pool.
			</p>
			<p>
			There are several ways you can customize the plugin, e.g. different scores for correct answers, add bonus questions,
				add your own rankings, etc. See the contents of this help file for details. If you have any questions,
				you can ask them on the
				<a target="_blank" href="http://wordpress.org/support/plugin/football-pool">WordPress support forum</a>.
			</p>

			<h2>Index</h2>
			<ol>
				<li><a href="#introduction">Introduction</a></li>
				<li><a href="#tutorial">Video tutorial</a></li>
				<li><a href="#admin">Admin pages</a></li>
				<li><a href="#times">Time</a></li>
				<li><a href="#points">Points</a></li>
				<li><a href="#rankings">Rankings &amp; Scoring</a></li>
				<li><a href="#leagues">Leagues</a></li>
				<li><a href="#players">Players</a></li>
				<li><a href="#predictions">Predictions</a></li>
				<li><a href="#bonusquestions">Bonus questions</a></li>
				<li><a href="#teams-groups-and-matches">Teams, groups and matches</a></li>
				<li><a href="#layout">Changing the plugin layout</a></li>
				<li><a href="#shortcodes">Shortcodes</a></li>
				<li><a href="#charts">Using charts</a></li>
                <li><a href="#hooks">Extending the plugin: Actions and Filters</a></li>
                <li><a href="#cli">WP CLI commands</a></li>
				<li><a href="#caching">Caching plugins</a></li>
				<li><a href="#the-end">Anything else? And contact details.</a></li>
			</ol>

			<h2 id="tutorial">Video tutorial</h2>
			<p>
				First, Janek from WP Simple Hacks website made a very nice
				<a href="https://wpsimplehacks.com/how-to-create-a-football-pool-site-with-wordpress/" target="_blank">guide
					about my plugin</a>. It even has a video where he explains how to set up the plugin. This can be a
				really good start when you first start using the plugin. The basic concepts are explained on the website
				and in the video.
				Then, if you want more information, you can use this help page to learn about all concepts, settings,
				shortcodes, etc. in more detail.
			</p>

			<h2 id="admin">Admin pages</h2>
			<p>
				The admin pages of the plugin let you define all necessary parts of the plugin. Every admin page contains
				contextual help: use the help tab at the top right of every screen if you need information about the
				admin page.<br>
				<img class="screenshot" src="<?php echo $img_dir; ?>screenshot-admin-help.png" alt="screenshot">
			</p>
			<p>You can use this help file for more detailed information about all the aspects of the plugin.</p>

			<h2 id="times">Time</h2>
			<h3>What's with the stop times, dynamic times, etc.? I don't get it.</h3>
			<p>
				Let me explain. Users have only a limited amount of time to fill in or change their predictions.
				For matches you can choose between a certain amount of time before the kickoff of the match (dynamic
				time), or a single date/time for all matches. The default is a dynamic time setting of 900 seconds
				(= 15 minutes) before the start of a match.<br>
				Bonus questions each have an 'answer before' date and time. But you may override these individual values
				with a single stop time for all bonus questions. The default is to allow for a 'answer before' time per
				question.
			</p>
		
			<h3>Questions and plugin settings</h3>
			<p>
				The times in the plugin options and the 'answer before' times in the bonus question admin must be
				entered in local time (the plugin stores them in the database in UTC).
			</p>
		
			<h3>Matches</h3>
			<p>
				<strong>Matches have to be entered or imported with
					<a target="_blank" href="http://en.wikipedia.org/wiki/Coordinated_Universal_Time"
					   title="Coordinated Universal Time">UTC</a> times</strong> for the kickoff. The admin screen also
				shows the times for the match in your own timezone (according to the <a href="options-general.php">setting
					in WordPress</a>) so you can check if the times are correct.
			</p>

			<div class="help important">
				<p><strong>Debugging timezone problems</strong></p>
				<p><strong>Tip:</strong> Always test if your
					<a href="options-general.php" title="WordPress general settings">timezone setting</a> and
					<a href="admin.php?page=footballpool-options" title="Football Pool plugin settings">plugin times</a>
					are correct. Change the date of one of your bonus questions and one of your matches (or the
					corresponding stop time in the plugin settings) and check if the question and match are correctly
					blocked or open. If not, check your plugin settings and WordPress settings.
				</p>
				<p>The plugin also has a helper page that displays some debug info on your plugin and server settings. The helper page can be found <a target="_blank" href="<?php echo FOOTBALLPOOL_PLUGIN_URL, 'admin/timezone-test.php'; ?>" title="debug info on date and time settings">here</a>.</p>
			</div>
		
			<h2 id="points">Points</h2>
			<p>
				The plugin uses 4 different scores that are rewarded to players for the match predictions they do:
				<strong>full points</strong>, <strong>toto points</strong>, <strong>goal bonus</strong>
				and <strong>goal difference bonus</strong>.
				The <strong>toto points</strong> are rewarded if the right match result is predicted (win, loss or draw).
				A player gets the <strong>full score</strong> if also the exact amount of goals was predicted.
			</p>
			<p>
				If you set the <strong>goal bonus</strong> to anything other than zero (default is zero), then this
				bonus is added to the scored points if the goals predicted are right; even if the match result was
				wrong (e.g. result is 2-1 and user predicted 1-1). <strong>Beware</strong>: these points are also
				rewarded double in case of a full score (win or draw), because the player has both amount of goals
				correct (see examples below).
			</p>
			<p>
				If you set the <strong>goal difference bonus</strong> to anything other than zero (default is zero),
				then this bonus is added to the scored points if the user predicted the correct winner and the user
				was right about the goal difference (e.g. result is 2-1 and the user predicted 3-2).
				This bonus is <strong>not</strong> rewarded when the user predicted the wrong winner (e.g. result
				is 2-1 and the user predicted 2-3), when already a full score was awarded or when the match result
				is a draw (e.g. 2-2).
			</p>
			<p>
				Your current settings are:
			</p>
			<table>
				<tr><td>full points:</td><td><?php echo $fullpoints; ?></td></tr>
				<tr><td>toto points:</td><td><?php echo $totopoints; ?></td></tr>
				<tr><td>goal bonus:</td><td><?php echo $goalpoints; ?></td></tr>
				<tr><td>goal difference bonus:</td><td><?php echo $diffpoints; ?></td></tr>
			</table>
			<p></p>
			<table class="widefat help">
			<tr>
				<th>match result</th>
				<th>user predicted</th>
				<th>points scored *</th>
			</tr>
			<tr>
				<td>3-1</td>
				<td>1-0</td>
				<td>
					toto points.<br>
					total = <?php self::echo_score( 3, 1, 1, 0, $joker_multiplier, ( $totopoints ) ); ?>
				</td>
			</tr>
			<tr>
				<td>3-1</td>
				<td>2-0</td>
				<td>
					toto points plus goal difference bonus for the correct goal difference (2 goals difference).<br>
					total = <?php echo $totopoints; ?> + <?php echo $diffpoints; ?> =
						<?php self::echo_score( 3, 1, 2, 0, $joker_multiplier, ( $totopoints + $diffpoints ) ); ?>
				</td>
			</tr>
			<tr>
				<td>3-1</td>
				<td>3-0</td>
				<td>
					toto points plus goal bonus for the correct amount of goals for the home team.<br>
					total = <?php echo $totopoints; ?> + <?php echo $goalpoints; ?> =
						<?php self::echo_score( 3, 1, 3, 0, $joker_multiplier, ( $totopoints + $goalpoints ) ); ?>
				</td>
			</tr>
			<tr>
				<td>2-1</td>
				<td>2-1</td>
				<td>
					full points plus two times the goal bonus for the correct amount of goals for the home team and
					the away team.<br>
					total = <?php echo $fullpoints; ?> + <?php echo $goalpoints; ?> + <?php echo $goalpoints; ?> =
						<?php self::echo_score( 2, 1, 2, 1, $joker_multiplier, ( $fullpoints + ( 2 * $goalpoints ) ) ); ?>
				</td>
			</tr>
			<tr>
				<td>2-1</td>
				<td>1-1</td>
				<td>
					goal bonus for the correct amount of goals for the away team.<br>
					total = <?php self::echo_score( 2, 1, 1, 1, $joker_multiplier, ( $goalpoints ) ); ?>
				</td>
			</tr>
			<tr>
				<td>2-1</td>
				<td>0-0</td>
				<td><?php self::echo_score( 2, 1, 0, 0, $joker_multiplier, 'no points' ); ?></td>
			</tr>
			<tr>
				<td>1-1</td>
				<td>1-1</td>
				<td>
					full points plus two times the goal bonus for the correct amount of goals for the home team and
					the away team.<br>
					total = <?php echo $fullpoints; ?> + <?php echo $goalpoints; ?> + <?php echo $goalpoints; ?> =
						<?php self::echo_score( 1, 1, 1, 1, $joker_multiplier, ( $fullpoints + ( 2 * $goalpoints ) ) ); ?>
				</td>
			</tr>
			<tr>
				<td>1-1</td>
				<td>0-0</td>
				<td>
					toto points.<br>
					total = <?php self::echo_score( 1, 1, 0, 0, $joker_multiplier, ( $totopoints ) ); ?>
				</td>
			</tr>
			</table>
			<p><em>* Based on your current settings. Hover over the outcome to see the calculated result.</em></p>
			<h3>The multiplier (joker)</h3>
			<p>
				If enabled by the admin (default is true), a player in the pool gets one (or more) multipliers.
				A multiplier can be used by a player on a match to increase the points for that match when the prediction
				is correct.<br>
				The multiplier may be placed and/or moved to other matches as long the prediction for a match is still
				changeable. A multiplier is activated at the moment the match that it is placed on, is locked.
				And once activated the multiplier cannot be moved anymore.
			</p>
			<p>
				Players can set the multiplier by clicking on the football icon in the match card.
			</p>
			<p>
				The plugin has a couple of options on the settings page to enable/disable the multiplier functionality,
				to set the amount of multipliers that a player can use and the factor with which a score is multiplied.
			</p>

			<h2 id="rankings">Rankings &amp; Scoring</h2>
			<p>
				The players of the plugin are ranked in a list (a ranking) that adds up the points scored for all matches
				and all questions in the pool (this is called the default ranking).<br>
				But the plugin also has the ability to calculate a ranking of just a subset of the matches and/or bonus
				questions (e.g. a ranking for the first half of the season and one for the second half). If you want to
				use this feature, make a new <a href="?page=footballpool-rankings">ranking</a> and attach the required
				matches and/or questions to this ranking. This is called the ranking definition. The custom rankings can
				be used with a ranking shortcode, in a ranking widget or on the ranking and charts page.
			</p>
			<p>
				See the <a href="#shortcodes">shortcode section</a> for details about the use of these custom rankings in
				your posts or pages.
			</p>
			<p>
				Only matches with a match date in the past (date lies before the start time of the calculation) are used
				to calculate the ranking. And the same applies to bonus questions: only questions for which the score
				date lies before the start of the calculation are added to the total score for the user.
			</p>
			<h3 id="ranking-calculation">Ranking calculation</h3>
			<p>
				By default an admin will be automatically notified for a (re)calculation of the rankings when saving a
				match or question, or when changing your pool players. If you want to (temporarily) disable this
				automatic calculation, e.g. when you want to enter multiple matches at once or when you
				<a href="#ranking-calculation-wp-cli">use WP-CLI</a> to do the calculation, you may disable this
				feature in the <a href="?page=footballpool-options#option-section-1">plugin options</a> and do a
				manual recalculation when you've finished editing.
			</p>
			<div class="help important">
				<p><strong>Important:</strong> Calculating a ranking takes time. The more players or rankings you have,
					the more time it takes to (re)calculate the ranking tables. All rankings are 'cached' in the database.
					So, once calculated, your players/visitors shouldn't notice a delay when displaying a ranking, but an
					admin saving a match outcome or adding the scores for a bonus question will have to wait for the
					ranking calculations to finish.
				</p>
			</div>
			<div class="help important">
				<p><strong>Tip:</strong> On my local machine I have found that
					<a href="?page=footballpool-help#ranking-calculation-wp-cli">using WP-CLI</a> to do the calculation
					is a lot faster than executing it via the admin interface. In some tests up to 10 times faster!</p>
			</div>
		
			<h3 id="ranking-calculation">Default calculation or simple calculation</h3>
			<p>
				The plugin calculates the ranking and scores for each player at any point in time (that is, for the points
				in time when there are matches or questions). This 'historic' data is used by the charts and the shortcodes
				to display a rank or amount of points at a certain time in a tournament.
			</p>
			<p>
				And while this is a pretty cool feature, it comes with the downside that these calculations take a long
				time to complete. Especially when the tournament is in its final stages (a lot of matches to process)
				and/or when you have a lot of players in the pool. Also, creating custom rankings adds to this
				calculation time.<br>
				You can boost calculation with a bigger, better server or investing time in optimizing the DB performance.
				But adding more resources or doing optimizations will not always help. Or, this not always possible for you.
			</p>

			<p>
				The plugin also has the option to <a href="admin.php?page=footballpool-options#option-section-1"
				                                     target="_blank">switch to a simple calculation</a> method.
				This calculation does not calculate all values in time, but only the latest status. This drastically
				reduces the time needed for a calculation because it will only calculate and store the latest ranking.
				But with the calculation method in simple mode it is therefor no longer possible to use charts. The
				charts will be automatically hidden, regardless of your settings. And it is no longer possible to use
				a date parameter in the shortcodes for ranking or score (e.g.
				<span class="code">[fp-user-rank date="2018-06-24 12:00"]</span>). The plugin will simply ignore the
				date parameter and will just return the latest status.
			</p>
			<div class="help important">
				<p><strong>Important:</strong> After a switch to another calculation method, you have to do a
					calculation to change the data in the database to match the calculation method!</p>
			</div>
			<div class="help important">
				<p><strong>Tip:</strong> If you decide to switch to another calculation method when your pool already
					started, I recommend putting your site in maintenance mode first before you start the calculation.
					Because when switching the calculation the current ranking will cause strange effects. Another
					option is to truncate the ranking tables before you switch the mode.
				</p>
			</div>

			<h3>What if the calculation gives an error?</h3>
			<p>
				When the calculation gives an error there are several things that can be the cause of that. For example,
				the necessary truncate/drop or delete rights on the database could be missing, or some other database
				error. There is only one thing that you can do to know what caused the error: set the
				<a href="https://wordpress.org/support/article/debugging-in-wordpress/" title="More info about the
				debug settings of WordPress" target="_blank">debug settings</a> of WordPress to true. Go to your
				wp-config.php file, set/change the following constants and do a calculation. This will generate a
				debug.log file in your wp-content folder.
			</p>
			<?php
			$code_block = <<<'EOT'
<?php
// Enable WP_DEBUG mode
define( 'WP_DEBUG' , true );

// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );
EOT;
			Football_Pool_Utils::highlight_string( $code_block );
			?>

			<p>
				If you need help with the error, then open a topic on the
				<a href="http://wordpress.org/support/plugin/football-pool" target="_blank">support forum</a> of the
				plugin. Please also post the log messages.
			</p>

			<h3 id="calculation-already-running">"Calculation already running" message</h3>
			<p>
				Plugin version 2.5.0 got a new calculation method. A calculation is being processed in a temporary table
				and once the calculation is finished, the temp table is activated. Only one calculation can run and if
				one is already running, other admins get a message that they will have to wait.
				If - for some reason - the plugin keeps telling you that a calculation is already running, but you are
				sure that this is wrong, you can override this message and still start a new one by adding the following
				constant in your wp-config.php file or by starting the calculation with the button below this paragraph
				(button is only active if calculation is stuck and AJAX requests are enabled).
			</p>
			<?php
			$code_block = <<<'EOT'
<?php
define( 'FOOTBALLPOOL_FORCE_CALCULATION' , true );
EOT;
			Football_Pool_Utils::highlight_string( $code_block );
			?>
			<p>
				If the calculation stopped with an error, or if it was interrupted before it could finish, the above may
				also apply.
			</p>
			<?php
			if ( Football_Pool_Utils::get_fp_option( 'calculation_in_progress', 0, 'int' ) === 1
				&& FOOTBALLPOOL_RANKING_CALCULATION_AJAX ) {
				echo self::link_button(
					__( 'Force Calculation', 'football-pool' ),
					array( '', 'FootballPoolAdmin.force_calculation()' ),
					true,
					'js-button',
					'primary'
				);
			} else {
				echo self::link_button(
					__( 'Force Calculation', 'football-pool' ),
					array( '', 'return false' ),
					true,
					'js-button',
					'primary disabled'
				);
			}
			?>

			<h2 id="leagues">Leagues</h2>
			<p>
				The plugin supports placing players in different leagues. For example when you want to group players
				per department, or friends and family, or paying and non-paying, etc. When playing with leagues an
				admin has to 'approve' the league for which a player subscribed. That can be done on the
				<a href="?page=footballpool-users">Users page</a> of the Football Pool plugin.
			</p>
			<p>
				When using leagues all players have to be a member of a league, otherwise they are not considered to
				be a pool player.
			</p>

			<h2 id="players">Players</h2>
			<p>
				There are two ways the plugin can handle your blog users: via leagues or not via leagues. If playing
				with leagues, your blog users have to be in an active league before they can participate in the pool.
				New subscribers to your blog choose a league when subscribing, but existing users have to change this
				setting after the plugin is installed (or the admin can do this for them on the
				<a href="?page=footballpool-users">Users page</a>).
			</p>
			<p>
				When not using leagues all your blog users are automatically players in the pool. If you want to exclude
				some players from the rankings (e.g. the admin), you can disable them in the
				<a href="?page=footballpool-users">Users page</a> of the Football Pool plugin.
			</p>
			<p>
				If you want new players to be able to register to your site, make sure you have this setting enabled in
				the <a href="options-general.php">general settings</a> of WordPress.
			</p>

			<h2 id="predictions">Predictions</h2>
			<h3>Background saving (asychronous)</h3>
			<p>
				The plugin supports asynchronous saves via AJAX calls. This means that predictions can be automatically
				saved without the need for a player to press a save button. This option can be activated by an admin
				on the plugin options screen. Please beware that the asynchronous saves have a small delay before the
				call to the backend is done and, more important, on slow connections (e.g. via a mobile network) this
				might cause problems for your players if they miss that the save has not finished. Therefore, the option
				is disabled by default.
			</p>
			<h3>Audit log</h3>
			<p>
				The plugin logs all changes in predictions to the database. An admin has access to these logs and can
				filter the log on player or search the log on date. The last save date for a player can be shown on the
				frontend via the shortcode [fp-last-save] and this date is also outputted next to the save button as
				a hidden element (you can show it via CSS).
			</p>

			<h2 id="bonusquestions">Bonus questions</h2>
			<h3>Types</h3>
			<p>There are 3 types of bonus questions:
			<ol>
				<li>Text questions (single line and multi-line)</li>
				<li>Multiple choice questions (one answer)</li>
				<li>Multiple choice questions (one or more answers)</li>
			</ol>
			Each question type can also show an (optional) image.
			</p>
			<p>
				For multiple choice questions you have to give 2 or more options to the players. The possible answers
				must be entered as a semicolon separated list. Bonus questions have an 'auto set' checkbox for the
				multiple choice questions. If this checkbox is checked on a save of the answer, all user answers will
				be checked against this answer (using a case-insensitive text compare) and the 'correct' setting for
				these user answers will be auto set after the save action.
			</p>
			<h3>Giving points</h3>
			<p>
				After the 'answer before' date has passed and your players may not alter their answers, an admin has to
				manually approve all answers for a question. For this, go to the
				<a href="?page=footballpool-bonus">Questions admin screen</a> and click on the "User Answers" link.
				Multiple choice questions can be automatically validated with the 'auto set' checkbox on the edit screen
				of the question (just make sure the answer you use on this screen is exactly the same as one of the given
				options).<br>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-bonus-user-answers-1.png" alt="screenshot">
			</p>
			<p>
				In the user answer screen information about the question is shown as a reference (1). The question and
				default points are shown and - if an admin has filled in the answer - the answer is also shown.<br>
				For each player click the appropiate radiobutton for a right or wrong answer (2). If an answer is
				considered correct you have the option to give a different amount of points to that user (3). For
				example to give extra bonuspoints or to give half the points for an incomplete answer. Leave blank if
				you want to give the default points for the question.<br>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-bonus-user-answers-2.png" alt="screenshot">
			</p>
			<div class="help important">
				<p>
					Please note that for points for bonus questions to be added to the total points for a player, an
					admin also has to fill in the score date field for that question. The score date is used to determine
					the order in which points are plotted in the charts and to determine if the question should be added
					in the ranking calculation. If the score date is not set by the admin, then the score date will be
					automatically set to the current time and date upon a save of the user answers. If a score date is
					set in the future, then the points will only be added to the total score when a calculation is started
					after that date.
				</p>
			</div>

			<h2 id="teams-groups-and-matches">Teams, groups and matches</h2>
			<p>
				In the pool your blog users can predict the outcome of matches in a competition. A competition consists
				of multiple teams that play each other in matches. The game schedule can be entered on the
				<a href="?page=footballpool-games">Matches</a> admin page. On that page the matches may be entered
				manually or uploaded via a CSV file. See information below about the format of the CSV file, or export
				an existing game schedule for an example. The format of the export (full data or minimal data) can be
				set on the options page of the plugin.
			</p>
			<h3>Groups</h3>
			<p>
				Teams may be grouped in groups. For example Group A, Group B, etc. for a tournament. Or in one group in
				the case of a national competition. The groups page in the blog (/tournament/groups) shows the ranking
				table for matches played in match type 1 by default. If you wish to use another match type for the
				calculation of points for a team, or use multiple match types, you can alter this in the
				<a href="?page=footballpool-options">plugin options</a>.
			</p>

			<h3>Photos and flags</h3>
			<p>
				Assets can be uploaded to the football-pool dir in the WP uploads dir
				<?php echo FOOTBALLPOOL_UPLOAD_DIR; ?>.
			</p>

			<h3>CSV file import</h3>
			<p>
				The CSV file (must be in <a target="_blank"
				                            href="http://superuser.com/questions/479756/eol-in-notepad-and-notepad"
				                            title="tip: use Notepad++ to convert to the correct EOL format">UNIX or
					Windows/DOS EOL format</a>) can be uploaded in one of the following formats:
			</p>
			<ol>
				<li>minimal data (only the basic information about teams);</li>
				<li>full data (all information).</li>
			</ol>
			<p>
				If you choose the minimal data, extra information about stadiums and teams may be entered on the individual
				admin pages. If a team, stadium, group or match type in the CSV file does not already exist, it will be
				added to the database.
				For the full data all information about teams, venues, etc. must be given. If a team, venue, etc. already
				exists, it won't be updated. If a team does not exist, the information (e.g. photo) in the first row where
				that item appears, will be added in the database.
			</p>

			<p>
				If a culture code is included in the filename, e.g. <span class="code">uefa2012-en_US.txt</span>, then
				the plugin can filter the files according to the culture that is set as the locale for the blog.
			</p>
			<p>
				The header of the file may contain optional meta information about the author of the import and/or the
				location of the assets for the teams and venues. If meta information exists in the CSV file, the
				information is added on the file select list. File header example:
			</p>
			<pre class="code">
			/*
			 Contributor: Antoine Hurkmans
			 Assets URI: https://dl.dropbox.com/u/397845/wordpressfootballpool/uefa-european-championship-2012.zip
			*/
			</pre>
			<p>
				or, when you want to give credits to the original author of the schedule when you only translated
				the team names, etc.:
			</p>
			<pre class="code">
			/*
			 Contributor: Antoine Hurkmans
			 Translator: John Doe
			 Assets URI: https://dl.dropbox.com/u/397845/wordpressfootballpool/uefa-european-championship-2012.zip
			*/
			</pre>

			<div class="help important">
				<p><strong>Important note: </strong>
					Versions 2.9.3 and below used a semicolon (";") as delimiter for the values in the file.
					Higher versions use the comma (","). If you would like to keep using the
					semicolon, then add the following line to your wp-config:</p>
				<p><span class="code">define( 'FOOTBALLPOOL_CSV_DELIMITER', ';' );</span></p>
			</div>

			<h4>Minimal data</h4>
			<!--p>
			<em>CSV file header:</em> play_date;home_team;away_team;stadium;match_type
			</p-->
			<table class="widefat help" caption="Minimal data">
				<tr><th>column</th><th>description</th><th>example</th></tr>
				<tr>
					<td class="row-title">play_date</td>
					<td>The date and start time of the match in Y-m-d H:i notation (<a href="#times">UTC</a>).</td>
					<td>2012-10-28 18:00</td>
				</tr>
				<tr>
					<td class="row-title">home_team</td>
					<td>Name of a team. Teams may be added upfront on the <a href="?page=footballpool-teams">teams
							admin page</a>.
					</td>
					<td>The Netherlands</td>
				</tr>
				<tr>
					<td class="row-title">away_team</td>
					<td>Name of a team. Teams may be added upfront on the <a href="?page=footballpool-teams">teams
							admin page</a>.
					</td>
					<td>England</td>
				</tr>
				<tr>
					<td class="row-title">stadium</td>
					<td>Name of a stadium. Stadiums may be added upfront on the <a href="?page=footballpool-venues">venues
							admin page</a>.
					</td>
					<td>Olympic Stadium</td>
				</tr>
				<tr>
					<td class="row-title">match_type</td>
					<td>Matches may be grouped with a match type. Match types may be added upfront on the <a href="?page=footballpool-matchtypes">match type admin page</a>.</td>
					<td>Quarter final</td>
				</tr>
			</table>
			<h4>Full data</h4>
			<!--p>
			<em>CSV file header:</em> play_date;home_team;away_team;stadium;match_type;home_team_photo;home_team_flag;home_team_link;home_team_group;home_team_group_order;home_team_is_real;away_team_photo;away_team_flag;away_team_link;away_team_group;away_team_group_order;away_team_is_real;stadium_photo
			</p-->
			<table class="widefat help" caption="Full data">
				<tr><th>column</th><th>description</th><th>example</th></tr>
				<tr>
					<td class="row-title">play_date</td>
					<td>The date and start time of the match in Y-m-d H:i notation (<a href="#times">UTC</a>).</td>
					<td>2012-10-28 18:00</td>
				</tr>
				<tr>
					<td class="row-title">home_team</td>
					<td>Name of a team. Teams may be added upfront on the <a href="?page=footballpool-teams">teams admin page</a>.</td>
					<td>The Netherlands</td>
				</tr>
				<tr>
					<td class="row-title">away_team</td>
					<td>Name of a team. Teams may be added upfront on the <a href="?page=footballpool-teams">teams admin page</a>.</td>
					<td>England</td>
				</tr>
				<tr>
					<td class="row-title">stadium</td>
					<td>Name of a stadium. Stadiums may be added upfront on the <a href="?page=footballpool-venues">venues admin page</a>.</td>
					<td>Olympic Stadium</td>
				</tr>
				<tr>
					<td class="row-title">match_type</td>
					<td>Matches may be grouped with a match type. Match types may be added upfront on the <a href="?page=footballpool-matchtypes">match type admin page</a>.</td>
					<td>Quarter final</td>
				</tr>
				<tr>
					<td class="row-title">home_team_photo</td>
					<td>Team photo for the home team. Full URL or path relative to "<?php echo $user_img_dir ?>teams/".</td>
					<td>netherlands.jpg</td>
				</tr>
				<tr>
					<td class="row-title">home_team_flag</td>
					<td>Flag image for the home team. Full URL or path relative to "<?php echo $user_img_dir ?>flags/".</td>
					<td>netherlands.png</td>
				</tr>
				<tr>
					<td class="row-title">home_team_link</td>
					<td>Link to a page or website with information about the home team.</td>
					<td>http://www.uefa.com/uefaeuro/season=2012/teams/team=95/index.html</td>
				</tr>
				<tr>
					<td class="row-title">home_team_group</td>
					<td>The group in which the home team is placed.</td>
					<td>Group A</td>
				</tr>
				<tr>
					<td class="row-title">home_team_group_order</td>
					<td>The order in a group in case multiple teams have the same scores.</td>
					<td>1</td>
				</tr>
				<tr>
					<td class="row-title">home_team_is_real</td>
					<td>Is the home team a real team? Example of a real team "The Netherlands", a non-real team "Winner match 30". Can be 1 or 0.</td>
					<td>1</td>
				</tr>
				<tr>
					<td class="row-title">away_team_photo</td>
					<td>Team photo for the away team. Full URL or path relative to "<?php echo $user_img_dir ?>teams/".</td>
					<td>england.jpg</td>
				</tr>
				<tr>
					<td class="row-title">away_team_flag</td>
					<td>Flag image for the away team. Full URL or path relative to "<?php echo $user_img_dir ?>flags/".</td>
					<td>england.png</td>
				</tr>
				<tr>
					<td class="row-title">away_team_link</td>
					<td>Link to a page or website with information about the away team.</td>
					<td>http://www.uefa.com/uefaeuro/season=2012/teams/team=39/index.html</td>
				</tr>
				<tr>
					<td class="row-title">away_team_group</td>
					<td>The group in which the away team is placed.</td>
					<td>Group A</td>
				</tr>
				<tr>
					<td class="row-title">away_team_group_order</td>
					<td>The order in a group in case multiple teams have the same scores.</td>
					<td>1</td>
				</tr>
				<tr>
					<td class="row-title">away_team_is_real</td>
					<td>Is the away team a real team? Example of a real team "The Netherlands", a non-real team "Winner match 30". Can be 1 or 0.</td>
					<td>1</td>
				</tr>
				<tr>
					<td class="row-title">stadium_photo</td>
					<td>Photo of the stadium where the match is played. Full URL or path relative to "<?php echo $user_img_dir ?>stadiums/".</td>
					<td>olympic-stadium.jpg</td>
				</tr>
			</table>

			<h2 id="layout">Changing the plugin layout</h2>
			<h3>Style</h3>
			<p>The plugin has some basic styling that will hopefully not interfere with your theme. If you want to change the style of the plugin, you can do so by using a seperate CSS file in a child theme, or by adding rules to the "<a href="customize.php" target="_blank">Additional/Extra CSS</a>" field of your theme (if your theme supports this). Creating a child theme is <a href="https://developer.wordpress.org/themes/advanced-topics/child-themes/" target="_blank">really simple</a> and is the approach I prefer most.<br>
			Just follow the CSS rules about specificity to overwrite the plugin's style (see Keegan Street's <a target="_blank" href="http://specificity.keegan.st/">specificity calculator</a> for a cool help in determining the specificity of a selector). I don't recommend changing the CSS of the plugin, as it will be overwritten on every update.
			</p>
			<h3>Templates</h3>
			<p>Some data that is displayed in the plugin is handled via a template. These templates consist of HTML and parameters. See the table below for an overview of the templates that are available in the plugin at the moment and the parameters that can be used. A parameter must be surrounded by "<?php echo FOOTBALLPOOL_TEMPLATE_PARAM_DELIMITER; ?>", e.g. <?php echo FOOTBALLPOOL_TEMPLATE_PARAM_DELIMITER; ?>home_team<?php echo FOOTBALLPOOL_TEMPLATE_PARAM_DELIMITER; ?>.</p>
			<p>The templates and the available data (the parameters) can be changed via hooks (see the <a href="#hooks">section about extending the plugin</a> for more information about WordPress hooks).</p>
			<table class="widefat help">
				<tr>
					<th>functionality</th><th>hook</th><th>description</th><th>parameters</th>
				</tr>
				<tr>
					<td>prediction form</td>
					<td>footballpool_predictionform_template_start</td>
					<td>Opening HTML for the prediction form.</td>
					<td>form_id<br>user_id</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_predictionform_template_end</td>
					<td>Closing HTML for the prediction form.</td>
					<td>form_id<br>user_id</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_predictionform_match_template</td>
					<td>HTML for a match row.</td>
					<td>form_id<br>
						match_id<br>
						match_type_id<br>
						match_type<br>
						match_timestamp<br>
						match_date<br>
						match_time<br>
						match_day<br>
						match_datetime_formatted<br>
						match_utcdate<br>
						match_stats_url<br>
						stadium_id<br>
						stadium_name<br>
						home_team_id<br>
						away_team_id<br>
						home_team<br>
						away_team<br>
						home_team_flag<br>
						away_team_flag<br>
						home_score<br>
						away_score<br>
						group_id<br>
						group_name<br>
						home_input<br>
						away_input<br>
						joker<br>
						user_score<br>
						stats_link<br>
						css_class
					</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_predictionform_match_type_template</td>
					<td>HTML for the match type row (placed between matches when there is a new match type).</td>
					<td>see match row</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_predictionform_date_row_template</td>
					<td>HTML for the date row (placed between matches when there is a new match date).</td>
					<td>see match row</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_predictionform_linked_questions_template</td>
					<td>HTML for questions that are linked to a match (placed after the match).</td>
					<td>form_id<br>
						match_id<br>
						question_id<br>
						question
					</td>
				</tr>
				<tr>
					<td>match table</td>
					<td>footballpool_match_table_template_start</td>
					<td>Opening HTML for the match table.</td>
					<td>-</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_match_table_template_end</td>
					<td>Closing HTML for the match table.</td>
					<td>-</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_match_table_match_template</td>
					<td>HTML for a match row.</td>
					<td>match_id<br>
						match_type_id<br>
						match_type<br>
						match_timestamp<br>
						match_date<br>
						match_time<br>
						match_day<br>
						match_datetime_formatted<br>
						match_utcdate<br>
						match_stats_url<br>
						stats_link<br>
						stadium_id<br>
						stadium_name<br>
						home_team_id<br>
						away_team_id<br>
						home_team<br>
						away_team<br>
						home_team_flag<br>
						away_team_flag<br>
						home_score<br>
						away_score<br>
						group_id<br>
						group_name<br>
						css_class
					</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_match_table_match_type_template</td>
					<td>HTML for the match type row (placed between matches when there is a new match type).</td>
					<td>see match row</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_match_table_date_row_template</td>
					<td>HTML for the date row (placed between matches when there is a new match date).</td>
					<td>see match row</td>
				</tr>
				<tr>
					<td>ranking table</td>
					<td>footballpool_ranking_template_start</td>
					<td>Opening HTML for the ranking table.</td>
					<td>-</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_ranking_template_end</td>
					<td>Closing HTML for the ranking table.</td>
					<td>-</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_ranking_ranking_row_template</td>
					<td>HTML for a row in the ranking table.</td>
					<td>rank<br>
						user_id<br>
						user_name<br>
						user_link<br>
						user_avatar<br>
						num_predictions<br>
						points<br>
						league_image<br>
						league_name<br>
						css_class<br>
						last_score<br>
						ranking_row_number<br>
					</td>
				</tr>
				<tr>
					<td>group table</td>
					<td>footballpool_group_table_start_template</td>
					<td>Opening HTML for the group table.</td>
					<td>group_name<br>
						css_class<br>
						wins_expl<br>
						wins_thead<br>
						draws_expl<br>
						draws_thead<br>
						losses_expl<br>
						losses_thead<br>
						matches_expl<br>
						matches_thead<br>
						points_expl<br>
						points_thead<br>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_group_table_end_template</td>
					<td>Closing HTML for the group table.</td>
					<td>see template start</td>
				</tr>
				<tr>
					<td></td>
					<td>footballpool_group_table_group_row_template</td>
					<td>HTML for one team row in the group table.</td>
					<td>group_name<br>
						group_id<br>
						team_id<br>
						css_class<br>
						plays<br>
						wins<br>
						draws<br>
						losses<br>
						points<br>
						goals_for<br>
						goals_against<br>
					</td>
				</tr>
					<tr>
						<td>statistics match prediction table</td>
						<td>footballpool_matchpredictions_template_start</td>
						<td>Opening HTML for the table with match predictions for all users.</td>
						<td>-</td>
					</tr>
					<tr>
						<td></td>
						<td>footballpool_matchpredictions_template_end</td>
						<td>Closing HTML for the table with match predictions for all users.</td>
						<td>-</td>
					</tr>
					<tr>
						<td></td>
						<td>footballpool_matchpredictions_row_template</td>
						<td>HTML for a row in the table with match predictions (shows the prediction for one user).</td>
						<td>current_user_css_class<br>
							user_url<br>
							user_name<br>
							home_score<br>
							away_score<br>
							joker_title_text<br>
							joker_css_class<br>
							score<br>
						</td>
					</tr>
					<tr>
						<td>shortcode fp-user-list</td>
						<td>footballpool_fp-user-list_template_start</td>
						<td>Opening HTML for the fp-user-list shortcode.</td>
						<td>-</td>
					</tr>
					<tr>
						<td></td>
						<td>footballpool_fp-user-list_template_end</td>
						<td>Closing HTML for the fp-user-list shortcode.</td>
						<td>-</td>
					</tr>
					<tr>
						<td></td>
						<td>footballpool_fp-user-list_template_row</td>
						<td>HTML for a row in the user list.</td>
						<td>user_id<br>
							user_avatar<br>
							user_name<br>
						</td>
					</tr>
			</table>
			<p>Template example (for a match row in the prediction form):<br><br>
			<span class="code"><?php echo htmlentities( '<tr><td>%match_time%</td><td>%home_team% %home_team_flag%</td><td>%home_input% - %away_input%</td><td>%away_team_flag% %away_team%</td></tr>' ); ?>
			</span><br>
			</p>
			<p>See the <a href="#hooks">hooks section</a> in this help file for an example about how to use a hook to change the template for the ranking table.</p>

			<h2 id="shortcodes">Shortcodes</h2>
			<p>This plugin has several shortcodes that can be added in the content of your posts or pages. Because adding a shortcode and remembering all the options of a shortcode can be a hassle, the visual editor of WordPress is extended with a button (the classic mode) that makes adding these shortcodes a bit easier.
			</p>

			<p>
			<img class="screenshot" src="<?php echo $img_dir; ?>screenshot-shortcode-button-editor.png" alt="screenshot">
			</p>
			<p>The different shortcodes are explained in the following paragraphs.</p>

			<h3>[fp-predictions]</h3>
			<p>Shows the predictions for a given match and/or question.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">match</td>
					<td>The numeric id for the match</td>
					<td><a href="?page=footballpool-games">match id</a> (integer)</td>
					<td>none</td>
				</tr>
				<tr>
					<td class="row-title">question</td>
					<td>The numeric id for the question</td>
					<td><a href="?page=footballpool-bonus">question id</a> (integer)</td>
					<td>none</td>
				</tr>
				<tr>
					<td class="row-title">text</td>
					<td>Text to display if no predictions can be shown (invalid id, or predictions not publicly viewable)</td>
					<td>string</td>
					<td>empty string</td>
				</tr>
				<tr>
					<td class="row-title">use_querystring</td>
					<td>Set to 'yes' if you want the shortcode to use the querystring values to get the parameters</td>
					<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
					<td>no</td>
				</tr>
			</table>
			<p>example:<br>
			<span class="code">[fp-predictions match=1]</span><br>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-shortcode-predictions.png" alt="screenshot">
			</p>

			<h3>[fp-user-score]</h3>
			<p>Shows the score for a given user.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">user</td>
					<td>The numeric id for user</td>
					<td><a href="users.php">user id</a> (integer)</td>
					<td>current user</td>
				</tr>
				<tr>
					<td class="row-title">ranking</td>
					<td>The numeric id for the ranking from which the score has to be taken</td>
					<td><a href="?page=footballpool-rankings">ranking id</a> (integer)</td>
					<td>default ranking</td>
				</tr>
				<tr>
					<td class="row-title">date</td>
					<td>Calculate the score untill this date.</td>
					<td>one of the following strings:<ul><li>now: current date is used</li><li>postdate: the date of the post is used</li><li>any valid formatted date (Y-m-d H:i)</li></ul></td>
					<td>now</td>
				</tr>
				<tr>
					<td class="row-title">text</td>
					<td>text to display if no user or no score is found</td>
					<td>string</td>
					<td>0</td>
				</tr>
				<tr>
					<td class="row-title">use_querystring</td>
					<td>Set to 'yes' if you want the shortcode to use the querystring values to get the parameters</td>
					<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
					<td>no</td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-user-score user=1 text="no score"]</span><br>
				<span class="code">[fp-user-score user=58 ranking=2 text="no score"]</span><br>
				<span class="code">[fp-user-score user=5 date="2013-06-01 12:00"]</span><br>
				<span class="code">[fp-user-score use_querystring="yes"]</span><br>
			</p>

			<h3>[fp-user-ranking]</h3>
			<p>Shows the ranking for a given user.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">user</td>
					<td>The numeric id for user</td>
					<td><a href="users.php">user id</a> (integer)</td>
					<td>current user</td>
				</tr>
				<tr>
					<td class="row-title">ranking</td>
					<td>The numeric id for the ranking from which the ranking has to be taken</td>
					<td><a href="?page=footballpool-rankings">ranking id</a> (integer)</td>
					<td>default ranking</td>
				</tr>
				<tr>
					<td class="row-title">league_rank</td>
					<td>Whether or not to show the rank in the user's league. If set to "no" then the rank in the overall ranking will be returned.</td>
					<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
					<td>no</td>
				</tr>
				<tr>
					<td class="row-title">date</td>
					<td>Get the ranking for this date.</td>
					<td>one of the following strings:<ul><li>now: current date is used</li><li>postdate: the date of the post is used</li><li>any valid formatted date (Y-m-d H:i)</li></ul></td>
					<td>now</td>
				</tr>
				<tr>
					<td class="row-title">text</td>
					<td>text to display if no user or no ranking is found</td>
					<td>string</td>
					<td>empty string</td>
				</tr>
			</table>
			<p>example:<br>
			<span class="code">[fp-user-ranking user=1 text="not ranked"]</span><br>
			<span class="code">[fp-user-ranking user=58 ranking=2 text="not ranked"]</span><br>
			<span class="code">[fp-user-ranking user=5 date="2013-06-01 12:00"]</span><br>
			<span class="code">[fp-user-ranking user=5 date="postdate"]</span><br>
			</p>

			<h3>[fp-league-info]</h3>
			<p>Shows info about a league. E.g the total points or the average points (points divided by the number of players) of a league.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">league</td>
					<td>The league ID</td>
					<td><a href="?page=footballpool-leagues">league id</a> (integer)<br>or 'user' for the league of the logged in user.</td>
					<td>all users</td>
				</tr>
				<tr>
					<td class="row-title">ranking</td>
					<td>The numeric id for the ranking from which the ranking has to be taken (to be used with one of the points values for the info parameter)</td>
					<td><a href="?page=footballpool-rankings">ranking id</a> (integer)</td>
					<td>default ranking</td>
				</tr>
				<tr>
					<td class="row-title">info</td>
					<td>What info about the league to show.</td>
					<td>one of the following strings:<ul><li>name: name of the league</li><li>points: total points scored for users in the league</li><li>avgpoints: the average points (total points divided by number of players)</li><li>numplayers: the number of players in the league</li><li>playernames: a list of players is returned</li></ul></td>
					<td>name</td>
				</tr>
				<tr>
					<td class="row-title">format</td>
					<td>optional format for the output (uses <a href="http://php.net/sprintf" target="_blank">sprintf</a> notation)</td>
					<td>string</td>
					<td></td>
				</tr>
			</table>
			<p>example:<br>
			<span class="code">[fp-league-info league=3 info="name"]</span><br>
			<span class="code">[fp-league-info league=3 info="avgpoints" format="%.1f"]</span><br>
			<span class="code">[fp-league-info league=3 info="playernames"]</span><br>
			</p>

			<h3>[fp-group]</h3>
			<p>Shows a group standing for the group stage of the tournament. Parameter "id" must be given. If "id" is
			ommited, or not a valid group id, then nothing will be returned.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">id</td>
					<td>The numeric id for the group</td>
					<td><a href="?page=footballpool-groups">group id</a> (integer)</td>
					<td>1</td>
				</tr>
			</table>
			<p>example:<br>
			<span class="code">[fp-group id=3]</span><br>
			<img class="screenshot" src="<?php echo $img_dir; ?>example-shortcode-group.png" alt="screenshot">
			</p>

			<h3>[fp-ranking]</h3>
			<p>Shows the ranking at a given moment in time. Accepts multiple parameters. And just like the widget, if a logged in user of your blog (current_user) is in the ranking, his/her name will be highlighted.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">num</td>
					<td>The number of rows in the ranking (top N)</td>
					<td>1..n (integer)</td>
					<td>5</td>
				</tr>
				<tr>
					<td class="row-title">league</td>
					<td>Show ranking for this league.<br>If the pool does not use leagues, then this parameter is ignored.</td>
					<td><a href="?page=footballpool-leagues">league id</a> (integer)<br>or 'user' for the league of the logged in user.</td>
					<td>all users</td>
				</tr>
				<tr>
					<td class="row-title">date</td>
					<td>Calculate the ranking untill this date.</td>
					<td>one of the following strings:<ul><li>now: current date is used</li><li>postdate: the date of the post is used</li><li>any valid formatted date (Y-m-d H:i)</li></ul></td>
					<td>now</td>
				</tr>
				<tr>
					<td class="row-title">ranking</td>
					<td>Show scores calculated in this ranking.<br>Defaults to all matches and all questions.</td>
					<td><a href="?page=footballpool-rankings">ranking id</a> (integer)</td>
					<td></td>
				</tr>
				<!--tr>
					<td class="row-title">show_num_predictions</td>
					<td>If set to true also the number of predictions a user saved (matches and answers to questions) is shown in the ranking.</td>
					<td>1 = true<br>0 = false</td>
					<td>depends on the 'Show number of predictions?' setting on the <a href="?page=footballpool-options">options page</a></td>
				</tr-->
			</table>
			<p>
				example:<br>
				<span class="code">[fp-ranking num=5 ranking=4]</span><br>
				<!--span class="code">[fp-ranking num=5 show_num_predictions=1]</span><br-->
				<span class="code">[fp-ranking league=2]</span><br>
				<span class="code">[fp-ranking league="user"]</span><br>
				<span class="code">[fp-ranking num=5 date="postdate"]</span><br>
				<span class="code">[fp-ranking num=5 date="2012-06-22 11:00"]</span><br>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-shortcode-ranking.png" alt="screenshot">
			</p>

			<h3>[fp-predictionform]</h3>
			<p>Shows a prediction form for the selected matches, matches in a matchtype and/or bonus questions. All parameters are cumulative, so all given matches and matches in a matchtype are put together in one form.</p>
			<p>All arguments can be entered in the following formats (example for matches):</p>
			<table>
				<tr><td>match 1</td><td>&rarr;</td><td>match="1"</td></tr>
				<tr><td>matches 1 to 5</td><td>&rarr;</td><td>match="1-5"</td></tr>
				<tr><td>matches 1, 3 and 6</td><td>&rarr;</td><td>match="1,3,6"</td></tr>
				<tr><td>matches 1 to 5 and 10</td><td>&rarr;</td><td>match="1-5,10"</td></tr>
			</table>
			<p>If an argument is left empty it is ignored. Matches are always displayed first in a prediction form.</p>
			<p>If the current visitor is not logged in, a default text is shown (the default message can be changed with the <span class="code">text</span> parameter).</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">match</td>
					<td>Collection of <a href="?page=footballpool-games">match ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">matchtype</td>
					<td>Collection of <a href="?page=footballpool-matchtypes">match type ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">question</td>
					<td>Collection of <a href="?page=footballpool-bonus">question ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">text</td>
					<td>The text to display when a visitor is not logged in. Use with an empty string to display nothing (<span class="code">text=""</span>). To use the default text, just omit the parameter</td>
					<td>string</td>
					<td></td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-predictionform match="1-5"]</span><br>
				<span class="code">[fp-predictionform match="1-4,9-12" question="1,5,10"]</span><br>
				<span class="code">[fp-predictionform matchtype="1" text=""]</span><br>
			</p>

			<h3>[fp-next-match-form]</h3>
			<p>
				Shows a prediction form for the next match (or matches if there are multiple matches starting at
				the same time).
			</p>
			<p>
				If the current visitor is not logged in or there are no next matches, an empty &lt;span&gt; is
				returned that can be given a textual message via CSS.
			</p>
			<table class="widefat help">
				<tr>
					<th>parameter</th>
					<th>description</th>
					<th>values</th>
					<th>default</th>
				</tr>
				<tr>
					<td class="row-title">num</td>
					<td>Maximum of matches to show in the form</td>
					<td>int</td>
					<td>no maximum</td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-next-match-form]</span><br>
				<span class="code">[fp-next-match-form num="1"]</span><br>
			</p>

			<h3>[fp-matches]</h3>
			<p>Shows the info table for the selected matches, matches in a matchtype or matches for a group in the group phase. The matches and match types parameter are cumulative, so all given match ids and matches in a matchtype are put together in one table.</p>
			<p>All arguments (except the group parameter) can be entered in the following formats (example for matches):</p>
			<table>
				<tr><td>match 1</td><td>&rarr;</td><td>match="1"</td></tr>
				<tr><td>matches 1 to 5</td><td>&rarr;</td><td>match="1-5"</td></tr>
				<tr><td>matches 1, 3 and 6</td><td>&rarr;</td><td>match="1,3,6"</td></tr>
				<tr><td>matches 1 to 5 and 10</td><td>&rarr;</td><td>match="1-5,10"</td></tr>
			</table>
			<p>If an argument is left empty it is ignored. If a group ID is given the other parameters are ignored.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">match</td>
					<td>Collection of <a href="?page=footballpool-games">match ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">matchtype</td>
					<td>Collection of <a href="?page=footballpool-matchtypes">match type ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">group</td>
					<td>The <a href="?page=footballpool-groups">group id</a> for which the matches have to be displayed.</td>
					<td><a href="?page=footballpool-groups">group id</a> (integer)</td>
					<td></td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-matches match="1-5"]</span><br>
				<span class="code">[fp-matches match="1-4,9-12" matchtype="2"]</span><br>
				<span class="code">[fp-matches matchtype="1"]</span><br>
				<span class="code">[fp-matches group="1"]</span><br>
			</p>

			<h3>[fp-next-matches]</h3>
			<p>Shows a matches table for the upcoming matches (based on date).</p>
			<p>The matchtype can be entered in the following formats:</p>
			<table>
				<tr><td>matchtype 1</td><td>&rarr;</td><td>matchtype="1"</td></tr>
				<tr><td>matchtypes 1 to 5</td><td>&rarr;</td><td>matchtype="1-5"</td></tr>
				<tr><td>matchtypes 1, 3 and 6</td><td>&rarr;</td><td>matchtype="1,3,6"</td></tr>
				<tr><td>matchtypes 1 to 5 and 10</td><td>&rarr;</td><td>matchtype="1-5,10"</td></tr>
			</table>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">matchtype</td>
					<td>Only show matches for the given <a href="?page=footballpool-matchtypes">match type ids</a>.</td>
					<td>see formats above</td>
					<td>don't filter for match types</td>
				</tr>
				<tr>
					<td class="row-title">group</td>
					<td>The <a href="?page=footballpool-groups">group id</a> for which the matches have to be displayed.</td>
					<td><a href="?page=footballpool-groups">group id</a> (integer)</td>
					<td>don't filter for group</td>
				</tr>
				<tr>
					<td class="row-title">date</td>
					<td>Show matches scheduled after this date.</td>
					<td>one of the following strings:<ul><li>now: current date is used</li><li>postdate: the date of the post is used</li><li>any valid formatted date (Y-m-d H:i)</li></ul></td>
					<td>now</td>
				</tr>
				<tr>
					<td class="row-title">num</td>
					<td>Number of matches to show.</td>
					<td>integer</td>
					<td>5</td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-next-matches]</span><br>
				<span class="code">[fp-next-matches date="2016-04-01 12:00"]</span><br>
				<span class="code">[fp-next-matches matchtype="4-8"]</span><br>
				<span class="code">[fp-next-matches group="1" date="now"]</span><br>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-shortcode-next-matches.png" alt="screenshot">
			</p>

			<h3>[fp-last-matches]</h3>
			<p>Shows a matches table for the last started matches (based on date).</p>
			<p>The matchtype can be entered in the following formats:</p>
			<table>
				<tr><td>matchtype 1</td><td>&rarr;</td><td>matchtype="1"</td></tr>
				<tr><td>matchtypes 1 to 5</td><td>&rarr;</td><td>matchtype="1-5"</td></tr>
				<tr><td>matchtypes 1, 3 and 6</td><td>&rarr;</td><td>matchtype="1,3,6"</td></tr>
				<tr><td>matchtypes 1 to 5 and 10</td><td>&rarr;</td><td>matchtype="1-5,10"</td></tr>
			</table>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">matchtype</td>
					<td>Only show matches for the given <a href="?page=footballpool-matchtypes">match type ids</a>.</td>
					<td>see formats above</td>
					<td>don't filter for match types</td>
				</tr>
				<tr>
					<td class="row-title">group</td>
					<td>The <a href="?page=footballpool-groups">group id</a> for which the matches have to be displayed.</td>
					<td><a href="?page=footballpool-groups">group id</a> (integer)</td>
					<td>don't filter for group</td>
				</tr>
				<tr>
					<td class="row-title">date</td>
					<td>Show matches scheduled before this date.</td>
					<td>one of the following strings:<ul><li>now: current date is used</li><li>postdate: the date of the post is used</li><li>any valid formatted date (Y-m-d H:i)</li></ul></td>
					<td>now</td>
				</tr>
				<tr>
					<td class="row-title">num</td>
					<td>Number of matches to show.</td>
					<td>integer</td>
					<td>5</td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-last-matches]</span><br>
				<span class="code">[fp-last-matches date="2016-04-01 12:00"]</span><br>
				<span class="code">[fp-last-matches matchtype="4-8"]</span><br>
				<span class="code">[fp-last-matches group="1" date="now"]</span><br>
			</p>

			<h3>[fp-register]link text[/fp-register]</h3>
			<p>Shows a link to the register page of WordPress. Text between the tags will be the text for the link. If no content is given, then a default text is shown as the link text. A redirect link to the post or page is automatically added if the get_permalink function does not return false.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">title</td>
					<td>Title parameter for the &lt;a href&gt;</td>
					<td>string</td>
					<td>empty; don't display a tooltip</td>
				</tr>
				<tr>
					<td class="row-title">new</td>
					<td>Open link in a new window/tab.</td>
					<td>integer: 0 (no) or 1 (yes)</td>
					<td>0</td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">Click [fp-register]here[/fp-register] to register for this pool.</span><br>
				<span class="code">Click [fp-register new=1 title="Go to the registration page"]here[/fp-register] to register for this pool.</span><br>
			</p>

			<h3>[fp-countdown]</h3>
			<p>
				Counts down to a date and time. If no date is given, the time of the first match of the tournament is used.
				If a valid match number is given, it counts down to that match. If the text value "next" is passed for the
				match parameter, the shortcode will try to find the next match (if there is one).<br>
				A textual countdown is added to the post (or page) wich updates automatically. The shortcode uses a default
				format for the displayed text, but you can override it with the format_string parameter.
			</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">date</td>
					<td>The date and time to count down to.</td>
					<td>Y-m-d H:i</td>
					<td>empty</td>
				</tr>
				<tr>
					<td class="row-title">match</td>
					<td>ID of the match to count down to.</td>
					<td>One of the following:<ul><li><a href="?page=footballpool-games">match id</a> (integer)</li><li>next</li></ul></td>
					<td>empty</td>
				</tr>
				<tr>
					<td class="row-title">texts</td>
					<td>A semi colon separated string with texts to put in front of and behind the counter. Don't forget spaces (if applicable). Must contain 4 texts:<ol><li>before counter if time has not passed</li><li>after counter if time has not passed</li><li>before counter if time has passed</li><li>after counter if time has passed</li></ol><br>
					If value is "none" then no texts are added.<br>
					If left empty or ommitted then the default texts are used.</td>
					<td>One of the following:<ul><li>string;string;string;string</li><li>none</li></ul></td>
					<td>empty; default texts are used.</td>
				</tr>
				<tr>
					<td class="row-title">display</td>
					<td>Display counter inline or as a separate block.</td>
					<td>One of the following strings:<ul><li>inline</li><li>block</li></ul></td>
					<td>block</td>
				</tr>
				<tr>
					<td class="row-title">format</td>
					<td>The time format for the countdown.</td>
					<td>One of the following numbers:<ul><li>1 (only seconds)</li><li>2 (days, hours, minutes, seconds)</li><li>3 (hours, minutes, seconds)</li></ul></td>
					<td>2</td>
				</tr>
				<tr>
					<td class="row-title">format_string</td>
					<td>The text format for the countdown. The format string uses some placeholders for the display of the values. If any of these placeholders are used in the string, they will be automatically replaced with the correct values.
						<ul>
							<li>{d} placeholder for the number of days</li>
							<li>{h} placeholder for the number of hours</li>
							<li>{m} placeholder for the number of minutes</li>
							<li>{s} placeholder for the number of seconds</li>
							<li>{days} placeholder for the (translated) text for day/days</li>
							<li>{hrs} placeholder for the (translated) text for hour/hours</li>
							<li>{min} placeholder for the (translated) text for minute/minutes</li>
							<li>{sec} placeholder for the (translated) text for sec/seconds</li>
						</ul>
					</td>
					<td>string</td>
					<td>defaults to comma-separated values, e.g. {d} {days}, {h} {hrs}, etc.</td>
				</tr>
			</table>
			<p>
				examples:<br>
				<span class="code">[fp-countdown]</span><br>
				<span class="code">[fp-countdown date="2012-06-22 11:00"]</span><br>
				<span class="code">[fp-countdown match="3"]</span><br>
				<span class="code">[fp-countdown date="2012-06-22 11:00" texts="Wait ; until this date;; have passed since the date"]</span><br>
				<span class="code">[fp-countdown display="inline" match="3" format="1"]</span><br>
			</p>

			<h3>[fp-match-scores]</h3>
			<p>Shows the scores for users for one or more matches in a table. Matches can be entered by match id and/or by giving a match type. All matches from the match and match type parameter are combined.</p>
			<p>Because matches are displayed in columns, the table can get very large quickly, so make sure you don't add too much matches. Or think of some clever styling to keep the table readable for the users.</p>
			<!--<p>Every table cell also contains the user's prediction, but these are hidden by default. If you want to show the prediction, you can change the display of the corresponding span via CSS (e.g. <span class="inline code">.user-prediction { display: inline!important; }</span>).</p>-->
			<p>Users, match and matchtype argument can be entered in the following formats (example for matches):</p>
			<table>
				<tr><td>match 1</td><td>&rarr;</td><td>match="1"</td></tr>
				<tr><td>matches 1 to 5</td><td>&rarr;</td><td>match="1-5"</td></tr>
				<tr><td>matches 1, 3 and 6</td><td>&rarr;</td><td>match="1,3,6"</td></tr>
				<tr><td>matches 1 to 5 and 10</td><td>&rarr;</td><td>match="1-5,10"</td></tr>
			</table>

			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">league</td>
					<td>Show all users in this league.</td>
					<td><a href="?page=footballpool-leagues">league id</a> (integer)<br>or 'user' for the league of the logged in user.</td>
					<td>all users</td>
				</tr>
				<tr>
					<td class="row-title">users</td>
					<td>A collection of user id's. If user id's are given then the league parameter is ignored.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">match</td>
					<td>Collection of <a href="?page=footballpool-games">match ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">matchtype</td>
					<td>Collection of <a href="?page=footballpool-matchtypes">match type ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
					<tr>
						<td class="row-title">display</td>
						<td>Decide if you want to show the points scored per match, the prediction or both.</td>
						<td>one of the following strings:<ul><li>points</li><li>predictions</li><li>both</li></ul></td>
						<td>points</td>
					</tr>
					<tr>
						<td class="row-title">hide_zeroes</td>
						<td>Set to 'yes' if you want to hide a score of 0 points in a cell.</td>
						<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
						<td>no</td>
					</tr>
					<tr>
						<td class="row-title">show_total</td>
						<td>Set to 'yes' if you want to show the total of scores of each user in the row.</td>
						<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
						<td>no</td>
					</tr>
	                <tr>
	                    <td class="row-title">use_querystring</td>
	                    <td>Set to 'yes' if you want the shortcode to use the querystring values to get the parameters</td>
	                    <td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
	                    <td>no</td>
	                </tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-match-scores matchtype=1]</span><br>
				<span class="code">[fp-match-scores users="1-10" match="1,2"]</span><br>
				<span class="code">[fp-match-scores users="1,5,8" match="1-5" display="both" hide_zeroes="yes" show_totals="yes"]</span><br>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-shortcode-scores.png" alt="screenshot">
			</p>

			<h3>[fp-question-scores]</h3>
			<p>Shows the scores for users for one or more questions in a table.</p>
			<p>Because questions are displayed in columns, the table can get very large quickly, so only the question
				number is shown by default and the question text is in the title (shown on hover). You can also show
				the question ID (hidden by default).</p>
			<p>Users and question argument can be entered in the following formats (example for question):</p>
			<table>
				<tr><td>question 1</td><td>&rarr;</td><td>question="1"</td></tr>
				<tr><td>questions 1 to 5</td><td>&rarr;</td><td>question="1-5"</td></tr>
				<tr><td>questions 1, 3 and 6</td><td>&rarr;</td><td>question="1,3,6"</td></tr>
				<tr><td>questions 1 to 5 and 10</td><td>&rarr;</td><td>question="1-5,10"</td></tr>
			</table>

			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">league</td>
					<td>Show all users in this league.</td>
					<td><a href="?page=footballpool-leagues">league id</a> (integer)<br>or 'user' for the league of the logged in user.</td>
					<td>all users</td>
				</tr>
				<tr>
					<td class="row-title">users</td>
					<td>A collection of user id's. If user id's are given then the league parameter is ignored.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">question</td>
					<td>Collection of <a href="?page=footballpool-questions">question ids</a>.</td>
					<td>see formats above</td>
					<td></td>
				</tr>
				<tr>
					<td class="row-title">display</td>
					<td>Decide if you want to show the points scored per question, the answer or both.</td>
					<td>one of the following strings:<ul><li>points</li><li>predictions</li><li>both</li></ul></td>
					<td>points</td>
				</tr>
				<tr>
					<td class="row-title">hide_zeroes</td>
					<td>Set to 'yes' if you want to hide a score of 0 points in a cell.</td>
					<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
					<td>no</td>
				</tr>
				<tr>
					<td class="row-title">show_total</td>
					<td>Set to 'yes' if you want to show the total of scores of each user in the row.</td>
					<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
					<td>no</td>
				</tr>
				<tr>
					<td class="row-title">use_querystring</td>
					<td>Set to 'yes' if you want the shortcode to use the querystring values to get the parameters</td>
					<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
					<td>no</td>
				</tr>
			</table>
			<p>example:<br>
				<span class="code">[fp-question-scores question=1]</span><br>
				<span class="code">[fp-question-scores users="1-10" question="1,2"]</span><br>
				<span class="code">[fp-question-scores users="1,5,8" question="1-5" display="both" hide_zeroes="yes" show_totals="yes"]</span><br>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-shortcode-question-scores.png" alt="screenshot">
			</p>

			<h3>[fp-user-list]</h3>
			<p>
				Shows a list of users in the pool. If you want to display the avatar, please make sure you set the
				constant 'FOOTBALLPOOL_NO_AVATAR' to false in the wp-config.php file. The template for this shortcode
				can be altered via filters.
			</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">league</td>
					<td>Show ranking for this league.<br>If the pool does not use leagues, then this parameter is ignored.</td>
					<td><a href="?page=footballpool-leagues">league id</a> (integer)<br>or 'user' for the league of the logged in user.</td>
					<td>all users</td>
				</tr>
				<tr>
					<td class="row-title">num</td>
					<td>Number of users to show.</td>
					<td>integer</td>
					<td>all users</td>
				</tr>
				<tr>
					<td class="row-title">latest</td>
					<td>Set to 'yes' if you want to show the latest registrations for your pool (please note: only works in combination with the num parameter).</td>
					<td>one of the following strings:<ul><li>yes</li><li>no</li></ul></td>
					<td>no</td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-user-list]</span><br>
				<span class="code">[fp-user-list league="2"]</span><br>
				<span class="code">[fp-user-list league="user"]</span><br>
				<span class="code">[fp-user-list latest="yes" num="5"]</span><br>
			</p>

			<h3>[fp-money-in-the-pot]</h3>
			<p>
				Simple shortcode that calculates the amount of users in a league (or all users) times a given amount and returns
				this total sum.<br>
				Can be useful if your players add a stake to the pot and you want to show the total amount in a post or in other part
				of the website.
			</p>
			<p>League parameter can be entered in the following formats:</p>
			<table>
				<tr><td>league for the user</td><td>&rarr;</td><td>league="user"</td></tr>
				<tr><td>league 1</td><td>&rarr;</td><td>league="1"</td></tr>
				<tr><td>leagues 1 to 5</td><td>&rarr;</td><td>league="1-5"</td></tr>
				<tr><td>leagues 1, 3 and 6</td><td>&rarr;</td><td>league="1,3,6"</td></tr>
				<tr><td>leagues 1 to 5 and 10</td><td>&rarr;</td><td>league="1-5,10"</td></tr>
			</table>
			<p>If an argument is left empty it is ignored. If you omit the league parameter, the shortcode will assume all players in the pool are paying participants.</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">league</td>
					<td>Collection of <a href="?page=footballpool-leagues">league ids</a> or one.</td>
					<td>see formats above</td>
					<td>all users</td>
				</tr>
				<tr>
					<td class="row-title">amount</td>
					<td>The stake every player adds to the pot</td>
					<td>int / float</td>
					<td>0</td>
				</tr>
				<tr>
					<td class="row-title">format</td>
					<td>optional format for the output (uses <a href="http://php.net/sprintf" target="_blank">sprintf</a> notation)</td>
					<td>string</td>
					<td></td>
				</tr>
			</table>
			<p>
				example:<br>
				<span class="code">[fp-money-in-the-pot amount="10"]</span><br>
				<span class="code">[fp-money-in-the-pot league="user" amount="10"]</span><br>
				<span class="code">[fp-money-in-the-pot league="2" amount="7.5" format="€ %.2f"]</span><br>
			</p>

			<h3>[fp-last-calc-date]</h3>
			<p>
				Returns the last date and time when a ranking calculation was finished successfully. Or an empty
				span if the value could not be found.
			</p>
			<table class="widefat help">
				<tr><th>parameter</th><th>description</th><th>values</th><th>default</th></tr>
				<tr>
					<td class="row-title">format</td>
					<td>The format in which the date and time should be displayed (uses the
						<a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">date</a>
						formatting).</td>
					<td>string</td>
					<td>d-m-Y \a\t H:i</td>
				</tr>
			</table>
			<p>
				examples:<br>
				<span class="code">[fp-last-calc-date]</span><br>
				<span class="code">[fp-last-calc-date format="d M 'y"]</span><br>
			</p>

			<h3>[fp-stats-settings] or [fp-chart-settings]</h3>
			<p>Some themes don't correctly show the chart settings icon on the statistics page, or, the theme styling ruins the display of the icon near the title. To overcome this problem, you can use this shortcode to display the settings icon somewhere else on the page. The shortcode returns an empty string on all other pages.</p>
			<p>If you need the hide the original icon in the title, set the constant <span class="code">FOOTBALLPOOL_CHANGE_STATS_TITLE</span> to false in your wp-config file.</p>
			<?php
			Football_Pool_Utils::highlight_string( 'define( "FOOTBALLPOOL_CHANGE_STATS_TITLE", false );' );
			?>
			<h3>Other shortcodes</h3>
			<p>See <a href="?page=footballpool-options">Football Pool plugin settings</a> for some basic shortcodes that  will display the value for a plugin setting.</p>
			<p>

			<h2 id="charts">Using charts</h2>
			<p>
			The charts feature uses the Highcharts API to display the interactive charts. Because of the <a target="_blank" href="http://wordpress.org/extend/plugins/about/">WordPress license guidelines</a> I may not include this library in the package.
			Please refer to the license terms of the Highcharts API to determine if you may use it on your website.
			</p>
			<p>For now you have to follow these steps:</p>
			<ol>
				<li>Download the Highcharts API from <a target="_blank" href="http://www.highcharts.com/download">http://www.highcharts.com/download</a>.</li>
				<li>Place the <span class="code">highcharts.js</span> file in the following path <span class="code">/wp-content/plugins/highcharts-js/highcharts.js</span>.</li>
				<li>Enable the charts on the <a href="?page=footballpool-options">Options page</a>.</li>
			</ol>
			<p>
				If you don't want to use charts, then disable this option on the <a href="?page=footballpool-options">Options page</a>.
			</p>
			<p>
				<img class="screenshot" src="<?php echo $img_dir; ?>example-chart.png" alt="screenshot">
			</p>

			<h2 id="hooks">Extending the plugin: Actions and Filters</h2>
			<p>If you want to alter the output or behavior of the plugin there are several hooks you can use. If you want to learn more about hooks, see <a target="_blank" href="http://wp.tutsplus.com/tutorials/plugins/writing-extensible-plugins-with-actions-and-filters/">this tutorial</a> or <a target="_blank" href="http://codex.wordpress.org/Plugin_API">the Codex</a>. Place your custom code in your theme's functions.php file or in your own plugin (<a href="http://codex.wordpress.org/Writing_a_Plugin" target="_blank">how to write your own plugin</a>).</p>
			<p>Search for <span class="code">do_action</span> or <span class="code">apply_filters</span> in the plugin's PHP files for the exact location of the different hooks.
			</p>
			<p>There is a <a target="_blank" href="https://wordpress.org/support/topic/extension-plugins-for-the-football-pool-plugin/">post on the support forum</a> where I placed some working examples of extension plugins that use some of the filters in the plugin.</p>
			<div class="help important">
				<p>Please note that some of the examples below use <a title="more on closures" href="http://www.php.net/manual/en/functions.anonymous.php" target="_blank">closures</a>. If you don't have PHP version 5.3 or higher, you'll have to rewrite the example to a named function.</p>
			</div>

			<h3>Simple and/or short examples:</h3>
			<?php
			$code_block = <<<'EOT'
<?php
// a bit of useless example that shows the page ID at the top of every page from the plugin,
// but it shows how relatively simple it is to add something to the page output.
add_filter( 'footballpool_pages_html', 'show_page_id', null, 2 );
function show_page_id( $content, $id ) {
	return "<p>page id = {$id}</p>{$content}";
}
?>
EOT;

			Football_Pool_Utils::highlight_string( $code_block );

			$code_block = <<<'EOT'
<?php
// add an extra div around the ranking table (when displayed with the fp-ranking shortcode)
add_filter( 'footballpool_shortcode_html_fp-ranking', function ( $html ) {
	return '<div class="extra-div">' . $html . '</div>';
} );
?>
EOT;

			Football_Pool_Utils::highlight_string( $code_block );

			$code_block = <<<'EOT'
<?php
// only show the first 20 users in the user selector
add_filter( 'footballpool_userselector_widget_users', function ( $a ) {
	return array_slice( $a, 0, 20 );
} );
?>
EOT;

			Football_Pool_Utils::highlight_string( $code_block );

			$code_block = <<<'EOT'
<?php
// Show number of predictions in the ranking table and also the user avatar.
// If you want the page, shortcode or widget to have different layouts,
// you can differentiate with the $type.
add_filter( 'footballpool_ranking_template_start', 
				function( $template_start, $league, $user, $ranking_id, $all_user_view, $type ) {
	// add a row with column headers
	$template_start .= sprintf( '<tr>
									<th></th>
									<th class="user">%s</th>
									<th class="num-predictions">%s</th>
									<th class="score">%s</th>
									%s</tr>'
								, __( 'user', 'football-pool' )
								, strtolower( __( 'Predictions', 'football-pool' ) )
								, __( 'points', 'football-pool' )
								, ( $all_user_view ? '<th></th>' : '' )
						);
	return $template_start;
}, null, 6 );
add_filter( 'footballpool_ranking_ranking_row_template', function( $template, $all_user_view, $type ) {
	if ( $all_user_view ) {
		$ranking_template = '<tr class="%css_class%">
								<td style="width:3em; text-align: right;">%rank%.</td>
								<td><a href="%user_link%">%user_avatar%%user_name%</a></td>
								<td class="num-predictions">%num_predictions%</td>
								<td class="ranking score">%points%</td>
								<td>%league_image%</td>
								</tr>';
	} else {
		$ranking_template = '<tr class="%css_class%">
								<td style="width:3em; text-align: right;">%rank%.</td>
								<td><a href="%user_link%">%user_avatar%%user_name%</a></td>
								<td class="num-predictions">%num_predictions%</td>
								<td class="ranking score">%points%</td>
								</tr>';
	}
	return $ranking_template;
}, null, 3 );
?>
EOT;

			Football_Pool_Utils::highlight_string( $code_block );
			?>
			<h3>A bit more advanced examples:</h3>
			<?php

			$code_block = <<<'EOT'
<?php
// add a simple pagination to the ranking page
add_filter( 'footballpool_print_ranking_ranking', 'fp_pagination', 90 );
add_filter( 'footballpool_ranking_page_html', 'fp_pagination_html', null, 2 );
// and, with the same functions, add a simple pagination to the statistics page (view=matchpredictions)
add_filter( 'footballpool_statistics_matchpredictions', 'fp_pagination', 90 );
add_filter( 'footballpool_statistics_matchpredictions_html', 'fp_pagination_html', null, 2 );

function fp_pagination( $items ) {
	$pagination = new Football_Pool_Pagination( count( $items ) );
	$pagination->set_page_param( 'fp_page' );
	$pagination->set_page_size( 10 );
	$offset = ( ( $pagination->current_page - 1 ) * $pagination->get_page_size() );
	$length = $pagination->get_page_size();
	return array_slice( $items, $offset, $length );
}

function fp_pagination_html( $html, $items ) {
	$pagination = new Football_Pool_Pagination( count( $items ), true );
	$pagination->set_page_param( 'fp_page' );
	$pagination->set_page_size( 10 );
	return $html . $pagination->show( 'return' );
}
?>
EOT;

			Football_Pool_Utils::highlight_string( $code_block );

			$code_block = <<<'EOT'
<?php
// don't use admin approval for league registration of new users
// just put them in the league they chose
add_filter( 'footballpool_new_user', function( $user_id, $league_id ) {
	Football_Pool::update_user_custom_tables( $user_id, $league_id );
}, null, 2 );
?>
EOT;

			Football_Pool_Utils::highlight_string( $code_block );

			$code_block = <<<'EOT'
<?php
// add a column with the group order in the group widget using PHP Simple HTML DOM Parser
// (download it from http://simplehtmldom.sourceforge.net/ and add it to the dir where you placed this script)
add_filter( 'footballpool_widget_html_group', function( $html ) {
	require_once 'simple_html_dom.php';
	
	$html_dom = new simple_html_dom();
	$html_dom->load( $html );
	
	// add extra column in the header
	$th = $html_dom->find( 'th.team', 0 );
	$th->outertext = '<th></th>' . $th->outertext;
	// add numbering before the team name
	$i = 1;
	foreach ( $html_dom->find( 'tr' ) as $tr ) {
		foreach( $tr->find( 'td.team' ) as $td ) {
			$td->outertext = sprintf( '<td>%d</td>%s', $i++, $td->outertext );
		}
	}
	
	$output = $html_dom->save();
	$html_dom->clear();
	unset( $html_dom );
	
	return $output;
} );
?>
EOT;

			Football_Pool_Utils::highlight_string( $code_block );
			?>

	        <h2 id="cli">WP CLI commands</h2>
			<p>
				If you have WP-CLI installed (see <a href="http://wp-cli.org" target="_blank">wp-cli.org</a>
				for details), the plugin can also do a couple of commands from the command line. The commands
				are listed below.
			</p>
			<p>
				If you want to see all the possible sub commands, use the standard WP-CLI help command:
			</p>
			<pre class="code-block"><code class="bash">$ wp help football-pool</code></pre>

			<p>
				If you want to see all the possible options for a sub command in the <code class="inline">football-pool</code> command, use this:
			</p>
			<pre class="code-block"><code class="bash">$ wp help football-pool calc</code></pre>

			<h3 id="ranking-calculation-wp-cli">Ranking calculation</h3>
	        <p>
		        From my own experience I've found that calculations via the command line are a lot faster than via the
		        web interface.
		        Calculating via the command line may also open the door for scheduling the calculation in a cron job.
		        But as I have absolutely no experience with this :), I won't be able to help you with the configuration
		        of crons.</p>
	        <pre class="code-block"><code class="bash">$ wp football-pool calc
Calculating scores  100% [=========================================] 1:07 / 0:26

Success: Calculation completed. Thanks for your patience.
$
	        </code></pre>
	        <p>
	        If the calculation stops with a warning that another calculation is in progress, you can use the optional parameter <code class="inline">--force-calculation</code> to ignore the warning and start a new calculation.
	        </p>
	        <pre class="code-block"><code class="bash">$ wp football-pool calc --force-calculation</code></pre>

            <p>The <code class="inline">calc</code> CLI command also supports switching to (a.k.a. explicitly setting) a different calculation method. See all parameters via the CLI help for more details.</p>

			<h3 id="import-wp-cli">Import match results</h3>
			<p>Import the end result of matches via a simple CSV file. The file should contain three columns: </p>
			<ol><li>match id</li><li>score for the home team</li><li>score for the visiting team</li></ol>
			<p>
				e.g.<br><br>
				<span class="code">1,0,1</span><br>
				<span class="code">2,0,2</span><br>
				<span class="code">3,2,2</span><br>
				<span class="code">6,5,2</span>
			</p>
			<pre class="code-block"><code class="bash">$ wp football-pool import --file=week1.csv
importing  100% [===============================================] 0:08 / 0:08
+----------+-----------+--------------+----------+----------+---------------+
| match id | home team | away team    | old data | new data | import result |
+----------+-----------+--------------+----------+----------+---------------+
| 1        | Russia    | Saudi Arabia | 1 - 0    | 0 - 1    | success       |
| 2        | Egypt     | Uruguay      | 0 - 2    | 0 - 2    | success       |
| 3        | Portugal  | Spain        | 1 - 1    | 2 - 2    | success       |
| 6        | Peru      | Denmark      | 5 - 2    | 5 - 2    | success       |
+----------+-----------+--------------+----------+----------+---------------+
$
			</code></pre>
			<p>
				If you want to do a test run (reading the file without importing the values), you can use the parameter <code class="inline">--dry-run</code> to view the output.
			</p>
			<pre class="code-block"><code class="bash">$ wp football-pool import --file=week1.csv --dry-run</code></pre>

			<h3 id="test-data-wp-cli">Create test data</h3>
			<div class="help important">
				<p><strong>Important:</strong> Use this command with care! Always back up your database.</p>
			</div>
			<p>
				This command creates test users in your database and fills the prediction table with predictions for the
				matches that are defined (scores will be random numbers between 0 and 4). Every test user will be placed
				in the default league (when using leagues).
			</p>
			<pre class="code-block"><code class="bash">$ wp football-pool test-data --users=100 --yes
Creating users  100% [===============================================================] 0:52 / 0:51
Created 100 users with random predictions for 64 matches.
$
		</code></pre>
		<p>
			If you want to delete the test users and their data, you can use the parameter <code class="inline">--delete</code>.
		</p>
		<pre class="code-block"><code class="bash">$ wp football-pool test-data --delete --yes
Deleting users  100% [===============================================================] 0:25 / 0:25
Test data deleted.
$
			</code></pre>

			<p>
				If you want to perform a calculation after the test data has been created or deleted,
				you can add the parameter <code class="inline">--calc</code>. This will start the calculation
				CLI command after the test-data command has finished.
			</p>

			<div class="help important">
				<p>
					<strong>Important:</strong> The test-data command always requires a confirmation (--yes).
					If not provided, it will do nothing and exit.
				</p>
			</div>

			<h2 id="caching">Caching plugins</h2>
			<h3>General rules</h3>
			<p>
				The football pool's prediction form should not be cached for the players. This may cause trouble.
				Always test all aspects of the plugin when you have caching enabled and check the error log for any
				messages. Try to exclude parts of the plugin from the cache when you see strange behavior.
			</p>
			<h3>Object Cache</h3>
			<p>
				I've added the football pool's cache calls to a group called 'footballpool-non-persistent' and defined
				the group as non-persistent. If your caching plugin does not listen to this definition, then maybe you
				can add it to a setting of the cache. E.g. W3 Total Cache has a setting for it on the 'Object cache' page.
				See screenshot below.
			</p>
			<p>
				<img class="screenshot" src="<?php echo $img_dir; ?>w3-total-cache-non-persistent-groups.png"
				     alt="W3 Total Cache settings">
			</p>

			<h2 id="the-end">Anything else?</h2>
			<p>
				It was real fun writing this plugin, and I hope you had/have as much fun using it. If not, please
	            let me know. You can leave a question, feature request or a bug report at the support forum. And
	            you can leave a review for the plugin
	            <a href="https://wordpress.org/support/plugin/football-pool/reviews/">here</a>.
	        </p>
			<p>
				Writing this plugin and maintaining it takes a lot of time. If you liked using this plugin please
	            consider a <a href="https://paypal.me/wpfootballpool" target="_blank">small donation</a>.<br>
				Or a little fan mail is also appreciated :)
			</p>

			<p>
				Thank you!<br>
				Antoine Hurkmans<br><br>
				email: <a href="mailto:wordpressfootballpool@gmail.com">wordpressfootballpool@gmail.com</a><br>
				forum: <a target="_blank" href="http://wordpress.org/support/plugin/football-pool">Support forum</a><br>
				help translating the plugin: <a target="_blank" href="https://translate.wordpress.org/projects/wp-plugins/football-pool/">translate.wordpress.org</a>(more info on the general process can be found <a target="_blank" href="https://make.wordpress.org/polyglots/handbook/translating/first-steps/">here</a>)<br>
			</p>

			<?php self::admin_footer(); ?>

			<?php
			// Place this bit after the admin_footer() to make sure the 'main' form is closed.
			//self::donate_button();
			?>

		</div> <!-- end help page -->

		<?php
	}

}

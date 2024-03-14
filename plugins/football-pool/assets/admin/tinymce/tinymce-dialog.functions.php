<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

function group_options() {
	$groups = Football_Pool_Groups::get_groups();
	foreach ( $groups as $group ) {
		printf( '<option value="%d">%s</option>', $group->id, Football_Pool_Utils::xssafe( $group->name ) );
	}
}

function date_now_postdate_custom_fieldset( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<fieldset>
				<legend>
					<a href="//php.net/manual/en/function.date.php" title="%1$s" target="_blank">%2$s</a>
				</legend>
				<div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="%3$s-now" name="%3$s-date" value="now" checked="checked">
							%4$s
						</label>
					</div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="%3$s-postdate" name="%3$s-date" value="postdate">
							%5$s
						</label>
					</div>
					<div>
						<label class="mce-label fp-mce-radio">
							<input type="radio" id="%3$s-custom" name="%3$s-date" value="custom" 
								onclick="jQuery( '#%3$s-custom-value' ).focus();">
							%6$s: 
								<input class="mce-textbox" type="text" id="%3$s-date-custom-value" placeholder="Y-m-d H:i" 
									onclick="jQuery( '#%3$s-custom' ).prop( 'checked', true );">
						</label>
					</div>
				</div>
			</fieldset>
		</div>
HTML;
		
		printf( $html
				, __( 'information about PHP\'s date format', 'football-pool' )
				, __( 'Date', 'football-pool' )
				, $shortcode
				, __( 'now', 'football-pool' )
				, __( 'postdate', 'football-pool' )
				, __( 'custom date', 'football-pool' )
		);
}

function ranking_options() {
	global $pool;
	$rankings = $pool->get_rankings( 'user defined' );
	$options = '';
	foreach ( $rankings as $ranking ) {
		$options .= sprintf( '<option value="%d">%s</option>'
							, $ranking['id']
							, Football_Pool_Utils::xssafe( $ranking['name'] ) 
					);
	}
	return $options;
}

function ranking_select_with_default( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-id">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-id">
					<optgroup label="%3$s">
						<option value="0" selected="selected">%4$s</option>
					</optgroup>
					<optgroup label="%5$s">
						%6$s
					</optgroup>
				</select>
			</div>
		</div>
HTML;
	
	printf( $html
			, $shortcode
			, __( 'Select a ranking', 'football-pool' )
			, __( 'default', 'football-pool' )
			, __( 'all scores', 'football-pool' )
			, __( 'or choose a user defined ranking', 'football-pool' )
			, ranking_options()
	);
}

function league_options() {
	global $pool;
	$leagues = $pool->get_leagues( true );
	$options = '';
	foreach ( $leagues as $league ) {
		$options .= sprintf( '<option value="%d">%s</option>'
							, $league['league_id']
							, Football_Pool_Utils::xssafe( $league['league_name'] ) 
					);
	}
	return $options;
}

function league_select( $shortcode, $multiple = false ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-league-id">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-league-id"%4$s>
					%3$s
				</select>
			</div>
		</div>
HTML;

	printf( $html
			, $shortcode
			, __( 'Select a league', 'football-pool' )
			, league_options()
			, ( $multiple === true ? ' multiple="multiple"' : '' )
	);
}

function league_select_with_default( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-league">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-league">
					<optgroup label="%3$s">
						<option value="0" selected="selected">%4$s</option>
					</optgroup>
					<optgroup label="%5$s">
						%6$s
					</optgroup>
				</select>
			</div>
		</div>
HTML;

	printf( $html
			, $shortcode
			, __( 'Select a league', 'football-pool' )
			, __( 'default', 'football-pool' )
			, __( 'all players', 'football-pool' )
			, __( 'or choose a league', 'football-pool' )
			, league_options()
	);
}

function league_select_with_default_and_user( $shortcode, $multiple = false ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-league">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-league"%9$s>
					<optgroup label="%3$s">
						<option value="0" selected="selected">%4$s</option>
					</optgroup>
					<optgroup label="%7$s">
						<option value="user">%8$s</option>
					</optgroup>
					<optgroup label="%5$s">
						%6$s
					</optgroup>
				</select>
			</div>
		</div>
HTML;

	printf( $html
			, $shortcode
			, __( 'Select a league', 'football-pool' )
			, __( 'default', 'football-pool' )
			, __( 'all players', 'football-pool' )
			, __( 'or choose a league', 'football-pool' )
			, league_options()
			, __( 'user', 'football-pool' )
			, __( 'league for logged in user', 'football-pool' )
			, ( $multiple === true ? ' multiple="multiple" style="height:100px;"' : '' )
	);
}

function label_textbox( $label, $input_id, $params = null ) {
	$param_string = $div_id = '';
	if ( is_array( $params ) ) {
		// First process special settings
		if ( isset( $params['div_id'] ) ) {
			$div_id = sprintf( ' id="%s"', $params['div_id'] );
			unset( $params['div_id'] );
		}
		if ( isset( $params['label_link'] ) ) {
			$tooltip = isset( $params['label_tooltip'] ) ? $params['label_tooltip'] : '';
			$label = sprintf( '<a href="%s" target="_blank" title="%s">%s</a>', $params['label_link'], $tooltip, $label );
			// Remove them from the array
			unset( $params['label_link'] );
			unset( $params['label_tooltip'] );
		}
		
		// Rest is parameters for the <input>
		foreach( $params as $param => $val ) {
			$param_string .= sprintf( '%s="%s" ', $param, $val );
		}
	}
	
	$html = <<<'HTML'
		<div%4$s>
			<label class="mce-label fp-mce-text" for="%1$s">%2$s</label>
			<div>
				<input class="mce-textbox" type="text" id="%1$s" %3$s/>
			</div>
		</div>
HTML;
	
	printf( $html, $input_id, $label, $param_string, $div_id );
}

function create_options( $options ) {
    $html = '';
    foreach ($options as $key => $val ) {
        $html .= sprintf( '<option value="%s">%s</option>', esc_attr( $key ), esc_attr( $val ) );
    }
    return $html;
}

function label_select( $label, $select_id, $options, $multiple = false ) {
    $html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s"%4$s>
					%3$s
				</select>
			</div>
		</div>
HTML;

    printf( $html
        , $select_id
        , __( $label, 'football-pool' )
        , create_options( $options )
        , ( $multiple === true ? ' multiple="multiple"' : '' )
    );
}

function label_checkbox( $label, $input_id, $params = null ) {
	$param_string = $div_id = '';
	if ( is_array( $params ) ) {
		// First process special settings
		if ( isset( $params['div_id'] ) ) {
			$div_id = sprintf( ' id="%s"', $params['div_id'] );
			unset( $params['div_id'] );
		}
		if ( isset( $params['label_link'] ) ) {
			$tooltip = isset( $params['label_tooltip'] ) ? $params['label_tooltip'] : '';
			$label = sprintf( '<a href="%s" target="_blank" title="%s">%s</a>', $params['label_link'], $tooltip, $label );
			// Remove them from the array
			unset( $params['label_link'] );
			unset( $params['label_tooltip'] );
		}
		
		// Rest is parameters for the <input>
		foreach( $params as $param => $val ) {
			$param_string .= sprintf( '%s="%s" ', $param, $val );
		}
	}
	
	$html = <<<'HTML'
		<div%4$s>
			<label class="mce-label fp-mce-checkbox" for="%1$s">%2$s</label>
			<div>
				<input class="mce-checkbox" type="checkbox" id="%1$s" %3$s/>
			</div>
		</div>
HTML;
	
	printf( $html, $input_id, $label, $param_string, $div_id );
}

function match_options() {
	global $pool;
	$options = '';
	foreach ( $pool->matches->matches as $match ) {
		$option_text = sprintf( '%d: %s - %s (%s)'
								, $match['id']
								, Football_Pool_Utils::xssafe( $match['home_team'] )
								, Football_Pool_Utils::xssafe( $match['away_team'] )
								, Football_Pool_Utils::date_from_gmt( $match['date'] )
						);
		$options .= sprintf( '<option value="%d">%s</option>', $match['id'], $option_text );
	}
	return $options;
}

function match_select_multiple( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-match-id">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-match-id" multiple="multiple" style="height:100px;">
					%3$s
				</select>
			</div>
		</div>
HTML;
	
	printf( $html
			, $shortcode
			, __( 'Match', 'football-pool' )
			, match_options()
	);
}

function match_select( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-match">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-match">
					<option value="0">%3$s</option>
					%4$s
				</select>
			</div>
		</div>
HTML;
	
	printf( $html
			, $shortcode
			, __( 'Match', 'football-pool' )
			, __( 'Select a match', 'football-pool' )
			, match_options()
	);
}

function bonusquestion_options() {
	global $pool;
	$questions = $pool->get_bonus_questions();
	$options = '';
	foreach( $questions as $question ) {
		if ( $question['match_id'] == 0 ) {
			$options .= sprintf( '<option value="%d">%d: %s</option>'
								, $question['id']
								, $question['id']
								, Football_Pool_Utils::xssafe( $question['question'] )
						);
		}
	}
	return $options;
}

function question_select_multiple( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-question-id">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-question-id" multiple="multiple" style="height:100px;">
					%3$s
				</select>
			</div>
		</div>
HTML;

	printf( $html
		, $shortcode
		, __( 'Question', 'football-pool' )
		, bonusquestion_options()
	);
}

function question_select( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-question">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-question">
					<option value="0">%3$s</option>
					%4$s
				</select>
			</div>
		</div>
HTML;
	
	printf( $html
			, $shortcode
			, __( 'Question', 'football-pool' )
			, __( 'Select a question', 'football-pool' )
			, bonusquestion_options()
	);
}

function user_options() {
	global $pool;
	$options = '';
	$users = $pool->get_users( 0 );
	foreach ( $users as $user ) {
		$options .= sprintf( '<option value="%d">%s</option>'
							, $user['user_id']
							, Football_Pool_Utils::xssafe( $user['user_name'] )
					);
	}
	return $options;
}

function user_select_multiple( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-user-id">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-user-id" multiple="multiple" style="height:100px;">
					%3$s
				</select>
			</div>
		</div>
HTML;
	
	printf( $html
			, $shortcode
			, __( 'Select a user', 'football-pool' )
			, user_options()
	);
}

function user_select( $shortcode, $params = null ) {
	$param_string = $div_id = '';
	if ( is_array( $params ) ) {
		// First process special settings
		if ( isset( $params['div_id'] ) ) {
			$div_id = sprintf( ' id="%s"', $params['div_id'] );
			unset( $params['div_id'] );
		}
		if ( isset( $params['label_link'] ) ) {
//			$tooltip = isset( $params['label_tooltip'] ) ? $params['label_tooltip'] : '';
//			$label = sprintf( '<a href="%s" target="_blank" title="%s">%s</a>', $params['label_link'], $tooltip, $label );
			// Remove them from the array
			unset( $params['label_link'] );
			unset( $params['label_tooltip'] );
		}
		
		// Rest is parameters for the <input>
		foreach( $params as $param => $val ) {
			$param_string .= sprintf( '%s="%s" ', $param, $val );
		}
	}
	
	$html = <<<'HTML'
		<div%8$s>
			<label class="mce-label" for="%1$s-user-id">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-user-id"%7$s>
					<optgroup label="%3$s">
						<option value="" selected="selected">%4$s</option>
					</optgroup>
					<optgroup label="%5$s">
						%6$s
					</optgroup>
				</select>
			</div>
		</div>
HTML;
	
	printf( $html
			, $shortcode
			, __( 'Select a user', 'football-pool' )
			, __( 'default', 'football-pool' )
			, __( 'logged in user', 'football-pool' )
			, __( 'or choose another user', 'football-pool' )
			, user_options()
			, $param_string
			, $div_id
	);
}

function matchtype_options() {
	$options = '';
	$match_types = Football_Pool_Matches::get_match_types();
	foreach( $match_types as $match_type ) {
		$options .= sprintf( '<option value="%d">%s</option>'
							, $match_type->id
							, Football_Pool_Utils::xssafe( $match_type->name )
					);
	}
	return $options;
}

function matchtype_select( $shortcode ) {
	$html = <<<'HTML'
		<div>
			<label class="mce-label" for="%1$s-matchtype-id">%2$s</label>
			<div>
				<select class="mce-select" id="%1$s-matchtype-id" style="height:100px;" multiple="multiple">
					%3$s
				</select>
			</div>
		</div>
HTML;
	
	printf( $html
			, $shortcode
			, __( 'Select one or more match types', 'football-pool' )
			, matchtype_options()
	);
}

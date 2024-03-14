/**
 * @preserve Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

jQuery( document ).ready( function() {
	// Set a callback to the get_shortcode() function in the parent window.
	parent.tinymce.activeEditor.get_shortcode = FootballPoolTinyMCE.get_shortcode;

	// remove the loading message
	// jQuery( '.fp-mce-loading' ).hide( 'fast' );
	// jQuery( '.form-dialog' ).show( 'fast' );
} );

var FootballPoolTinyMCE = ( function( $ ) {
	
	function get_shortcode() {
		let selected_shortcode,
			shortcode = '', 
			close_tag = false,
			content = false,
			preserve_content = false,
			the_text = '',
			scope = '',
			atts = '';

		// declare all parameters
		let slug, title, new_window, inline, count_to, the_date;
		let match, matches, question, questions, matchtypes;
		let user, users;
		let league, league_id, league_rank;
		let group_id, group;
		let ranking, ranking_id;
		let txt, no_texts, info;
		let format, time_format, format_string, num, date, amount;
		let use_querystring;
		let show_total, hide_zeroes, display;
		let key, def, type;
		let latest_registrations;

		selected_shortcode = $( '#shortcode' ).val();
		scope = '#' + selected_shortcode;
		switch( selected_shortcode ) {
			case 'fp-link':
				slug = $( '#slug', scope ).val();
				if ( slug !== '' ) atts += ' slug="' + slug + '"';
				break;
			case 'fp-register':
				preserve_content = true;
				close_tag = true;
				title = $( '#link-title', scope ).val();
				if ( title !== '' ) atts += ' title="' + title + '"';
				new_window = $( '#link-window', scope ).is( ':checked' );
				if ( new_window ) atts += ' new="1"';
				break;
			case 'fp-countdown':
				count_to = $( 'input[name=count_to]:checked', scope ).val();
				if ( count_to === 'date' ) {
					the_date = $( '#count-date', scope ).val();
					if ( the_date !== '' ) atts += ' date="' + the_date + '"';
				} else if ( count_to === 'match' ) {
					match = $( '#count-match', scope ).val();
					if ( match > 0 || match === 'next' ) atts += ' match="' + match + '"';
				}
				inline = $( '#count-inline', scope ).is( ':checked' );
				if ( inline ) atts += ' display="inline"';
				no_texts = $( '#count-no-texts', scope ).is( ':checked' );
				let texts = '';
				if ( no_texts ) {
					texts = 'none';
				} else {
					texts = [
						$( '#text-1', scope ).val(),
						$( '#text-2', scope ).val(),
						$( '#text-3', scope ).val(),
						$( '#text-4', scope ).val()
					].join( ';' );
				}
				if ( texts !== '' && texts !== ';;;' ) atts += ' texts="' + texts + '"';
				time_format = $( '#count-format', scope ).val();
				if ( time_format > 0 ) atts += ' format="' + time_format + '"';
				format_string = $( '#count-format-string', scope ).val();
				if ( format_string !== '' ) atts += ' format_string="' + format_string + '"';
				break;
			case 'fp-group':
				group = $( '#group-id', scope ).val();
				if ( group > 0 ) atts += ' id=' + group;
				break;
			case 'fp-ranking':
				ranking = $( '#ranking-id', scope ).val();
				if ( ranking > 0 ) atts += ' ranking="' + ranking + '"';
				league = $( '#ranking-league', scope ).val();
				if ( league > 0 || league === 'user' ) atts += ' league="' + league + '"';
				num = $( '#ranking-num', scope ).val();
				if ( num > 0 ) atts += ' num="' + num + '"';
				date = $( 'input:radio[name=ranking-date]:checked', scope ).val();
				if ( date === 'custom' ) date = $( '#ranking-date-custom-value', scope ).val();
				if ( date !== '' ) atts += ' date="' + date + '"';
				break;
			case 'fp-user-list':
				league = $( '#user-list-league', scope ).val();
				if ( league > 0 || league === 'user' ) atts += ' league="' + league + '"';
				latest_registrations = $( '#user-list-latest', scope ).is( ':checked' );
				if ( latest_registrations ) atts += ' latest="yes"';
				num = $( '#user-list-num', scope ).val();
				if ( num > 0 ) atts += ' num="' + num + '"';
				break;
			case 'fp-predictions':
				match = $( '#predictions-match', scope ).val();
				if ( match > 0 ) atts += ' match="' + match + '"';
				question = $( '#predictions-question', scope ).val();
				if ( question > 0 ) atts += ' question="' + question + '"';
				txt = $( '#predictions-text', scope ).val();
				if ( txt !== '' ) atts += ' text="' + txt + '"';
				use_querystring = $( '#predictions-use-querystring', scope ).is( ':checked' );
				if ( use_querystring ) atts += ' use_querystring="yes"';
				break;
			case 'fp-user-score':
				ranking = $( '#user-score-ranking-id', scope ).val();
				if ( ranking > 0 ) atts += ' ranking="' + ranking + '"';
				user = $( '#user-score-user-id', scope ).val();
				if ( user !== '' ) atts += ' user="' + user + '"';
				txt = $( '#user-score-text', scope ).val();
				if ( txt !== '' ) atts += ' text="' + txt + '"';
				date = $( 'input:radio[name=user-score-date]:checked', scope ).val();
				if ( date === 'custom' ) date = $( '#user-score-date-custom-value', scope ).val();
				if ( date !== '' ) atts += ' date="' + date + '"';
				use_querystring = $( '#user-score-use-querystring', scope ).is( ':checked' );
				if ( use_querystring ) atts += ' use_querystring="yes"';
				break;
			case 'fp-user-ranking':
				ranking = $( '#user-ranking-ranking-id', scope ).val();
				if ( ranking > 0 ) atts += ' ranking="' + ranking + '"';
				league_rank = $( '#user-ranking-league-rank', scope ).is( ':checked' );
				if ( league_rank ) atts += ' league_rank="yes"';
				user = $( '#user-ranking-user-id', scope ).val();
				if ( user !== '' ) atts += ' user="' + user + '"';
				txt = $( '#user-ranking-text', scope ).val();
				if ( txt !== '' ) atts += ' text="' + txt + '"';
				date = $( 'input:radio[name=user-ranking-date]:checked', scope ).val();
				if ( date === 'custom' ) date = $( '#user-ranking-date-custom-value', scope ).val();
				if ( date !== '' ) atts += ' date="' + date + '"';
				break;
			case 'fp-predictionform':
				matches = $( '#match-id', scope ).val() || [];
				if ( matches.length > 0 ) atts += ' match="' + matches.join( ',' ) + '"';
				matchtypes = $( '#matchtype-id', scope ).val() || [];
				if ( matchtypes.length > 0 ) atts += ' matchtype="' + matchtypes.join( ',' ) + '"';
				questions = $( '#question-id', scope ).val() || [];
				if ( questions.length > 0 ) atts += ' question="' + questions.join( ',' ) + '"';
				break;
			case 'fp-next-match-form':
				num = $( '#next-match-form-num', scope ).val();
				if ( num > 0 ) atts += ' num="' + num + '"';
				break;
			case 'fp-matches':
				group_id = $( '#matches-group-id', scope ).val();
				if ( group_id !== '' ) atts += ' group="' + group_id + '"';
				matches = $( '#matches-match-id', scope ).val() || [];
				if ( matches.length > 0 ) atts += ' match="' + matches.join( ',' ) + '"';
				matchtypes = $( '#matches-matchtype-id', scope ).val() || [];
				if ( matchtypes.length > 0 ) atts += ' matchtype="' + matchtypes.join( ',' ) + '"';
				break;
			case 'fp-next-matches':
				group_id = $( '#next-matches-group-id', scope ).val();
				if ( group_id !== '' ) atts += ' group="' + group_id + '"';
				matchtypes = $( '#next-matches-matchtype-id', scope ).val() || [];
				if ( matchtypes.length > 0 ) atts += ' matchtype="' + matchtypes.join( ',' ) + '"';
				date = $( 'input:radio[name=next-matches-date]:checked', scope ).val();
				if ( date === 'custom' ) date = $( '#next-matches-date-custom-value', scope ).val();
				if ( date !== '' ) atts += ' date="' + date + '"';
				num = $( '#next-matches-num', scope ).val();
				if ( num > 0 ) atts += ' num="' + num + '"';
				break;
			case 'fp-last-matches':
				group_id = $( '#last-matches-group-id', scope ).val();
				if ( group_id !== '' ) atts += ' group="' + group_id + '"';
				matchtypes = $( '#last-matches-matchtype-id', scope ).val() || [];
				if ( matchtypes.length > 0 ) atts += ' matchtype="' + matchtypes.join( ',' ) + '"';
				date = $( 'input:radio[name=last-matches-date]:checked', scope ).val();
				if ( date === 'custom' ) date = $( '#last-matches-date-custom-value', scope ).val();
				if ( date !== '' ) atts += ' date="' + date + '"';
				num = $( '#last-matches-num', scope ).val();
				if ( num > 0 ) atts += ' num="' + num + '"';
				break;
			case 'fp-league-info':
				league_id = $( '#league-info-league-id', scope ).val();
				if ( league_id > 0 ) atts += ' league="' + league_id + '"';
				info = $( 'input:radio[name=league-info-info]:checked', scope ).val();
				if ( info !== '' ) atts += ' info="' + info + '"';
				ranking_id = $( '#league-info-ranking-id', scope ).val();
				if ( ranking_id > 0 ) atts += ' ranking="' + ranking_id + '"';
				format = $( '#league-info-format', scope ).val();
				if ( format !== '' ) atts += ' format="' + format + '"';
				break;
			case 'fp-match-scores':
				league_id = $( '#match-scores-league', scope ).val();
				if ( league_id > 0 ) atts += ' league="' + league_id + '"';
				users = $( '#match-scores-user-id', scope ).val() || [];
				if ( users.length > 0 ) atts += ' users="' + users.join( ',' ) + '"';
				matches = $( '#match-scores-match-id', scope ).val() || [];
				if ( matches.length > 0 ) atts += ' match="' + matches.join( ',' ) + '"';
				matchtypes = $( '#match-scores-matchtype-id', scope ).val() || [];
				if ( matchtypes.length > 0 ) atts += ' matchtype="' + matchtypes.join( ',' ) + '"';
				use_querystring = $( '#match-scores-use-querystring', scope ).is( ':checked' );
				if ( use_querystring ) atts += ' use_querystring="yes"';
				show_total = $( '#match-scores-show-total', scope ).is( ':checked' );
				if ( show_total ) atts += ' show_total="yes"';
				hide_zeroes = $( '#match-scores-hide-zeroes', scope ).is( ':checked' );
				if ( hide_zeroes ) atts += ' hide_zeroes="yes"';
				display = $( '#match-scores-display', scope ).val();
				if ( ['points', 'predictions', 'both'].includes( display ) ) atts += ' display="' + display + '"';
				break;
			case 'fp-question-scores':
				league_id = $( '#question-scores-league', scope ).val();
				if ( league_id > 0 ) atts += ' league="' + league_id + '"';
				users = $( '#question-scores-user-id', scope ).val() || [];
				if ( users.length > 0 ) atts += ' users="' + users.join( ',' ) + '"';
				questions = $( '#question-scores-question-id', scope ).val() || [];
				if ( questions.length > 0 ) atts += ' question="' + questions.join( ',' ) + '"';
				use_querystring = $( '#question-scores-use-querystring', scope ).is( ':checked' );
				if ( use_querystring ) atts += ' use_querystring="yes"';
				show_total = $( '#question-scores-show-total', scope ).is( ':checked' );
				if ( show_total ) atts += ' show_total="yes"';
				hide_zeroes = $( '#question-scores-hide-zeroes', scope ).is( ':checked' );
				if ( hide_zeroes ) atts += ' hide_zeroes="yes"';
				break;
			case 'fp-plugin-option':
				key = $( '#plugin-option-key', scope ).val();
				if ( key !== '' ) atts += ' option="' + key + '"';
				def = $( '#plugin-option-default', scope ).val();
				if ( def !== '' ) atts += ' default="' + def + '"';
				// var type = $( '#plugin-option-type', scope ).val();
				type = $( 'input:radio[name=plugin-option-type]:checked', scope ).val();
				if ( type !== '' ) atts += ' type="' + type + '"';
				break;
			case 'fp-money-in-the-pot':
				league = $( '#money-in-the-pot-league', scope ).val() || [];
				if ( league.length > 0 ) {
					if ( Array.isArray( league ) ) league = league.join( ',' );
					atts += ' league="' + league + '"';
				}
				amount = $( '#money-in-the-pot-amount', scope ).val();
				if ( amount !== '' ) atts += ' amount="' + amount + '"';
				format = $( '#money-in-the-pot-format', scope ).val();
				if ( format !== '' ) atts += ' format="' + format + '"';
				break;
			case 'fp-last-calc-date':
				format = $( '#last-calc-date-format', scope ).val();
				if ( format !== '' ) atts += ' format="' + format + '"';
				break;
			default:
				//nothing
		}
		
		if ( selected_shortcode !== '' ) {
			if ( preserve_content && parent.tinymce.activeEditor.selection.getContent() !== '' ) {
				the_text = parent.tinymce.activeEditor.selection.getContent( { format : 'text' } );
			}
			shortcode = '[' + selected_shortcode + atts + ']';
			shortcode += the_text;
			shortcode += ( close_tag ? '[/' + selected_shortcode + ']' : '' );
		}
		
		return shortcode;
	}
	
	function display_shortcode_options( shortcode ) {
		if ( shortcode !== '' ) {
			$( '#mce-set-parameters-header' ).show();
			let shortcode_div = $( '#' + shortcode );
			if ( shortcode_div.length === 0 ) {
				shortcode_div = $( '#no-shortcode-params' );
			}
			shortcode_div.addClass( 'current' );
			$( '.shortcode-options-panel' ).not( shortcode_div ).removeClass( 'current' );
		} else {
			$( '.shortcode-options-panel' ).removeClass( 'current' );
			$( '#mce-set-parameters-header' ).hide();
		}
	}
	
	function toggle_count_texts( id ) {
		const text_ids = ['#text-1', '#text-2', '#text-3', '#text-4'];
		if ( $( '#' + id ).is( ':checked' ) ) {
			FootballPoolAdmin.set_input_param( 'placeholder', text_ids, 'none' );
		} else {
			FootballPoolAdmin.restore_input_param( 'placeholder', text_ids );
		}
		FootballPoolAdmin.disable_inputs( text_ids, id );
	}
	
	function toggle_select_row( clicked, shortcode ) {
		clicked = $( clicked ).attr( 'for' );
		$( '#fp-' + shortcode + ' select' ).each( function() {
			if ( $( this ).attr( 'id' ) === clicked ) {
				$( this ).show( 'slow' );
			} else {
				$( this ).hide( 'slow' );
			}
		} );
	}

	return {
		// public methods
		display_shortcode_options: display_shortcode_options,
		toggle_count_texts: toggle_count_texts,
		toggle_select_row: toggle_select_row,
		get_shortcode: get_shortcode
	};

} )( jQuery );
	

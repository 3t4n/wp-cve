/*global tinymce */
( function () {

	/**
	 * Check is empty.
	 *
	 * @param  {string} value
	 * @return {bool}
	 */
	function bbpShortcodesIsEmpty( value ) {
		value = value.toString();

		if ( 0 !== value.length ) {
			return false;
		}

		return true;
	}

	/**
	 * Add the shortcodes downdown.
	 */
	tinymce.PluginManager.add( 'bbpress_shortcodes', function ( editor ) {
		var ed = tinymce.activeEditor;
		editor.addButton( 'bbpress_shortcodes', {
			text: ed.getLang( 'bbpress_shortcodes.shortcode_title' ),
			title: ed.getLang( 'bbpress_shortcodes.shortcode_title' ),
			icon: 'bbpress-shortcodes',
			type: 'menubutton',
			menu: [
				{
					text: ed.getLang( 'bbpress_shortcodes.forums' ),
					menu: [
						{
							text: ed.getLang( 'bbpress_shortcodes.forum_index' ),
							onclick: function () {
								editor.insertContent( '[bbp-forum-index]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.forum_form' ),
							onclick: function () {
								editor.insertContent( '[bbp-forum-form]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.single_forum' ),
							onclick: function () {
								editor.windowManager.open({
									title: ed.getLang( 'bbpress_shortcodes.single_forum' ),
									body: [
										{
											type:  'textbox',
											name:  'id',
											label: ed.getLang( 'bbpress_shortcodes.forum_id' )
										}
									],
									onsubmit: function ( e ) {
										var id         = bbpShortcodesIsEmpty( e.data.id ) ? '' : ' id="' + e.data.id + '"';

										if ( ! bbpShortcodesIsEmpty( e.data.id ) ) {
											editor.insertContent( '[bbp-single-forum ' + id + ']' );
										} else {
											editor.windowManager.alert( ed.getLang( 'bbpress_shortcodes.need_id' ) );
										}
									}
								});
							}
						}
					]
				},{
					text: ed.getLang( 'bbpress_shortcodes.topics' ),
					menu: [
						{
							text: ed.getLang( 'bbpress_shortcodes.topic_index' ),
							onclick: function () {
								editor.insertContent( '[bbp-topic-index]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.topic_form' ),
							onclick: function () {
								editor.insertContent( '[bbp-topic-form]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.forum_topic_form' ),
							onclick: function () {
								editor.windowManager.open({
									title: ed.getLang( 'bbpress_shortcodes.forum_topic_form' ),
									body: [
										{
											type:  'textbox',
											name:  'id',
											label: ed.getLang( 'bbpress_shortcodes.forum_id' )
										}
									],
									onsubmit: function ( e ) {
										var id         = bbpShortcodesIsEmpty( e.data.id ) ? '' : ' forum_id="' + e.data.id + '"';

										if ( ! bbpShortcodesIsEmpty( e.data.id ) ) {
											editor.insertContent( '[bbp-topic-form ' + id + ']' );
										} else {
											editor.windowManager.alert( ed.getLang( 'bbpress_shortcodes.need_id' ) );
										}
									}
								});
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.single_topic' ),
							onclick: function () {
								editor.windowManager.open({
									title: ed.getLang( 'bbpress_shortcodes.single_topic' ),
									body: [
										{
											type:  'textbox',
											name:  'id',
											label: ed.getLang( 'bbpress_shortcodes.topic_id' )
										}
									],
									onsubmit: function ( e ) {
										var id         = bbpShortcodesIsEmpty( e.data.id ) ? '' : ' id="' + e.data.id + '"';

										if ( ! bbpShortcodesIsEmpty( e.data.id ) ) {
											editor.insertContent( '[bbp-single-topic ' + id + ']' );
										} else {
											editor.windowManager.alert( ed.getLang( 'bbpress_shortcodes.need_id' ) );
										}
									}
								});
							}
						}
					]
				},{
					text: ed.getLang( 'bbpress_shortcodes.replies' ),
					menu: [
						{
							text: ed.getLang( 'bbpress_shortcodes.reply_form' ),
							onclick: function () {
								editor.insertContent( '[bbp-reply-form]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.single_reply' ),
							onclick: function () {
								editor.windowManager.open({
									title: ed.getLang( 'bbpress_shortcodes.single_reply' ),
									body: [
										{
											type:  'textbox',
											name:  'id',
											label: ed.getLang( 'bbpress_shortcodes.reply_id' )
										}
									],
									onsubmit: function ( e ) {
										var id         = bbpShortcodesIsEmpty( e.data.id ) ? '' : ' id="' + e.data.id + '"';

										if ( ! bbpShortcodesIsEmpty( e.data.id ) ) {
											editor.insertContent( '[bbp-single-reply ' + id + ']' );
										} else {
											editor.windowManager.alert( ed.getLang( 'bbpress_shortcodes.need_id' ) );
										}
									}
								});
							}
						}
					]
				},{
					text: ed.getLang( 'bbpress_shortcodes.topic_tags' ),
					menu: [
						{
							text: ed.getLang( 'bbpress_shortcodes.display_topic_tags' ),
							onclick: function () {
								editor.insertContent( '[bbp-topic-tags]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.single_tag' ),
							onclick: function () {
								editor.windowManager.open({
									title: ed.getLang( 'bbpress_shortcodes.single_tag' ),
									body: [
										{
											type:  'textbox',
											name:  'id',
											label: ed.getLang( 'bbpress_shortcodes.tag_id' )
										}
									],
									onsubmit: function ( e ) {
										var id         = bbpShortcodesIsEmpty( e.data.id ) ? '' : ' id="' + e.data.id + '"';

										if ( ! bbpShortcodesIsEmpty( e.data.id ) ) {
											editor.insertContent( '[bbp-single-tag ' + id + ']' );
										} else {
											editor.windowManager.alert( ed.getLang( 'bbpress_shortcodes.need_id' ) );
										}
									}
								});
							}
						}
					]
				},{
					text: ed.getLang( 'bbpress_shortcodes.views' ),
					menu: [
						{
							text: ed.getLang( 'bbpress_shortcodes.popular' ),
							onclick: function () {
								editor.insertContent( '[bbp-single-view id="popular"]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.no_replies' ),
							onclick: function () {
								editor.insertContent( '[bbp-single-view id="no-replies"]' );
							}
						}
					]
				},{
					text: ed.getLang( 'bbpress_shortcodes.search' ),
					menu: [
						{
							text: ed.getLang( 'bbpress_shortcodes.search_input' ),
							onclick: function () {
								editor.insertContent( '[bbp-search]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.search_form' ),
							onclick: function () {
								editor.insertContent( '[bbp-search-form]' );
							}
						}
					]
				},{
					text: ed.getLang( 'bbpress_shortcodes.account' ),
					menu: [
						{
							text: ed.getLang( 'bbpress_shortcodes.login' ),
							onclick: function () {
								editor.insertContent( '[bbp-login]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.register' ),
							onclick: function () {
								editor.insertContent( '[bbp-register]' );
							}
						},
						{
							text: ed.getLang( 'bbpress_shortcodes.lost_pass' ),
							onclick: function () {
								editor.insertContent( '[bbp-lost-pass]' );
							}
						}
					]
				},				
				{
					text: ed.getLang( 'bbpress_shortcodes.statistics' ),
					onclick: function () {
						editor.insertContent( '[bbp-stats]' );
					}
				}
			]
		});
	});
})();
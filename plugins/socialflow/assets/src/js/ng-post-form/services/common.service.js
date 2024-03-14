export default class CommonService {
	/* @ngInject */
	constructor( cacheService ) {
		this.$postContentWrap = angular.element( '#wp-content-wrap' );
		this.$postContent     = angular.element( '#content' );
		this.$postTitle       = angular.element( '#title' );

		this._cacheService = cacheService;
	}

	/**
	 * Remove html tags, shortcodes, html special chars and whitespaces from the beginning and end of text
	 * @param  {string} text Text to be cleand
	 * @return {string}      CLean text
	 */
	cleanText( text ) {
		text = text.replace(/<(?:.|\n)*?>/gm, '').replace( /\[(?:.|\n)*?\]/gm, '' );
		text = text.replace( '&nbsp;', '' );
		return text.trim();
	}

	getPostTitle() {
		return this.$postTitle;
	}

	getPostTitleValue() {
		return this.getPostTitle().val();
	}

	getPostContent() {
		return this.$postContent;
	}

	getPostContentValue() {
		if ( 'undefined' !== typeof tinyMCE 
			 && tinyMCE.activeEditor 
			 && this.$postContentWrap.hasClass( 'tmce-active' )
			 && 'undefined' !== typeof tinyMCE.activeEditor.initialized
        ) {
			return tinyMCE.activeEditor.getContent();
		} 

		if ( this._cacheService.isAjax() ) 
			return null;

		return this.getPostContent().val();
	}
}
export default {
	template: sfPostFormTmpls.errors,
	controller: function( cacheService, $sce ) {
		this.errors = cacheService.get( 'errors' );
		this.showErrors = ( this.errors && 0 != this.errors.length );

		this.trustAsHtml = ( text ) => {
			return $sce.trustAsHtml( text );
		}
	}
}
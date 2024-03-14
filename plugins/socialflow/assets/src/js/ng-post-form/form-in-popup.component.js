export default {
	template: sfPostFormTmpls.formInPopup,
	controller: function( httpService, $timeout, cacheService, $sce, $scope ) {
		this.showForm = false;
		this.message  = '';
		this.showSpinner = false;
		this.ajaxData = {
			stats: {},
			errors: []
		};

		angular.element( 'body' ).on( 'thickbox:removed', (e) => {
			$timeout( () => {
				cacheService.clear();

				this.clearForm();
			});
		});

		angular.element( '.sf-open-popup' ).on( 'click', (e) => {
			e.preventDefault();

			let $btn = angular.element( e.currentTarget );

			httpService
				.post({
					action: 'sf-composeform-data',
					post: $btn.data( 'postId' )
				})
				.then( ( data ) => {
		            data.post.formId = true;
					cacheService.set( data );
					cacheService.isAjax( true );

					this.showForm = true;
				});
		});

		this.submit = ( e ) => {
			let data = angular.element( e.currentTarget ).serializeArray();
			let dataObj = {};

			data.forEach( ( field ) => {
				dataObj[ field.name ] = field.value;
			});

			dataObj['post_id'] = cacheService.get( 'post' ).ID;

			this.showSpinner = true;

			httpService
				.post( dataObj )
				.then( ( data ) => {
					this.showSpinner = false;

					this.message = $sce.trustAsHtml( data.form_message );

					if ( data.stats ) {
						cacheService.set( data.stats, 'stats' );

						this.ajaxData.stats = data.stats;
						
						$timeout( () => $scope.$apply() );
					}

				});
		}

		this.clearForm = () => {
			this.showForm = false;
			this.message  = '';
		}
	}
}
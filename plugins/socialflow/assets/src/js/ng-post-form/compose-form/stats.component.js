export default {
	bindings: {
		ajaxData: '<'
	},
	template: sfPostFormTmpls.statsFull,
	controller: function( cacheService, httpService, $sce, $scope ) {
		let data   = cacheService.get( 'stats' );
		let postId = cacheService.get( 'post' ).ID;

		$scope.ajaxData = this.ajaxData;


		this.logs     = formatLogsArray( data.logs );
		this.lastSent = data.last_sent;
		this.showList = false;
		$scope.$watch( 'ajaxData.stats', () => {
			data = cacheService.get( 'stats' );

			this.logs     = formatLogsArray( data.logs );
			this.lastSent = data.last_sent;
		});


		this.toggleList = ( e ) => {
			e.preventDefault();

			this.showList = ! this.showList;
		}

		this.updateLogs = ( e ) => {
			e.preventDefault();

			angular.forEach( this.logs, ( log, key ) => {
				if ( 1 == log.message.is_published )
					return;
				updateSingleLog( log, key );
			});
		}

		this.trustAsHtml = ( text ) => {
			return $sce.trustAsHtml( text );
		}

		function updateSingleLog( log, key ) {
			log.showSpinner = true;

			httpService
				.get({
					action:     'sf-get-message', 
					post_id:    postId, 
					id:         log.message.content_item_id, 
					time:       log.time, 
					account_id: log.account.id,
				})
				.then( ( data ) => {
					log.showSpinner = false;
					log.message.status = data;
				});
		}

		function formatLogsArray( logs ) {
			let output = [];

			angular.forEach( logs, ( data, time ) => {
				let i = 0;

				angular.forEach( data.accounts, ( account, accountId ) => {
					angular.forEach( account.messages, ( message, key ) => {
						output.push({
							date: ( 0 == i ) ? data.date : '',
							time: time,
							account: {
								id: accountId,
								name: ( 0 == key ) ? account.name : '',
							},
							message: message,
							showSpinner: false,
						});
						i++;
					});
				});
			});

			return output;
		}
	}
}
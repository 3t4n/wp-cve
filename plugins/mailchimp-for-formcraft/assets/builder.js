FormCraftApp.controller('MailChimpController', function($scope, $http) {
	$scope.addMap = function(){
		if ($scope.SelectedList=='' || $scope.SelectedColumn==''){return false;}
		if (typeof $scope.$parent.Addons.MailChimp.Map=='undefined')
		{
			$scope.$parent.Addons.MailChimp.Map = [];
		}
		$scope.$parent.Addons.MailChimp.Map.push({
			'listID': $scope.SelectedList,
			'listName': jQuery('#mc-map-input .select-list option:selected').text(),
			'columnID': $scope.SelectedColumn,
			'columnName': jQuery('#mc-map-input .select-column option:selected').text(),
			'formField': jQuery('#mc-map-input .select-field').val()
		});
	}
	$scope.removeMap = function ($index)
	{
		$scope.$parent.Addons.MailChimp.Map.splice($index, 1);
	}
	$scope.testKey = function(){
		if (typeof $scope.$parent.Addons.MailChimp!='undefined') {
			jQuery('#mc-cover .api-key button').addClass('loading');
			$http.get(FC.ajaxurl+'?action=formcraft_mailchimp_test_api&key='+$scope.Addons.MailChimp.api_key).success(function(response) {
				jQuery('#mc-cover .api-key button').removeClass('loading');
				if (response.success) {
					$scope.$parent.Addons.MailChimp.validKey = $scope.Addons.MailChimp.api_key;
					jQuery('#mc-cover .api-key button').addClass('loading');
					$http.get(FC.ajaxurl+'?action=formcraft_mailchimp_get_lists&key='+$scope.Addons.MailChimp.validKey).success(function(response){
						jQuery('#mc-cover .api-key button').removeClass('loading');
						if (response.success) {
							$scope.MCLists = response.lists;
							$scope.SelectedList = '';
						}
					});
				} else {
					$scope.$parent.Addons.MailChimp.validKey = false;
				}
			});
		}
	}
	$scope.Init = function(){
		if ( typeof $scope.MCLists =='undefined' && typeof $scope.$parent.Addons!='undefined' && typeof $scope.$parent.Addons.MailChimp!='undefined' && typeof $scope.$parent.Addons.MailChimp.validKey!='undefined' && $scope.$parent.Addons.MailChimp.validKey!=false)
		{
			jQuery('#mc-cover').addClass('loading');
			$http.get(FC.ajaxurl+'?action=formcraft_mailchimp_get_lists&key='+$scope.Addons.MailChimp.validKey).success(function(response){
				jQuery('#mc-cover').removeClass('loading');
				if (response.success)
				{
					$scope.MCLists = response.lists;
					$scope.SelectedList = '';
				}
			});
		}
	}
	$scope.$watch('SelectedList', function(){
		if (typeof $scope.$parent.Addons!='undefined' && $scope.SelectedList!='undefined' && $scope.SelectedList!='')
		{
			jQuery('#mc-cover').addClass('loading');
			$http.get(FC.ajaxurl+'?action=formcraft_mailchimp_get_columns&key='+$scope.Addons.MailChimp.validKey+'&id='+$scope.SelectedList).success(function(response){
				jQuery('#mc-cover').removeClass('loading');
				if (response.success)
				{
					$scope.MCColumns = response.columns;
					$scope.SelectedColumn = '';
				}
			});
		}
	});
	$scope.$watch('Addons.MailChimp.validKey', function(){
		if (typeof $scope.$parent.Addons!='undefined')
		{
			if (typeof $scope.$parent.Addons.MailChimp.validKey!='undefined' && $scope.$parent.Addons.MailChimp.validKey!=false)
			{
				$scope.$parent.Addons.MailChimp.showOptions = true;
			}
			else
			{
				$scope.$parent.Addons.MailChimp.showOptions = false;
			}
		}
	});
});
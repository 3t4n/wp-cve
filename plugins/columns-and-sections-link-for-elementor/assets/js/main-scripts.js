
    var WrapperLinksHander = function ($scope, $) {
      if (isEditMode) {
        return;
      }
      if ($scope.data("wts-url") && $scope.data("wts-link") == "yes") {
        $scope.on("click", function (e) {
          if (
            $scope.data("wts-url") &&
            $scope.data("wts-new-window") == "yes"
          ) {
            window.open($scope.data("wts-url"));
          } else {
            location.href = $scope.data("wts-url");
          }
        });
      }
    };

    elementorFrontend.hooks.addAction(
      "frontend/element_ready/global",
      WrapperLinksHander
    );

//END 

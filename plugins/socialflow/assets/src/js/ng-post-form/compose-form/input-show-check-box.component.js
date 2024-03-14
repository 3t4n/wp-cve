class CheckBoxInputCustom {
    /* @ngInject */
    constructor( $scope ,accountsService) {
    }


    showButtonCustom( socialType ) {
        cobsole.log(socialType)
    }
}

export default {
    bindings: {
        global: '<'
    },
    template: sfPostFormTmpls.inputShowCheckBox,
    controller: function( fieldService, cacheService ) {
        let a = 1;


    }
}

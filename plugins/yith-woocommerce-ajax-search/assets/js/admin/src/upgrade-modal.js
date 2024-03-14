import Cookies from 'js-cookie';
class YWCAS_UPGRADE_MODAL  {

    constructor(){
        this.showModalLinks = jQuery(document).find('.ywcas-show-modal');
        this.button = jQuery(document).find('.ywcas-modal-upgrade-button');
        this.button.on('click',this.convert.bind(this));
        this.showModalLinks.on('click', this.manualShowModal.bind(this));
        this.isVisited = typeof Cookies.get( 'ywcas_modal_visited' ) !== 'undefined';

        if( !this.isVisited){
            this.init();
        }
    }

    init(){

        this.showModal();
    }
    showModal(){
        let modal = 	wp.template(
            'ywcas-upgrade-modal'
        );

        yith.ui.modal({
            allowWpMenu:false,
            allowWpMenuInMobile:false,
            title:false,
            footer:false,
            content : modal(),
            width:'400px',
            onClose: function (){
                Cookies.set( 'ywcas_modal_visited', 'yes', {
                    path: '/',
                    expires: 30,
                } );
            }
        });

    }

    manualShowModal(event){
        event.preventDefault();
        this.showModal();
    }
    convert(event){
        event.preventDefault();
    }
}

jQuery( document ).ready( function ( $ ) {
  new YWCAS_UPGRADE_MODAL();
});
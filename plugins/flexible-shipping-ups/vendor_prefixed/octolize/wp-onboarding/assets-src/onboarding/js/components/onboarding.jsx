import React from "react";
import Popup from "./popup";

/**
 * Onboarding REACT class.
 */
export default class Onboarding extends React.Component {
    /**
     * @param {Object} props
     */
    constructor( props ) {
        super( props );

        let settings = props.settings || {};

        this.state = {
            ajax: settings.ajax || null,
            popups: settings.popups || [],
            logo_img: settings.logo_img || null,
            label_step: settings.label_step || 'Step',
            assets_url: settings.assets_url || '',
            open_auto: settings.open_auto || false,
            page: settings.page || 'none',
            current_step: 0,
            steps: parseInt( settings.steps || 0, 10 ),
        };

        if ( this.state.open_auto ) {
            this.sendTrackerAutoShowPopup();
        }

        this.onClick = this.onClick.bind( this );
        this.onClose = this.onClose.bind( this );
        this.onFieldValueChanged = this.onFieldValueChanged.bind( this );

        if ( settings.openOnboardingButton ) {
            this.displayOnboardingOnClick( settings.openOnboardingButton );
        }
        if ( settings.activateOnScrollElement ) {
            this.displayOnboardingOnScroll( document.querySelector( settings.activateOnScrollElement ) );
        }
    }

    /**
     *
     * @param openOnboardingButton
     */
    displayOnboardingOnClick( openOnboardingButton ) {
        let self = this;

        openOnboardingButton.addEventListener( 'click', function ( e ) {
            e.preventDefault();

            self.showPopup( self.state.current_step );

            self.state.open_auto = false;

            self.sendTrackerDataEvent( self.state.ajax.action.click );
        } );
    }

    /**
     *
     * @param {string} popup_id
     * @param {string} field_id
     * @param {string} value
     * @param {boolean} checked
     */
    onFieldValueChanged( popup_id, field_id, value, checked ) {
        let popups = this.state.popups;
        popups.forEach(function(popup, popup_index){
           if ( popup.id === popup_id ) {
               let fields = popup.content;
               fields.forEach(function(field, field_index){
                   if (field.id === field_id) {
                       field.value = value;
                       field.checked = checked;
                   }
               });
           }
        });

        this.setState( { popups: popups } );
    }

    /**
     *
     * @param openOnboardingButton
     */
    displayOnboardingOnScroll( openOnboardingButton ) {
        if ( !this.state.open_auto ) {
            return;
        }

        let self = this;
        let actived = false;

        document.addEventListener( 'scroll', function ( e ) {
            if ( !actived && self.isElementInViewport( openOnboardingButton ) ) {
                self.sendAJAX( self.state.ajax.action.auto_show_popup );

                self.showPopup( 0 );

                actived = true;
            }
        } );
    }

    /**
     * @returns {JSX.Element}
     */
    render() {
        let self = this;

        return (
            this.state.popups.map( ( popup, index ) => {
                return (
                    <Popup
                        id={index}
                        key={"popup-" + index}
                        popup_id={popup.id}
                        ajax={self.state.ajax}
                        assets_url={self.state.assets_url}
                        label_step={self.state.label_step}
                        logo_img={self.state.logo_img}
                        steps={self.state.steps}
                        content={popup}
                        on_button_click={self.onClick}
                        on_close_popup={self.onClose}
                        on_field_value_changed={self.onFieldValueChanged}
                    />
                );
            } )
        );
    }

    /**
     *
     * @param el
     * @returns {boolean}
     */
    isElementInViewport( el ) {
        let rect = el.getBoundingClientRect();

        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (
                window.innerHeight || document.documentElement.clientHeight
            ) &&
            rect.right <= (
                window.innerWidth || document.documentElement.clientWidth
            )
        );
    }

    /**
     *
     * @param {object|integer} item
     * @param {integer }key
     */
    onClick( item, key ) {

        this.hidePopup( key );

        if ( typeof item === 'number' ) {
            this.showPopup( item );
        } else if ( item.popup ) {
            let next_key = this.findPopupKey( item.popup );

            if ( item.type === 'ajax' ) {
                this.showPopup( key );
                this.sendFormData(item.popup);
            } else {
                if (next_key !== -1) {
                    this.showPopup(next_key);
                }
                if (item.type === 'close') {
                    this.hidePopup(this.findPopupKey(item.popup));
                    window.location.reload();
                } else {
                    this.sendTrackerDataEvent('click', item.popup);
                }
            }
        } else if ( item.action && this.state.open_auto ) {
            this.sendTrackerDataEvent( item.action );
        }
    }

    onClose() {
        this.sendTrackerDataEvent( 'close' );
    }

    /**
     *
     * @param {string} id
     * @returns {*}
     */
    findPopupKey( id ) {
        return this.state.popups.findIndex( function ( element ) {
            return element.id === id;
        } );
    }

    /**
     *
     * @param {integer} index
     */
    showPopup( index ) {
        let state = this.state;
        this.changePopupShow( index, true );
        state.current_step = index;
        this.setState( state );
    }

    /**
     *
     * @param {integer} index
     */
    hidePopup( index ) {
        this.changePopupShow( index, false );
    }

    /**
     *
     * @param {integer} index
     * @param {boolean} status
     */
    changePopupShow( index, status ) {
        let state = this.state;
        if ( state.popups[index] ) {
            state.popups[index].show = status;
        }

        this.setState( state );
    }

    /**
     * @param {string} ajax_action
     * @param {FormData} custom_data
     * @param {callback} callback
     */
    sendAJAX( ajax_action, custom_data, callback ) {
        let data = new FormData();
        data.append( 'action', ajax_action );
        data.append( '_ajax_nonce', this.state.ajax.nonce );

        if ( custom_data ) {
            custom_data.forEach( function ( value, key ) {
                data.append( key, value );
            } );
        }

        let xhr = new XMLHttpRequest();
        xhr.open( 'POST', this.state.ajax.url );
        if ( callback ) {
            xhr.onreadystatechange = callback;
        }
        xhr.send( data );
    }

    /**
     * @param {string} popup_id
     */
    sendFormData( popup_id ) {
        let self = this;
        let data = new FormData();
        data.append( 'popup_id', popup_id );
        this.state.popups.forEach(function(popup, popup_index){
            if ( popup.id === popup_id ) {
                popup.content.forEach(function(field, field_index){
                    if ( field.type !== 'html' && ( field.type !== 'checkbox' || field.checked ) ) {
                        data.append(field.name, field.value);
                    }
                });
            }
        });
        let callback = function() {
            if ( this.readyState === 4 && this.status === 200 ) {
                let response = JSON.parse( this.responseText );
                let current_step = self.state.current_step;
                response.popups.forEach(function (popup, index) {
                    if (popup.show) {
                        current_step = index;
                    }
                });
                self.setState( {popups: response.popups, current_step: current_step} );
                self.sendTrackerDataEvent( self.state.ajax.action.event );
            }
        };
        this.sendAJAX( this.state.ajax.action.save_fields, data, callback )
    }

    /**
     * @param {string} event
     */
    sendTrackerDataEvent( event ) {
        let data = new FormData();
        data.append( 'event', event );
        data.append( 'step', this.state.popups[ this.state.current_step ].id );
        data.append( 'page', this.state.page );

        this.sendAJAX( this.state.ajax.action.event, data )
    }

    /**
     * @param {string} event
     */
    sendTrackerAutoShowPopup() {
        let data = new FormData();
        data.append( 'step', this.state.popups[ this.state.current_step ].id );
        data.append( 'page', this.state.page );
        this.sendAJAX( this.state.ajax.action.auto_show_popup, data )
    }

}

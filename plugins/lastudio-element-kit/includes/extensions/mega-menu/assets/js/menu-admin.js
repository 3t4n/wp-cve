(function( $, navSettingsConfig ) {

    'use strict';

    Vue.config.devtools = true;

    window.LaStudioMenuNavSettings = {

        navItemsSettingsInstance: null,

        init: function() {
            this.initNavItemsSettingsInstance();
            this.initTriggers();
            this.initEvents();

        },

        initEvents: function() {
            $( document ).on( 'click', '.lakit-menu-item-settings__trigger', this.openItemSettingPopup );
            $( document ).on( 'sortstop', '#menu-to-edit', this.updateMenuItemDeep );
        },

        initTriggers: function() {

            let itemsSettings = navSettingsConfig.itemsSettings;

            $( '#menu-to-edit .menu-item' ).each( function() {
                var $this = $( this ),
                    depth = LaStudioMenuNavSettings.getItemDepth( $this ),
                    id    = LaStudioMenuNavSettings.getItemId( $this ),
                    infoTemplate    = '';

                $this.addClass( 'lakit-menu-item' );

                if ( itemsSettings.hasOwnProperty( id ) && itemsSettings[ id ].hasOwnProperty( 'menu_type' ) ) {
                    if ( 'mega' === itemsSettings[ id ][ 'menu_type' ] ) {
                        infoTemplate = `<span class="lakit-menu-item-settings__info-label mega-enabled">${ navSettingsConfig.labels.itemMegaEnableLabel }</span>`;
                    }
                }

                let triggerTemplate = `<span class="lakit-menu-item-settings__trigger" data-item-id="${ id }" data-item-depth="${ depth }">${ navSettingsConfig.labels.itemTriggerLabel }</span>`;

                $this.append( `<span class="lakit-menu-item-settings"><span class="jet-menu-item-settings__info">${ infoTemplate }</span>${ triggerTemplate }</span>` );
            });

        },

        initNavItemsSettingsInstance: function() {

            this.navItemsSettingsInstance = new Vue( {
                el: '#lakit-menu-settings-nav',
                data: {
                    navSettings: navSettingsConfig,
                    controlData: navSettingsConfig.controlData,
                    debonceSavingInterval: null,
                    ajaxAction: null,
                    getItemDataState: false,
                    itemSavingState: false,
                    itemSettingItem: false,
                    itemId: false,
                    itemDepth: 0,
                    editorVisible: false,
                    iconSet: [],
                },

                mounted: function() {
                    let self = this;
                    // Get icons set
                    fetch( self.navSettings.iconsFetchJson, {
                        mode: 'cors'
                    } ).then( function( res ) {
                        return res.json();
                    } ).then( function( json ) {
                        self.iconSet = json.icons;
                    } );

                },

                watch: {
                    itemId: function( newValue, prevValue ) {
                        this.getItemData();
                    },
                },

                computed: {
                    preparedItemSettings: function() {
                        let prepared = {};

                        for ( let option in this.controlData ) {
                            prepared[ option ] = this.controlData[ option ]['value'];
                        }

                        return prepared;
                    },

                    currentEditorUrl: function() {
                        let url = '';

                        url = this.navSettings.editURL.replace( '%id%', this.itemId );
                        url = url.replace( '%menuid%', this.navSettings.currentMenuId );

                        return url;
                    },

                    isTopItem: function() {
                        return 0 === this.itemDepth;
                    },

                    defaultActiveTab: function() {
                        return 0 === this.itemDepth ? 'mega-menu-tab' : 'icon-tab';
                        return 'mega-menu-tab';
                    }

                },

                methods: {

                    openEditor: function() {
                        this.editorVisible = true;
                    },

                    navSettingPopupClose: function() {
                        this.itemSettingItem = true;

                        if ( this.editorVisible ) {
                            this.editorVisible = false;
                        } else {
                            this.itemSettingItem = false;
                        }
                    },

                    getItemData: function() {

                        let self = this;

                        this.ajaxAction = $.ajax( {
                            type: 'POST',
                            url: ajaxurl,
                            dataType: 'json',
                            data: {
                                action: 'lakit_get_nav_item_settings',
                                data: {
                                    itemId: self.itemId,
                                    itemDepth: self.itemDepth,
                                }
                            },
                            beforeSend: function( jqXHR, ajaxSettings ) {

                                if ( null !== self.ajaxAction ) {
                                    self.ajaxAction.abort();
                                }

                                self.getItemDataState = true;
                            },
                            success: function( responce, textStatus, jqXHR ) {
                                self.getItemDataState = false;

                                if ( responce.success ) {
                                    let responseSettings = responce.data.settings,
                                        newControlData   = self.controlData;

                                    for ( let setting in responseSettings ) {
                                        self.$set( self.controlData[ setting ], 'value', responseSettings[ setting ] );
                                    }
                                }
                            }
                        } );
                    },

                    saveItemSettings: function() {
                        let self = this;

                        this.ajaxAction = $.ajax( {
                            type: 'POST',
                            url: ajaxurl,
                            dataType: 'json',
                            data: {
                                action: 'lakit_save_nav_item_settings',
                                data: {
                                    itemId: self.itemId,
                                    itemDepth: self.itemDepth,
                                    itemSettings: self.preparedItemSettings
                                }
                            },
                            beforeSend: function( jqXHR, ajaxSettings ) {

                                if ( null !== self.ajaxAction ) {
                                    self.ajaxAction.abort();
                                }

                                self.itemSavingState = true;
                            },
                            success: function( responce, textStatus, jqXHR ) {
                                self.itemSavingState = false;

                                self.$CXNotice.add( {
                                    message: responce.data.message,
                                    type: responce.success ? 'success' : 'error',
                                    duration: 4000,
                                } );

                            }
                        } );
                    }
                }
            } );
        },

        openItemSettingPopup: function() {
            let $this   = $( this ),
                itemId      = $this.data( 'item-id' ),
                itemDepth   = $this.data( 'item-depth' );

            LaStudioMenuNavSettings.navItemsSettingsInstance.$data.itemSettingItem = true;
            LaStudioMenuNavSettings.navItemsSettingsInstance.$data.itemId = itemId;
            LaStudioMenuNavSettings.navItemsSettingsInstance.$data.itemDepth = itemDepth;
        },

        getItemId: function( $item ) {
            let id = $item.attr( 'id' );
            return id.replace( 'menu-item-', '' );
        },

        getItemDepth: function( $item ) {
            let depthClass = $item.attr( 'class' ).match( /menu-item-depth-\d/ );

            if ( ! depthClass[0] ) {
                return 0;
            }

            return parseInt(depthClass[0].replace( 'menu-item-depth-', '' ));
        },

        oneOf: function( value, validList ) {

            for ( let i = 0; i < validList.length; i++ ) {
                if ( value == validList[ i ] ) {
                    return true;
                }
            }

            return false;
        },

        updateMenuItemDeep: function (event, ui){
            setTimeout(function (){
                var deep = LaStudioMenuNavSettings.getItemDepth( ui.item );
                $('.lakit-menu-item-settings__trigger', ui.item).attr('data-item-depth', deep).data('item-depth', deep);
            }, 150);
        }
    }

    window.LaStudioMenuNavSettings.init();

})( jQuery, window.LaStudioKitMenuConfig );

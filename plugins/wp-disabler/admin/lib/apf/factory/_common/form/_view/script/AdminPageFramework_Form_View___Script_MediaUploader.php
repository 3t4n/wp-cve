<?php 
/**
	Admin Page Framework v3.8.20 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/admin-page-framework>
	Copyright (c) 2013-2019, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class AdminPageFramework_Form_View___Script_MediaUploader extends AdminPageFramework_Form_View___Script_Base {
    public function construct() {
        if (!function_exists('wp_enqueue_media')) {
            return;
        }
        wp_enqueue_script('jquery');
        add_action(is_admin() ? 'admin_footer' : 'wp_footer', array($this, '_replyToEnqueueMedia'), 1);
    }
    public function _replyToEnqueueMedia() {
        wp_enqueue_media();
    }
    static public function getScript() {
        $_aParams = func_get_args() + array(null);
        $_oMsg = $_aParams[0];
        if (!function_exists('wp_enqueue_media')) {
            return "";
        }
        $_sReturnToLibrary = esc_js($_oMsg->get('return_to_library'));
        $_sSelect = esc_js($_oMsg->get('select'));
        $_sInsert = esc_js($_oMsg->get('insert'));
        return <<<JAVASCRIPTS
(function ( $ ) {
            
    getAdminPageFrameworkCustomMediaUploaderSelectObject = function() {
        return wp.media.view.MediaFrame.Select.extend({

            initialize: function() {
                wp.media.view.MediaFrame.prototype.initialize.apply( this, arguments );

                _.defaults( this.options, {
                    multiple:   true,
                    editing:    false,
                    state:      'insert',
                    metadata:   {}
                });

                this.createSelection();
                this.createStates();
                this.bindHandlers();
                this.createIframeStates();
            },

            createStates: function() {
                var options = this.options;

                // Add the default states.
                this.states.add([
                    // Main states.
                    new wp.media.controller.Library({
                        id:         'insert',
                        title:      'Insert Media',
                        priority:   20,
                        toolbar:    'main-insert',
                        filterable: 'image',
                        library:    wp.media.query( options.library ),
                        multiple:   options.multiple ? 'reset' : false,
                        editable:   true,
                        // If the user isn't allowed to edit fields,
                        // can they still edit it locally?
                        allowLocalEdits: true,

                        // Show the attachment display settings.
                        displaySettings: true,
                        // Update user settings when users adjust the
                        // attachment display settings.
                        displayUserSettings: true
                    }),

                    // Embed states.
                    new wp.media.controller.Embed( options ),
                ]);

                if ( wp.media.view.settings.post.featuredImageId ) {     
                    this.states.add( new wp.media.controller.FeaturedImage() );
                }
            },

            bindHandlers: function() {
                // from Select
                this.on( 'router:create:browse', this.createRouter, this );
                this.on( 'router:render:browse', this.browseRouter, this );
                this.on( 'content:create:browse', this.browseContent, this );
                this.on( 'content:render:upload', this.uploadContent, this );
                this.on( 'toolbar:create:select', this.createSelectToolbar, this );
                

                this.on( 'menu:create:gallery', this.createMenu, this );
                this.on( 'toolbar:create:main-insert', this.createToolbar, this );
                this.on( 'toolbar:create:main-gallery', this.createToolbar, this );
                this.on( 'toolbar:create:featured-image', this.featuredImageToolbar, this );
                this.on( 'toolbar:create:main-embed', this.mainEmbedToolbar, this );

                var handlers = {
                        menu: {
                            'default': 'mainMenu'
                        },

                        content: {
                            'embed':          'embedContent',
                            'edit-selection': 'editSelectionContent'
                        },

                        toolbar: {
                            'main-insert':      'mainInsertToolbar'
                        }
                    };

                _.each( handlers, function( regionHandlers, region ) {
                    _.each( regionHandlers, function( callback, handler ) {
                        this.on( region + ':render:' + handler, this[ callback ], this );
                    }, this );
                }, this );
            },

            // Menus
            mainMenu: function( view ) {
                view.set({
                    'library-separator': new wp.media.View({
                        className: 'separator',
                        priority: 100
                    })
                });
            },

            // Content
            embedContent: function() {
                var view = new wp.media.view.Embed({
                    controller: this,
                    model:      this.state()
                }).render();

                this.content.set( view );
                view.url.focus();
            },

            editSelectionContent: function() {
                var state = this.state(),
                    selection = state.get( 'selection' ),
                    view;

                view = new wp.media.view.AttachmentsBrowser({
                    controller: this,
                    collection: selection,
                    selection:  selection,
                    model:      state,
                    sortable:   true,
                    search:     false,
                    dragInfo:   true,

                    AttachmentView: wp.media.view.Attachment.EditSelection
                }).render();

                view.toolbar.set( 'backToLibrary', {
                    text:     '{$_sReturnToLibrary}',
                    priority: -100,

                    click: function() {
                        this.controller.content.mode( 'browse' );
                    }
                });

                // Browse our library of attachments.
                this.content.set( view );
            },

            // Toolbars             
            selectionStatusToolbar: function( view ) {
                var editable = this.state().get('editable');

                view.set( 'selection', new wp.media.view.Selection({
                    controller: this,
                    collection: this.state().get( 'selection' ),
                    priority:   -40,

                    // If the selection is editable, pass the callback to
                    // switch the content mode.
                    editable: editable && function() {
                        this.controller.content.mode( 'edit-selection' );
                    }
                }).render() );
            },

            mainInsertToolbar: function( view ) {
                var controller = this;

                this.selectionStatusToolbar( view );

                view.set( 'insert', {
                    style:    'primary',
                    priority: 80,
                    text:     '{$_sSelect}',
                    requires: { selection: true },

                    click: function() {
                        var state = controller.state(),
                            selection = state.get( 'selection' );

                        controller.close();
                        state.trigger( 'insert', selection ).reset();
                    }
                });
            },

            featuredImageToolbar: function( toolbar ) {
                this.createSelectToolbar( toolbar, {
                    text:  l10n.setFeaturedImage,
                    state: this.options.state || 'upload'
                });
            },
           
            mainEmbedToolbar: function( toolbar ) {
                
                /**
                 * 3.4.2+ When the vertical menu is switched to the Insert from URL pane, if the library has a value, 
                 * it causes an error saying 'undefined is not a funciton' with the line calling library.on(...).
                 * So here we need to unset the 'library' element.
                 */
                var state = this.state();    
                state.set( 'library', false );

                toolbar.view = new wp.media.view.Toolbar.Embed({
                    controller: this,
                    text: '{$_sInsert}'
                });
   
            }        
            
        });
    }            
    
}( jQuery ));
JAVASCRIPTS;
        
    }
    }
    
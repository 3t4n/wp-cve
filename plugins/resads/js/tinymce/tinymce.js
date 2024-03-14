(function(){
    if(typeof ajax_object.ajaxurl !== 'undefined' && typeof tinymce !== 'undefined')
    {
        /**
         * ResAds AdSpot Button
         */
        tinymce.create("tinymce.plugins.resads_button_plugin", {
            /**
             * Init
             * @param {object} ed
             * @param {string} url
             * @returns {undefined}
             */
            init : function(ed, url) {
                /**
                 * Add new Button
                 */    
                ed.addButton("resads", {
                    title : "Adspots",
                    cmd : "resads_command",
                    image : url + "/adspot-button.png"
                });
                /**
                 * Button functionality
                 */
                ed.addCommand("resads_command", function() {
                    /**
                     * Get Adspots via ajax
                     * @returns {Array|return_adspots.return}
                     */
                    function get_adspots()
                    {
                        var adspots = [];
                        /** Get adspots **/
                        jQuery.ajax({
                            url: ajax_object.ajaxurl,
                            type: 'post',
                            async: false,
                            data: {
                                action: 'resads_get_adspots'
                            },
                            success: function(return_adspots) {
                                if(typeof return_adspots['return'] !== 'undefined')
                                {
                                    adspots = return_adspots['return'];
                                }
                            }
                        });
                        return adspots;
                    }
                    /**
                     * Create listbox option array
                     * @param {Array} adspots
                     * @returns {Array}
                     */
                    function create_listbox_options(adspots) 
                    {
                        if(typeof adspots === 'object')
                        {
                            var options = [];
                            for(var i = 0; i < adspots.length; i++)
                            {
                                if(typeof adspots[i]['adspot_name'] !== 'undefined' && typeof adspots[i]['adspot_id'] !== 'undefined')
                                {
                                    options[i] = { 
                                        text: adspots[i]['adspot_name'], 
                                        value: adspots[i]['adspot_id'] 
                                    };
                                }
                            }
                        }
                        return options;
                    }
                    /**
                     * Open Window Manager
                     */
                    ed.windowManager.open(
                        {
                            title   : 'ResAds Adspots',
                            body    : [
                                {
                                    type   : 'listbox',
                                    name   : 'adspot',
                                    label  : 'Adspot',
                                    values : create_listbox_options(get_adspots())
                                }
                            ],
                            /**
                             * On Submit insert [resads id="%s"] Shortcode
                             * @param {object} e
                             * @returns {undefined}
                             */
                            onsubmit: function(e) {
                                if(typeof e.data['adspot'] !== 'undefined')
                                {
                                    var return_text = '[resads_adspot id="' + e.data['adspot'] + '"]';
                                    ed.execCommand("mceInsertContent", 0, return_text);
                                }
                            }
                        }
                    );
                });
            },
            getInfo : function() {
                return {
                    longname : "ResAds Adspots",
                    author : "web-mv.de",
                    version : "1"
                };
            }
        });
        tinymce.PluginManager.add("resads_button_plugin", tinymce.plugins.resads_button_plugin);
    }
})();

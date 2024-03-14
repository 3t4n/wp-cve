var proto_snap = proto_snap = proto_snap || {};
(function(proto_snap, $) {
    proto_snap.generate_shortcode = function(number, instance, defaults) {
        var shortcode = '[featured_image_pro ';
        $.each ( instance, function( key, option ) {
			if ( option == undefined )
				return;
            if ( 0 === option.length )
                return;

            if ( 'shortcode' == key || 'preset' == key )
                return;

            if ( typeof defaults['key'] !== 'undefined' )
                defaultvalue = defaults[key];
            else
                defaultvalue = null;
            if ( 'catarray' == key ) {
                var option = $.map(option, function(value, key) {
                       return [value];
                });
                option = option.join(',');
                if ( option != '0' && option != '' ) {
                    shortcode += "cat='" + option + "' ";
                }
            }
            else if ( option == 'on' ) {
                shortcode += key + "=true ";
            } else if ( option == 'off' ) {
                shortcode += key + "=false ";
            }

            else if ( typeof option == 'string' )
                shortcode += key + "='" + option + "' ";
            else if ( typeof option !== 'undefined' )
                shortcode += key + "=" + option + " ";
        } );
        if ( typeof instance.fitwidth === 'undefined' )
            shortcode += ' fitwidth=false ';
        if ( typeof instance.captionhr === 'undefined' )
            shortcode += ' captionhr=false ';
        if ( typeof instance.captionhr === 'undefined' )
            shortcode += ' excerpthr=false ';
        if ( typeof instance.tooltip === 'undefined' )
            shortcode += ' tooltip=false ';
        shortcode += ']';
        $('#widget-featured_image_pro_masonry_widget-' + number + '-shortcode').text(shortcode);
    };

    $(document).on( 'widget-added', load_widget );
    $(document).on( 'widget-updated', load_widget );
    $(document).ready( load_widget );

    /**
     * Widget Loader - Works for both classic widget
     * screen and Customizer. Only loaded when needed
     *
     * @param event e - The event, exists at all times.
     * @param element|null widget_target - The target (does not exist on a standard load)
     * @visiblity private
     */
    function load_widget(e, widget_target, a, b, c) {

        if ($('body').hasClass('wp-customizer')) {
            if ($('#customize-controls').closest(e.target).length == 0)
                return;
        }
        var search_root = this;
        // if this is a widget-added or widget-updated triggered function, then the
        // widget_target is not undefined.
        if ( typeof widget_target !== 'undefined' ) {
            search_root = widget_target;
        }

        $(search_root).find('.proto-snap-widget-wrapper').each(function(k, v) {
            var $target = $(v).children().first(),
                number = $target.data('widget-number'),
                shortcode_meta = $target.data('proto-shortcode-meta'),
                loaded = $target.data("loaded");	//loaded determines whether or not event has been attached

            // decode shortcode components and generate shortcode
            shortcode_meta = decodeURIComponent(shortcode_meta);
            shortcode_meta = JSON.parse(shortcode_meta);
            defaults = shortcode_meta.defaults;

            // forces instance to be an object so it can be iterated with $.each() consistently
            instance = $.extend({}, shortcode_meta.instance);

            $target.data('instance', instance);

            /**
             * This only happens when necessary, generally on first load or update.
             */
                fsubmit = $(k).closest('form').find(':submit'); //find the submit button
                $target.find('.panel').accordion({
                    heightStyle: "content"
                });
                $(v).find('.proto-color').each(function(ck, cv) {
                    $(cv).wpColorPicker({
                        'change': function() {
                                var self = this;
                                setTimeout(function() {
                                        $(self).trigger( 'change' );
                                }, 100);
                        },
                        'clear': function(e) {
                                var self = this;
                                setTimeout(function() {
                                        $(self).trigger( 'change' );
                                }, 100);
                        }
                    });
                });
                proto_snap.generate_shortcode( number, instance, defaults );
                /**
                 * Change shortcode settings on input change.
                 */
                $target.find(' .proto-field').on( 'change', function(e) {
                    var input_name_raw = $(this).attr('name'),
                        instance = $target.data('instance'),
                        input_type = $(this).attr('type'),
                        input_value = (input_type == 'checkbox' || input_type=='radio') ? ( $(this).is(':checked') ? 'on':'off' ) : $(this).attr('value'),
                        regexp = new RegExp('\\[(.*)\\]\\[(.*)\\]');

                    input_name = input_name_raw.match(regexp);

                    instance[input_name[2]] = input_value;
                    proto_snap.generate_shortcode( input_name[1], instance, defaults );
                });
        });
    }
}) (proto_snap, jQuery);


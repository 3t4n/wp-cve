var blockLiteProps;
var customizing_display_lite = lassoLiteOptionsData.block_customize;
var attributes = build_lasso_gutenberg_attributes();
var toogle_attributes = customizing_display_lite['toogle_attributes'];
var default_attributes = ['show_short_code', 'short_code', 'button_text', 'button_update_text', 'button_edit_display'];
var default_lasso_shortcode_attributes = ['ref', 'id', 'link_id', 'type', 'category'];
var customize_attribute_codes = [];
var focus_customize_data = [];
var window_url_detail;
var shortcodes_reload = [];

jQuery(document).ready(function() {
	// EVERYTHING HERE IS A UNIQUE SCOPE
	function this_init(){
		// Start calling your functions from here:
		scan_lasso_shortcodes();
	}

	let blockLoaded = false;
	let blockLoadedInterval = setInterval(function() {
		if(document.getElementById('post-title-0')  // Working with version < 5.9
			|| document.getElementsByClassName('editor-post-title').length) { // Working with version >= 5.9
			blockLoaded = true;
			this_init();
		}
		if(blockLoaded) {
			clearInterval(blockLoadedInterval);
		}
	}, 500);

	function scan_lasso_shortcodes(){
		let lasso_shortcode_blocks = jQuery('div[data-type="affiliate-plugin/lasso"]');
		if(lasso_shortcode_blocks.length > 0) {
			for (let index = 0; index < lasso_shortcode_blocks.length; index++) {
				const element = jQuery(lasso_shortcode_blocks[index]);
				let blockId = element.attr('id').replace(/^block-/gm,'');
				let shortcode = element.find('input').val();
				getLassoShortcodeHtml(blockId, shortcode);
			}
		}
	}
});

function add_short_code_single_block(obj) {
	let link_slug = jQuery(obj).data('link-slug');
	let post_id   = jQuery(obj).data('post-id');
	let shortcode = '[lasso ref="' + link_slug + '" id="' + post_id + '"]';
	let block_id  = blockLiteProps.clientId;

	getLassoShortcodeHtml(block_id, shortcode, true);
	setBlockAttributes(block_id, shortcode);
}

function getLassoShortcodeHtml(blockId, shortcode) {
	let loading_img = '<div class="py-5"><div class="loader"></div></div>';
	jQuery.ajax({
		url: lassoLiteOptionsData.ajax_url,
		type: 'get',
		data: {
			action: 'lasso_lite_get_shortcode_content',
			nonce: lassoLiteOptionsData.optionsNonce,
			shortcode: shortcode,
		},
		beforeSend: function( xhr ) {
			jQuery('#block-' + blockId).find('div.shortcode-html').html(loading_img);
		}
	})
		.done(function(res) {
			res = res.data;
			html = res.html;
			jQuery('#block-' + blockId).find('div.shortcode-html').html(html);
		})
		.always(function() {
			jQuery('#block-' + blockId).find('div.py-5').remove();
		});
}

function setBlockAttributes(block_id, shortcode) {
	blockLiteProps.setAttributes({
		show_short_code: true,
		short_code: shortcode,
		button_text: 'Select a New Display',
		button_update_text: 'Update Display',
		button_edit_display: 'Edit Display'
	});

	// if blockLiteProps.setAttributes doesn't work, it will update the shortcode
	jQuery('#block-' + block_id).find('input').val(shortcode);

	// hide the popup
	let lasso_block = jQuery('#lasso-display-add');
	lasso_block.modal('hide');
	lasso_block.removeClass('show');
	jQuery('.jquery-modal.blocker.current').trigger('click');
}

function open_url_detail_window(props) {
	blockLiteProps = props;
	if( typeof window_url_detail === 'object' ) {
		window_url_detail.close();  // close windows are opening
	}

	let shortcode          = blockLiteProps.attributes.short_code;
	let current_attributes = get_lasso_shortcode_attributes(shortcode);
	let detail_page        = '';
	let post_id            = 0;

	if ( current_attributes.hasOwnProperty('id') ) {
		post_id     = current_attributes.id;
		detail_page = lassoLiteOptionsData.site_url + "/wp-admin/edit.php?post_type=surl&page=surl-url-details&post_id=" + post_id;
	}

	if ( post_id !== 0 && ! isNaN(post_id) ) {
		shortcodes_reload.push({blockId: blockLiteProps.clientId, shortcode: blockLiteProps.attributes.short_code});
		window_url_detail = window.open(detail_page,'_blank');
		window_url_detail.onload = function(){
			this.onbeforeunload = function(){
				for ( let i = 0; i < shortcodes_reload.length; i++ ) {
					getLassoShortcodeHtml(shortcodes_reload[i].blockId, shortcodes_reload[i].shortcode);
				}
				shortcodes_reload = [];
			}
		}
	}
}

function get_lasso_shortcode_attributes( shortcode ) {
	let result = {};

	try {
		let raw_attributes = shortcode.replace(/\[lasso/g, '').replace(/\]/g, '').trim();
		let temporary_element = '<div ' + raw_attributes + '></div>';
		temporary_element = jQuery(temporary_element);

		jQuery(temporary_element).each(function() {
			jQuery.each(this.attributes, function() {
				if(this.specified) {
					result[this.name] = this.value;
				}
			});
		});
	} catch (e) {}

	return result;
}

function LassoIcon(props) {
	let width = props.width ? props.width : '100';
	let height = props.height ? props.height : '100';
	return React.createElement("svg", {xmlns: "http://www.w3.org/2000/svg",width: width, height: height,viewBox: "0 0 500 500"},
		React.createElement("defs", null, React.createElement("clipPath", {id: "b"},
		React.createElement("rect", {width: "500",height: "500"}))),
		React.createElement("circle", {cx: "249.5", cy: "249.5", r: "249.5", transform: "translate(1 1)", fill: "#5e36ca"}),
		React.createElement("g", {id: "a","clipPath": "url(#b)"},
		React.createElement("g", {transform: "translate(59.684 92.664)"},
		React.createElement("g", {transform: "translate(90.918 0.437)"},
		React.createElement("path", { d: "M177.568,52.494h0a25.365,25.365,0,0,0-25.84,25.613l.443,9.957c-.371,62.1-18.019,59.155-20.892,58.341V30.649C131.284,16.543,119.335,5,104.734,5h0C90.128,5,78.179,16.543,78.179,30.649V147.743C53.909,154.035,58.167,82.39,58.167,82.39V57.457c0-14.374-13.9-25.989-28.805-25.989h0c-14.874,0-24.29,11.759-24.29,26.133L5,82.673C12.208,193.8,78.179,183.648,78.179,183.648l.036,37.434H131.32l-.036-37.542C200.1,183.267,204.391,88.3,204.391,88.3v-9.89C204.385,64.155,192.318,52.494,177.568,52.494Z", transform: "translate(-5 -5)", fill: "#00ffd3" }),
		React.createElement("path", { d: "M4.762,37.732c0,10.173,6.178,18.5,13.736,18.5h44.43c7.558,0,13.741-8.325,13.741-18.5L81.416,0H0Z",transform: "translate(59.721 257.209)", fill: "#cc4afc" })),
		React.createElement("path", { d: "M195.564,425.8H103.779c-4.193.017-7.588,4.181-7.6,9.321v14.692c.011,5.14,3.406,9.3,7.6,9.321h91.785c4.2-.014,7.6-4.178,7.609-9.321V435.121C203.159,429.978,199.76,425.814,195.564,425.8Z",transform: "translate(41.681 -205.257)",fill: "#cc4afc"}))));
}

wp.blocks.registerBlockType('affiliate-plugin/lasso', {
	title: 'Lasso Lite',
	icon: React.createElement(LassoIcon, null),
	category: 'common',
	keywords: [
		"link",
		"affiliate",
		"lasso"
	],
	attributes,
	edit: function(props) {
		function onChangeContent( e ) {
			props.setAttributes( { short_code: e.target.value } );
			update_customize_data( e.target.value );
		}

		function update_customize_data( shortcode ) {
            try {
                if ( shortcode && shortcode.match(/\[lasso.*\]/) ) {
                    var current_attributes               = get_lasso_shortcode_attributes(shortcode);
                    var current_attribute_codes          = Object.keys(current_attributes);
                    var customize_attribute_codes        = get_customize_attribute_codes();
                    var customize_attribute_code_missing = customize_attribute_codes.filter(x => !current_attribute_codes.includes(x));

                    // Update customize data if existing in shortcode
                    for (const property in current_attributes) {
                        if ((default_attributes.indexOf(property) === -1) && (typeof props.attributes[property] != 'undefined')) {
                            props.setAttributes( { [property]: current_attributes[property] } );
                            jQuery('input.cuz-attr-' + property).val(current_attributes[property]);
                        }
                    }

                    // Delete customize data if don't exist in shortcode
                    for (const index in customize_attribute_code_missing) {
                        props.setAttributes( { [customize_attribute_code_missing[index]]: '' } );
                        jQuery('input.cuz-attr-' + customize_attribute_code_missing[index]).val('');
                    }
                }
            } catch (e) {
                console.log('ERROR: ', e);
            }
        }

        function get_customize_attribute_codes() {
            if (customize_attribute_codes.length) {
                return customize_attribute_codes;
            }

            for (const attr_code in props.attributes) {
                if (default_attributes.indexOf(attr_code) == -1) {
                    customize_attribute_codes.push(attr_code);
                }
            }

            return customize_attribute_codes;
        }

        function on_change_customize_data( e ) {
            try {
                var value     = e.target.value;
                var attr_code = e.target.className.replace('cuz-attr-', '');

                value = value.replace(/\"/g, '');
                // ID format: Replace whitespace by "-"
                if ( 'anchor_id' == attr_code ) {
                    value = value.replace(/\s/g, '-').replace(/(\-)+/g, '-');
                }
                e.target.value = value; // Don't allow double quote value
                props.setAttributes( { [attr_code]: value } ); // Update new value for editor attribute

                // Build new shortcode content
                var new_short_code = customize_shortcode(attr_code, value);
                props.setAttributes( { short_code: new_short_code } );
                jQuery('#block-' + props.clientId).find('input.shortcode-input').val(new_short_code);
            } catch (e) {
                console.log('Error: On change customize data', e);
            }
        }

        /**
         * Return suitable toogle function name for each Lasso attribute.
         *
         * @param attr_code Lasso shortcode attribute code.
         * @returns toogle function name
         */
        function get_toogle_function( attr_code ) {
            let toogle_onchange_function = on_change_toggle_price;

            switch(attr_code) {
                case 'field':
                    toogle_onchange_function = on_change_toggle_field;
                    break;
                case 'rating':
                    toogle_onchange_function = on_change_toggle_rating;
                    break;
            }

            return toogle_onchange_function;
        }

        function on_change_toggle_price( value ) {
            on_change_toggle_data( 'price', value );
        }

        function on_change_toggle_field( value ) {
            on_change_toggle_data( 'field', value );
        }

        function on_change_toggle_rating( value ) {
            on_change_toggle_data( 'rating', value );
        }

        function on_change_toggle_data( attr_code, value ) {
            try {
                // Build new shortcode content
                let new_short_code = customize_shortcode(attr_code, value);
                props.attributes[attr_code] = value;
                props.setAttributes( { short_code: new_short_code } );
                jQuery('#block-' + props.clientId).find('input.shortcode-input').val(new_short_code);
                this.getLassoShortcodeHtml(props.clientId, new_short_code);
            } catch (e) {
                console.log('Error: On change customize data', e);
            }
        }

        function focus_customize( e ) {
            var value     = e.target.value;
            var attr_code = e.target.className.replace('cuz-attr-', '');

            if (typeof focus_customize_data[props.clientId] == 'undefined') {
                focus_customize_data[props.clientId] = [];
            }

            focus_customize_data[props.clientId][attr_code] = value;
        }

        function update_custimized_display( e ) {
            var value     = e.target.value;
            var attr_code = e.target.className.replace('cuz-attr-', '');
            var blockId   = props.clientId;

            if (value != focus_customize_data[blockId][attr_code]) {
                var blockId = props.clientId;
                var shortcode = props.attributes.short_code;
                this.getLassoShortcodeHtml(blockId, shortcode);
            }
        }

        function handle_customize_key_press( event ) {
            if (event.key == 'Enter') {
                focus_customize( event );
                this.getLassoShortcodeHtml(props.clientId, props.attributes.short_code);
            }
        }

        function render_customize_content() {
            var customize_content = [
                React.createElement(
                    "div",
                    {
                        dangerouslySetInnerHTML: {
                            __html: customizing_display_lite['notice']
                        },
                        className: 'cuz-notice',
                    },
                )
            ];
            var shortcode = props.attributes.short_code;
            if (props.attributes.show_short_code && shortcode) {
                var current_attributes = get_lasso_shortcode_attributes( shortcode );
                var display_type       = current_attributes['type'] ? current_attributes['type'] : 'single';

                if (display_type in customizing_display_lite) {
                    var customizing_display_lite_item = customizing_display_lite[display_type];
                    var available_attributes = customizing_display_lite_item['attributes'];

                    for (const property in available_attributes) {
                        let attr_name = available_attributes[property]['name'];
                        let attr_code = available_attributes[property]['attr'];
                        let attr_desc = available_attributes[property]['desc'];
                        let input_type_el;

                        // Toogle input
                        if (toogle_attributes.includes(attr_code)) {
                            let checked = true;

                            if (shortcode && shortcode.match(/\[lasso.*\]/)) {
                                checked = current_attributes[attr_code] !== 'hide';
                            }

                            input_type_el = React.createElement(
                                wp.components.ToggleControl,
                                {
                                    onChange: get_toogle_function( attr_code ),
                                    checked: checked,
                                    className: 'cuz-attr-' + attr_code,
                                });
                        } else { // Text box input
                            input_type_el = React.createElement(
                                "input",
                                {
                                    type: "text",
                                    // value: props.attributes.short_code, // input can't be changed
                                    defaultValue: props.attributes[attr_code], // input can be changed
                                    onChange: on_change_customize_data,
                                    onFocus: focus_customize,
                                    onBlur: update_custimized_display,
                                    onKeyPress: handle_customize_key_press,
                                    style:{
                                        display: props.attributes.show_short_code ? 'block' : 'none',
                                        width: '100%%',

                                    },
                                    className: 'cuz-attr-' + attr_code,
                                }
                            );
                        }

                        var el = React.createElement(
                            "div",
                            {
                                style:{
                                    display: props.attributes.show_short_code ? 'block' : 'none',
                                },
                                className: 'cuz-item',
                            },
                            [
                                React.createElement(
                                    "div",
                                    {
                                        className: 'cuz-name',
                                    },
                                    attr_name
                                ),
                                input_type_el,
                                wp.element.createElement( 'div', {
                                    dangerouslySetInnerHTML: {
                                        __html: attr_desc
                                    },
                                    className: 'cuz-desc',
                                })
                            ]
                        );

                        customize_content.push(el);
                    }
                }
            }

            return customize_content;
        }

        function customize_shortcode( cus_attr_name, cus_attr_value ) {
            var shortcode = props.attributes.short_code;
            if (shortcode && shortcode.match(/\[lasso.*\]/)) {
                var current_attributes = get_lasso_shortcode_attributes( shortcode );

                if (Object.keys( current_attributes ).length !== 0) {
                    shortcode = get_new_customize_shortcode( current_attributes, cus_attr_name, cus_attr_value );
                }
            }

            return shortcode;
        }

		return wp.element.createElement(
			wp.element.Fragment,
			null,
			wp.element.createElement(
                wp.blockEditor.InspectorControls,
                null,
                wp.element.createElement(
                    wp.components.PanelBody,
                    {
                        title: 'Customize Display',
                        initialOpen: true,
                        className: 'customize-wrapper'
                    },
                    render_customize_content()
                )
            ),
			React.createElement(
				"div",
				{
					style: {
						textAlign: 'center',
						backgroundColor: "#5E36CA",
						borderRadius: "10px",
						padding: "0px 0px 20px 0px",
						fontFamily: '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif',
					}
				},
				React.createElement(
					"div",
					{
						style:{
							display: props.attributes.show_short_code ? 'block' : 'none',
							margin: '0 auto',
							background: 'white',
							padding: '1px 0',
							'text-align': 'initial',
						},
						class: 'shortcode-html'
					},
					''
				),
				React.createElement(
					"div",
					{
						style: {
							display: 'flex',
							alignItems: 'center',
							padding: '10px 0 0 0',
							justifyContent: 'center',
						}
					},
					React.createElement(LassoIcon, {width: 50, height: 50}),
					React.createElement(
						"span",
						{
							style: {
								fontSize: '26px',
								fontWeight: 700,
							}
						}
					)
				),
				React.createElement(
					"span",
					{
						style:{
							display: props.attributes.show_short_code ? 'none' : 'block',
							marginBottom: '20px',
							marginTop: '10px',
							fontSize: '18px',
							color: '#ffffff',
							fontFamily: '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif',
						}
					},
					"Choose a Lasso Link to display."
				),
				React.createElement(
					"input",
					{
						type: "text",
						// value: props.attributes.short_code, // input can't be changed
						defaultValue: props.attributes.short_code, // input can be changed
						onChange: onChangeContent,
						style:{
							display: props.attributes.show_short_code ? 'block' : 'none',
							margin: '10px auto 20px auto',
							padding: '0.5rem 0.75rem',
							borderRadius: '0.5rem',
							border: '1px solid #ced4da',
							width: '85%',
							height: 'auto',
							lineHeight: '2',
							fontSize: '1rem',
							fontFamily: '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif',
						},
						class: 'shortcode-input'
					}
				),
				React.createElement(
					"button",
					{
						style: {
							display: props.attributes.short_code !== '' ? 'inline-block' : 'none',
							backgroundColor: "#22BAA0",
							color: '#ffffff',
							padding: "0.75rem 2rem",
							borderRadius: '100rem',
							fontSize: '1rem',
							margin: '0.5rem',
							fontWeight: 800,
							fontFamily: '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif',
							border: 0,
							cursor: 'pointer'
						},
						onClick: function(e) {
							let blockId = props.clientId;
							let shortcode = props.attributes.short_code;
							this.getLassoShortcodeHtml(blockId, shortcode);
						}.bind(this)
					},
					props.attributes.button_update_text
				),
				React.createElement(
					"button",
					{
						style: {
							backgroundColor: "#22BAA0",
							color: '#ffffff',
							padding: "0.75rem 2rem",
							borderRadius: '100rem',
							fontSize: '1rem',
							margin: '0.5rem',
							fontWeight: 800,
							fontFamily: '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif',
							border: 0,
							cursor: 'pointer'
						},
						onClick: function() {
							this.lasso_lite_pop_up(props)
						}.bind(this)
					},
					props.attributes.button_text
				),
				React.createElement(
					"button",
					{
						style: {
							display: props.attributes.short_code !== '' ? 'inline-block' : 'none',
							backgroundColor: "#22BAA0",
							color: '#ffffff',
							padding: "0.75rem 2rem",
							borderRadius: '100rem',
							fontSize: '1rem',
							margin: '0.5rem',
							fontWeight: 800,
							fontFamily: '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif',
							border: 0,
							cursor: 'pointer'
						},
						onClick: function() {
							this.open_url_detail_window(props)
						}.bind(this)
					},
					props.attributes.button_edit_display
				),
			)
		);
	},
	save: function(props) {
		return wp.element.createElement(
			"div",
			null,
			props.attributes.short_code
		);
	}
});

function lasso_lite_pop_up(props) {
	blockLiteProps = props;
	let lasso_display      = jQuery('#lasso-display-add');
	let lasso_display_type = jQuery('#lasso-display-type');
	lasso_display.modal('toggle');

	// hide other tab, only show the types of shortcode (single, button, image, grid, list, gallery)
	if (lasso_display.hasClass('modal')) {
		lasso_display.removeClass('modal');
		lasso_display.addClass('show');
		lasso_display.find('.close-modal').remove();

		lasso_display.find('.modal-content').children().addClass('d-none');
		lasso_display_type.removeClass('d-none');
	}

	// hide the popup when clicking out of `lasso-display-add`
	jQuery(document).click(function(e) {
		let el = jQuery(e.target);
		let id = el.attr('id');
		if(id == 'lasso-display-add') {
			lasso_display.modal('hide');
			lasso_display.removeClass('show');
			lasso_display.find('.close-modal').remove();
			jQuery('.jquery-modal.blocker.current').trigger('click');
		}
	});
}

function build_lasso_gutenberg_attributes() {
	let result = {
		show_short_code: {
			type: 'boolean',
			default: false
		},
		short_code: {
			type: 'string',
			default: ''
		},
		button_text: {
			type: 'string',
			default: 'Add a Display'
		},
		button_update_text: {
			type: 'string',
			default: 'Update Display'
		},
		button_edit_display: {
			type: 'string',
			default: 'Edit Display'
		},
	};

	try {
        for (const property in customizing_display_lite['all_attributes']) {
            var attr_name = customizing_display_lite['all_attributes'][property];
            result[attr_name] = {
                type: 'string',
                default: ''
            };
        }
    } catch (e) {
        console.log('Error: Build customize display data', e);
    }

	return result;
}

function get_new_customize_shortcode( current_attributes, cus_attr_name, cus_attr_value ) {
    var attribute_content = '';
    var old_customize_attributes = [];

    current_attributes[cus_attr_name] = cus_attr_value; // Add/Update new customize value

    // Build default attributes and newest customize before
    for (const property in current_attributes) {
        if ((default_lasso_shortcode_attributes.indexOf(property) !== -1) || (property === cus_attr_name) ) {
            var value = current_attributes[property];
            if ( toogle_attributes.includes(property) ) { // Toogle attributes
                let attr_value = current_attributes[property] ? 'show' : 'hide';

                // Add "hide" value for toogle attribute, else do nothing
                if ( 'hide' === attr_value ) {
                    attribute_content += ' ' + property + '="' + attr_value + '"';
                }
            } else if (value) { // Text box attributes
                attribute_content += ' ' + property + '="' + current_attributes[property] + '"';
            }
        } else {
            old_customize_attributes.push(property);
        }
    }

    // Build old customize attributes later
    old_customize_attributes.forEach(old_cuz_attr => {
        var value = current_attributes[old_cuz_attr];
        if (value) {
            attribute_content += ' ' + old_cuz_attr + '="' + current_attributes[old_cuz_attr] + '"';
        }
    });

    return '[lasso' + attribute_content + ']';
}

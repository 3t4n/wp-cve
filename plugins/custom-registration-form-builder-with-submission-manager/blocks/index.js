//import apiFetch from '@wordpress/api-fetch';
const {registerBlockType} = wp.blocks; //Blocks API
const {createElement, useState} = wp.element; //React.createElement
const { subscribe, select, dispatch } = wp.data;
const {__} = wp.i18n; //translation functions
const {InspectorControls, BlockControls, useBlockProps } = wp.blockEditor; //Block inspector wrapper
const {TextControl, SelectControl, ServerSideRender, PanelBody, ToggleControl} = wp.components; //WordPress form inputs and server-side renderer
const el = wp.element.createElement;
const iconEl = el('svg', {width: 20, height: 20},
        el('rect', {fill: "none", height: "24", width: "24"}),
        el('rect', {height: "4", width: "4", x: "10", y: "4"}),
        el('rect', {height: "4", width: "4", x: "4", y: "16"}),
        el('rect', {height: "4", width: "4", x: "4", y: "10"}),
        el('rect', {height: "4", width: "4", x: "4", y: "4"}),
        el('rect', {height: "4", width: "4", x: "16", y: "4"}),
        el('polygon', {points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91"}),
        el('polygon', {points: "14,12.03 14,10 10,10 10,14 12.03,14"}),
        // el('polygon', { points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91" } ),
        el('path', {d: "M20.85,11.56l-1.41-1.41c-0.2-0.2-0.51-0.2-0.71,0l-1.06,1.06l2.12,2.12l1.06-1.06C21.05,12.07,21.05,11.76,20.85,11.56z"})
        );

const iconEl2 = el('svg', {width: 20, height: 20},
        el('rect', {fill: "none", height: "24", width: "24"}),
        el('rect', {height: "4", width: "4", x: "10", y: "4"}),
        el('rect', {height: "4", width: "4", x: "4", y: "16"}),
        el('rect', {height: "4", width: "4", x: "4", y: "10"}),
        el('rect', {height: "4", width: "4", x: "4", y: "4"}),
        el('rect', {height: "4", width: "4", x: "16", y: "4"}),
        el('polygon', {points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91"}),
        el('polygon', {points: "14,12.03 14,10 10,10 10,14 12.03,14"}),
        // el('polygon', { points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91" } ),
        el('path', {d: "M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"})
        );


const rmEditIcon = el('svg', {
    xmlns: 'http://www.w3.org/2000/svg',
    height: '24px',
    viewBox: '0 0 24 24',
    width: '24px',
    fill: '#0083e8'
}, [
    el('path', {
        d: 'M0 0h24v24H0V0z',
        fill: 'none'
    }),
    el('path', {
        d: 'M14.06 9.02l.92.92L5.92 19H5v-.92l9.06-9.06'
    }),
    el('path', {
        d: 'M17.66 3c-.25 0-.51.1-.7.29l-1.83 1.83 3.75 3.75 1.83-1.83c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.2-.2-.45-.29-.71-.29'
    }),
    el('path', {
        d: 'M13.06 6.81L3 17.25V21h3.75L17.81 9.94l-3.75-3.75'
    })
]);



var Forms = '';
wp.apiFetch({path: 'regmagic/v1/forms'}).then((forms) => {
    Forms = forms;
});

function timerange_options()
{
    var type = [];
    type[0] = {value: 'all', label: 'All'};
    type[1] = {value: 'year', label: 'This Year'};
    type[2] = {value: 'month', label: 'This Month'};
    type[3] = {value: 'week', label: 'This Week'};
    type[4] = {value: 'today', label: 'Today'};
    return type;
}


var searchRequest = null;
var g_rm_customtab = '';
var g_rm_acc_color = '';


function load_rm_submissions_page() {
    var clearInterval = false;
    setInterval(function () {
        g_rm_acc_color = jQuery('#rm_dummy_link_for_primary_color_extraction').css('color');
        if (typeof g_rm_acc_color === 'undefined') {
            g_rm_acc_color = '#000';
        }
        var rmagic_jq = jQuery(".rmagic");
        rmagic_jq.find("[data-rm_apply_acc_color='true']").css('color', g_rm_acc_color);
        rmagic_jq.find("[data-rm_apply_acc_bgcolor='true']").css('background-color', g_rm_acc_color);
        if (document.getElementById('rmsubmissionTrigger') && clearInterval === false) {
            g_rm_customtab = new RMCustomTabs({
                container: '#rm_front_sub_tabs',
                animation: 'fade',
                accentColor: g_rm_acc_color,
                activeTabIndex: 0
            });

            redirecttosametab(0);
            clearInterval = true;
        }
    }, 500);
}
let rm_processing_title_change = false;
let rm_editor_page_lastitle = select('core/editor').getEditedPostAttribute('title'); 
function rm_show_notice_on_title_Change(rm_new_Title) {
  const keywords = ['contact', 'form'];
  if (!rm_processing_title_change && keywords.some(keyword => rm_new_Title.toLowerCase().includes(keyword))) {
    rm_processing_title_change = true;
    dispatch('core/notices').createInfoNotice(rm_ajax_object.rm_gutenberg_strings.gutenberg_notice.template, rm_get_gutenberg_notice_settings());
  }
}
const rm_check_title_state = subscribe(() => {
const rm_editor_current_title = select('core/editor').getEditedPostAttribute('title');
  if (!rm_processing_title_change && rm_editor_current_title !== rm_editor_page_lastitle) {
    rm_show_notice_on_title_Change(rm_editor_current_title);
    rm_editor_page_lastitle = rm_editor_current_title;
  }
});

function rm_get_gutenberg_notice_settings() {
    let rm_plugin_name = 'Registration-magic';
    let rm_notice_settings = {
      id: rm_plugin_name,
      isDismissible: true,
      HTML: true,
      __unstableHTML: true,
      actions: [{
        className: 'rm-block-get-started-notice-btn',
        variant: 'primary',
        label: rm_ajax_object.rm_gutenberg_strings.gutenberg_notice.button
      }]
    };
    if (!rm_ajax_object.rm_gutenberg_strings.gutenberg_guide) {
      rm_notice_settings.actions[0].url = rm_ajax_object.rm_gutenberg_strings.gutenberg_notice.url;
      return rm_notice_settings;
    }
    // return rm_notice_Settings;
  };
//   gutenberg RM Notice End

registerBlockType('regmagic-blocks/form-page', {
    title: __('Registration Form', 'custom-registration-form-builder-with-submission-manager'), // Block title.
    category: 'regmagic', //category
    icon: el('svg', {
    xmlns: 'http://www.w3.org/2000/svg',
    height: '24px',
    viewBox: '0 0 24 24',
    width: '24px',
    fill: '#0083e8',
    class:'rm-block-icon-svg'
}, [
    el('path', {
        d: 'M0 0h24v24H0V0z',
        fill: 'none'
    }),
    el('path', {
        d: 'M11 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zM5 18c.2-.63 2.57-1.68 4.96-1.94l2.04-2c-.39-.04-.68-.06-1-.06-2.67 0-8 1.34-8 4v2h9l-2-2H5zm15.6-5.5l-5.13 5.17-2.07-2.08L12 17l3.47 3.5L22 13.91z'
    })
]),
    supports: {
        customClassName: false,
        className: false,
        html: false
    },
    attributes: {
        fid: {
            //default: rm_ajax_object.rm_form_default_ID,
            default: null,
            type: 'string'
        }
    },
    //display the post title
    edit(props) {
        const attributes = props.attributes;
        const setAttributes = props.setAttributes;

        //Function to update id attribute
        function changeFid(fid) {
            setAttributes({fid});
        }
        const blockProps = useBlockProps();
        blockProps.className += ' rm-registration-form';

        //Display block preview and UI
        return createElement('div', blockProps, [

            //Preview a block with a PHP render callback
            createElement(wp.serverSideRender, {
                block: 'regmagic-blocks/form-page',
                attributes: attributes
            }),
            //Block inspector
            createElement(InspectorControls, {},
                    [
                        createElement(PanelBody, {title: 'Form Settings', initialOpen: true},
                                //A simple text control for post id
                                createElement(SelectControl, {
                                    value: attributes.fid,
                                    label: __('Select Form', 'custom-registration-form-builder-with-submission-manager'),
                                    help: __('Choose Form that need to show.', 'custom-registration-form-builder-with-submission-manager'),
                                    onChange: changeFid,
                                    options: Forms
                                })
                                )
                    ]
                    ),
                    createElement(BlockControls, {}, [
                        createElement('button', {
                            className: 'rm_block_toolbar_btn',
                            //icon:rmEditIcon,
                            onClick: () => {
                                let rm_form_field_url = `admin.php?page=rm_field_manage&rm_form_id=${attributes.fid}`;
                                window.open(rm_form_field_url, '_blank');
                            }
                           
                        }, [
                            createElement('span', { className: 'rm-edit-form-icon'},rmEditIcon)
                            
                        ])
                    ])
        ]);
    },
    save() {
        return null;//save has to exist. This all we need
    }
});

registerBlockType('regmagic-blocks/submission-page', {
    title: __('Submissions', 'custom-registration-form-builder-with-submission-manager'), // Block title.
    category: 'regmagic', //category
    icon: el('svg', {
        xmlns: 'http://www.w3.org/2000/svg',
        enableBackground: 'new 0 0 24 24',
        height: '24px',
        viewBox: '0 0 24 24',
        width: '24px',
        fill: '#0083e8',
        class:'rm-block-icon-svg'
    }, [
        el('path', {
            d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.35 18.5C8.66 17.56 10.26 17 12 17s3.34.56 4.65 1.5c-1.31.94-2.91 1.5-4.65 1.5s-3.34-.56-4.65-1.5zm10.79-1.38C16.45 15.8 14.32 15 12 15s-4.45.8-6.14 2.12C4.7 15.73 4 13.95 4 12c0-4.42 3.58-8 8-8s8 3.58 8 8c0 1.95-.7 3.73-1.86 5.12z'
        }),
        el('path', {
            d: 'M12 6c-1.93 0-3.5 1.57-3.5 3.5S10.07 13 12 13s3.5-1.57 3.5-3.5S13.93 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z'
        })
    ]),
    supports: {
        customClassName: false,
        className: false,
        html: false
    },
    //display the post title
    edit(props) {
        const attributes = props.attributes;
        const setAttributes = props.setAttributes;

        //Function to update id attribute

        //Display block preview and UI
        return createElement('div', {}, [

            //Preview a block with a PHP render callback
            createElement(wp.serverSideRender, {
                block: 'regmagic-blocks/submission-page'
            }),
            load_rm_submissions_page()
        ]);
    },
    save() {
        return null;//save has to exist. This all we need
    }
});


registerBlockType('regmagic-blocks/login-page', {
    title: __('Login', 'custom-registration-form-builder-with-submission-manager'), // Block title.
    category: 'regmagic', //category
    icon: el('svg', {
        xmlns: 'http://www.w3.org/2000/svg',
        height: '24',
        viewBox: '0 -960 960 960',
        width: '24',
        fill: '#0083e8',
        class:'rm-block-icon-svg'
    }, [
        el('path', {
            d: 'M480-120v-80h280v-560H480v-80h280q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H480Zm-80-160-55-58 102-102H120v-80h327L345-622l55-58 200 200-200 200Z'
        })
    ]),
    supports: {
        customClassName: false,
        className: false,
        html: false
    },
    //display the post title
    edit(props) {
        const attributes = props.attributes;
        const setAttributes = props.setAttributes;

        //Function to update id attribute

        //Display block preview and UI
        return createElement('div', {}, [

            //Preview a block with a PHP render callback
            createElement(wp.serverSideRender, {
                block: 'regmagic-blocks/login-page'
            })
        ]);
    },
    save() {
        return null;//save has to exist. This all we need
    }
});
if(rm_ajax_object.premium_active == true){
registerBlockType('regmagic-blocks/users-page', {
    title: __('Users', 'custom-registration-form-builder-with-submission-manager'), // Block title.
    category: 'regmagic', //category
    icon: el('svg', {
        xmlns: 'http://www.w3.org/2000/svg',
        height: '24',
        viewBox: '0 -960 960 960',
        width: '24',
        fill: '#0083e8',
        class:'rm-block-icon-svg'
    }, [
        el('path', {
            d: 'M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 320Zm0-400Z'
        })
    ]),
    supports: {
        customClassName: false,
        className: false,
        html: false
    },
    attributes: {
        fid: {
            default: '',
            type: 'string'
        },
        timerange: {
            default: 'all',
            type: 'string'
        }
    },
    //display the post title
    edit(props) {
        const attributes = props.attributes;
        const setAttributes = props.setAttributes;

        //Function to update id attribute
        function changeFid(fid) {
            setAttributes({fid});
        }
        function changetimeRange(timerange) {
            setAttributes({timerange});
        }

        //Display block preview and UI
        return createElement('div', {}, [

            //Preview a block with a PHP render callback
            createElement(wp.serverSideRender, {
                block: 'regmagic-blocks/users-page',
                attributes: attributes
            }),
            //Block inspector
            createElement(InspectorControls, {},
                    [
                        createElement(PanelBody, {title: 'Users Settings', initialOpen: true},
                                //A simple text control for post id
                                createElement(SelectControl, {
                                    value: attributes.fid,
                                    label: __('Select Form', 'custom-registration-form-builder-with-submission-manager'),
                                    help: __('Choose Form that need to show.', 'custom-registration-form-builder-with-submission-manager'),
                                    onChange: changeFid,
                                    options: Forms
                                }),
                                createElement(SelectControl, {
                                    value: attributes.timerange,
                                    label: __('Select Timrange', 'custom-registration-form-builder-with-submission-manager'),
                                    help: __('Choose timerange to dislay users.', 'custom-registration-form-builder-with-submission-manager'),
                                    onChange: changetimeRange,
                                    options: timerange_options()
                                })
                                )
                    ]
                    )
        ]);
    },
    save() {
        return null;//save has to exist. This all we need
    }
});
}

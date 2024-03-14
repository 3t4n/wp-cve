jQuery(function()
	{
		(function( blocks, element ) {
            var el = wp.element.createElement,
                source 		= blocks.source,
	            InspectorControls   = ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls;
		    var category 	= {slug:'cp-contact-form-with-paypal', title : 'CP Contact Form with PayPal'};

		    var _wp$components = wp.components,
                 SelectControl = _wp$components.SelectControl,
                 ServerSideRender = wp.serverSideRender;                
                
			/* Plugin Category */
			blocks.getCategories().push({slug: 'cp-contact-form-with-paypal', title: 'CP Contact Form with PayPal'}) ;

			
            /* ICONS */
	        const iconCPCFWPP = el('img', { width: 20, height: 20, src:  "data:image/gif;base64,R0lGODlhFAARAOYAAP//////AP8A//8AAAD//wD/AAAA/wAAAAAe/wAf/wEf/wEe/gEf/gEg/gMg/gMh/gMi/gMh/QUk+yNJ5gAZdwEUXCpT3wAhfURt5wAfcAEeZwAngwAogwEmdgEmdQEgaAEfZgAthQArgwArgQApfAEkbAAvhws4jENnoTJcmlJ5owBTmQFcqVqEpwB3wABqr0t9mwWK0FSFn1aHnwCc4gCa3gCR1wme4ACh4wCV1ku8332ms4qvt3DO3JLGzJPGy3/U2ova3JbKypnMyoTe1ofa05W/uZ7Sx6HYxqbNwarr0aPPvajfxKvjwrHcwrHpwLfxv7fvv7jxvrrzvrrtvrv1vb/6vMP8wMf8xcX+v8X8wMD8uMP+u8H9usb7wJjcjZjbjZncjpjbjpvekJrdkJrcj5vdkZ3gk6Lkl7z5ssD8tr75tMP/usT/usL9uMP+usb+vprdjpnbjcL+t8T/ucb/vP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAHYALAAAAAAUABEAAAfFgAiCg4SFhm1tdGBfiI2OjoKNYGKPlW1vkVyCEnFtbmRzYGWPXQgLYGGWiFtjjlYICqiqbGtnjlWGuboOiGpgaI1LMDMyRnCNTQgPbVKCDBiILRwmJhtJjUgIEG1RggmNKSIdHhkqWohDCBFgZo9eJyMgGhUvRIg/ym1QsBaIVCEkPpRYUaMIIh/5pgiagMjJBRY5bNDQgQWREAQNHKVBxIOCCxxBlGRpdASjLEc7UMS4ceUREwQMwMipVKcHkEpPdOkkFAgAOw==" } );            

			/* Form's shortcode */
			blocks.registerBlockType( 'cpcfwpp/form-rendering', {
                title: 'CP Contact Form with PayPal', 
                icon: iconCPCFWPP,    
                category: 'cp-contact-form-with-paypal',
				supports: {
					customClassName: false,
					className: false
				},
				attributes: {
			      	  formId: {
			            type: 'string'
		              },
			      	  instanceId: {
			            type: 'string'
		              }
			      },           
	            edit: function( { attributes, className, isSelected, setAttributes }  ) {             
                    const formOptions = cpcfwpp_forms.forms;
                    if (!formOptions.length)
                        return el("div", null, 'Please create a payment form first.' );
                    var iId = attributes.instanceId;
                    if (!iId)
                    {                        
                        iId = formOptions[0].value+parseInt(Math.random()*100000);
                        setAttributes({instanceId: iId });
                    }
                    if (!attributes.formId)
                        setAttributes({formId: formOptions[0].value });
                    cpcfwpp_renderForm(iId);
			    	var focus = isSelected;
					return [
						!!focus && el(
							InspectorControls,
							{
								key: 'cpcfwpp_inspector'
							},
							[
								el(
									'span',
									{
										key: 'cpcfwpp_inspector_help',
										style:{fontStyle: 'italic'}
									},
									'If you need help: '
								),
								el(
									'a',
									{
										key		: 'cpcfwpp_inspector_help_link',
										href	: 'https://cfpaypal.dwbooster.com/contact-us',
										target	: '_blank'
									},
									'CLICK HERE'
								)
							]
						),
						el(SelectControl, {
                                value: attributes.formId,
                                options: formOptions,
                                onChange: function(evt){         
                                    setAttributes({formId: evt});
                                    iId = evt+parseInt(Math.random()*100000);
                                    setAttributes({instanceId: iId });
                                    cpcfwpp_renderForm(iId);                                   
			    				},
                        }),
                        el(ServerSideRender, {
                             block: "cpcfwpp/form-rendering",
                             attributes: attributes
                        })
					];
				},

				save: function( props ) {
					return null;
				}
			});

		} )(
			window.wp.blocks,
			window.wp.element
		);
	}
);
function cpcfwpp_renderForm(id) {      
    if (jQuery("#form_structure"+id).length)
    {
        try
        {
            var cp_appbooking_fbuilder_myconfig = {"obj":"{\"pub\":true,\"identifier\":\"_"+id+"\",\"messages\": {}}"};
            var f = jQuery("#fbuilder_"+id).fbuilderCFWPP(jQuery.parseJSON(cp_appbooking_fbuilder_myconfig.obj));
            f.fBuild.loadData("form_structure"+id);                     
        } catch (e) { setTimeout ('cpcfwpp_renderForm('+id+')',250);}
    }
    else
    {
        setTimeout ('cpcfwpp_renderForm('+id+')',50);
    }
}
jQuery(function()
	{
		(function( blocks, element ) {
            var el = wp.element.createElement,
                source 		= blocks.source,
	            InspectorControls   = ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls;
		    var category 	= {slug:'easy-form-builder', title : 'CP Easy Form Builder'};

		    var _wp$components = wp.components,
                 SelectControl = _wp$components.SelectControl,
                 ServerSideRender = wp.serverSideRender;                
                
			/* Plugin Category */
			blocks.getCategories().push({slug: 'cpefbuilder', title: 'CP Easy Form Builder'}) ;

			
            /* ICONS */
	        const iconcpefbuilder = el('img', { width: 20, height: 20, src:  "data:image/gif;base64,R0lGODlhFAARANUAAAAAAP///wAAAgEBAwMDBQQEBgUFBwYGCAcHCQgICgoKDAsLDQ0NDw4OEBQUFhYWGBsbHR8fISIiJP39//z8/vv7/fr6/Pn5+/j4+vf3+fb2+PX19/T09vPz9fLy9PDw8u/v8e7u8Obm6OXl59jY2v7+/////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAACYALAAAAAAUABEAAAbDQIVgyGAICgXDUHBAChCIhiBA5XACFEqgQs1oNBPqkBqwYrXcgBcshmQyieGHGgp87ZptZTgakUsWFmQBcwEbVA8CfX+Bg4WHAQ8KCwsCBARPCAUDQw5Ll2NUIiJkkAEdWRliBllIrq9IFWkBAqwUsLiyZEMeHgETYVQTJVTEWyUloa8dHRUTG4fIu1MBy83P0ca0CmRfH2/VrhgYhB9PJCRagwEgFxeqVF8RCOjqg+3v3RoRAhISB0s+JUjAaUgDKUEAADs=" } );            

			/* Form's shortcode */
			blocks.registerBlockType( 'cpefbuilder/form-rendering', {
                title: 'CP Easy Form Builder', 
                icon: iconcpefbuilder,    
                category: 'cpefbuilder',
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
                    const formOptions = cpefbuilder_forms.forms;
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
                    cpefbuilder_renderForm(iId);
			    	var focus = isSelected;
					return [
						!!focus && el(
							InspectorControls,
							{
								key: 'cpefbuilder_inspector'
							},
							[
								el(
									'span',
									{
										key: 'cpefbuilder_inspector_help',
										style:{fontStyle: 'italic'}
									},
									'If you need help: '
								),
								el(
									'a',
									{
										key		: 'cpefbuilder_inspector_help_link',
										href	: 'https://wordpress.dwbooster.com/contact-us',
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
                                    cpefbuilder_renderForm(iId);                                   
			    				},
                        }),
                        el(ServerSideRender, {
                             block: "cpefbuilder/form-rendering",
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
function cpefbuilder_renderForm(id) {      
    if (jQuery("#form_structure"+id).length)
    {
        try
        {
            var cp_appbooking_fbuilder_myconfig = {"obj":"{\"pub\":true,\"identifier\":\"_"+id+"\",\"messages\": {}}"};
            var f = jQuery("#fbuilder_"+id).fbuilder(jQuery.parseJSON(cp_appbooking_fbuilder_myconfig.obj));
            f.fBuild.loadData("form_structure"+id);                     
        } catch (e) { setTimeout ('cpefbuilder_renderForm('+id+')',250);}
    }
    else
    {
        setTimeout ('cpefbuilder_renderForm('+id+')',50);
    }
}
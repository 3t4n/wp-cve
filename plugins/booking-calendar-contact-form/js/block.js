jQuery(function()
	{
		(function( blocks, element ) {
            var el = wp.element.createElement,
                source 		= blocks.source,
	            InspectorControls   = ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls;
		    var category 	= {slug:'booking-calendar-contact-form', title : 'Booking Calendar Contact Form'};

		    var _wp$components = wp.components,
                 SelectControl = _wp$components.SelectControl,
                 ServerSideRender = wp.serverSideRender;                
                
			/* Plugin Category */
			blocks.getCategories().push({slug: 'cpbccf', title: 'Booking Calendar Contact Form'}) ;

			
            /* ICONS */
   	        const iconCPBCCF = el('img', { width: 20, height: 20, src:  "data:image/gif;base64,R0lGODlhFAATAPcAAP//////AP8A//8AAAD//wD/AAAA/wAAAK8CA6sJCrkgIakmJ8R7fM+MjdGcnc6jpMmDhcqgocWcnejIyeHLzPTo6rSxs//+/4WEhfXy9vXw+O7r8cbEyNDP0c3Mzr28wZaVm6urrKOjpJSUlVNXWBccHWtvb4eJidze3vP09MTFxb2+vkFEQ05TUfv+/LzgvszizE9RT5WXlUdIR+3u7dPU087Pzs3OzaWmpZaXlhSZBU60QXfFakqeO2qqXqzao9bn093w2TiwFzuzGmG2S2m/Um7CWDevFTuxGUWwJEuwK0yuLZfUhcXnu1mtO2TFPUy/HWPGN3nJWLPjnqivpWPIMGbLM2HDM2nMN72/vK6wrWTJMGjLNZTWcanWktb3xHXSPHrTRobWV2mHV6W1nGSmOG18Y+b62e7556KkoN7f3b6/vba3tausqvn79svMyZOhUH+DN4aGg5ubmJycmoqKiIKCgMvLybS0srKysHh4d/39/NnZ2MLCwaGhoJubmpOTkpCQj5KKabqLbff088SCe9m+u+fe3YBnZc7CweLV1LlsarB+fM6mpe7W1Z0AAK8BAZcEA7ULC6sLC7QPD6cYFqcdHagiIrM4N7pAP7E+PrRISLBRULRXVrNubc1+fsd9fcF9fb58fMOBgMmGhtSQkMOGhtiWltKYl9Wenc+cm+Kurdaqqd2xsd+7u9a0tNy+vte9verPz9nCwu7Z2fLe3t7MzO7e3unh4fny8v7+/vr6+vn5+ff39/T09PDw8O7u7u3t7erq6uXl5dzc3Nvb28PDw8HBwb+/v7q6urOzs7CwsK2traurq6mpqZ2dnZiYmIuLi4mJiX5+fn19fXp6enh4eG9vb////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAANgALAAAAAAUABMAAAj/AAEIHEgQAAoOMF64IKgLwIUONoI0+cGExw4fFnroAFKwiRQyVJQMEXLkCJIkS8rEEVRh4AUvUJwQKRIFCxcrW6rAKeQAFYRaAjeMecLDCJErYMKI6TKIAqxPDQ45cAQggxkpXZhM+XIGjRtHD1QlkIRgky1TuXTJUSNQA4gPAGiRmlVpEqVTlxY1ogVghAcAKUiUMAFggihDCzCR6hSJUyxXAJzlAbBLxYkWeyowwmXp1ioEj0bBkgWgz5yBK1jQIISokapMCiBpUjRqD4BidQaqmVFjlwxPrF6VSmUrFFAAwOz0EugrRhuBF1gxaJUKlCJeAvfIGSZQF7RjA/fIdYogIZEy7gIB2SiYPdgdP3+OiUA2sFmy7L1+DeNzYw0ePGz0AQ0OAxnjTArCqFHDG1mwoUUadARihx7X5DAQMc8UY8MxyuCQgzTTWGPNNNE8swwxAwEDzR/SUGNNNRjkEIIxxQSzC3u6MBNIM8bwIcyN7A0UEAA7" } )       

			/* Form's shortcode */
			blocks.registerBlockType( 'cpbccf/form-rendering', {
                title: 'Booking Calendar Contact Form', 
                icon: iconCPBCCF,    
                category: 'cpbccf',
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
                    const formOptions = cpbccf_forms.forms;
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
                    cpbccf_renderForm(iId);
			    	var focus = isSelected;
					return [
						!!focus && el(
							InspectorControls,
							{
								key: 'cpbccf_inspector'
							},
							[
								el(
									'span',
									{
										key: 'cpbccf_inspector_help',
										style:{fontStyle: 'italic'}
									},
									'If you need help: '
								),
								el(
									'a',
									{
										key		: 'cpbccf_inspector_help_link',
										href	: 'https://bccf.dwbooster.com/contact-us',
										target	: '_blank'
									},
									'CLICK HERE'
								)
							]
						)/**,
						el(SelectControl, {
                                value: attributes.formId,
                                options: formOptions,
                                onChange: function(evt){         
                                    setAttributes({formId: evt});
                                    iId = evt+parseInt(Math.random()*100000);
                                    setAttributes({instanceId: iId });
                                    cpbccf_renderForm(iId);                                   
			    				},
                        })*/,
                        el(ServerSideRender, {
                             block: "cpbccf/form-rendering",
                             attributes: attributes
                        })
					];
				},

				save: function( props ) {
					return props.attributes.shortcode;
				}
			});

		} )(
			window.wp.blocks,
			window.wp.element
		);
	}
);
var pathCalendar = BCCFURLS.siteurl;
function cpbccf_renderForm(id) {      
    if (jQuery("#form_structure"+id).length)
    {
        try
        {
            var cp_appbooking_fbuilder_myconfig = {"obj":"{\"pub\":true,\"identifier\":\"_"+id+"\",\"messages\": {}}"};
            var f = jQuery("#fbuilder_"+id).BCCFfbuilder(jQuery.parseJSON(cp_appbooking_fbuilder_myconfig.obj));
            f.fBuild.loadData("form_structure"+id);                
            jQuery("#calarea"+id).rcalendar({"calendarId":id,
                                                    "partialDate":true,
                                                    "defaultColor":'F66',
                                                    "partial_defaultColor":'F66',
                                                    "edition":false,
                                                    "minDate":"12/13/2018",
                                                    "maxDate":"",
                                                    "dformat":"mm/dd/yy",
                                                    "workingDates":[1,1,1,1,1,1,1],
	    			                                "holidayDates":[],
	    			                                "startReservationWeekly":[1,1,1,1,1,1,1],
	    			                                "startReservationDates":[],
	    			                                "fixedReservationDates":false,
	    			                                "fixedReservationDates_length":1,
                                                    "language":"",
                                                    "firstDay":0,
                                                    "numberOfMonths":1                                                    });
       

        } catch (e) { setTimeout ('cpbccf_renderForm('+id+')',250);}
    }
    else
    {
        setTimeout ('cpbccf_renderForm('+id+')',50);
    }
}
const { registerBlockType } = wp.blocks;

registerBlockType('wc-mailchimp/custom-cta', {
    title: 'WC MAILCHIMP SUBSCRIBE',
    description: 'Block to add WC MAILCHIMP SUBSCRIBE DISCOUNT',
    icon: 'screenoptions', // The icon of block in editor.
    category: 'common', // The category of block in editor.
    attributes: {
        shortcode_selected_option: {
            type: 'string',
            default: 'no'
        },
        width_entered:{
            type: 'string',
            default: '400'
        },
        float_entered:{
            type: 'string',
            default: 'inherit'
        },
        flex_direction_entered:{
            type:'string',
            default:'column'
        },
        top_text_entered:{
            type:'string',
        },
         top_text_color_given_entered:{
            type:'string',
            default:'#000'
         }

    },
    edit({ attributes, setAttributes }) {

        function shortcode_option(event) {
            setAttributes({ shortcode_selected_option: event.target.value })
            localStorage.setItem("radio_option", event.target.value);
                      
        }
        function width_value(event){
            setAttributes({ width_entered: event.target.value })

        }
        function FLOAT_ALIGN_DIRECTION(event){
            setAttributes({ float_entered: event.target.value })
            localStorage.setItem("float_align_value", event.target.value);
          var x = localStorage.getItem("float_align_value");
        }
        
        function FLEX_DIRECT(event){
            setAttributes({ flex_direction_entered: event.target.value })
        }
        function TOP_TEXT_COLOR(event){
            setAttributes({ top_text_color_given_entered: event.target.value })
            
        }
        function TOP_TEXT_VALUE(event){
            setAttributes({top_text_entered: event.target.value})
        }

        var selectedOption = attributes.shortcode_selected_option;
        const options = ["Only Email", "First Name and Email", "First Name, Last Name and Email"];
     
        // pass a function to map
        if (selectedOption) {
            return <div class="wcmnd-custom-shortcode" style={{border: '2px solid black' , backgroundColor: "#ECECEC" , paddingBottom:'5px'}}>
                        <div class="components-placeholder__label">
                                <span class="block-editor-block-icon has-colors">
                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false">
                                        <path d="M9 9V3H3v6h6zm8 0V3h-6v6h6zm-8 8v-6H3v6h6zm8 0v-6h-6v6h6z"></path>
                                    </svg>
                                </span>WC MAILCHIMP SUBSCRIBE DISCOUNT
                        </div>


                        <div class="wcmnd_input_field_container" >
                            <div  style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                <b style={{fontWeight: 'bold'}}>Display Fields</b>
                            </div>
                            <div style={{display: 'flex' ,flexDirection:'row', flexWrap:'wrap'}}>
                            {options.map(selected_option_for_shortcode => (
                                <div class="wp_option" style= {{ marginTop: '2px' , paddingLeft:'15px' }}>       
                                     {(selectedOption == selected_option_for_shortcode) ? 
                                      <div>
                                          <input type="radio" id="mailchimp_shortcode" value={selected_option_for_shortcode} name="opt" onClick={shortcode_option} checked/>
                                          {selected_option_for_shortcode}
                                        </div> : 
                                       <div><input type="radio"  value={selected_option_for_shortcode} name="opt" onClick={shortcode_option} />{selected_option_for_shortcode}</div>}
                                </div>
                            ))}
                            </div>

                            <div  style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                <b style={{fontWeight: 'bold'}}>Form Properties</b>
                            </div>
                            <div style={{display: 'flex' ,flexDirection:'row', flexWrap:'wrap'}}>
                            <div  style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                Width:&nbsp;&nbsp;&nbsp;&nbsp;<input type="number" value={attributes.width_entered}  name="width" size="10" onChange={width_value} />
                            </div>
                            
                            <div class ="wcmnd_input_field" style={{ marginTop: '2px' , paddingLeft:'15px'}}> Align:&emsp;&emsp;&emsp;&emsp;
                            <select name="mail_ching_discount_float_direction" id="mail_ching_discount_float_direction" onChange={FLOAT_ALIGN_DIRECTION}>
                                    <option value="">Select Alignment</option>
                                    <option value="left">Left</option>
                                    <option value="inherit">Inherit</option>
                                    <option value="right">Right</option>
                                   
                             </select>
                            
                            </div><br/>
                            </div>

                            <div style={{display: 'flex' ,flexDirection:'row', flexWrap:'wrap'}}>
                            <div style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                Top Text:<input type="text" value={attributes.top_text_entered} size='20' name="top_text_enter" onChange={TOP_TEXT_VALUE} />
                            </div>
                            <div style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                Top Text Color:<input type="text" value={attributes.top_text_color_given_entered} size='13' name="top_text_color" onChange={TOP_TEXT_COLOR} />
                            </div>
                            </div>
                        </div>
                     </div>
                }
        else {
            return <div class ="wcmnd-custom-shortcode" style={{border: '2px solid black' , backgroundColor: "#ECECEC" , paddingBottom:'5px' }}>
             
                        <div class="components-placeholder__label">
                                    <span class="block-editor-block-icon has-colors">
                                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false">
                                            <path d="M9 9V3H3v6h6zm8 0V3h-6v6h6zm-8 8v-6H3v6h6zm8 0v-6h-6v6h6z"></path>
                                        </svg>
                                    </span>WC MAILCHIMP SUBSCRIBE DISCOUNT
                        </div>
 
                        <div class="wcmnd_input_field_container" style={{ marginTop: '2px' , paddingLeft:'15px'}} >
                                <div  style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                   <b style={{fontWeight: 'bold'}}>Display Fields</b>
                                </div>
                                <div style={{display: 'flex' ,flexDirection:'row', flexWrap:'wrap'}}>                             
                                {options.map(selected_option_for_shortcode=>(
                                        <div>
                                            <input type="radio" value={selected_option_for_shortcode} name="opt" onClick={shortcode_option} />
                                            {selected_option_for_shortcode}
                                        </div>
                                    
                                    ))}
                                </div>
                                <div style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                   Width:<input type="number" value={attributes.width_entered}  name="width" onChange={width_value} />
                                </div>
                                <div class ="wcmnd_input_field"> Align: <select name="mail_ching_discount_float_direction" id="mail_ching_discount_float_direction" onChange={FLOAT_ALIGN_DIRECTION}>
                                    <option value="">Select Alignment</option>
                                    <option value="left">Left</option>
                                    <option value="inherit">Inherit</option>
                                    <option value="right">Right</option>
                                   
                             </select>
                            
                            </div>
                            <div style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                Top Text:<input type="text" value={attributes.top_text_entered}  name="top_text_enter" onChange={TOP_TEXT_VALUE} />
                            </div>
                            <div style={{ marginTop: '2px' , paddingLeft:'15px'}}>
                                Top Text Color:<input type="text" value={attributes.top_text_color_given_entered}  name="top_text_color" onChange={TOP_TEXT_COLOR} />
                            </div>
                         </div>
                     </div>

            }
    },

    save({ attributes }) {

        switch (attributes.shortcode_selected_option) {
            case 'Only Email':
                var email = 'yes';
                var first_name_email = 'no';
                var first_last_name_email = 'no';
                break;
            case 'First Name and Email':
                var first_name_email = 'yes';
                var email = 'yes';
                var first_last_name_email = 'no';
                break;
            case 'First Name, Last Name and Email':
                var first_name_email = 'yes';
                var email = 'yes';
                var first_last_name_email = 'yes';
                break;

            default:
                var first_name_email = 'no';
                var email = 'no';
                var first_last_name_email = 'no';
                break;
        }
        var width_given = attributes.width_entered;
        var float_given = attributes.float_entered;
        var flex_Direction_given = attributes.flex_direction_entered;
        var top_text_given = attributes.top_text_entered;
        var top_text_color_given = attributes.top_text_color_given_entered;
       
        return <div>[wc_mailchimp_subscribe_discount email= "{email}" first_name= "{first_name_email}" last_name = "{first_last_name_email}" width= "{width_given}px" float_align = "{float_given}" flex_direction="{flex_Direction_given}" top_text="{top_text_given}" top_text_color="{top_text_color_given}"]</div>;
    }
});
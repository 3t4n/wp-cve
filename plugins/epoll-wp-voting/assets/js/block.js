//  Import CSS.
wp.blocks.registerBlockType( 'epoll-wp-voting/poll-show', {
    
        title:"Insert Poll - ePoll",
        icon:"chart-pie",
        category:"common",
        attributes: {
            poll_id: {type: 'string'},
            poll_type: {
                enum: [ 'grid', 'list' ]
            }
          },

          edit: function(props) {
            function updatePollId(event) {
              props.setAttributes({poll_id: event.target.value})
            }
            function updatePollType(event,value) {
              event.preventDefault();
           
              props.setAttributes({poll_type: 1})
            }

            console.log(props.attributes.poll_id);
            var propsHide = "show_epoll_block_btn";
            var propsDisabled = false;
            if(props.attributes.poll_type){
              propsHide = "hide_epoll_block_btn";
              propsDisabled = true;
            }
            
              return React.createElement(
                "div",
                {className:"components-placeholder wp-block-embed is-large"},
                React.createElement(
                  "div",
                  {className:"components-placeholder__label"},
                  React.createElement(
                    "span",
                    {className:"block-editor-block-icon has-colors"},
                    React.createElement(
                      "i",
                      {className:"dashicons dashicons-chart-pie", style:{fontStyle:"normal"}},
                    )
                    ),
                    "Enter Poll ID"
                ),

                
           
                React.createElement(
                  "fieldset",
                  {className:"components-placeholder__fieldset"},
  
                    React.createElement(
                      "legend",
                      {className:"components-placeholder__instructions"},
                      "Paste a link to the content you want to display on your site."
                    ),
                    React.createElement(
                      "form",
                      {className:"epoll-editor-block-form", style:{display:"flex"},
                      onSubmit:updatePollType,
                    },
                      
                      React.createElement(
                        "input",
                        {
                          className:"epoll-editor-block-input components-placeholder__input",
                          type:"number",
                          ariaLabel:"Enter Poll ID",
                          value: props.attributes.poll_id, 
                          disabled:propsDisabled,
                          placeholder:"Enter poll id to embed poll here…",
                          onChange: updatePollId 
                        },
                        null),
                        
                        React.createElement(
                          "button",
                          {
                            className:"epoll-editor-block-input button-primary "+propsHide,
                            
                            ariaLabel:"Enter Poll ID",
                            value: props.attributes.poll_type, 
                            placeholder:"Enter poll id to embed poll here…",
                            
                          },
                          "Add/Embed"),

                       
  
                      
                    ),
                          React.createElement(
                            "div",
                            {className:"components-placeholder__learn-more"},
  
                            React.createElement(
                              "small",
                              {className:"components-external-link"},
                              "You will see demo on page preview"
                            ),
                            null
                          ),
                          null
                          ),
                          
                null
              );
                            
            
            
          },
          save: function(props) {
            return wp.element.createElement(
              "div",
              {className:"it_epoll_wp_block_container"},
              "[IT_EPOLL id=\""+props.attributes.poll_id+"\"][/IT_EPOLL]"
            );
          }
        });
var isActive = false;
var lastEditor = null;
const { __ } = wp.i18n;
var el = wp.element.createElement;
var BlockControls = wp.editor.BlockControls;
var InspectorControls = wp.editor.InspectorControls;
var components = wp.components;
var TextControl = wp.components.TextControl;
var SelectControl = wp.components.SelectControl;
var RangeControl = wp.components.RangeControl;
/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */
wp.blocks.registerBlockType("wpurdu/wpurdu", {
  title: __( 'WP Urdu' ),
  icon: React.createElement("span", {
    class: "wpurdu-logo"
  }),
  category: "common",
  presets: ["@wordpress/default"],

  attributes: {
    align: {
      type: "string"
    },
    content: {
      type: "string",
      source: "html",
      selector: "p"
    },
    font_style: {
		type: 'string'
	},
	font_size: {
		type: 'integer'
	},
	line_height: {
		type: 'integer'
	},
  },
  keywords: [
		__( 'WPUrdu' ),
		__( 'Urdu' ),
		__( 'Roman Urdu' ),
	],
  edit: function(props) {
    return new UrduBlockEditor(props);
    // Create block UI using WordPress createElement

  },
  save: function(props) {
    const { align, content } = props.attributes;
    const { font_size, font_style, line_height } = props.attributes;
    let align_class  = null;
    let e_font_size  = null;
    let e_font_style  = null;
    let e_line_height  = null;
    if(typeof align !== "undefined"){
        align_class = align;
    }

    if(typeof font_size !== "undefined"){
       e_font_size = font_size+"px";
    }
    if(typeof font_style !== "undefined"){
         e_font_style = font_style;
    }

    if(typeof line_height !== "undefined"){
       e_line_height = line_height+"px";
    }

    let updated_content = content;
    if (typeof content !== "undefined") {
      updated_content = content.replace("<p>", "").replace("</p>", "");
    }

    return wp.element.createElement(wp.blockEditor.RichText.Content, {
      tagName: "p",
      className: align_class,
      value: updated_content,
      style: {
        fontSize: e_font_size,
        fontFamily: e_font_style,
        lineHeight:e_line_height
    	  }
    });
  }
});

class UrduBlockEditor extends React.Component {
  constructor(props) {
    super(props);
    this.elementReference = React.createRef();
    this.Id = "urdu-" + props.clientId;
    const editor_id = `.${this.Id}`;
    this.urdu_typing = new translatable(editor_id);
    this.counter = 0;
  }
  render() {
    const props = this.props;
//     console.log(props.attributes);
    const { align, font_size, font_style, line_height } = props.attributes;
    let align_class  = null;
    let e_font_size  = null;
    let e_font_style  = null;
    let e_line_height  = null;
    if(typeof align === "undefined" || align === "right"){
        align_class = 'right';
    } else {
	    align_class = align;
    }

    if(typeof line_height === "undefined"){
        e_line_height = "28px";
    } else {
	    e_line_height = line_height+"px";
    }
    if(typeof font_size === "undefined"){
        e_font_size = "14px";
    } else {
	    e_font_size = font_size+"px";
    }
    if(typeof font_style === "undefined"){
        e_font_style = "inherit";
    } else {
	    e_font_style = font_style;
    }

//     console.log(align_class);
    return [
      this.toolbar(),
      this.toggleButton(),
      this.changeButton(),
      this.sidebarElements(),
      React.createElement(wp.blockEditor.RichText, {
        multiline: false,
        className: [
          props.className,
          this.Id,
          `text-align-${align_class}`,
          `has-text-align-${align_class}`
        ],
        value: props.attributes.content,
        ref: this.elementReference,
        id: "urdu-" + Date.now(),
        placeholder: __( "Text in urdu" ),
		style: {
            fontSize: e_font_size,
            fontFamily: e_font_style,
            lineHeight:e_line_height
        },
        onChange: function(content) {
          const editor_id = ".urdu-" + props.clientId;
          let updated_content = content;
          if (isActive) {
            // Remove break
            const br_find = new RegExp('<br[^>]*>');
            let content_data = _.compact(content.split(br_find));
            if(content_data.length === 0 ){
              content_data = ['',''];
            }
            if(content_data.length === 1){
              content_data.push('');
            }
            updated_content = content_data[0].replace('/<br[^>]*>/g',' ');
            //Loop and create new blocks
            content_data.forEach((content,index)=>{
              if(index === 0){
                return;
              }
              const WPUrduBlock = wp.blocks.createBlock('wpurdu/wpurdu',{content});
              props.insertBlocksAfter(WPUrduBlock);
            });
            // Update editor content
            setTimeout(() => {
              jQuery(editor_id).html(updated_content);
            }, 10);
          }

          props.setAttributes({ content: updated_content });
        }
      })
    ];
  }
  sidebarElements() {
	const props = this.props;
	var attributes = props.attributes;
    return el(InspectorControls, {
            key: 'inspector'
        },
        el(components.PanelBody, {
                title: __('WP Urdu Typography', 'wpurdu'),
                className: 'block-content',
                initialOpen: true
            },
            el('p', {}, __('Change Typography', 'wpurdu')),
            el(SelectControl, {
	            label: __("Font", "olympus-google-fonts"),
                type: "string",
                value: ((attributes.font_style) ? attributes.font_style : "inherit"),
                options: [{
                    label: __('Select Font Family', 'wpurdu'),
                    value: "inherit"
                },{
                    label: "Nafees Nastaleeq  اُردُو",
                    value: "Nafees Nastaleeq,sans-serif"
                }],
                onChange: function(new_font_style) {
                    props.setAttributes({
                        font_style: new_font_style
                    })
                }
            }),
            el(RangeControl, {
                label: __('Font Size', 'wpurdu'),
                value: ((attributes.font_size) ? attributes.font_size : 14),
                onChange: function(new_font_size) {
                    props.setAttributes({
                        font_size: new_font_size
                    })
                },
                initialPosition: 14,
                allowReset: !0,
                min: "10",
                max: "50"
            }),
            el(RangeControl, {
                label: __('Line Height', 'wpurdu'),
                value: ((attributes.line_height) ? attributes.line_height : 28),
                onChange: function(new_line_height) {
                    props.setAttributes({
                        line_height: new_line_height
                    })
                },
                initialPosition: 28,
                allowReset: !0,
                min: "10",
                max: "100"
            })
        )
    )
  }
  componentDidMount() {
    const editor_id = `.${this.Id}`;
    this.urdu_typing.initialize();

    /* Removes paragraph tags to ensure edit compatibility */
    const { content } = this.props.attributes;
    let updated_content = content;
//     console.log(updated_content);
    if (content !== undefined) {
      updated_content = content.replace("<p>", "").replace("</p>", "");
    }

    // Update Content
    lastEditor = jQuery(editor_id).html(updated_content);

    setTimeout(function() {
      document.querySelector(editor_id).focus();
    }, 10);
  }
  changeButton(){
	if(this.counter == 0){
		const urdu_typing = this.urdu_typing;
		let block_id = `#${this.Id}`;
		block_id = block_id.replace('urdu-','block-');

		const props = this.props;
	    const { align } = props.attributes;
// 	    console.log(align);
	    if(typeof align === "undefined" || align === "right"){

	    } else {
		    setTimeout(() => {
	              jQuery(block_id).find(".wp-urdu-block-editor-button").removeClass("active").addClass("disabled");
				 jQuery(block_id).find(".wp-urdu-block-editor-button").parent().addClass("mce-active");
				 jQuery(block_id).find(".wp-urdu-block-editor-button .text").text(__( "Enable WPUrdu" ));
				 urdu_typing.changeState();
	        }, 50);

	    }
	    this.counter++;
    }
  }
  toggleButton() {
  	const props = this.props;
    const { align } = props.attributes;
    const urdu_typing = this.urdu_typing;
    return React.createElement(
      "button",
      {
        className: "wp-urdu-block-editor-button active",
        onClick: function(event) {
          var target = event.target;
          var button = target;

          if (target.tagName != "BUTTON") {
            button = target.parentElement;
          }

          var text = button.querySelector(".text");
          if (!button.classList.contains("disabled")) {
            text.innerText = __( "Enable WPUrdu" );
            jQuery(".is-selected .dashicons-editor-alignleft").parent().trigger("click");
          } else {
            text.innerText = __( "Disable WPUrdu" );
            if(jQuery(".is-selected .dashicons-editor-aligncenter").parent().hasClass("is-active")){
	            jQuery(".is-selected .dashicons-editor-aligncenter").parent().trigger("click");
            } else {
	            jQuery(".is-selected .dashicons-editor-alignright").parent().trigger("click");
            }
          }
          button.classList.toggle("disabled");
          button.classList.toggle("active");
          button.parentElement.classList.toggle("mce-active");
          urdu_typing.changeState();
        }
      },
      [
        React.createElement("span", {
          className: "wpurdu-logo active"
        }),
        React.createElement(
          "span",
          {
            className: "text"
          },
          __( "Disable WPUrdu" )
        )
      ]
    );
  }
  toolbar() {
    const props = this.props;
    const { align } = props.attributes;
    //debugger;
    return React.createElement(
      wp.editor.BlockControls,
      {},
      React.createElement(wp.editor.AlignmentToolbar, {
        value: align,
        onChange: align => {
          props.setAttributes({ align: align });
        }
      })
    );
  }
}

jQuery("body").on("keydown", ".wp-block-wpurdu-wpurdu", function(e) {
  if (e.key == "Enter") {
    isActive = true;
  } else {
    isActive = false;
  }
});

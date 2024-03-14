var isEnter = false;
var lastEditor = null;
let isSpace = false;
/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */
wp.blocks.registerBlockType("zozuk/wphindi", {
  title: "WPHindi",
  icon: React.createElement("span", {
    class: "wphindi-logo"
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
    }
  },
  edit: function(props) {
    return new HindiEditor(props);
  },
  save: function(props) {
    const { align, content } = props.attributes;
    let alignClass  = null;
    if(align !== undefined){
        alignClass = `text-align-${align}`;
    }

    // Ensures backward compatibility with older versions. 
    let newContent = content;
    if (content !== undefined) {
      newContent = content.replace("<p>", "").replace("</p>", "");
    }

    return wp.element.createElement(wp.blockEditor.RichText.Content, {
      tagName: "p",
      className: alignClass,
      value: newContent
    });
  },
  deprecated: [
    {
      attributes: {
        content: {
          type: "string",
          source: "html",
          selector: "div"
        }
      },
      edit: function(props) {
        return React.createElement("div", {
          dangerouslySetInnerHTML: {
            __html: props.attributes.content
          }
        });
      },
      save: function(props) {
        const { align } = props.attributes;
        return React.createElement("div", {
          dangerouslySetInnerHTML: {
            __html: props.attributes.content
          }
        });
      }
    }
  ],
  transforms: {
    from: [
        {
            type: 'block',
            blocks: [ 'core/paragraph' ],
            transform: function ( attributes ) {
                return wp.blocks.createBlock( 'zozuk/wphindi', {
                    content: attributes.content,
                    align: attributes.align
                } );
            },
        },
    ]
  }
});

class HindiEditor extends React.Component {
  constructor(props) {
    super(props);
    this.elementReference = React.createRef();
    this.Id = "zz-" + props.clientId;
    const editorID = `.${this.Id}`;
    this.hindiTyping = new WPHindi.writer(editorID);
  }
  render() {
    const props = this.props;
    const { align } = props.attributes;
    return [
      this.toolbar(),
      this.toggleButton(),
      React.createElement(wp.blockEditor.RichText, {
        multiline: false,
        className: [
          props.className,
          this.Id,
          `text-align-${align}`,
          `has-text-align-${align}`
        ],
        value: props.attributes.content,
        ref: this.elementReference,
        id: "zz-" + Date.now(),
        placeholder: "Start typing in hindi",
        onChange: function(content) {
          const editorID = ".zz-" + props.clientId;
          let newContent = content;
          if (isEnter) {
            // Spit content from break
            const brRegExp = new RegExp('<br[^>]*>');
            let contentArray = _.compact(content.split(brRegExp));
            if(contentArray.length === 0 ){
              contentArray = ['',''];
            }
            if(contentArray.length === 1){
              contentArray.push('');
            }
            newContent = contentArray[0].replace('/<br[^>]*>/g','');
            //Loop and create new blocks
            contentArray.forEach((content,index)=>{
              if(index === 0){
                return;
              }
              const WPHindiBlock = wp.blocks.createBlock('zozuk/wphindi',{content});
              props.insertBlocksAfter(WPHindiBlock);
            });
            // Update editor content
            setTimeout(() => {
              jQuery(editorID).html(newContent);
            }, 10);
          }

          props.setAttributes({ content: newContent });
        }
      })
    ];
  }
  componentDidMount() {
    const editorID = `.${this.Id}`;
    this.hindiTyping.load();

    /* Removes paragraph tags to ensure edit compatibility */
    const { content } = this.props.attributes;
    let newContent = content;
    if (content !== undefined) {
      newContent = content.replace("<p>", "").replace("</p>", "");
    }

    // Update Content
    lastEditor = jQuery(editorID).html(newContent);

    setTimeout(function() {
      document.querySelector(editorID).focus();
    }, 10);
  }

  toggleButton() {
    const hindiTyping = this.hindiTyping;
    return React.createElement(
      "button",
      {
        className: "wp-hindi-block-editor-button active",
        onClick: function(event) {
          var target = event.target;
          var button = target;

          if (target.tagName != "BUTTON") {
            button = target.parentElement;
          }

          var text = button.querySelector(".text");
          if (!button.classList.contains("disabled")) {
            text.innerText = "Enable WPHindi";
          } else {
            text.innerText = "Disable WPHindi";
          }
          button.classList.toggle("disabled");
          button.classList.toggle("active");
          button.parentElement.classList.toggle("mce-active");
          hindiTyping.toggle();
        }
      },
      [
        React.createElement("span", {
          className: "wphindi-logo active"
        }),
        React.createElement(
          "span",
          {
            className: "text"
          },
          "Disable WPHindi"
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

jQuery("body").on("keydown", ".wp-block-zozuk-wphindi", function(e) {
  if (e.key == "Enter") {
    isEnter = true;
  } else {
    isEnter = false;
  }
});

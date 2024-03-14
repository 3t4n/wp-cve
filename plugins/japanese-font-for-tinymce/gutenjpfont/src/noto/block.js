//  Import CSS.
import "./style.scss";
import "./editor.scss";
import { registerFormatType } from "@wordpress/rich-text";
import { RichTextToolbarButton } from "@wordpress/block-editor";

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

const { RichText } = wp.blockEditor;

registerBlockType("tinyjpfont/noto", {
  title: "Noto Sans Japanese",
  icon: "edit",
  category: "common",
  example: {
    attributes: {
      cover: "https://gcs.raspi0124.dev/tinyjpfont/assets/noto.png",
      author: "raspi0124",
      pages: 500,
    },
  },
  attributes: {
    textString: {
      type: "array",
      source: "children",
      selector: "p",
    },
  },

  // props are passed to edit by default
  // props contains things like setAttributes and attributes
  edit(props) {
    // we are peeling off the things we need
    const { setAttributes, attributes } = props;

    // This function is called when RichText changes
    // By default the new string is passed to the function
    // not an event object like react normally would do
    function onTextChange(changes) {
      // works very much like setState
      setAttributes({
        textString: changes,
      });
    }

    return (
      <RichText
        tagName="p"
        value={attributes.textString}
        onChange={onTextChange}
        className="wp-block-tinyjpfont-noto"
        placeholder="Enter your favorite text with your fevorite font! yay!"
      />
    );
  },

  // again, props are automatically passed to save and edit
  save(props) {
    const { attributes } = props;

    // We want the text to be an h2 element
    // and we place the textString value just
    // like we would in a normal react app
    return <p class="wp-block-tinyjpfont-noto">{attributes.textString}</p>;
  },
});

//Add Noto Btn to Toolbar
const tinyjpfontNotobtn = (props) => {
  return (
    <RichTextToolbarButton
      icon="editor-textcolor"
      title="Noto Sans Japanese"
      onClick={() => {
        props.onChange(
          props.toggleFormat(props.value, { type: "tinyjpfont/notobtn" })
        );
      }}
      isActive={props.isActive}
    />
  );
};

registerFormatType("tinyjpfont/notobtn", {
  title: "Noto Sans Japanese",
  className: "wp-block-tinyjpfont-noto",
  tagName: "tinyjpfontNoto",
  edit: tinyjpfontNotobtn,
});

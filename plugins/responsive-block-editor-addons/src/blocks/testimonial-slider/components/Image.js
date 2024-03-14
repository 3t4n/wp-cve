const { RichText } = wp.blockEditor;

const { __ } = wp.i18n;

class TestimonialImage extends React.Component {
  render() {
    const { attributes, index_value } = this.props;
    let url_check = "";

    const image_arr = attributes.test_block[index_value];

    if (image_arr && typeof image_arr !== "undefined") {
      const image = image_arr["image"];
      let url = "";

      if (typeof image !== "undefined" && image !== null && image !== "") {
        url_check = image.url;
      }

      if (url_check !== "") {
        let size = image.sizes;
        let imageSize = attributes.imageSize;
        if (
          typeof size !== "undefined" &&
          typeof size[imageSize] !== "undefined"
        ) {
          url = size[imageSize].url;
        } else {
          url = url_check;
        }

        return (
          <div
            className="responsive-block-editor-addons-tm__image-content"
            key={"tm_img-wrap-" + index_value}
          >
            <div
              className="responsive-block-editor-addons-tm__image"
              key={"tm_img-" + index_value}
            >
              <img
                className="responsive-block-editor-addons-tm-img-src"
                src={url}
                alt={image.alt}
              />
            </div>
          </div>
        );
      } else {
        return null;
      }
    } else {
      return null;
    }
  }
}

export default TestimonialImage;

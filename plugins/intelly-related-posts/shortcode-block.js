var el = wp.element.createElement;

const irpIcon = el(
  'svg',
    {height: "24", width: "40"},
    el(
      'text',
      {x: '0', y: '15', fill: 'red'},
      "[irp]"
    )
);

wp.blocks.registerBlockType("data443/irp-shortcode", {
  title: "Inline Related Posts", // Block name visible to the user within the editor
  icon: irpIcon, // Toolbar icon displayed beneath the name of the block
  category: "data443-category", // The category under which the block will appear in the Add block menu
  attributes: {
    // The data this block will be storing
    id: {type: "string", default: 'Inline Related Posts'},
    shortcode: {type: "string", default: '[irp]'},
    items: {
      type: 'array',
      default: [],
      items: {
          type: 'object',
          properties: {
              stringValue: {
                  type: 'string',
                  default: 'Default String'
              },
              intValue: {
                  type: 'integer',
                  default: 0
              }
          }
      }
    }
  },
  edit: function (props) {
    // Defines how the block will render in the editor
    var ajaxurl = 'admin-ajax.php';
    const clientId = props.clientId;
    var selectBoxSelector = `.irp-post-select-${clientId}`;
    const selectBox = document.querySelector(selectBoxSelector);

    var data = {
      'action' : 'irp_list_posts',
      'irp_post_type' : 'post'
    };

    function isNumeric(value) {
      return /^\d+$/.test(value);
    }

    console.log('edit');
    console.log('selected attribute value: ', props.attributes.id);
    console.log('shortcode: ', props.attributes.shortcode);

    if (selectBox != null) {
      var availableOptionsCount = props.attributes.items.length;
      console.log('Number of available options: ', availableOptionsCount);

      if (availableOptionsCount > 0) {
        jQuery(selectBox).empty();
        props.attributes.items.forEach(option => {
            const optionElement = new Option(option.text, option.id);
            jQuery(selectBox).append(optionElement);
        });
      }

      const selectedValue = jQuery(selectBox).val();
      console.log("selected dropdown value: ", selectedValue);

      if (selectedValue != undefined) {
        if (isNumeric(props.attributes.id)) {
          if (selectedValue != props.attributes.id) {
            console.log('trigger change: ', props.attributes.id);
            jQuery(selectBox).val(props.attributes.id).trigger('change');
          }
        } else {
          console.log('trigger change 0');
          jQuery(selectBox).val(0).trigger('change');
        }
      }

      jQuery(selectBox).on('change', function () {
        const selectedValue = jQuery(this).val();
        console.log('onChange selected value: ', selectedValue);

        if (selectedValue != null) {
          var shortcode = '[irp posts="' + selectedValue +'"]';
          if (selectedValue == '[irp]' || selectedValue == 0) {
            shortcode = '[irp]';
          }
          props.attributes.id = selectedValue;
          if (props.attributes.shortcode != shortcode) {
            props.setAttributes({shortcode: shortcode});
          }
        }
      });

      // Initialize select2 first
      jQuery(selectBox).select2();

      // Then, initialize the ajax configuration
      jQuery(selectBox).select2({
        ajax: {
            url: ajaxurl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
              console.log('do ajax call');
              return {
                  action: 'irp_list_posts',
                  irp_post_type: 'post',
                  q: params.term,
                  page: params.page
              };
            },
            processResults: function (data) {
              data.page = data.page || 1;
              console.log("new results");
              data.items.unshift({text: 'Inline Related Posts', id: 0});
              props.attributes.items = data.items;
              return {
                  results: data.items,
                  more: false
              };
            },
            cache: true
        },
        placeholder: "Type here to search an item...",
        width: '100%'
      });
    } else {
      console.log('selectBox is null');
    }

    // build the JSX
    return el(
      "div",
      {
        className: "irp-shortcode-edit",
        style: {'border' : '2px black solid', 'padding' : '10px'},
      },
      el("h3", {style: {'margin' : '0', 'padding' : '0'}}, "Inline Related Posts"),
      el("details", {style: {'margin' : '0', 'padding' : '4'}},
        el('summary', null, "Tips (click to expand)"),
        el("ul", {style: {'margin' : '0', 'padding' : '0'}}, null),
        el("li", {style: {'margin' : '0', 'padding' : '0'}}, "When first placing the gutenberg block, click outside of the block in order to fill the select box with posts."),
        el("li", {style: {'margin' : '0', 'padding' : '0'}}, "Click on the dropdown to display the search box"),
        el("li", {style: {'margin' : '0', 'padding' : '0'}}, "Use the search box to narrow down the number of posts displayed in the dropdown since at most 100 posts will be displayed."),
      ),
      el('p', {style: {'margin' : '0', 'padding' : '0'}}, "Select post:"),
      el(
        "select",
        {
          className: `irp-post-select-${props.clientId}`,
          value: props.attributes.id,
        },
        el("option", {value: "[irp]"}, 'Inline Related Posts')
      ),
    ); // End return
  }, // End edit()

  save: function (props) {
    // Defines how the block will render on the frontend
    return el(
      "div",
      {
        className: "irp-shortcode",
      },
      props.attributes.shortcode
    );
  }
});

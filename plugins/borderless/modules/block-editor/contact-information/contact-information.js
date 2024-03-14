wp.blocks.registerBlockType('borderless/contact-information',{
    title: 'Borderless Contact Information',
    icon: 'admin-users',
    category: 'widgets',
    keywords: ['borderless', 'contact', 'info', 'information'],
    attributes: {
        alignment: { type: 'string' },
        direction: { type: 'string' },
        iconColor: { type: 'string' },
        textColor: { type: 'string' },
        companyName: { type: 'string' },
        companyOpeningHours: {type: 'string' },
        companyEmail: {type: 'string' },
        companyPhone: { type: 'string' },
        companyAddress: {type: 'string' },
    },
    edit: function(props) {

        function updateAlignment(event){ props.setAttributes ({ alignment: event.target.value }) }
        function updateDirection(event){ props.setAttributes ({ direction: event.target.value }) }
        function updateIconColor(event){ props.setAttributes ({ iconColor: event.target.value }) }
        function updateTextColor(event){ props.setAttributes ({ textColor: event.target.value }) }
        function updateCompanyName(event){ props.setAttributes ({ companyName: event.target.value }) }
        function updateOpeningHours(event){ props.setAttributes ({ companyOpeningHours: event.target.value }) }
        function updatecompanyEmail(event){ props.setAttributes ({ companyEmail: event.target.value }) }
        function updatecompanyPhone(event){ props.setAttributes ({ companyPhone: event.target.value }) }
        function updatecompanyAddress(event){ props.setAttributes ({ companyAddress: event.target.value }) }

        return React.createElement(
          "div",
          null,
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                for: ""
              },
              "Alignment:"
            ),
            React.createElement(
              "select",
              {
                value: props.attributes.alignment,
                onChange: updateAlignment
              },
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--align-left"
                },
                "Left"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--align-center"
                },
                "Center"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--align-right"
                },
                "Right"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--align-space-between"
                },
                "Space Between"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--align-space-around"
                },
                "Space Around"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--align-space-evenly"
                },
                "Space Evenly"
              )
            )
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                for: ""
              },
              "Direction:"
            ),
            React.createElement(
              "select",
              {
                value: props.attributes.direction,
                onChange: updateDirection
              },
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--direction-row"
                },
                "Row"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--direction-column"
                },
                "Column"
              )
            )
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                for: ""
              },
              "Icon Color:"
            ),
            React.createElement(
              "select",
              {
                value: props.attributes.iconColor,
                onChange: updateIconColor
              },
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--icon-default-color"
                },
                "Select"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--icon-light-mode"
                },
                "Light Mode"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--icon-dark-mode"
                },
                "Dark Mode"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--icon-primary-color"
                },
                "Primary Color"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--icon-secondary-color"
                },
                "Secondary Color"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--icon-tertiary-color"
                },
                "Tertiary Color"
              )
            )
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                for: ""
              },
              "Text Color:"
            ),
            React.createElement(
              "select",
              {
                value: props.attributes.textColor,
                onChange: updateTextColor
              },
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--text-default-color"
                },
                "Select"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--text-light-mode"
                },
                "Light Mode"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--text-dark-mode"
                },
                "Dark Mode"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--text-primary-color"
                },
                "Primary Color"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--text-secondary-color"
                },
                "Secondary Color"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-contact-info--text-tertiary-color"
                },
                "Tertiary Color"
              )
            )
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                class: "borderless-company-information__label"
              },
              "Company Name:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.companyName,
              onChange: updateCompanyName,
              placeholder: "company name"
            })
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                class: "borderless-company-information__label"
              },
              "Company Opening Hours:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.companyOpeningHours,
              onChange: updateOpeningHours,
              placeholder: "company opening hours"
            })
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                class: "borderless-company-information__label"
              },
              "Company Email:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.companyEmail,
              onChange: updatecompanyEmail,
              placeholder: "company email"
            })
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                class: "borderless-company-information__label"
              },
              "Company Phone:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.companyPhone,
              onChange: updatecompanyPhone,
              placeholder: "company phone"
            })
          ),
          React.createElement(
            "div",
            {
              class: "borderless-company-information__item"
            },
            React.createElement(
              "label",
              {
                class: "borderless-company-information__label"
              },
              "Company Address:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.companyAddress,
              onChange: updatecompanyAddress,
              placeholder: "company address"
            })
          )
        );
    },
    save: function(props) {

        const classes = [
          "borderless-company-information",
          props.attributes.alignment,
          props.attributes.direction,
          props.attributes.iconColor,
          props.attributes.textColor
        ];

        return React.createElement(
          "div",
          {
            class: classes.join(" ")
          },
          props.attributes.companyName
            ? React.createElement(
                "div",
                {
                  class: "borderless-company-information__item"
                },
                React.createElement(
                  "h3",
                  null,
                  props.attributes.companyName
                )
              )
            : "",
          props.attributes.companyOpeningHours
            ? React.createElement(
                "div",
                {
                  class: "borderless-company-information__item"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-icon-app__clock",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z"
                  })
                ),
                React.createElement(
                  "span",
                  null,
                  props.attributes.companyOpeningHours
                )
              )
            : "",
          props.attributes.companyEmail
            ? React.createElement(
                "div",
                {
                  class: "borderless-company-information__item"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-icon-app__mail",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"
                  })
                ),
                React.createElement(
                  "a",
                  {
                    href: "mailto:{props.attributes.companyEmail}"
                  },
                  props.attributes.companyEmail
                )
              )
            : "",
          props.attributes.companyPhone
            ? React.createElement(
                "div",
                {
                  class: "borderless-company-information__item"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-icon-app__phone",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"
                  })
                ),
                React.createElement(
                  "a",
                  {
                    href: "tel:{props.attributes.companyPhone}"
                  },
                  props.attributes.companyPhone
                )
              )
            : "",
          props.attributes.companyAddress
            ? React.createElement(
                "div",
                {
                  class:
                    "borderless-company-information__item borderless-company-information__item-address"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-icon-app__location",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 384 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"
                  })
                ),
                props.attributes.companyAddress
                  ? React.createElement(
                      "span",
                      null,
                      props.attributes.companyAddress
                    )
                  : ""
              )
            : ""
        );
        
    }
})
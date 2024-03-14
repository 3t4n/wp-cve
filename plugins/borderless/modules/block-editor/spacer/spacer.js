wp.blocks.registerBlockType('borderless/spacer',{
    title: 'Borderless Spacer',
    icon: 'move',
    category: 'widgets',
    keywords: ['borderless', 'spacer', 'space'],
    attributes: {
        unit: { type: 'string' },
        space: { type: 'string' },
    },
    edit: function(props) {

        function updateUnit(event){ props.setAttributes ({ unit: event.target.value }) }
        function updateSpace(event){ props.setAttributes ({ space: event.target.value }) }


        return React.createElement(
          "div",
          null,
          React.createElement(
            "div",
            {
              class: "borderless-backend-input__item"
            },
            React.createElement(
              "label",
              {
                for: ""
              },
              "Unit:"
            ),
            React.createElement(
              "select",
              {
                value: props.attributes.unit,
                onChange: updateUnit
              },
              React.createElement(
                "option",
                {
                  value: "px"
                },
                "PX"
              ),
              React.createElement(
                "option",
                {
                  value: "em"
                },
                "EM"
              ),
              React.createElement(
                "option",
                {
                  value: "rem"
                },
                "REM"
              ),
              React.createElement(
                "option",
                {
                  value: "vw"
                },
                "VW"
              ),
              React.createElement(
                "option",
                {
                  value: "vh"
                },
                "VH"
              )
            )
          ),
          React.createElement(
            "div",
            {
              class: "borderless-backend-input__item"
            },
            React.createElement(
              "label",
              {
                class: "borderless-backend-input__label"
              },
              "Space:"
            ),
            React.createElement("input", {
              type: "number",
              value: props.attributes.space,
              onChange: updateSpace
            })
          )
        );
    },
    save: function(props) {

        const classes = [
          "borderless-block-spacer"
        ];

        const styles = {
          height: props.attributes.space + props.attributes.unit
        };

        return React.createElement("div", {
          class: classes.join(" "),
          style: styles
        });
        
    }
})
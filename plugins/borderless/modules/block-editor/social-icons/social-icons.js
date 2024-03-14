wp.blocks.registerBlockType('borderless/social-icons',{
    title: 'Borderless Social Icons',
    icon: 'share',
    category: 'widgets',
    keywords: ['borderless', 'social', 'icons'],
    attributes: {
        alignment: { type: 'string' },
        direction: { type: 'string' },
        color: { type: 'string' },
        behance: {type: 'string' },
        dribbble: {type: 'string' },
        facebook: {type: 'string' },
        github: {type: 'string' },
        instagram: {type: 'string' },
        linkedin: {type: 'string' },
        medium: {type: 'string' },
        pinterest: {type: 'string' },
        reddit: {type: 'string' },
        rss: {type: 'string' },
        snapchat: {type: 'string' },
        tiktok: {type: 'string' },
        twitch: {type: 'string' },
        twitter: {type: 'string' },
        vimeo: {type: 'string' },
        wechat: {type: 'string' },
        whatsapp: {type: 'string' },
        youtube: {type: 'string' },
    },
    edit: function(props) {

        function updateAlignment(event){ props.setAttributes ({ alignment: event.target.value }) }
        function updateDirection(event){ props.setAttributes ({ direction: event.target.value }) }
        function updateColor(event){ props.setAttributes ({ color: event.target.value }) }
        function updateBehance(event){ props.setAttributes ({ behance: event.target.value }) }
        function updateDribbble(event){ props.setAttributes ({ dribbble: event.target.value }) }
        function updateFacebook(event){ props.setAttributes ({ facebook: event.target.value }) }
        function updateGithub(event){ props.setAttributes ({ github: event.target.value }) }
        function updateInstagram(event){ props.setAttributes ({ instagram: event.target.value }) }
        function updateLinkedin(event){ props.setAttributes ({ linkedin: event.target.value }) }
        function updateMedium(event){ props.setAttributes ({ medium: event.target.value }) }
        function updatePinterest(event){ props.setAttributes ({ pinterest: event.target.value }) }
        function updateReddit(event){ props.setAttributes ({ reddit: event.target.value }) }
        function updateRSS(event){ props.setAttributes ({ rss: event.target.value }) }
        function updateSnapchat(event){ props.setAttributes ({ snapchat: event.target.value }) }
        function updateTiktok(event){ props.setAttributes ({ tiktok: event.target.value }) }
        function updateTwitch(event){ props.setAttributes ({ twitch: event.target.value }) }
        function updateTwitter(event){ props.setAttributes ({ twitter: event.target.value }) }
        function updateVimeo(event){ props.setAttributes ({ vimeo: event.target.value }) }
        function updateWechat(event){ props.setAttributes ({ wechat: event.target.value }) }
        function updateWhatsapp(event){ props.setAttributes ({ whatsapp: event.target.value }) }
        function updateYoutube(event){ props.setAttributes ({ youtube: event.target.value }) }


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
                  value: "borderless-social-icons--align-left"
                },
                "Left"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--align-center"
                },
                "Center"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--align-right"
                },
                "Right"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--align-space-between"
                },
                "Space Between"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--align-space-around"
                },
                "Space Around"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--align-space-evenly"
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
                  value: "borderless-social-icons--direction-row"
                },
                "Row"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--direction-column"
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
              "Color:"
            ),
            React.createElement(
              "select",
              {
                value: props.attributes.color,
                onChange: updateColor
              },
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--light-mode"
                },
                "Light Mode"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--dark-mode"
                },
                "Dark Mode"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--primary-color"
                },
                "Primary Color"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--secondary-color"
                },
                "Secondary Color"
              ),
              React.createElement(
                "option",
                {
                  value: "borderless-social-icons--tertiary-color"
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
              "Behance:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.behance,
              onChange: updateBehance,
              placeholder: "Behance URL Profile"
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
              "Dribbble:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.dribbble,
              onChange: updateDribbble,
              placeholder: "Dribbble URL Profile"
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
              "Facebook:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.facebook,
              onChange: updateFacebook,
              placeholder: "Facebook URL Profile"
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
              "Github:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.github,
              onChange: updateGithub,
              placeholder: "Github URL Profile"
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
              "Instagram:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.instagram,
              onChange: updateInstagram,
              placeholder: "Instagram URL Profile"
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
              "Linkedin:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.linkedin,
              onChange: updateLinkedin,
              placeholder: "Linkedin URL Profile"
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
              "Medium:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.medium,
              onChange: updateMedium,
              placeholder: "Medium URL Profile"
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
              "Pinterest:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.pinterest,
              onChange: updatePinterest,
              placeholder: "Pinterest URL Profile"
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
              "Reddit:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.reddit,
              onChange: updateReddit,
              placeholder: "Reddit URL Profile"
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
              "RSS:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.rss,
              onChange: updateRSS,
              placeholder: "RSS URL Profile"
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
              "Snapchat:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.snapchat,
              onChange: updateSnapchat,
              placeholder: "Snapchat URL Profile"
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
              "Tiktok:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.tiktok,
              onChange: updateTiktok,
              placeholder: "Tiktok URL Profile"
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
              "Twitch:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.twitch,
              onChange: updateTwitch,
              placeholder: "Twitch URL Profile"
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
              "Twitter:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.twitter,
              onChange: updateTwitter,
              placeholder: "Twitter URL Profile"
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
              "Vimeo:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.vimeo,
              onChange: updateVimeo,
              placeholder: "Vimeo URL Profile"
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
              "Wechat:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.wechat,
              onChange: updateWechat,
              placeholder: "Wechat URL Profile"
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
              "Whatsapp:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.whatsapp,
              onChange: updateWhatsapp,
              placeholder: "Whatsapp URL Profile"
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
              "Youtube:"
            ),
            React.createElement("input", {
              type: "text",
              value: props.attributes.youtube,
              onChange: updateYoutube,
              placeholder: "Youtube URL Profile"
            })
          )
        );
    },
    save: function(props) {

        const classes = [
          "borderless-social-icons",
          props.attributes.alignment,
          props.attributes.direction,
          props.attributes.color
        ];

        return React.createElement(
          "div",
          {
            class: classes.join(" ")
          },
          props.attributes.behance
            ? React.createElement(
                "a",
                {
                  href: props.attributes.behance,
                  target: "_blank",
                  title: "Behance",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__behance",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 576 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M232 237.2c31.8-15.2 48.4-38.2 48.4-74 0-70.6-52.6-87.8-113.3-87.8H0v354.4h171.8c64.4 0 124.9-30.9 124.9-102.9 0-44.5-21.1-77.4-64.7-89.7zM77.9 135.9H151c28.1 0 53.4 7.9 53.4 40.5 0 30.1-19.7 42.2-47.5 42.2h-79v-82.7zm83.3 233.7H77.9V272h84.9c34.3 0 56 14.3 56 50.6 0 35.8-25.9 47-57.6 47zm358.5-240.7H376V94h143.7v34.9zM576 305.2c0-75.9-44.4-139.2-124.9-139.2-78.2 0-131.3 58.8-131.3 135.8 0 79.9 50.3 134.7 131.3 134.7 61.3 0 101-27.6 120.1-86.3H509c-6.7 21.9-34.3 33.5-55.7 33.5-41.3 0-63-24.2-63-65.3h185.1c.3-4.2.6-8.7.6-13.2zM390.4 274c2.3-33.7 24.7-54.8 58.5-54.8 35.4 0 53.2 20.8 56.2 54.8H390.4z"
                  })
                )
              )
            : "",
          props.attributes.dribbble
            ? React.createElement(
                "a",
                {
                  href: props.attributes.dribbble,
                  target: "_blank",
                  title: "Dribbble",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__dribbble",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M256 8C119.252 8 8 119.252 8 256s111.252 248 248 248 248-111.252 248-248S392.748 8 256 8zm163.97 114.366c29.503 36.046 47.369 81.957 47.835 131.955-6.984-1.477-77.018-15.682-147.502-6.818-5.752-14.041-11.181-26.393-18.617-41.614 78.321-31.977 113.818-77.482 118.284-83.523zM396.421 97.87c-3.81 5.427-35.697 48.286-111.021 76.519-34.712-63.776-73.185-116.168-79.04-124.008 67.176-16.193 137.966 1.27 190.061 47.489zm-230.48-33.25c5.585 7.659 43.438 60.116 78.537 122.509-99.087 26.313-186.36 25.934-195.834 25.809C62.38 147.205 106.678 92.573 165.941 64.62zM44.17 256.323c0-2.166.043-4.322.108-6.473 9.268.19 111.92 1.513 217.706-30.146 6.064 11.868 11.857 23.915 17.174 35.949-76.599 21.575-146.194 83.527-180.531 142.306C64.794 360.405 44.17 310.73 44.17 256.323zm81.807 167.113c22.127-45.233 82.178-103.622 167.579-132.756 29.74 77.283 42.039 142.053 45.189 160.638-68.112 29.013-150.015 21.053-212.768-27.882zm248.38 8.489c-2.171-12.886-13.446-74.897-41.152-151.033 66.38-10.626 124.7 6.768 131.947 9.055-9.442 58.941-43.273 109.844-90.795 141.978z"
                  })
                )
              )
            : "",
          props.attributes.facebook
            ? React.createElement(
                "a",
                {
                  href: props.attributes.facebook,
                  target: "_blank",
                  title: "Facebook",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__facebook",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 320 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                  })
                )
              )
            : "",
          props.attributes.github
            ? React.createElement(
                "a",
                {
                  href: props.attributes.github,
                  target: "_blank",
                  title: "Github",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__github",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 496 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"
                  })
                )
              )
            : "",
          props.attributes.instagram
            ? React.createElement(
                "a",
                {
                  href: props.attributes.instagram,
                  target: "_blank",
                  title: "Github",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__instagram",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 448 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
                  })
                )
              )
            : "",
          props.attributes.linkedin
            ? React.createElement(
                "a",
                {
                  href: props.attributes.linkedin,
                  target: "_blank",
                  title: "Linkedin",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__linkedin",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 448 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"
                  })
                )
              )
            : "",
          props.attributes.medium
            ? React.createElement(
                "a",
                {
                  href: props.attributes.medium,
                  target: "_blank",
                  title: "Medium",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__medium",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M71.5 142.3c.6-5.9-1.7-11.8-6.1-15.8L20.3 72.1V64h140.2l108.4 237.7L364.2 64h133.7v8.1l-38.6 37c-3.3 2.5-5 6.7-4.3 10.8v272c-.7 4.1 1 8.3 4.3 10.8l37.7 37v8.1H307.3v-8.1l39.1-37.9c3.8-3.8 3.8-5 3.8-10.8V171.2L241.5 447.1h-14.7L100.4 171.2v184.9c-1.1 7.8 1.5 15.6 7 21.2l50.8 61.6v8.1h-144v-8L65 377.3c5.4-5.6 7.9-13.5 6.5-21.2V142.3z"
                  })
                )
              )
            : "",
          props.attributes.pinterest
            ? React.createElement(
                "a",
                {
                  href: props.attributes.pinterest,
                  target: "_blank",
                  title: "Pinterest",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__pinterest",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 496 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"
                  })
                )
              )
            : "",
          props.attributes.reddit
            ? React.createElement(
                "a",
                {
                  href: props.attributes.reddit,
                  target: "_blank",
                  title: "Reddit",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__reddit",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M201.5 305.5c-13.8 0-24.9-11.1-24.9-24.6 0-13.8 11.1-24.9 24.9-24.9 13.6 0 24.6 11.1 24.6 24.9 0 13.6-11.1 24.6-24.6 24.6zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-132.3-41.2c-9.4 0-17.7 3.9-23.8 10-22.4-15.5-52.6-25.5-86.1-26.6l17.4-78.3 55.4 12.5c0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.3 24.9-24.9s-11.1-24.9-24.9-24.9c-9.7 0-18 5.8-22.1 13.8l-61.2-13.6c-3-.8-6.1 1.4-6.9 4.4l-19.1 86.4c-33.2 1.4-63.1 11.3-85.5 26.8-6.1-6.4-14.7-10.2-24.1-10.2-34.9 0-46.3 46.9-14.4 62.8-1.1 5-1.7 10.2-1.7 15.5 0 52.6 59.2 95.2 132 95.2 73.1 0 132.3-42.6 132.3-95.2 0-5.3-.6-10.8-1.9-15.8 31.3-16 19.8-62.5-14.9-62.5zM302.8 331c-18.2 18.2-76.1 17.9-93.6 0-2.2-2.2-6.1-2.2-8.3 0-2.5 2.5-2.5 6.4 0 8.6 22.8 22.8 87.3 22.8 110.2 0 2.5-2.2 2.5-6.1 0-8.6-2.2-2.2-6.1-2.2-8.3 0zm7.7-75c-13.6 0-24.6 11.1-24.6 24.9 0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.1 24.9-24.6 0-13.8-11-24.9-24.9-24.9z"
                  })
                )
              )
            : "",
          props.attributes.rss
            ? React.createElement(
                "a",
                {
                  href: props.attributes.rss,
                  target: "_blank",
                  title: "RSS",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__rss",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 448 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M128.081 415.959c0 35.369-28.672 64.041-64.041 64.041S0 451.328 0 415.959s28.672-64.041 64.041-64.041 64.04 28.673 64.04 64.041zm175.66 47.25c-8.354-154.6-132.185-278.587-286.95-286.95C7.656 175.765 0 183.105 0 192.253v48.069c0 8.415 6.49 15.472 14.887 16.018 111.832 7.284 201.473 96.702 208.772 208.772.547 8.397 7.604 14.887 16.018 14.887h48.069c9.149.001 16.489-7.655 15.995-16.79zm144.249.288C439.596 229.677 251.465 40.445 16.503 32.01 7.473 31.686 0 38.981 0 48.016v48.068c0 8.625 6.835 15.645 15.453 15.999 191.179 7.839 344.627 161.316 352.465 352.465.353 8.618 7.373 15.453 15.999 15.453h48.068c9.034-.001 16.329-7.474 16.005-16.504z"
                  })
                )
              )
            : "",
          props.attributes.snapchat
            ? React.createElement(
                "a",
                {
                  href: props.attributes.snapchat,
                  target: "_blank",
                  title: "Snapchat",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__snapchat",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M510.846 392.673c-5.211 12.157-27.239 21.089-67.36 27.318-2.064 2.786-3.775 14.686-6.507 23.956-1.625 5.566-5.623 8.869-12.128 8.869l-.297-.005c-9.395 0-19.203-4.323-38.852-4.323-26.521 0-35.662 6.043-56.254 20.588-21.832 15.438-42.771 28.764-74.027 27.399-31.646 2.334-58.025-16.908-72.871-27.404-20.714-14.643-29.828-20.582-56.241-20.582-18.864 0-30.736 4.72-38.852 4.72-8.073 0-11.213-4.922-12.422-9.04-2.703-9.189-4.404-21.263-6.523-24.13-20.679-3.209-67.31-11.344-68.498-32.15a10.627 10.627 0 0 1 8.877-11.069c69.583-11.455 100.924-82.901 102.227-85.934.074-.176.155-.344.237-.515 3.713-7.537 4.544-13.849 2.463-18.753-5.05-11.896-26.872-16.164-36.053-19.796-23.715-9.366-27.015-20.128-25.612-27.504 2.437-12.836 21.725-20.735 33.002-15.453 8.919 4.181 16.843 6.297 23.547 6.297 5.022 0 8.212-1.204 9.96-2.171-2.043-35.936-7.101-87.29 5.687-115.969C158.122 21.304 229.705 15.42 250.826 15.42c.944 0 9.141-.089 10.11-.089 52.148 0 102.254 26.78 126.723 81.643 12.777 28.65 7.749 79.792 5.695 116.009 1.582.872 4.357 1.942 8.599 2.139 6.397-.286 13.815-2.389 22.069-6.257 6.085-2.846 14.406-2.461 20.48.058l.029.01c9.476 3.385 15.439 10.215 15.589 17.87.184 9.747-8.522 18.165-25.878 25.018-2.118.835-4.694 1.655-7.434 2.525-9.797 3.106-24.6 7.805-28.616 17.271-2.079 4.904-1.256 11.211 2.46 18.748.087.168.166.342.239.515 1.301 3.03 32.615 74.46 102.23 85.934 6.427 1.058 11.163 7.877 7.725 15.859z"
                  })
                )
              )
            : "",
          props.attributes.tiktok
            ? React.createElement(
                "a",
                {
                  href: props.attributes.tiktok,
                  target: "_blank",
                  title: "Tiktok",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__tiktok",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "-32 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "m432.734375 112.464844c-53.742187 0-97.464844-43.722656-97.464844-97.464844 0-8.285156-6.714843-15-15-15h-80.335937c-8.28125 0-15 6.714844-15 15v329.367188c0 31.59375-25.707032 57.296874-57.300782 57.296874s-57.296874-25.703124-57.296874-57.296874c0-31.597657 25.703124-57.300782 57.296874-57.300782 8.285157 0 15-6.714844 15-15v-80.335937c0-8.28125-6.714843-15-15-15-92.433593 0-167.632812 75.203125-167.632812 167.636719 0 92.433593 75.199219 167.632812 167.632812 167.632812 92.433594 0 167.636719-75.199219 167.636719-167.632812v-145.792969c29.851563 15.917969 63.074219 24.226562 97.464844 24.226562 8.285156 0 15-6.714843 15-15v-80.335937c0-8.28125-6.714844-15-15-15zm0 0"
                  })
                )
              )
            : "",
          props.attributes.twitch
            ? React.createElement(
                "a",
                {
                  href: props.attributes.twitch,
                  target: "_blank",
                  title: "Twitch",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__twitch",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M391.17,103.47H352.54v109.7h38.63ZM285,103H246.37V212.75H285ZM120.83,0,24.31,91.42V420.58H140.14V512l96.53-91.42h77.25L487.69,256V0ZM449.07,237.75l-77.22,73.12H294.61l-67.6,64v-64H140.14V36.58H449.07Z"
                  })
                )
              )
            : "",
          props.attributes.twitter
            ? React.createElement(
                "a",
                {
                  href: props.attributes.twitter,
                  target: "_blank",
                  title: "Twitter",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__twitter",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 512 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"
                  })
                )
              )
            : "",
          props.attributes.vimeo
            ? React.createElement(
                "a",
                {
                  href: props.attributes.vimeo,
                  target: "_blank",
                  title: "Vimeo",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__vimeo",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 448 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M447.8 153.6c-2 43.6-32.4 103.3-91.4 179.1-60.9 79.2-112.4 118.8-154.6 118.8-26.1 0-48.2-24.1-66.3-72.3C100.3 250 85.3 174.3 56.2 174.3c-3.4 0-15.1 7.1-35.2 21.1L0 168.2c51.6-45.3 100.9-95.7 131.8-98.5 34.9-3.4 56.3 20.5 64.4 71.5 28.7 181.5 41.4 208.9 93.6 126.7 18.7-29.6 28.8-52.1 30.2-67.6 4.8-45.9-35.8-42.8-63.3-31 22-72.1 64.1-107.1 126.2-105.1 45.8 1.2 67.5 31.1 64.9 89.4z"
                  })
                )
              )
            : "",
          props.attributes.wechat
            ? React.createElement(
                "a",
                {
                  href: props.attributes.wechat,
                  target: "_blank",
                  title: "Wechat",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__wechat",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 576 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M385.2 167.6c6.4 0 12.6.3 18.8 1.1C387.4 90.3 303.3 32 207.7 32 100.5 32 13 104.8 13 197.4c0 53.4 29.3 97.5 77.9 131.6l-19.3 58.6 68-34.1c24.4 4.8 43.8 9.7 68.2 9.7 6.2 0 12.1-.3 18.3-.8-4-12.9-6.2-26.6-6.2-40.8-.1-84.9 72.9-154 165.3-154zm-104.5-52.9c14.5 0 24.2 9.7 24.2 24.4 0 14.5-9.7 24.2-24.2 24.2-14.8 0-29.3-9.7-29.3-24.2.1-14.7 14.6-24.4 29.3-24.4zm-136.4 48.6c-14.5 0-29.3-9.7-29.3-24.2 0-14.8 14.8-24.4 29.3-24.4 14.8 0 24.4 9.7 24.4 24.4 0 14.6-9.6 24.2-24.4 24.2zM563 319.4c0-77.9-77.9-141.3-165.4-141.3-92.7 0-165.4 63.4-165.4 141.3S305 460.7 397.6 460.7c19.3 0 38.9-5.1 58.6-9.9l53.4 29.3-14.8-48.6C534 402.1 563 363.2 563 319.4zm-219.1-24.5c-9.7 0-19.3-9.7-19.3-19.6 0-9.7 9.7-19.3 19.3-19.3 14.8 0 24.4 9.7 24.4 19.3 0 10-9.7 19.6-24.4 19.6zm107.1 0c-9.7 0-19.3-9.7-19.3-19.6 0-9.7 9.7-19.3 19.3-19.3 14.5 0 24.4 9.7 24.4 19.3.1 10-9.9 19.6-24.4 19.6z"
                  })
                )
              )
            : "",
          props.attributes.whatsapp
            ? React.createElement(
                "a",
                {
                  href: props.attributes.whatsapp,
                  target: "_blank",
                  title: "Whatsapp",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__whatsapp",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 448 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"
                  })
                )
              )
            : "",
          props.attributes.youtube
            ? React.createElement(
                "a",
                {
                  href: props.attributes.youtube,
                  target: "_blank",
                  title: "Youtube",
                  class: "borderless-social-icon"
                },
                React.createElement(
                  "svg",
                  {
                    class: "borderless-social-icon-svg__youtube",
                    xmlns: "http://www.w3.org/2000/svg",
                    viewBox: "0 0 576 512"
                  },
                  React.createElement("path", {
                    fill: "currentColor",
                    d: "M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"
                  })
                )
              )
            : ""
        );
        
    }
})
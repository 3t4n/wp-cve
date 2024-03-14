(function (blocks, editor, components, i18n, element) {
  // Load Components
  var __ = i18n.__
  var el = element.createElement
  var Button = components.Button

  const widgetIcon = el('svg',
    {
      class: 'organic-widget-area-icon',
      width: 24,
      height: 24
    },
    el('g', {},
      el('path',
        {
          fill: '#1e1e1e',
          d: 'M12 8c-2.206 0-4 1.794-4 4s1.794 4 4 4c2.206 0 4-1.794 4-4s-1.794-4-4-4zM12 15c-1.653 0-3-1.347-3-3s1.347-3 3-3 3 1.347 3 3-1.347 3-3 3z'
        }
      ),
      el('path',
        {
          fill: '#1e1e1e',
          d: 'M12 10c-1.103 0-2 0.897-2 2s0.897 2 2 2c1.103 0 2-0.897 2-2s-0.897-2-2-2zM12 13c-0.55 0-1-0.45-1-1s0.45-1 1-1c0.55 0 1 0.45 1 1s-0.45 1-1 1z'
        }
      ),
      el('path',
        {
          fill: '#1e1e1e',
          d: 'M22 9h-1.512c-0.106-0.3-0.228-0.594-0.366-0.881l1.069-1.069c0.378-0.378 0.584-0.881 0.584-1.416s-0.209-1.038-0.584-1.416l-1.419-1.419c-0.775-0.775-2.037-0.778-2.819-0.009l-1.091 1.078c-0.281-0.134-0.572-0.253-0.863-0.356v-1.512c0-1.103-0.897-2-2-2h-2c-1.103 0-2 0.897-2 2v1.512c-0.3 0.106-0.594 0.228-0.881 0.366l-1.069-1.069c-0.378-0.378-0.881-0.584-1.416-0.584 0 0 0 0 0 0-0.534 0-1.038 0.209-1.416 0.584l-1.416 1.416c-0.378 0.378-0.584 0.881-0.584 1.416s0.209 1.038 0.584 1.416l1.069 1.069c-0.138 0.288-0.259 0.581-0.366 0.881h-1.506c-1.103 0-2 0.897-2 2v2c0 1.103 0.897 2 2 2h1.512c0.106 0.3 0.228 0.594 0.366 0.881l-1.069 1.069c-0.378 0.378-0.584 0.881-0.584 1.416s0.209 1.038 0.584 1.416l1.416 1.416c0.781 0.781 2.050 0.781 2.828 0l1.069-1.069c0.288 0.138 0.581 0.259 0.881 0.366v1.5c0 1.103 0.897 2 2 2h2c1.103 0 2-0.897 2-2v-1.512c0.3-0.106 0.594-0.228 0.881-0.366l1.069 1.069c0.378 0.378 0.881 0.584 1.416 0.584s1.038-0.209 1.416-0.584l1.419-1.419c0.775-0.775 0.778-2.041 0.009-2.819l-1.078-1.091c0.134-0.281 0.253-0.572 0.356-0.863h1.509c1.103 0 2-0.897 2-2v-2c0-1.103-0.897-2-2-2zM22 13h-2.253c-0.456 0-0.856 0.309-0.969 0.75-0.156 0.612-0.413 1.231-0.741 1.791-0.228 0.391-0.166 0.884 0.153 1.206l1.591 1.613-1.419 1.419-1.594-1.594c-0.322-0.322-0.822-0.387-1.216-0.153-0.563 0.334-1.172 0.584-1.806 0.747-0.441 0.113-0.75 0.513-0.75 0.969v2.253h-2v-2.253c0-0.456-0.309-0.856-0.75-0.969-0.634-0.162-1.241-0.416-1.806-0.747-0.159-0.094-0.334-0.141-0.509-0.141-0.259 0-0.516 0.1-0.706 0.294l-1.594 1.594-1.416-1.416 1.594-1.594c0.322-0.322 0.387-0.822 0.153-1.216-0.334-0.563-0.584-1.172-0.747-1.806-0.113-0.441-0.513-0.75-0.969-0.75l-2.247 0.003v-2h2.253c0.456 0 0.856-0.309 0.969-0.75 0.162-0.634 0.416-1.241 0.747-1.806 0.231-0.394 0.169-0.894-0.153-1.216l-1.594-1.594 1.416-1.416 1.594 1.594c0.322 0.322 0.822 0.387 1.216 0.153 0.563-0.334 1.172-0.584 1.806-0.747 0.441-0.112 0.75-0.513 0.75-0.969v-2.25h2v2.253c0 0.456 0.309 0.856 0.75 0.969 0.612 0.156 1.231 0.412 1.791 0.741 0.391 0.228 0.884 0.166 1.206-0.153l1.613-1.591 1.419 1.419-1.594 1.594c-0.322 0.322-0.387 0.822-0.153 1.216 0.334 0.563 0.584 1.172 0.747 1.806 0.113 0.441 0.513 0.75 0.969 0.75h2.253v1.997z'
        }
      )
    )
  )

  function slugify (string) {
    const a = 'àáâäæãåāăąçćčđďèéêëēėęěğǵḧîïíīįìłḿñńǹňôöòóœøōõőṕŕřßśšşșťțûüùúūǘůűųẃẍÿýžźż·/_,:;'
    const b = 'aaaaaaaaaacccddeeeeeeeegghiiiiiilmnnnnoooooooooprrsssssttuuuuuuuuuwxyyzzz------'
    const p = new RegExp(a.split('').join('|'), 'g')

    return string.toString().toLowerCase()
      .replace(/\s+/g, '-') // Replace spaces with -
      .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
      .replace(/&/g, '-and-') // Replace & with 'and'
      .replace(/[^\w\-]+/g, '') // Remove all non-word characters
      .replace(/\-\-+/g, '-') // Replace multiple - with single -
      .replace(/^-+/, '') // Trim - from start of text
      .replace(/-+$/, '') // Trim - from end of text
  }

  // Register Block.
  const organicWidgetArea = blocks.registerBlockType('organic/widget-area', {
    title: __('Widget Area', 'owa'),
    description: __('Add a Widget Area anywhere in your page/post.', 'owa'),
    icon: widgetIcon,
    category: 'widgets',
    // keywords: ['Organic Widget Area'],
    supports: {
      html: false,
      align: ['wide', 'full']
    },
    attributes: {
      widgetAreaTitle: {
        type: 'string',
        default: ''
      },
      isSaved: {
        type: 'string',
        default: ''
      },
      alignment: {
        type: 'string',
        default: 'none'
      }
    },
    edit: function (props) {
      var className = props.attributes.className
      var widgetAreaTitle = props.attributes.widgetAreaTitle
      var setAttributes = props.setAttributes
      var inputButtonText = __('Save', 'owa')
      var desccontent = __('Enter a name for the Widget Area, then enter the Customizer to add Widgets.', 'owa')

      if (widgetAreaTitle && widgetAreaTitle !== '') {
        inputButtonText = __('Update', 'owa')
        desccontent = __('Enter the Customizer to add Widgets to this area.', 'owa')
      }

      var Header = el('div',
        {
          className: 'owa-setup-header'
        },
        el('div',
          {
            className: 'owa-setup-icon'
          },
          widgetIcon
        ),
        el('h4',
          {
            className: 'owa-setup-title components-placeholder__label'
          },
          __('Widget Area', 'owa')
        ),
        el('p',
          {
            className: 'owa-setup-description'
          },
          desccontent
        )
      )

      var InputButtons = el('div',
        {
          className: 'owa-setup-form components-placeholder__fieldset'
        },
        el('input', {
          value: widgetAreaTitle,
          onChange: function (event) {
            setAttributes({ widgetAreaTitle: event.target.value })
          },
          type: 'text'
        }),
        el(Button,
          {
            isDefault: true,
            onClick: function onClick () {
              jQuery('.editor-post-publish-button,.editor-post-publish-panel__toggle').trigger('click')
            }
          },
          inputButtonText
        )
      )

      if (!window.location.origin) {
        window.location.origin = window.location.protocol + '//' + window.location.hostname + (window.location.port ? ':' + window.location.port : '')
      }

      var customizerLink = window.location.origin + '/wp-admin/customize.php?url=' + encodeURIComponent(wp.data.select('core/editor').getPermalink())
      var customizerBtnTxt = __('Customizer View', 'owa')

      var CustomizerButton = el('div',
        {
          className: 'is-button is-default'
        },
        el(Button,
          { isDefault: true },
          customizerBtnTxt
        )
      )

      if (widgetAreaTitle && widgetAreaTitle !== '') {
        customizerLink = customizerLink + '&autofocus[section]=sidebar-widgets-' + slugify(widgetAreaTitle)
        CustomizerButton = el('div',
          {
            className: 'is-button is-default'
          },
          el(Button, {
            isPrimary: true,
            href: customizerLink
          },
          customizerBtnTxt
          )
        )
      }

      return [
        el('div',
          {
            className: className
          },
          el('div',
            {
              className: 'owa-setup components-placeholder is-large'
            },
            Header,
            InputButtons,
            CustomizerButton
          )
        )
      ]
    },
    save: function (props) {
      return null
    }
  })
})(
  window.wp.blocks,
  window.wp.editor,
  window.wp.components,
  window.wp.i18n,
  window.wp.element
)

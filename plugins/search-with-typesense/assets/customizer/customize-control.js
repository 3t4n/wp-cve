/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

function init () {
  window.wp.customize.bind('ready', function () {

    function postSectionMessage (expand) {
      // window.wp.customize.previewer.preview is not available until both customize and customize.previewer are ready.
      window.wp.customize.previewer.container[0].querySelector('iframe').contentWindow.postMessage({ cmswtOpenSearch: expand }, '*') // Assume ES5 envorinment.
    }

    //this triggers when the customizer preview (the one that contains the iframe) is ready
    wp.customize.previewer.bind('ready', function () {
      setTimeout(function () {
        if (wp.customize.section('typesense_popup').expanded()) {
          postSectionMessage(true)
        }
      }, 800)
    })

    //on section expanded
    wp.customize.section('typesense_popup', function (section) {
      section.expanded.bind(function (isExpanded) {
        if (isExpanded) {
          postSectionMessage(true)
        } else {
          postSectionMessage(false)
        }
      })
    })
  })
}

jQuery(document).ready(function ($) {
  /**
   * Dropdown Select2 Custom Control
   *
   * @author Anthony Hortin <http://maddisondesigns.com>
   * @license http://www.gnu.org/licenses/gpl-2.0.html
   * @link https://github.com/maddisondesigns
   */

  // This function adds the $availabe_post_types values like post,page,book to the HTML as data-id attribute to be used in sortable arguments since select2 only adds the title by default
  function formatState (state) {
    if (!state.id) {
      return state.text
    }
    let $state = $(
      '<span></span>'
    )

    $state.text(state.text)
    $state.attr('data-id', state.id)

    return $state
  }

  $('.customize-control-dropdown-select2').each(function () {

    $('.customize-control-select2').select2({
      allowClear: true,
      templateSelection: formatState // change selected values html
    })

    $('ul.select2-selection__rendered').sortable({
      containment: 'parent',
      update: function () { // on drag on drop of the options get the changed value and set the value and trigger the change.
        let valFromSorter = []
        $('.customize-control-select2').parent().find('ul.select2-selection__rendered').children('li').each(function (i, li) {
          let id = $(li).find('span[data-id]').data('id')
          valFromSorter.push(id)
        })
        valFromSorter = valFromSorter.filter(x => x !== undefined)
        $('.customize-control-dropdown-select2').val(valFromSorter).trigger('change')
      }
    })
  })

  $('.customize-control-select2').on('change', function () {
    var select2Val = $(this).val()
    $(this).parent().find('.customize-control-dropdown-select2').val(select2Val).trigger('change')
  })
})

if (document.readyState !== 'loading') {
  init()
} else {
  document.addEventListener('DOMContentLoaded', init)
}
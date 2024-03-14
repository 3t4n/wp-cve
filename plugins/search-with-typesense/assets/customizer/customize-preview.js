function applySelectiveRefresh () {
  //selective refresh
  window.wp.customize('typesense_customizer_instant_search[search_placeholder]', function (value) {
    value.bind(function (to) {
      document.querySelector('.cmswt-InstantSearchPopup .ais-SearchBox-input').setAttribute('placeholder', to)
    })
  })

  // Instant search popup highlight color 
  window.wp.customize('typesense_customizer_instant_search[color]', function (value) {
    value.bind(function (to) {
      let searchPopupHighlights = document.querySelectorAll('.cmswt-InstantSearchPopup .ais-Highlight-highlighted')
      if (searchPopupHighlights.length > 0) {
        searchPopupHighlights.forEach(function (item) {
          item.style['background'] = to
        })
      }

      let searchSnippetHighlights = document.querySelectorAll('.cmswt-InstantSearchPopup .ais-Snippet-highlighted')
      if (searchSnippetHighlights.length > 0) {
        searchSnippetHighlights.forEach(function (item) {
          item.style['background'] = to
        })
      }

      let descHighlights = document.querySelectorAll('.cmswt-InstantSearchPopup .hit-description')
      if (descHighlights.length > 0) {
        descHighlights.forEach(function (item) {
          markEls = item.querySelectorAll('mark')
          markEls.forEach(function (mark) {
            console.log(mark)
            mark.style['background'] = to
          })
        })
      }
    })
  })

  // Instant search popup show/hide sortby
  window.wp.customize('typesense_customizer_instant_search[sort_by]', function (value) {
    value.bind(function (to) {
      let searchPopupSortby = document.querySelectorAll('.cmswt-Sort')
      if (searchPopupSortby.length > 0) {
        searchPopupSortby.forEach(function (item) {
          if ('hide' === to) {
            item.style['display'] = 'none'
          } else {
            item.style['display'] = 'block'
          }
        })
      }
    })
  })

  // Instant search popup show/hide sortby
  window.wp.customize('typesense_customizer_instant_search[filter]', function (value) {
    value.bind(function (to) {
      let searchPopupFilter = document.querySelectorAll('.cmswt-FilterPanel')
      let searchPopupMainPanel = document.querySelectorAll('.cmswt-MainPanel')
      if (searchPopupFilter.length > 0) {
        searchPopupFilter.forEach(function (item) {
          let searchWrapper = item.closest('.cmswt-InstantSearch')

          if ('hide' === to) {
            item.style['display'] = 'none'
            searchWrapper.classList.add('multi-source')
            searchWrapper.classList.remove('single-source')
          } else {
            item.style['display'] = 'block'
            searchWrapper.classList.add('single-source')
            searchWrapper.classList.remove('multi-source')
          }
        })
      }
    })
  })

  // Instant search popup columns
  window.wp.customize('typesense_customizer_instant_search[no_of_cols]', function (value) {
    value.bind(function (to) {
      let listElement = document.querySelector('.cmswt-InstantSearchPopup .ais-Hits-list')

      //could be infinite pagination
      if (listElement === null) {
        listElement = document.querySelector('.cmswt-InstantSearchPopup .ais-InfiniteHits-list')
      }

      //if it's still null abort
      if (listElement !== null) {
        let oldClassName = listElement.className.match(/(cm-col-\d+)|(cm-col-)/)
        let newClassName = oldClassName[0].replace(/(cm-col-\d+)|(cm-col-)/, 'cm-col-' + to)
        oldClassName.forEach(c => {
          if (listElement.classList.contains(c)) {
            listElement.classList.remove(c)
          }
        })
        listElement.classList.add(newClassName)
      }

    })
  })
}

document.addEventListener('DOMContentLoaded', applySelectiveRefresh)

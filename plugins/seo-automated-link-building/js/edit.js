jQuery(function ($) {
  var setPageResult = function (page) {
    if (page) {
      $('[name="page"]').val(page.id);
      $('#page-result').show();
      $('#page-result .text').text(page.title);
      $('#no-page-result').hide();
    } else {
      $('[name="page"]').val('');
      $('#page-result').hide();
      $('#no-page-result').show();
    }
  };

  $('.seo-automated-link-building-form').on('submit', function (e) {
    var $form = $(this);
    var title = $.trim($form.find('[name="title"]').val());
    if (!title) {
      alert(seoAutomatedLinkBuildingEdit.titleWarning);
      e.preventDefault();
      e.stopPropagation();
      return;
    }

    var $urlInput = $form.find('[name="url"]');
    if ($urlInput.is(':visible')) {
      var url = $.trim($form.find('[name="url"]').val());
      if (!url) {
        alert(seoAutomatedLinkBuildingEdit.urlWarning);
        e.preventDefault();
        e.stopPropagation();
        return;
      }
    } else {
      var page = $.trim($form.find('[name="page"]').val());
      if (!page) {
        alert(seoAutomatedLinkBuildingEdit.pageWarning);
        e.preventDefault();
        e.stopPropagation();
        return;
      }
    }

    var keywords = taggle.getTagValues();
    if (!keywords.length) {
      alert(seoAutomatedLinkBuildingEdit.keywordsWarning);
      e.preventDefault();
      e.stopPropagation();
      return;
    }
  });

  $('[name="pagesearch"]').autoComplete({
    minChars: 0,
    source: function (term, response) {
      try {
        xhr.abort();
      } catch (e) {
      }
      xhr = $.post(seoAutomatedLinkBuildingEdit.adminAjax, {
        action: 'seo_automated_link_building_find_pages',
        search: term
      }, function (data) {
        response(data);
      }, 'json');
    },
    renderItem: function (item, search) {
      return $('<div class="autocomplete-suggestion">')
        .attr('data-id', item.id)
        .attr('data-type', item.type)
        .attr('data-title', item.title)
        .text(item.title + ' (' + item.type + ')')
        .wrap('<div>')
        .parent()
        .html();
    },
    onSelect: function (e, term, item) {
      setPageResult(item.data());
      e.preventDefault();
    },
  });

  $('#useCustomUrl').on('click', function (e) {
    e.preventDefault();
    $(this).closest('tr')
      .hide()
      .next()
      .show();
  });

  $('#useWebsitePage').on('click', function (e) {
    e.preventDefault();
    $(this).closest('tr')
      .hide()
      .prev()
      .show();
  });

  $('#page-result-exit').on('click', function () {
    setPageResult(null);
  });

  $('.numToUnlimited').on('click', function () {
    $('input[name=num]').val('-1');
  });

  setPageResult(seoAutomatedLinkBuildingEdit.page);

  // tags support
  var taggle = new Taggle('seo-automated-link-building-keywords', {
    placeholder: seoAutomatedLinkBuildingEdit.replaceHint,
    tags: seoAutomatedLinkBuildingEdit.keywords || [],
    hiddenInputName: 'keywords[]',
    submitKeys: [9, 13],
    delimiter: '\t',
    preserveCase: true,
  });
});

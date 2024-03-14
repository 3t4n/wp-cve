"use strict";

function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : String(i); }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/**
 * AJAX Request Queue
 *
 * - add()
 * - remove()
 * - run()
 * - stop()
 *
 * @since 1.0.0
 */
var DemoImporterAjaxQueue = function () {
  var requests = [];
  return {
    /**
     * Add AJAX request
     *
     */
    add: function add(opt) {
      requests.push(opt);
    },
    /**
     * Remove AJAX request
     *
     */
    remove: function remove(opt) {
      if (jQuery.inArray(opt, requests) > -1) {
        requests.splice($.inArray(opt, requests), 1);
      }
    },
    /**
     * Run / Process AJAX request
     *
     */
    run: function run() {
      var self = this,
        oriSuc;
      if (requests.length) {
        oriSuc = requests[0].complete;
        requests[0].complete = function () {
          if (typeof oriSuc === 'function') oriSuc();
          requests.shift();
          self.run.apply(self, []);
        };
        jQuery.ajax(requests[0]);
      } else {
        self.tid = setTimeout(function () {
          self.run.apply(self, []);
        }, 1000);
      }
    },
    /**
     * Stop AJAX request
     *
     */
    stop: function stop() {
      requests = [];
      clearTimeout(this.tid);
    },
    /**
     * Debugging.
     *
     * @param  {mixed} data Mixed data.
     */
    _log: function _log(data, level) {
      var date = new Date();
      var time = date.toLocaleTimeString();
      var color = '#444';
      if (_typeof(data) == 'object') {
        console.log(data);
      } else {
        console.log(data + ' ' + time);
      }
    }
  };
}();
(function ($, _DemoImporterPlus) {
  var DemoImporterSSEImport = {
    complete: {
      posts: 0,
      media: 0,
      users: 0,
      comments: 0,
      terms: 0
    },
    updateDelta: function updateDelta(type, delta) {
      this.complete[type] += delta;
      var self = this;
      requestAnimationFrame(function () {
        self.render();
      });
    },
    updateProgress: function updateProgress(type, complete, total) {
      var text = complete + '/' + total;
      if ('undefined' !== type && 'undefined' !== text) {
        total = parseInt(total, 10);
        if (0 === total || isNaN(total)) {
          total = 1;
        }
        var percent = parseInt(complete, 10) / total;
        var progress = Math.round(percent * 100) + '%';
        var progress_bar = percent * 100;
        if (progress_bar <= 100) {
          var process_bars = document.getElementsByClassName('di-process');
          for (var i = 0; i < process_bars.length; i++) {
            process_bars[i].value = progress_bar;
          }
          DemoImporterPlus._log_title('Importing Content.. ' + progress, false, false);
        }
      }
    },
    render: function render() {
      var types = Object.keys(this.complete);
      var complete = 0;
      var total = 0;
      for (var i = types.length - 1; i >= 0; i--) {
        var type = types[i];
        this.updateProgress(type, this.complete[type], this.data.count[type]);
        complete += this.complete[type];
        total += this.data.count[type];
      }
      this.updateProgress('total', complete, total);
    }
  };
  DemoImporterPlus = (_DemoImporterPlus = {
    visited_sites_and_pages: [],
    mouseLocation: false,
    action_slug: '',
    import_start_time: '',
    import_end_time: '',
    wpforms_url: '',
    page_settings_flag: true,
    init: function init() {
      DemoImporterPlus._prepareSites();
      DemoImporterPlus._bind();
    },
    get_site_id: function get_site_id() {
      var site_id = $('#site-pages').attr('data-site-id') || '';
      return site_id.replace('id-?', '');
    },
    /**
     * Prepare sites for loading
     *
     * @access private
     *
     * @method _prepareSites
     */
    _prepareSites: function _prepareSites() {
      var $container = $('#demo-import-plus').infiniteScroll({
        path: function path() {
          if (demoImporterVars.allowedDemos.length > 0) {
            return "".concat(demoImporterVars.demoAPIURL, "/wp-json/demoimporterplusapi/v1/dipa-demos?ids=").concat(demoImporterVars.allowedDemos.join(','), "&per_page=100");
          }
          return "".concat(demoImporterVars.demoAPIURL, "/wp-json/demoimporterplusapi/v1/dipa-demos?page=").concat(this.pageIndex);
        },
        // load response as JSON
        responseBody: 'json',
        status: '.loader-wrap',
        history: false
      });
      $container.on('load.infiniteScroll', function (event, body, path, response) {
        $('.di-sites-ldr-placeholder').hide();
        $('.single-site-wrap > .svg-animated-loader').hide();
        var LoadedPages = $container.data('infiniteScroll').loadCount;
        var TotalPages = Math.abs(response.headers.get('x-wp-totalpages'));
        if (LoadedPages <= TotalPages) {
          var theTemplate = wp.template('demo-importer-plus-page-builder-sites');
          // compile body data into HTML
          var data = [];
          body.data.forEach(function (d) {
            data.push(d);
            // demoImporterVars.default_page_builder_sites.push(d);
          });
          var itemsHTML = theTemplate(data);
          // convert HTML string into elements
          var $items = $(itemsHTML);
          // append item elements
          $container.infiniteScroll('appendItems', $items);
          if (LoadedPages == TotalPages) {
            $container.trigger('last.infiniteScroll');
            $container.infiniteScroll('destroy');
            return;
          }
        }
      });
      $container.infiniteScroll('loadNextPage');
    },
    /**
     * Binds events for the Demo Importer Plus.
     *
     * @access private
     * @method _bind
     */
    _bind: function _bind() {
      $('.dip-category-filter-anchor, .dip-category-filter-items').hover(function () {
        DemoImporterPlus.mouseLocation = true;
      }, function () {
        DemoImporterPlus.mouseLocation = false;
      });
      $('body').mouseup(function () {
        if (!DemoImporterPlus.mouseLocation) DemoImporterPlus._closeFilter();
      });
      $(document).on('click', '.dip-category-filter-anchor', DemoImporterPlus._toggleFilter);
      $(document).on('click', '.dip-filter-wrap-checkbox, .dip-category', DemoImporterPlus._filterClick);
      $(document).on('click', '#demo-importer-plus-welcome-form .page-builders li', DemoImporterPlus._change_page_builder);
      $(document).on('click', '#demo-import-plus .demo-import-plus-previewing-site .theme-screenshot, #demo-import-plus .demo-import-plus-previewing-site .theme-name, .demo-importer-plus-search-results .demo-import-plus-previewing-site .theme-screenshot, .demo-importer-plus-search-results .demo-import-plus-previewing-site .theme-name', DemoImporterPlus.show_page_popup_from_search);
      $(document).on('click', '#single-pages .site-single', DemoImporterPlus._change_site_preview_screenshot);
      $(document).on('click', '.demo-importer-previewing-single-pages .back-to-layout', DemoImporterPlus._go_back);
      $(document).on('click', '.site-import-layout-button', DemoImporterPlus.show_page_popup_from_sites);
      $(document).on('click', '.site-import-cancel, .demo-import-sitest-result-prev .close, .demo-import-sites-popup .close', DemoImporterPlus._close_popup);

      // Tooltip.
      $(document).on('click', '.demo-importer-plus-tooltip-icon', DemoImporterPlus._toggle_tooltip);

      // Import Process - page.
      $(document).on('click', '.demo-importer-plus-page-import-popup .site-install-site-button, .preview-page-from-search-result .site-install-site-button', DemoImporterPlus.import_page_process);
      $(document).on('demo-importer-plus-after-site-pages-required-plugins', DemoImporterPlus._page_api_call);

      // Import Process Site
      $(document).on('click', '.site-import-site-button', DemoImporterPlus._show_site_popup);
      $(document).on('click', '.demo-importer-plus-site-import-popup .site-install-site-button', DemoImporterPlus._resetData);
      // Skip & Import.
      $(document).on('demo-importer-plus-after-importer-sites-required-plugins', DemoImporterPlus._start_site_import);
      $(document).on('demo-importer-plus-reset-data', DemoImporterPlus._backup_before_rest_options);
      $(document).on('demo-importer-plus-sites-backup-settings-before-reset-done', DemoImporterPlus._reset_customizer_data);
      $(document).on('demo-importer-plus-sites-reset-customizer-data-done', DemoImporterPlus._reset_site_options);
      $(document).on('demo-importer-plus-sites-reset-site-options-done', DemoImporterPlus._reset_widgets_data);
      $(document).on('demo-importer-plus-sites-reset-widgets-data-done', DemoImporterPlus._reset_terms);
      $(document).on('demo-importer-plus-sites-delete-terms-done', DemoImporterPlus._reset_contact_form7);
      $(document).on('demo-importer-plus-sites-delete-contact-form7-done', DemoImporterPlus._reset_posts);
      $(document).on('demo-importer-plus-sites-reset-data-done', DemoImporterPlus._recheck_backup_options);
      $(document).on('demo-importer-plus-backup-settings-done', DemoImporterPlus._importCustomizerSettings);
      $(document).on('demo-importer-plus-import-customizer-settings-done', DemoImporterPlus._importXML);
      $(document).on('demo-importer-plus-import-xml-done', DemoImporterPlus.import_siteOptions);
      $(document).on('demo-importer-plus-import-options-done', DemoImporterPlus._importWidgets);
      $(document).on('demo-importer-plus-import-widgets-done', DemoImporterPlus._importEnd);

      // Plugin install & activate.
      $(document).on('wp-plugin-installing', DemoImporterPlus._pluginInstalling);
      $(document).on('wp-plugin-install-error', DemoImporterPlus._installError);
      $(document).on('wp-plugin-install-success', DemoImporterPlus._installSuccess);

      // Skip.
      $(document).on('click', '.demo-importer-plus-skip-and-import-step', DemoImporterPlus._remove_skip_and_import_popup);
      var timeout = null;
      $(document).on('input keyup', '.demo-importer-plus-search', function () {
        clearTimeout(timeout);
        timeout = setTimeout(DemoImporterPlus.searchDemo, 1000);
      });
      $(document).on('wp-theme-install-success', DemoImporterPlus._activateTheme);
    },
    searchDemo: function searchDemo(e) {
      $('.demo-importer-plus-search-results').show();
      $('#demo-import-plus').hide();

      // async function to fetch search results from API.
      var search_results = function search_results(search_term, demo_type, demo_cat) {
        var search_results = [];
        $.ajax({
          url: "".concat(demoImporterVars.demoAPIURL, "/wp-json/demoimporterplusapi/v1/dipa-demos/?search_term=").concat(search_term),
          type: 'GET',
          data: {
            search_term: search_term,
            demo_category: demo_cat,
            demo_type: demo_type
          },
          dataType: 'json',
          async: false,
          beforeSend: function beforeSend() {
            $('.di-sites-ldr-placeholder').show();
          },
          success: function success(response) {
            search_results = response.data;
            $('.di-sites-ldr-placeholder').hide();
          }
        });
        return search_results;
      };
      var search_input = $('.demo-importer-plus-search'),
        search_term = $.trim(search_input.val()) || '';
      var $filter_type = $('.dip-filter-wrap-checkbox input[name=dip-radio]:checked').val();
      var $filter_name = $('.dip-category-filter-anchor').attr('data-slug');

      // if ("" == search_term) {
      // 	$("#demo-import-plus").show();
      // 	$(".demo-importer-plus-search-results").html("");
      // 	return;
      // }

      // if search term is not empty fetch results from API.
      if (search_term || $filter_type || $filter_name) {
        var search_results = search_results(search_term, $filter_type, $filter_name);
      } else {
        $('#demo-import-plus').show();
        $('.demo-importer-plus-search-results').html('');
        return;
      }
      // if search term is not empty and results are not empty.
      if (search_results.length > 0) {
        var theTemplate = wp.template('demo-importer-plus-page-builder-sites');
        $('.demo-importer-plus-search-results').html(theTemplate(search_results));
      } else {
        if (search_term.length) {
          $('body').addClass('demo-importer-plus-no-search-result');
        }
        $('.demo-importer-plus-search-results').html(wp.template('demo-importer-plus-no-sites'));
      }
      $('body').removeClass('demo-importer-plus-no-search-result');

      // var sites = $("#demo-importer-plus .demo-importer-plus-theme"),
      // 	titles = $(
      // 		"#demo-importer-plus .demo-importer-plus-theme .theme-name"
      // 	),
      // 	searchTemplateFlag = false,
      // 	items = [];

      // if (search_term.length) {
      // 	search_input.addClass("has-input");
      // 	$("#demo-import-plus").addClass("searching");
      // 	searchTemplateFlag = true;
      // } else {
      // 	search_input.removeClass("has-input");
      // 	$("#demo-import-plus").removeClass("searching");
      // }

      // items =
      // 	DemoImporterPlus._get_sites_and_pages_by_search_term(
      // 		search_term
      // 	);

      // if (!DemoImporterPlus.isEmpty(items)) {
      // 	if (searchTemplateFlag) {
      // 		DemoImporterPlus.add_sites_after_search(items);
      // 	} else {
      // 		DemoImporterPlus.add_sites(items);
      // 	}
      // } else {
      // 	if (search_term.length) {
      // 		$("body").addClass("demo-importer-plus-no-search-result");
      // 	}
      // 	$("#demo-import-plus").html(
      // 		wp.template("demo-importer-plus-no-sites")
      // 	);
      // }
    },
    _closeFilter: function _closeFilter(e) {
      var items = $('.dip-category-filter-items');
      items.removeClass('visible');
    },
    _toggleFilter: function _toggleFilter(e) {
      var items = $('.dip-category-filter-items');
      if (items.hasClass('visible')) {
        items.removeClass('visible');
      } else {
        items.addClass('visible');
      }
    },
    _filterClick: function _filterClick(e) {
      DemoImporterPlus.filter_array = [];
      if ($(this).hasClass('dip-category')) {
        $('.dip-category-filter-anchor').attr('data-slug', $(this).data('slug'));
        $('.dip-category-filter-items').find('.dip-category').removeClass('category-active');
        $(this).addClass('category-active');
        $('.dip-category-filter-anchor').text($(this).text());
        $('.dip-category-filter-anchor').trigger('click');
        $('.demo-importer-plus-search').val('');
      }
      var $filter_name = $('.dip-category-filter-anchor').attr('data-slug');
      if ('' != $filter_name) {
        DemoImporterPlus.filter_array.push($filter_name);
      }
      if ($('.dip-filter-wrap-checkbox input[name=dip-radio]:checked').length) {
        $('.dip-filter-wrap-checkbox input[name=dip-radio]').removeClass('active');
        $('.dip-filter-wrap-checkbox input[name=dip-radio]:checked').addClass('active');
      }
      var $filter_type = $('.dip-filter-wrap-checkbox input[name=dip-radio]:checked').val();
      if ('' != $filter_type) {
        DemoImporterPlus.filter_array.push($filter_type);
      }
      DemoImporterPlus._closeFilter();
      $('.demo-importer-plus-search').trigger('keyup');
    },
    add_sites_after_search: function add_sites_after_search(data) {
      var template = wp.template('demo-importer-plus-page-builder-sites');
      $('#demo-import-plus').html(template(data));
    },
    add_sites: function add_sites(data) {
      var template = wp.template('demo-importer-plus-page-builder-sites');
      $('#demo-import-plus').html(template(data));
    },
    isEmpty: function isEmpty(obj) {
      for (var key in obj) {
        if (obj.hasOwnProperty(key)) return false;
      }
      return true;
    },
    _get_sites_and_pages_by_search_term: function _get_sites_and_pages_by_search_term(search_term) {
      var items = [],
        tags_strings = [];
      search_term = search_term.toLowerCase();
      if (search_term == '' && DemoImporterPlus.filter_array.length == 0) {
        return demoImporterVars.default_page_builder_sites;
      }
      var $filter_type = $('.dip-filter-wrap-checkbox input[name=dip-radio]:checked').val();
      var $filter_name = $('.dip-category-filter-anchor').attr('data-slug');
      for (site_id in demoImporterVars.default_page_builder_sites) {
        var current_site = demoImporterVars.default_page_builder_sites[site_id];
        var text_match = true;
        var free_match = true;
        var category_match = true;
        var match_id = '';
        if ('' != search_term) {
          text_match = false;
        }
        if ('' != $filter_name) {
          category_match = false;
        }
        if ('' != $filter_type) {
          free_match = false;
        }

        // Check in site title.
        if (current_site['site_title']) {
          var site_title = DemoImporterPlus._unescape_lower(current_site['site_title']);
          if (site_title.toLowerCase().includes(search_term)) {
            text_match = true;
            match_id = site_id;
          }
        }

        // Check in site tags.
        if (Object.keys(current_site['site_tags']).length) {
          for (site_tag_id in current_site['site_tags']) {
            var tag_title = current_site['site_tags'][site_tag_id];
            tag_title = DemoImporterPlus._unescape_lower(tag_title.replace('-', ' '));
            if (tag_title.toLowerCase().includes(search_term)) {
              text_match = true;
              match_id = site_id;
            }
          }
        }
        for (filter_id in DemoImporterPlus.filter_array) {
          var slug = DemoImporterPlus.filter_array[filter_id];
          if (slug == 'free' && 'free' == current_site['site_type']) {
            free_match = true;
            match_id = site_id;
          }
          if (slug == 'pro' && 'free' != current_site['site_type']) {
            free_match = true;
            match_id = site_id;
          }
          if (slug != 'free' && slug != 'pro' && undefined != slug) {
            for (cat_id in current_site['site_categories']) {
              if (slug.toLowerCase() == current_site['site_categories'][cat_id].slug) {
                category_match = true;
                match_id = site_id;
              }
            }
          }
        }
        if ('' != match_id) {
          if (text_match && category_match && free_match) {
            items[site_id] = current_site;
            items[site_id]['type'] = 'site';
            items[site_id]['site_id'] = site_id;
            items[site_id]['pages-count'] = undefined != current_site['pages'] ? Object.keys(current_site['pages']).length : 0;
            tags_strings.push(DemoImporterPlus._unescape_lower(current_site['title']));
            for (site_tag_id in current_site['sites-tag']) {
              var tag_title = current_site['sites-tag'][site_tag_id];
              tag_title = DemoImporterPlus._unescape_lower(tag_title.replace('-', ' '));
              if (tag_title.toLowerCase().includes(search_term)) {
                tags_strings.push(DemoImporterPlus._unescape_lower(tag_title));
              }
            }
          }
        }
      }
      if (search_term != '') {
        console.groupCollapsed('Search for "' + search_term + '"');
        DemoImporterPlus._log(items);
        console.groupEnd();
      }
      return items;
    },
    _unescape: function _unescape(input_string) {
      var title = _.unescape(input_string);
      title = title.replace('&#8211;', '-');
      title = title.replace('&#8217;', '\'');
      return title;
    },
    _unescape_lower: function _unescape_lower(input_string) {
      var input_string = DemoImporterPlus._unescape(input_string);
      return input_string.toLowerCase();
    },
    /**
     * Debugging.
     *
     * @param  {mixed} data Mixed data.
     */
    _log: function _log(data, level) {
      var date = new Date();
      var time = date.toLocaleTimeString();
      if (_typeof(data) == 'object') {
        console.log(data);
      } else {
        console.log(data + ' ' + time);
      }
    },
    _toggle_tooltip: function _toggle_tooltip(event) {
      event.preventDefault();
      var tip_id = $(event.currentTarget).data('tip-id') || '';
      if (tip_id && $('#' + tip_id).length) {
        $('#' + tip_id).toggle();
      }
    },
    _log_title: function _log_title(data, append) {
      var markup = '<p>' + data + '</p>';
      if (_typeof(data) == 'object') {
        var markup = '<p>' + JSON.stringify(data) + '</p>';
      }
      var selector = $('.dip-importing-wrap');
      if ($('.current-importing-status-title').length) {
        selector = $('.current-importing-status-title');
      }
      if (append) {
        selector.append(markup);
      } else {
        selector.html(markup);
      }
    },
    /**
     * Import Error Button.
     *
     * @param  {string} data Error message.
     */
    _importFailMessage: function _importFailMessage(message, heading, jqXHR, topContent) {
      heading = heading || 'The import process interrupted';
      var status_code = '';
      if (jqXHR) {
        status_code = jqXHR.status ? parseInt(jqXHR.status) : '';
      }
      if (200 == status_code && demoImporterVars.debug) {
        var output = demoImporterVars.importFailedMessageDueToDebug;
      } else {
        var output = topContent || demoImporterVars.importFailedMessage;
        if (message) {
          output += '<div class="current-importing-status">Error: ' + message + '</div>';
        }
      }
      $('.demo-import-plus-import-content').html(output);
      $('.demo-import-sitest-result-prev .heading h3').html(heading);
      $('.dip-demo-import').removeClass('updating-message installing button-primary').addClass('disabled').text('Import Failed!');
    },
    /**
     * Go back to all sites view
     *
     * @return null
     */
    _go_back: function _go_back(event) {
      event.preventDefault();
      DemoImporterPlus._clean_url_params('demo-importer-site');
      DemoImporterPlus._clean_url_params('demo-importer-page');
      $('.search-filter-wrap').show();
      $('.back-to-layout').hide();
      DemoImporterPlus.close_pages_popup();
    },
    /**
     * Close Popup
     *
     * @access private
     * @method _importDemo
     */
    _close_popup: function _close_popup() {
      DemoImporterPlus._clean_url_params('demo-importer-site');
      DemoImporterPlus._clean_url_params('demo-importer-page');
      $('.preview-page-from-search-result').hide();
      DemoImporterPlus.hide_popup();
    },
    close_pages_popup: function close_pages_popup() {
      $('#demo-import-plus').show();
      $('.di-sites__search-title').show();
      $('#site-pages').hide().html('');
      $('body').removeClass('demo-import-previewing-single-pages');
      $('.demo-import-sitest-result-prev').hide();
      $('#demo-importer-plus .demo-importe-theme').removeClass('current');
      DemoImporterPlus._clean_url_params('demo-importer-site');
      DemoImporterPlus._clean_url_params('demo-importer-page');
      DemoImporterPlus._clean_url_params('license');
    },
    /**
     * Change URL
     */
    _changeAndSetURL: function _changeAndSetURL(url_params) {
      var current_url = window.location.href;
      var current_url_separator = window.location.href.indexOf('?') === -1 ? '?' : '&';
      var new_url = current_url + current_url_separator + decodeURIComponent($.param(url_params));
      DemoImporterPlus._changeURL(new_url);
    },
    /**
     * Clean the URL.
     *
     * @param  string url URL string.
     * @return string     Change the current URL.
     */
    _changeURL: function _changeURL(url) {
      history.pushState(null, '', url);
    },
    /**
     * Get URL param.
     */
    _getParamFromURL: function _getParamFromURL(name, url) {
      if (!url) url = window.location.href;
      name = name.replace(/[\[\]]/g, '\\$&');
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, ' '));
    },
    _clean_url_params: function _clean_url_params(single_param) {
      var url_params = DemoImporterPlus._getQueryStrings();
      delete url_params[single_param];
      delete url_params['']; // Removed extra empty object.

      var current_url = window.location.href;
      var root_url = current_url.substr(0, current_url.indexOf('?'));
      if ($.isEmptyObject(url_params)) {
        var new_url = root_url + decodeURIComponent($.param(url_params));
      } else {
        var current_url_separator = root_url.indexOf('?') === -1 ? '?' : '&';
        var new_url = root_url + current_url_separator + decodeURIComponent($.param(url_params));
      }
      DemoImporterPlus._changeURL(new_url);
    },
    _remove_skip_and_import_popup: function _remove_skip_and_import_popup(event) {
      event.preventDefault();
      $(this).parents('.skip-and-import').addClass('demo-importer-plus-hide visited');
      if ($('.skip-and-import.demo-importer-plus-hide').not('.visited').length) {
        $('.skip-and-import.demo-importer-plus-hide').not('.visited').first().removeClass('demo-importer-plus-hide');
      } else {
        $('.demo-import-sitest-result-prev .default').removeClass('demo-importer-plus-hide');
        if ($('.demo-import-sitest-result-prev').hasClass('import-page')) {
          DemoImporterPlus.skip_and_import_popups = [];
          var notinstalled = DemoImporterPlus.required_plugins.notinstalled || 0;
          if (!notinstalled.length) {
            DemoImporterPlus.import_page_process();
          }
        }
      }
    },
    /**
     * Plugin Installation Error.
     */
    _installError: function _installError(event, response) {
      event.preventDefault();
      console.log(event);
      console.log(response);
      $('.demo-importer-plus-result-preview .heading h3').text('Plugin Installation Failed');
      $('.demo-import-plus-import-content').html('<p>Plugin "<b>' + response.name + '</b>" installation failed.</p><p>There has been an error on your website. Read an article <a href="https://rishitheme.com/docs/how-to-resolve-demo-import-issue/" target="blank">here</a> to solve the issue.</p>');
      $('.dip-demo-import').removeClass('updating-message installing button-primary').addClass('disabled').text('Import Failed!');
      wp.updates.queue = [];
      wp.updates.queueChecker();
      console.groupEnd();
    },
    /**
     * Installing Plugin
     */
    _pluginInstalling: function _pluginInstalling(event, args) {
      event.preventDefault();
      console.groupCollapsed('Installing Plugin "' + args.name + '"');
      DemoImporterPlus._log_title('Installing Plugin - ' + args.name);
      console.log(args);
    },
    /**
     * Install Success
     */
    _installSuccess: function _installSuccess(event, response) {
      event.preventDefault();
      console.groupEnd();

      // Reset not installed plugins list.
      var pluginsList = demoImporterVars.requiredPlugins.notinstalled;
      demoImporterVars.requiredPlugins.notinstalled = DemoImporterPlus._removePluginFromQueue(response.slug, pluginsList);

      // WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
      setTimeout(function () {
        console.groupCollapsed('Activating Plugin "' + response.name + '"');
        DemoImporterPlus._log_title('Activating Plugin - ' + response.name);
        DemoImporterPlus._log('Activating Plugin - ' + response.name);
        $.ajax({
          url: demoImporterVars.ajaxurl,
          type: 'POST',
          data: {
            action: 'demo-importer-plus-required-plugin-activate',
            init: response.init,
            // options: DemoImporterPlus.options_data,
            // enabledExtensions: DemoImporterPlus.enabled_extensions,
            _ajax_nonce: demoImporterVars._ajax_nonce
          }
        }).done(function (result) {
          DemoImporterPlus._log(result);
          if (result.success) {
            var pluginsList = demoImporterVars.requiredPlugins.inactive;
            DemoImporterPlus._log_title('Successfully Activated Plugin - ' + response.name);
            DemoImporterPlus._log('Successfully Activated Plugin - ' + response.name);

            // Reset not installed plugins list.
            demoImporterVars.requiredPlugins.inactive = DemoImporterPlus._removePluginFromQueue(response.slug, pluginsList);

            // Enable Demo Import Button
            DemoImporterPlus._enable_demo_import_button();
          }
          console.groupEnd();
        });
      }, 1200);
    },
    /**
     * Get query strings.
     *
     * @param  string string Query string.
     * @return string     	 Check and return query string.
     */
    _getQueryStrings: function _getQueryStrings(string) {
      return (string || document.location.search).replace(/(^\?)/, '').split('&').map(function (n) {
        return n = n.split('='), this[n[0]] = n[1], this;
      }.bind({}))[0];
    }
  }, _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DemoImporterPlus, "isEmpty", function isEmpty(obj) {
    for (var key in obj) {
      if (obj.hasOwnProperty(key)) return false;
    }
    return true;
  }), "_unescape", function _unescape(input_string) {
    var title = _.unescape(input_string);

    // @todo check why below character not escape with function _.unescape();
    title = title.replace('&#8211;', '-');
    title = title.replace('&#8217;', '\'');
    return title;
  }), "_unescape_lower", function _unescape_lower(input_string) {
    var input_string = DemoImporterPlus._unescape(input_string);
    return input_string.toLowerCase();
  }), "_set_preview_screenshot_by_page", function _set_preview_screenshot_by_page(element) {
    var large_img_url = $(element).find('.theme-screenshot').attr('data-featured-src') || '';
    var url = $(element).find('.theme-screenshot').attr('data-src') || '';
    var page_name = $(element).find('.theme-name').text() || '';
    $(element).siblings().removeClass('current_page');
    $(element).addClass('current_page');
    var page_id = $(element).attr('data-page-id') || '';
    if (page_id) {
      DemoImporterPlus._clean_url_params('demo-importer-page');
      var url_params = {
        'demo-importer-page': page_id
      };
      DemoImporterPlus._changeAndSetURL(url_params);
    }
    $('.site-import-layout-button').removeClass('disabled');
    if (page_name) {
      var title = demoImporterVars.strings.importSingleTemplate.replace('%s', page_name.trim());
      $('.site-import-layout-button').text(title);
    }
    if (url) {
      $('.single-site-preview').animate({
        scrollTop: 0
      }, 0);
      $('.single-site-preview img').addClass('loading').attr('src', url);
      var imgLarge = new Image();
      imgLarge.src = large_img_url;
      imgLarge.onload = function () {
        $('.single-site-preview img').removeClass('loading');
        $('.single-site-preview img').attr('src', imgLarge.src);
      };
    }
  }), "_change_site_preview_screenshot", function _change_site_preview_screenshot(event) {
    event.preventDefault();
    var item = $(event.currentTarget);
    DemoImporterPlus._set_preview_screenshot_by_page(item);
  }), "_change_page_builder", function _change_page_builder(event) {
    var page_builder = $(event.currentTarget).attr('data-page-builder') || '';
    $(event.currentTarget).parents('.page-builders').find('img').removeClass('active');
    $(event.currentTarget).find('img').addClass('active');
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'demo_importer_plus_change_page_builder',
        page_builder: page_builder
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Change Page Builder');
        DemoImporterPlus._log('Change Page Builder..');
      }
    }).done(function (response) {
      DemoImporterPlus._log(response);
      console.groupEnd();
      // location.reload();
    });
  }), "show_page_popup_from_search", function show_page_popup_from_search(event) {
    event.preventDefault();
    var page_id = $(event.currentTarget).parents('.demo-import-plus-theme').attr('data-page-id') || '';
    var site_id = $(event.currentTarget).parents('.demo-import-plus-theme').attr('data-site-id') || '';
    $('#demo-import-plus').hide();
    $('#site-pages').hide();
    $('.di-sites__search-title').hide();
    $('.search-filter-wrap').hide();
    $('.demo-importer-plus-search-results').hide();
    $('.back-to-layout').show();

    //check if demoImporterVars.default_page_builder_sites has site id
    var hasSite = demoImporterVars.default_page_builder_sites.find(function (site) {
      return site.id == site_id;
    }) || false;
    if (!hasSite) {
      $('.single-site-wrap > .svg-animated-loader').show();
      var CACHE_BUSTER = new Date().getTime();
      //Get site from API by site_id using fetch
      fetch("".concat(demoImporterVars.demoAPIURL, "/wp-json/demoimporterplusapi/v1/dipa-demos/").concat(site_id, "?nocache=").concat(CACHE_BUSTER)).then(function (response) {
        return response.json();
      }).then(function (data) {
        $('.single-site-wrap > .svg-animated-loader').hide();
        demoImporterVars.default_page_builder_sites.push(data.data);
        DemoImporterPlus.show_pages_by_site_id(site_id, page_id);
      });
    } else {
      DemoImporterPlus.show_pages_by_site_id(site_id, page_id);
    }
  }), "_get_id", function _get_id(site_id) {
    return site_id.replace('id-', '');
  }), "show_page_popup", function show_page_popup() {
    DemoImporterPlus.process_import_page();
  }), "_show_site_popup", function _show_site_popup(event) {
    event.preventDefault();
    if ($($(this)).hasClass('updating-message')) {
      return;
    }
    $('.demo-import-sitest-result-prev').addClass('import-site').removeClass('import-page');
    $('.demo-import-sitest-result-prev').removeClass('preview-page-from-search-result demo-importer-plus-page-import-popup dip-sites-activate-license').addClass('demo-importer-plus-popup demo-importer-plus-site-import-popup').show();
    var template = wp.template('demo-import-sitest-result-prev');
    $('.demo-import-sitest-result-prev').html(template('dip-sites')).addClass('preparing');
    $('.demo-import-plus-import-content').append('<div class="dip-loading-wrap"><div class="dip-loading-icon"></div></div>');
    DemoImporterPlus.action_slug = 'importer-sites';
    demoImporterVars.cpt_slug = 'demo-importer-plus';
    var site_id = $('#site-pages').attr('data-site-id') || '';
    site_id = DemoImporterPlus._get_id(site_id);
    if (DemoImporterPlus.visited_sites_and_pages[site_id]) {
      DemoImporterPlus.templateData = DemoImporterPlus.visited_sites_and_pages[site_id];
      DemoImporterPlus.process_site_data(DemoImporterPlus.templateData.data);
    } else {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'demo-import-site-api-request',
          demo_id: DemoImporterPlus.get_site_id(),
          url: '/' + site_id
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Requesting API');
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, '', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        console.log('Template API Response:');
        DemoImporterPlus._log(response);
        console.groupEnd();
        if (response.success) {
          DemoImporterPlus.visited_sites_and_pages[response.data.data.id] = response.data;
          DemoImporterPlus.templateData = response.data.data;
          DemoImporterPlus.process_site_data(DemoImporterPlus.templateData);
        } else {
          $('.demo-import-sitest-result-prev .heading > h3').text('Import Process Interrupted');
          $('.demo-import-plus-import-content').find('.dip-loading-wrap').remove();
          $('.demo-import-sitest-result-prev').removeClass('preparing');
          $('.demo-import-plus-import-content').html(wp.template('dip-sites-request-failed'));
          $('.dip-demo-import').removeClass('updating-message installing button-primary').addClass('disabled').text('Import Failed!');
        }
      });
    }
  }), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DemoImporterPlus, "_resetData", function _resetData() {
    DemoImporterPlus.import_start_time = new Date();
    if ($(this).hasClass('updating-message')) {
      return;
    }
    $(this).addClass('updating-message installing').text('Importing..');
    $('body').addClass('importing-site');
    $('.demo-import-sitest-result-prev .inner > h3').text('We\'re importing your website.');
    $('.install-theme-info').hide();
    $('.dip-importing-wrap').show();
    var output = '<div class="current-importing-status-title"></div><div class="current-importing-status-description"></div>';
    $('.current-importing-status').html(output);

    // Process Theme Activate and Install Process
    if ($('.demo-importer-plus-theme-activation .checkbox').is(':checked')) {
      var status = $('.demo-importer-plus-theme-activation .checkbox').data('status');
      DemoImporterPlus._installTheme(status);
    }

    // TODO: Process Theme Activate and Install Process
    $.ajax({
      url: demoImporterVars.ajaxurl,
      type: 'POST',
      data: {
        action: 'demo-importer-plus-set-reset-data',
        _ajax_nonce: demoImporterVars._ajax_nonce
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Site Reset Data');
      }
    }).done(function (response) {
      console.log('List of Reset Items:');
      DemoImporterPlus._log(response);
      console.groupEnd();
      if (response.success) {
        DemoImporterPlus.site_imported_data = response.data;

        // Process Bulk Plugin Install & Activate.
        DemoImporterPlus._bulkPluginInstallActivate();
      }
    });
  }), "_installTheme", function _installTheme(status) {
    var theme_slug = DemoImporterPlus.templateData.theme_slug;
    DemoImporterPlus._log_title(demoImporterVars.log.themeInstall);
    DemoImporterPlus._log(demoImporterVars.log.themeInstall);
    if (status == 'not-installed') {
      if (wp.updates.shouldRequestFilesystemCredentials && !wp.updates.ajaxLocked) {
        wp.updates.requestFilesystemCredentials();
      }
      wp.updates.installTheme({
        slug: theme_slug
      });
    } else if (status == 'installed-but-inactive') {
      DemoImporterPlus._activateTheme();
    }
  }), "_activateTheme", function _activateTheme(event, response) {
    // WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
    setTimeout(function () {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'demo-importer-plus-activate-theme',
          theme_name: DemoImporterPlus.templateData.theme_slug,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.log('Activating Theme..');
        }
      }).done(function (result) {
        DemoImporterPlus._log(result);
        if (result.success) {
          DemoImporterPlus._log_title(result.data.message);
          DemoImporterPlus._log(result.data.message);
        }
      });
    }, 3000);
  }), "_start_site_import", function _start_site_import() {
    if (DemoImporterPlus._is_reset_data()) {
      $(document).trigger('demo-importer-plus-reset-data');
    } else {
      $(document).trigger('demo-importer-plus-sites-reset-data-done');
    }
  }), "_is_reset_data", function _is_reset_data() {
    if ($('.demo-importer-plus-reset-data').find('.checkbox').is(':checked')) {
      return true;
    }
    return false;
  }), "_is_process_xml", function _is_process_xml() {
    if ($('.demo-importer-plus-import-xml').find('.checkbox').is(':checked')) {
      return true;
    }
    return false;
  }), "_is_process_customizer", function _is_process_customizer() {
    var theme_status = $('.demo-importer-plus-theme-activation .checkbox').length ? $('.demo-importer-plus-theme-activation .checkbox').is(':checked') : true;
    var customizer_status = $('.demo-importer-plus-import-customizer').find('.checkbox').is(':checked');
    if (theme_status && customizer_status) {
      return true;
    }
    return false;
  }), "_is_process_widgets", function _is_process_widgets() {
    if ($('.demo-importer-plus-import-widgets').find('.checkbox').is(':checked')) {
      return true;
    }
    return false;
  }), "_startImportCartFlows", function _startImportCartFlows(event) {
    if (DemoImporterPlus._is_process_xml() && '' !== DemoImporterPlus.cartflows_url) {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'demo-importer-plus-import-cartflows',
          cartflows_url: DemoImporterPlus.cartflows_url,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Importing Flows & Steps');
          DemoImporterPlus._log_title('Importing Flows & Steps..');
          DemoImporterPlus._log(DemoImporterPlus.cartflows_url);
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Import Cartflows Flow Failed!', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus._log(response);

        // 1. Fail - Import WPForms Options.
        if (false === response.success) {
          DemoImporterPlus._importFailMessage(response.data, 'Import Cartflows Flow Failed!');
          console.groupEnd();
        } else {
          console.groupEnd();
          // 1. Pass - Import Customizer Options.
          $(document).trigger(DemoImporterPlus.action_slug + '-import-cartflows-done');
        }
      });
    } else {
      $(document).trigger(DemoImporterPlus.action_slug + '-import-cartflows-done');
    }
  }), "_startImportContactForms", function _startImportContactForms(event) {
    if (DemoImporterPlus._is_process_xml() && '' !== DemoImporterPlus.wpforms_url) {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'dip-sites-import-wpforms',
          wpforms_url: DemoImporterPlus.wpforms_url,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Importing WP Forms');
          DemoImporterPlus._log_title('Importing WP Forms..');
          DemoImporterPlus._log(DemoImporterPlus.wpforms_url);
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Import WP Forms Failed!', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus._log(response);

        // 1. Fail - Import WPForms Options.
        if (false === response.success) {
          DemoImporterPlus._importFailMessage(response.data, 'Import WP Forms Failed!');
          console.groupEnd();
        } else {
          console.groupEnd();
          // 1. Pass - Import Customizer Options.
          $(document).trigger(DemoImporterPlus.action_slug + '-import-wpforms-done');
        }
      });
    } else {
      $(document).trigger(DemoImporterPlus.action_slug + '-import-wpforms-done');
    }
  }), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DemoImporterPlus, "_importCustomizerSettings", function _importCustomizerSettings(event) {
    if (DemoImporterPlus._is_process_customizer()) {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'demo-importer-plus-import-customizer-settings',
          // customizer_data: DemoImporterPlus.customizer_data,
          demo_id: DemoImporterPlus.get_site_id(),
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Importing Customizer Settings');
          DemoImporterPlus._log_title('Importing Customizer Settings..');
          // DemoImporterPlus._log(JSON.parse(DemoImporterPlus.customizer_data));
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Import Customizer Settings Failed!', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus._log(response);

        // 1. Fail - Import Customizer Options.
        if (false === response.success) {
          DemoImporterPlus._importFailMessage(response.data, 'Import Customizer Settings Failed!');
          console.groupEnd();
        } else {
          console.groupEnd();
          // 1. Pass - Import Customizer Options.
          $(document).trigger('demo-importer-plus-import-customizer-settings-done');
        }
      });
    } else {
      $(document).trigger('demo-importer-plus-import-customizer-settings-done');
    }
  }), "_importXML", function _importXML() {
    if (DemoImporterPlus._is_process_xml()) {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'demo-importer-plus-import-prepare-xml',
          wxr_url: DemoImporterPlus.wxr_url,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Importing Content');
          DemoImporterPlus._log_title('Importing Content..');
          DemoImporterPlus._log(DemoImporterPlus.wxr_url);
          $('.di-process-wrap').show();
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Prepare Import XML Failed!', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus._log(response);

        // 2. Fail - Prepare XML Data.
        if (false === response.success) {
          var error_msg = response.data.error || response.data;
          DemoImporterPlus._importFailMessage(demoImporterVars.xmlRequiredFilesMissing);
          console.groupEnd();
        } else {
          var xml_processing = $('.dip-demo-import').attr('data-xml-processing');
          if ('yes' === xml_processing) {
            return;
          }
          $('.dip-demo-import').attr('data-xml-processing', 'yes');

          // 2. Pass - Prepare XML Data.

          // Import XML though Event Source.
          DemoImporterSSEImport.data = response.data;
          DemoImporterSSEImport.render();
          $('.current-importing-status-description').html('').show();
          $('.current-importing-status-wrap').append('<div class="di-process-wrap"><progress class="di-process" max="100" value="0"></progress></div>');
          var evtSource = new EventSource(DemoImporterSSEImport.data.url);
          evtSource.onmessage = function (message) {
            var data = JSON.parse(message.data.replace(/&quot;/g, '"'));
            switch (data.action) {
              case 'updateDelta':
                DemoImporterSSEImport.updateDelta(data.type, data.delta);
                break;
              case 'complete':
                evtSource.close();
                $('.current-importing-status-description').hide();
                $('.dip-demo-import').removeAttr('data-xml-processing');
                document.getElementsByClassName('di-process').value = '100';
                $('.di-process-wrap').hide();
                console.groupEnd();
                $(document).trigger('demo-importer-plus-import-xml-done');
                break;
            }
          };
          evtSource.onerror = function (error) {
            evtSource.close();
            console.log(error);
            DemoImporterPlus._importFailMessage('', 'Import Process Interrupted');
          };
          evtSource.addEventListener('log', function (message) {
            var data = JSON.parse(message.data.replace(/&quot;/g, '"'));
            var message = data.message || '';
            if (message && 'info' === data.level) {
              message = message.replace(/"/g, function (letter) {
                return '';
              });
              $('.current-importing-status-description').html(message);
            }
            DemoImporterPlus._log(message, data.level);
          });
        }
      });
    } else {
      $(document).trigger('demo-importer-plus-import-xml-done');
    }
  }), "import_siteOptions", function import_siteOptions(event) {
    if (DemoImporterPlus._is_process_xml()) {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'demo-importer-plus-import-options',
          demo_id: DemoImporterPlus.get_site_id(),
          // options_data: DemoImporterPlus.options_data,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Importing Options');
          DemoImporterPlus._log_title('Importing Options..');
          $('.dip-demo-import .percent').html('');
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Import Site Options Failed!', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus._log(response);
        // 3. Fail - Import Site Options.
        if (false === response.success) {
          DemoImporterPlus._importFailMessage(response.data, 'Import Site Options Failed!');
          console.groupEnd();
        } else {
          console.groupEnd();

          // 3. Pass - Import Site Options.
          $(document).trigger('demo-importer-plus-import-options-done');
        }
      });
    } else {
      $(document).trigger('demo-importer-plus-import-options-done');
    }
  }), "_importWidgets", function _importWidgets(event) {
    if (DemoImporterPlus._is_process_widgets()) {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
          action: 'demo-importer-plus-import-widgets',
          demo_id: DemoImporterPlus.get_site_id(),
          // widgets_data: DemoImporterPlus.widgets_data,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Importing Widgets');
          DemoImporterPlus._log_title('Importing Widgets..');
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Import Widgets Failed!', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus._log(response);
        console.groupEnd();

        // 4. Fail - Import Widgets.
        if (false === response.success) {
          DemoImporterPlus._importFailMessage(response.data, 'Import Widgets Failed!');
        } else {
          // 4. Pass - Import Widgets.
          $(document).trigger('demo-importer-plus-import-widgets-done');
        }
      });
    } else {
      $(document).trigger('demo-importer-plus-import-widgets-done');
    }
  }), "_importEnd", function _importEnd(event) {
    $.ajax({
      url: demoImporterVars.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'demo-importer-plus-import-end',
        _ajax_nonce: demoImporterVars._ajax_nonce
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Import Complete!');
        DemoImporterPlus._log_title('Import Complete!');
        // console.groupCollapsed( 'Import Complete!' );
      }
    }).fail(function (jqXHR) {
      DemoImporterPlus._log(jqXHR);
      DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Import Complete Failed!', jqXHR);
      console.groupEnd();
    }).done(function (response) {
      DemoImporterPlus._log(response);
      console.groupEnd();

      // 5. Fail - Import Complete.
      if (false === response.success) {
        DemoImporterPlus._importFailMessage(response.data, 'Import Complete Failed!');
      } else {
        DemoImporterPlus.site_import_status = true;
        DemoImporterPlus.import_complete();
      }
    });
  }), "import_complete", function import_complete() {
    if (false === DemoImporterPlus.subscribe_status) {
      return;
    }
    $('body').removeClass('importing-site');
    var template = wp.template('demo-importer-plus-site-import-success');
    $('.demo-import-sitest-result-prev .inner').html(template());
    $('.rotating,.current-importing-status-wrap,.notice-warning').remove();
    $('.demo-import-sitest-result-prev').addClass('demo-importer-plus-result-preview');

    // 5. Pass - Import Complete.
    DemoImporterPlus._importSuccessButton();
    DemoImporterPlus.site_import_status = false;
    DemoImporterPlus.subscribe_status = false;
  }), "_importSuccessButton", function _importSuccessButton() {
    $('.dip-demo-import').removeClass('updating-message installing').removeAttr('data-import').addClass('view-site').removeClass('dip-demo-import').text(demoImporterVars.strings.viewSite).attr('target', '_blank').append('<i class="dashicons dashicons-external"></i>').attr('href', demoImporterVars.siteURL);
  }), "add_skip_and_import_popups", function add_skip_and_import_popups(templates) {
    if (Object.keys(templates).length) {
      for (temp_id in templates) {
        var template = wp.template(temp_id);
        var template_data = templates[temp_id] || '';
        $('.demo-import-sitest-result-prev .inner').append(template(template_data));
      }
      $('.demo-import-sitest-result-prev .inner > .default').addClass('demo-importer-plus-hide');
      $('.demo-import-sitest-result-prev .inner > .skip-and-import:not(:ldip-child)').addClass('demo-importer-plus-hide');
    }
  }), "process_site_data", function process_site_data(data) {
    if ('log_file' in data) {
      DemoImporterPlus.log_file_url = decodeURIComponent(data.log_file) || '';
    }

    // 1. Pass - Request Site Import
    DemoImporterPlus.customizer_data = JSON.stringify(data['customizer-data']) || '';
    DemoImporterPlus.wxr_url = encodeURI(data['wxr-path']) || '';
    DemoImporterPlus.options_data = JSON.stringify(data['site-option']) || '';
    DemoImporterPlus.theme_name = JSON.stringify(data['theme-name']) || '';
    DemoImporterPlus.enabled_extensions = JSON.stringify(data['dip-enabled-extensions']) || '';
    DemoImporterPlus.widgets_data = data['widgets-data'] || '';

    // Elementor Template Kit Markup.
    DemoImporterPlus.template_kit_markup(data);

    // Required Plugins.
    DemoImporterPlus.required_plugins_list_markup(data['required_plugins']);
  }), "template_kit_markup", function template_kit_markup(data) {
    if ('elementor' != demoImporterVars.default_page_builder) {
      return;
    }
  }), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DemoImporterPlus, "hide_popup", function hide_popup() {
    $('.demo-importer-plus-popup').hide();
  }), "process_import_page", function process_import_page() {
    DemoImporterPlus.hide_popup();
    var page_id = DemoImporterPlus._get_id($('#single-pages').find('.current_page').attr('data-page-id')) || '';
    var site_id = DemoImporterPlus._get_id($('#site-pages').attr('data-site-id')) || '';
    $('.demo-import-sitest-result-prev').removeClass('demo-importer-plus-site-import-popup demo-importer-plus-page-import-popup').addClass('preview-page-from-search-result demo-importer-plus-page-import-popup').show();
    $('.demo-import-sitest-result-prev').html(wp.template('demo-import-sitest-result-prev')).addClass('preparing');
    $('.demo-import-plus-import-content').append('<div class="dip-loading-wrap"><div class="dip-loading-icon"></div></div>');
    DemoImporterPlus.action_slug = 'site-pages';
    demoImporterVars.cpt_slug = 'site-pages';
    if (DemoImporterPlus.visited_sites_and_pages[page_id]) {
      DemoImporterPlus.templateData = DemoImporterPlus.visited_sites_and_pages[page_id];
      DemoImporterPlus.required_plugins_list_markup(DemoImporterPlus.templateData.data['page-required-plugins']);
    } else {
      // Request.
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'demo-import-site-api-request',
          demo_id: site_id,
          url: '/' + site_id + '?page=' + page_id
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Requesting API URL');
          DemoImporterPlus._log('Requesting API URL');
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Page Import API Request Failed!', jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus._log(response);
        console.groupEnd();
        if (response.success) {
          DemoImporterPlus.visited_sites_and_pages[response.data.data.id] = response.data;
          DemoImporterPlus.templateData = response.data;
          DemoImporterPlus.required_plugins_list_markup(DemoImporterPlus.templateData.data['page-required-plugins']);
        } else {
          $('.demo-import-sitest-result-prev .heading > h3').text('Import Process Interrupted');
          $('.demo-import-plus-import-content').find('.dip-loading-wrap').remove();
          $('.demo-import-sitest-result-prev').removeClass('preparing');
          $('.demo-import-plus-import-content').html(wp.template('demo-importr-plus-request-failed'));
          $('.demo-import-plus-impr').removeClass('updating-message installing button-primary').addClass('disabled').text('Import Failed!');
        }
      });
    }
  }), "_backup_before_rest_options", function _backup_before_rest_options() {
    DemoImporterPlus._backupOptions('demo-importer-plus-sites-backup-settings-before-reset-done');
    DemoImporterPlus.backup_taken = true;
  }), "_recheck_backup_options", function _recheck_backup_options() {
    DemoImporterPlus._backupOptions('demo-importer-plus-backup-settings-done');
    DemoImporterPlus.backup_taken = true;
  }), "_backupOptions", function _backupOptions(trigger_name) {
    // Customizer backup is already taken then return.
    if (DemoImporterPlus.backup_taken) {
      $(document).trigger(trigger_name);
    } else {
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'demo-importer-plus-backup-settings',
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Processing Customizer Settings Backup');
          DemoImporterPlus._log_title('Processing Customizer Settings Backup..');
        }
      }).fail(function (jqXHR) {
        DemoImporterPlus._log(jqXHR);
        DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Backup Customizer Settings Failed!', jqXHR);
        console.groupEnd();
      }).done(function (data) {
        DemoImporterPlus._log(data);

        // 1. Pass - Import Customizer Options.
        DemoImporterPlus._log_title('Customizer Settings Backup Done..');
        console.groupEnd();
        // Custom trigger.
        $(document).trigger(trigger_name);
      });
    }
  }), "_reset_customizer_data", function _reset_customizer_data() {
    $.ajax({
      url: demoImporterVars.ajaxurl,
      type: 'POST',
      data: {
        action: 'demo-importer-plus-reset-customizer-data',
        _ajax_nonce: demoImporterVars._ajax_nonce
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Reseting Customizer Data');
        DemoImporterPlus._log_title('Reseting Customizer Data..');
        console.log('# Reseting Customizer Data..');
      }
    }).fail(function (jqXHR) {
      DemoImporterPlus._log(jqXHR);
      DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Reset Customizer Settings Failed!', jqXHR);
      console.groupEnd();
    }).done(function (data) {
      DemoImporterPlus._log(data);
      DemoImporterPlus._log_title('Complete Resetting Customizer Data..');
      DemoImporterPlus._log('Complete Resetting Customizer Data..');
      console.groupEnd();
      $(document).trigger('demo-importer-plus-sites-reset-customizer-data-done');
    });
  }), "_reset_site_options", function _reset_site_options() {
    // Site Options.
    $.ajax({
      url: demoImporterVars.ajaxurl,
      type: 'POST',
      data: {
        action: 'demo-importer-plus-reset-site-options',
        _ajax_nonce: demoImporterVars._ajax_nonce
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Reseting Site Options');
        DemoImporterPlus._log_title('Reseting Site Options..');
        // console.log( '# Reseting Site Options..' );
      }
    }).fail(function (jqXHR) {
      DemoImporterPlus._log(jqXHR);
      DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Reset Site Options Failed!', jqXHR);
      console.groupEnd();
    }).done(function (data) {
      DemoImporterPlus._log(data);
      DemoImporterPlus._log_title('Complete Reseting Site Options..');
      console.groupEnd();
      $(document).trigger('demo-importer-plus-sites-reset-site-options-done');
    });
  }), "_reset_widgets_data", function _reset_widgets_data() {
    // Widgets.
    $.ajax({
      url: demoImporterVars.ajaxurl,
      type: 'POST',
      data: {
        action: 'demo-importer-plus-reset-widgets-data',
        _ajax_nonce: demoImporterVars._ajax_nonce
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Reseting Widgets');
        DemoImporterPlus._log_title('Reseting Widgets..');
        console.log('# Reseting Widgets..');
      }
    }).fail(function (jqXHR) {
      DemoImporterPlus._log(jqXHR);
      DemoImporterPlus._importFailMessage(jqXHR.status + ' ' + jqXHR.statusText, 'Reset Widgets Data Failed!', jqXHR);
      console.groupEnd();
    }).done(function (data) {
      DemoImporterPlus._log(data);
      DemoImporterPlus._log_title('Complete Reseting Widgets..');
      console.groupEnd();
      $(document).trigger('demo-importer-plus-sites-reset-widgets-data-done');
    });
  }), "_reset_posts", function _reset_posts() {
    if (DemoImporterPlus.site_imported_data['reset_posts'].length) {
      DemoImporterPlus.reset_remaining_posts = DemoImporterPlus.site_imported_data['reset_posts'].length;
      console.groupCollapsed('Deleting Posts');
      DemoImporterPlus._log_title('Deleting Posts..');
      $.each(DemoImporterPlus.site_imported_data['reset_posts'], function (index, post_id) {
        DemoImporterAjaxQueue.add({
          url: demoImporterVars.ajaxurl,
          type: 'POST',
          data: {
            action: 'demo-importer-plus-sites-delete-posts',
            post_id: post_id,
            _ajax_nonce: demoImporterVars._ajax_nonce
          },
          success: function success(result) {
            if (DemoImporterPlus.reset_processed_posts < DemoImporterPlus.site_imported_data['reset_posts'].length) {
              DemoImporterPlus.reset_processed_posts += 1;
            }
            DemoImporterPlus._log_title('Deleting Post ' + DemoImporterPlus.reset_processed_posts + ' of ' + DemoImporterPlus.site_imported_data['reset_posts'].length + '<br/>' + result.data);
            DemoImporterPlus.reset_remaining_posts -= 1;
            if (0 == DemoImporterPlus.reset_remaining_posts) {
              console.groupEnd();
              $(document).trigger('demo-importer-plus-sites-delete-posts-done');
              $(document).trigger('demo-importer-plus-sites-reset-data-done');
            }
          }
        });
      });
      DemoImporterAjaxQueue.run();
    } else {
      $(document).trigger('demo-importer-plus-sites-delete-posts-done');
      $(document).trigger('demo-importer-plus-sites-reset-data-done');
    }
  }), "_reset_contact_form7", function _reset_contact_form7() {
    if (DemoImporterPlus.site_imported_data['reset_contact_form7'].length) {
      DemoImporterPlus.reset_remaining_contact_form7 = DemoImporterPlus.site_imported_data['reset_contact_form7'].length;
      console.groupCollapsed('Deleting Contact Form 7');
      DemoImporterPlus._log_title('Deleting Contact Form 7..');
      $.each(DemoImporterPlus.site_imported_data['reset_contact_form7'], function (index, post_id) {
        DemoImporterAjaxQueue.add({
          url: demoImporterVars.ajaxurl,
          type: 'POST',
          data: {
            action: 'demo-importer-plus-sites-delete-contact-form7',
            post_id: post_id,
            _ajax_nonce: demoImporterVars._ajax_nonce
          },
          success: function success(result) {
            if (DemoImporterPlus.reset_processed_contact_form7 < DemoImporterPlus.site_imported_data['reset_contact_form7'].length) {
              DemoImporterPlus.reset_processed_contact_form7 += 1;
            }
            DemoImporterPlus._log_title('Deleting Form ' + DemoImporterPlus.reset_processed_contact_form7 + ' of ' + DemoImporterPlus.site_imported_data['reset_contact_form7'].length + '<br/>' + result.data);
            DemoImporterPlus._log('Deleting Form ' + DemoImporterPlus.reset_processed_contact_form7 + ' of ' + DemoImporterPlus.site_imported_data['reset_contact_form7'].length + '<br/>' + result.data);
            DemoImporterPlus.reset_remaining_contact_form7 -= 1;
            if (0 == DemoImporterPlus.reset_remaining_contact_form7) {
              console.groupEnd();
              $(document).trigger('demo-importer-plus-sites-delete-contact-form7-done');
            }
          }
        });
      });
      DemoImporterAjaxQueue.run();
    } else {
      $(document).trigger('demo-importer-plus-sites-delete-contact-form7-done');
    }
  }), _defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_defineProperty(_DemoImporterPlus, "_reset_terms", function _reset_terms() {
    if (DemoImporterPlus.site_imported_data['reset_terms'].length) {
      DemoImporterPlus.reset_remaining_terms = DemoImporterPlus.site_imported_data['reset_terms'].length;
      console.groupCollapsed('Deleting Terms');
      DemoImporterPlus._log_title('Deleting Terms..');
      $.each(DemoImporterPlus.site_imported_data['reset_terms'], function (index, term_id) {
        DemoImporterAjaxQueue.add({
          url: demoImporterVars.ajaxurl,
          type: 'POST',
          data: {
            action: 'demo-importer-plus-sites-delete-terms',
            term_id: term_id,
            _ajax_nonce: demoImporterVars._ajax_nonce
          },
          success: function success(result) {
            if (DemoImporterPlus.reset_processed_terms < DemoImporterPlus.site_imported_data['reset_terms'].length) {
              DemoImporterPlus.reset_processed_terms += 1;
            }
            DemoImporterPlus._log_title('Deleting Term ' + DemoImporterPlus.reset_processed_terms + ' of ' + DemoImporterPlus.site_imported_data['reset_terms'].length + '<br/>' + result.data);
            DemoImporterPlus._log('Deleting Term ' + DemoImporterPlus.reset_processed_terms + ' of ' + DemoImporterPlus.site_imported_data['reset_terms'].length + '<br/>' + result.data);
            DemoImporterPlus.reset_remaining_terms -= 1;
            if (0 == DemoImporterPlus.reset_remaining_terms) {
              console.groupEnd();
              $(document).trigger('demo-importer-plus-sites-delete-terms-done');
            }
          }
        });
      });
      DemoImporterAjaxQueue.run();
    } else {
      $(document).trigger('demo-importer-plus-sites-delete-terms-done');
    }
  }), "_bulkPluginInstallActivate", function _bulkPluginInstallActivate() {
    if (0 === Object.keys(demoImporterVars.requiredPlugins).length) {
      return;
    }

    // If has class the skip-plugins then,
    // Avoid installing 3rd party plugins.
    var not_installed = demoImporterVars.requiredPlugins.notinstalled || '';
    if ($('.demo-import-sitest-result-prev').hasClass('skip-plugins')) {
      not_installed = [];
    }
    var activate_plugins = demoImporterVars.requiredPlugins.inactive || '';

    // First Install Bulk.
    if (not_installed.length > 0) {
      DemoImporterPlus._installAllPlugins(not_installed);
    }

    // Second Activate Bulk.
    if (activate_plugins.length > 0) {
      DemoImporterPlus._activateAllPlugins(activate_plugins);
    }
    if (activate_plugins.length <= 0 && not_installed.length <= 0) {
      DemoImporterPlus._enable_demo_import_button();
    }
  }), "_activateAllPlugins", function _activateAllPlugins(activate_plugins) {
    DemoImporterPlus.remaining_activate_plugins = activate_plugins.length;
    $.each(activate_plugins, function (index, single_plugin) {
      DemoImporterAjaxQueue.add({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'demo-importer-plus-required-plugin-activate',
          init: single_plugin.init,
          options: DemoImporterPlus.options_data,
          enabledExtensions: DemoImporterPlus.enabled_extensions,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Activating Plugin "' + single_plugin.name + '"');
          DemoImporterPlus._log_title('Activating Plugin "' + single_plugin.name + '"');
        },
        success: function success(result) {
          console.log(result);
          console.groupEnd('Activating Plugin "' + single_plugin.name + '"');
          if (result.success) {
            var pluginsList = demoImporterVars.requiredPlugins.inactive;

            // Reset not installed plugins list.
            demoImporterVars.requiredPlugins.inactive = DemoImporterPlus._removePluginFromQueue(single_plugin.slug, pluginsList);

            // Enable Demo Import Button
            DemoImporterPlus._enable_demo_import_button();
          }
          DemoImporterPlus.remaining_activate_plugins -= 1;
          if (0 === DemoImporterPlus.remaining_activate_plugins) {
            console.groupEnd('Activating Required Plugins..');
          }
        }
      });
    });
    DemoImporterAjaxQueue.run();
  }), "_enable_demo_import_button", function _enable_demo_import_button(type) {
    type = undefined !== type ? type : 'free';
    $('.install-theme-info .theme-details .site-description').remove();
    switch (type) {
      case 'free':
        var notinstalled = demoImporterVars.requiredPlugins.notinstalled || 0;
        var inactive = demoImporterVars.requiredPlugins.inactive || 0;
        if ($('.demo-import-sitest-result-prev').hasClass('skip-plugins')) {
          notinstalled = [];
        }
        if (notinstalled.length === inactive.length) {
          $(document).trigger('demo-importer-plus-after-' + DemoImporterPlus.action_slug + '-required-plugins');
        }
        break;
      case 'upgrade':
        var demo_slug = $('.wp-full-overlay-header').attr('data-demo-slug');
        $('.dip-demo-import').addClass('go-pro button-primary').removeClass('dip-demo-import').attr('target', '_blank').attr('href', demoImporterVars.getUpgradeURL + demo_slug).text(demoImporterVars.getUpgradeText).append('<i class="dashicons dashicons-external"></i>');
        break;
      default:
        var demo_slug = $('.wp-full-overlay-header').attr('data-demo-slug');
        $('.dip-demo-import').addClass('go-pro button-primary').removeClass('dip-demo-import').attr('target', '_blank').attr('href', demoImporterVars.getProURL).text(demoImporterVars.getProText).append('<i class="dashicons dashicons-external"></i>');
        $('.wp-full-overlay-header').find('.go-pro').remove();
        if (false == demoImporterVars.isWhiteLabeled) {
          if (demoImporterVars.isPro) {
            $('.install-theme-info .theme-details').prepend(wp.template('dip-sites-pro-inactive-site-description'));
          } else {
            $('.install-theme-info .theme-details').prepend(wp.template('dip-sites-pro-site-description'));
          }
        }
        break;
    }
  }), "_installAllPlugins", function _installAllPlugins(not_installed) {
    $.each(not_installed, function (index, single_plugin) {
      wp.updates.queue.push({
        action: 'install-plugin',
        data: {
          slug: single_plugin.slug,
          init: single_plugin.init,
          name: single_plugin.name,
          success: function success() {
            $(document).trigger('wp-plugin-install-success', [single_plugin]);
          },
          error: function error() {
            $(document).trigger('wp-plugin-install-error', [single_plugin]);
          }
        }
      });
    });
    wp.updates.queueChecker();
  }), "import_page_process", function import_page_process() {
    if ($('.demo-importer-plus-page-import-popup .site-install-site-button, .preview-page-from-search-result .site-install-site-button').hasClass('updating-message')) {
      return;
    }
    $('.demo-importer-plus-page-import-popup .site-install-site-button, .preview-page-from-search-result .site-install-site-button').addClass('updating-message installing').text('Importing..');
    DemoImporterPlus.import_start_time = new Date();
    $('.demo-import-sitest-result-prev .inner > h3').text('We\'re importing your website.');
    $('.install-theme-info').hide();
    $('.dip-importing-wrap').show();
    var output = '<div class="current-importing-status-title"></div><div class="current-importing-status-description"></div>';
    $('.current-importing-status').html(output);

    // Process Bulk Plugin Install & Activate.
    DemoImporterPlus._bulkPluginInstallActivate();
  }), "required_plugins_list_markup", function required_plugins_list_markup(requiredPlugins) {
    if ('' === requiredPlugins) {
      return;
    }

    // or
    var $pluginsFilter = $('#plugin-filter');

    // Add disabled class from import button.
    $('.dip-demo-import').addClass('disabled not-click-able').removeAttr('data-import');
    $('.required-plugins').addClass('loading').html('<span class="spinner is-active"></span>');

    // Required Required.
    $.ajax({
      url: demoImporterVars.ajaxurl,
      type: 'POST',
      data: {
        action: 'demo-importer-plus-required-plugins',
        _ajax_nonce: demoImporterVars._ajax_nonce,
        demoId: DemoImporterPlus.get_site_id()
        // required_plugins: requiredPlugins,
        // options: DemoImporterPlus.options_data,
        // enabledExtensions: DemoImporterPlus.enabled_extensions,
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Required Plugins');
        console.log('Required Plugins of Template:');
        console.log(requiredPlugins);
      }
    }).fail(function (jqXHR) {
      DemoImporterPlus._log(jqXHR);

      // Remove loader.
      $('.required-plugins').removeClass('loading').html('');
      DemoImporterPlus._importFailMessage(jqXHR.status + jqXHR.statusText, 'Required Plugins Failed!', jqXHR);
      console.groupEnd();
    }).done(function (response) {
      console.log('Required Plugin Status From The Site:');
      DemoImporterPlus._log(response);
      console.groupEnd();
      if (false === response.success) {
        DemoImporterPlus._importFailMessage(response.data, 'Required Plugins Failed!', '', demoImporterVars.importFailedRequiredPluginsMessage);
      } else {
        required_plugins = response.data['required_plugins'];

        // Set compatibilities.
        var compatibilities = demoImporterVars.compatibilities;
        DemoImporterPlus.skip_and_import_popups = [];
        DemoImporterPlus.required_plugins = response.data['required_plugins'];
        if (response.data['update_avilable_plugins'].length) {
          compatibilities.warnings['update-available'] = demoImporterVars.compatibilities_data['update-available'];
          var list_html = '<ul>';
          for (var index = 0; index < response.data["update_avilable_plugins"].length; index++) {
            var element = response.data['update_avilable_plugins'][index];
            list_html += '<li>' + element.name + '</li>';
          }
          list_html += '</ul>';
          compatibilities.warnings['update-available']['tooltip'] = compatibilities.warnings['update-available']['tooltip'].replace('##LIST##', list_html);
        } else {
          delete compatibilities.warnings['update-available'];
        }
        if (response.data['third_party_required_plugins'].length) {
          DemoImporterPlus.skip_and_import_popups['dip-sites-third-party-required-plugins'] = response.data['third_party_required_plugins'];
        }
        var is_dynamic_page = $('#single-pages').find('.current_page').attr('data-dynamic-page') || 'no';
        if ('yes' === is_dynamic_page && 'site-pages' === DemoImporterPlus.action_slug) {
          DemoImporterPlus.skip_and_import_popups['dip-sites-dynamic-page'] = '';
        }

        // Release disabled class from import button.
        $('.demo-import-plus-impr').removeClass('disabled not-click-able').attr('data-import', 'disabled');

        // Remove loader.
        $('.required-plugins').removeClass('loading').html('');
        $('.required-plugins-list').html('');
        var output = '';

        /**
         * Count remaining plugins.
         * @type number
         */
        var remaining_plugins = 0;
        var required_plugins_markup = '';

        /**
         * Not Installed
         *
         * List of not installed required plugins.
         */
        if (typeof required_plugins.notinstalled !== 'undefined') {
          // Add not have installed plugins count.
          remaining_plugins += parseInt(required_plugins.notinstalled.length);
          $(required_plugins.notinstalled).each(function (index, plugin) {
            output += '<li class="plugin-card plugin-card-' + plugin.slug + '" data-slug="' + plugin.slug + '" data-init="' + plugin.init + '" data-name="' + plugin.name + '">' + plugin.name + '</li>';
          });
        }

        /**
         * Inactive
         *
         * List of not inactive required plugins.
         */
        if (typeof required_plugins.inactive !== 'undefined') {
          // Add inactive plugins count.
          remaining_plugins += parseInt(required_plugins.inactive.length);
          $(required_plugins.inactive).each(function (index, plugin) {
            output += '<li class="plugin-card plugin-card-' + plugin.slug + '" data-slug="' + plugin.slug + '" data-init="' + plugin.init + '" data-name="' + plugin.name + '">' + plugin.name + '</li>';
          });
        }
        if ('' == output) {
          $('.demo-import-sitest-result-prev').find('.dip-sites-import-plugins').hide();
        } else {
          $('.demo-import-sitest-result-prev').find('.dip-sites-import-plugins').show();
          $('.demo-import-sitest-result-prev').find('.required-plugins-list').html(output);
        }

        /**
         * Enable Demo Import Button
         * @type number
         */
        demoImporterVars.requiredPlugins = required_plugins;
        $('.demo-import-plus-import-content').find('.dip-loading-wrap').remove();
        $('.demo-import-sitest-result-prev').removeClass('preparing');

        // Compatibility.
        if (Object.keys(compatibilities.errors).length || Object.keys(compatibilities.warnings).length || Object.keys(DemoImporterPlus.skip_and_import_popups).length) {
          if (Object.keys(compatibilities.errors).length || Object.keys(compatibilities.warnings).length) {
            DemoImporterPlus.skip_and_import_popups['demo-importer-plus-compatibility-messages'] = compatibilities;
          }
          if (Object.keys(DemoImporterPlus.skip_and_import_popups).length) {
            DemoImporterPlus.add_skip_and_import_popups(DemoImporterPlus.skip_and_import_popups);
          }
        } else {
          // Avoid plugin activation, for pages only.

          if ('site-pages' === DemoImporterPlus.action_slug) {
            var notinstalled = demoImporterVars.requiredPlugins.notinstalled || 0;
            if (!notinstalled.length) {
              DemoImporterPlus.import_page_process();
            }
          }
        }
      }
      console.groupEnd();
    });
  }), "_page_api_call", function _page_api_call() {
    if (Object.keys(DemoImporterPlus.skip_and_import_popups).length) {
      return;
    }
    if (null == DemoImporterPlus.templateData) {
      return;
    }
    DemoImporterPlus.import_contact_form7(DemoImporterPlus.templateData.data['contact_form'], function (form_response) {
      $('body').addClass('importing-site');

      // Import Page Content
      $('.current-importing-status-wrap').remove();
      $('.demo-import-sitest-result-prev .inner > h3').text('We are importing page!');
      fetch(DemoImporterPlus.templateData.data['page-api-url']).then(function (response) {
        return response.json();
      }).then(function (data) {
        $.ajax({
          url: demoImporterVars.ajaxurl,
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'demo-importer-plus-create-page',
            _ajax_nonce: demoImporterVars._ajax_nonce,
            page_settings_flag: DemoImporterPlus.page_settings_flag,
            data: data
          },
          success: function success(response) {
            if (response.success) {
              $('body').removeClass('importing-site');
              $('.rotating,.current-importing-status-wrap,.notice-warning').remove();
              var template = wp.template('demo-importer-plus-page-import-success');
              $('.demo-import-sitest-result-prev .inner').html(template(response.data));
            } else {
              DemoImporterPlus._importFailMessage(response.data, 'Page Rest API Request Failed!');
            }
          }
        });
      })["catch"](function (err) {
        DemoImporterPlus._log(err);
        DemoImporterPlus._importFailMessage(response.data, 'Page Rest API Request Failed!');
      });
    });
  }), "_insertDemo", function _insertDemo(data) {
    if (undefined !== data && undefined !== data['post-meta']['_elementor_data']) {
      var page_id = DemoImporterPlus.templateData.data['page-id'];
      var page_data_single = DemoImporterPlus.templateData.data.pages[page_id];
      var templateModel = new Backbone.Model({
        getTitle: function getTitle() {
          return data['title'];
        }
      });
      var page_content = JSON.parse(data['post-meta']['_elementor_data']);
      var page_settings = '';
      var api_url = '';
      api_url = page_data_single['page-api-url'];
      $.ajax({
        url: demoImporterVars.ajaxurl,
        type: 'POST',
        data: {
          action: 'demo-importer-plus-page-elementor-batch-process',
          id: elementor.config.document.id,
          url: api_url,
          _ajax_nonce: demoImporterVars._ajax_nonce
        },
        beforeSend: function beforeSend() {
          console.groupCollapsed('Inserting Demo.');
        }
      }).fail(function (jqXHR) {
        console.log(jqXHR);
        console.groupEnd();
      }).done(function (response) {
        DemoImporterPlus.processing = false;
        $elscope.find('.demo-importer-plus-sites-content-wrap').removeClass('processing');
        page_content = response.data;
        console.log(page_content);
        console.groupEnd();
        if (undefined !== page_content && '' !== page_content) {
          if (undefined != $e && 'undefined' != typeof $e.internal) {
            elementor.channels.data.trigger('template:before:insert', templateModel);
            elementor.getPreviewView().addChildModel(page_content, {
              at: DemoImporterPlus.index
            } || {});
            elementor.channels.data.trigger('template:after:insert', {});
            $e.internal('document/save/set-is-modified', {
              status: true
            });
          } else {
            elementor.channels.data.trigger('template:before:insert', templateModel);
            elementor.getPreviewView().addChildModel(page_content, {
              at: DemoImporterPlus.index
            } || {});
            elementor.channels.data.trigger('template:after:insert', {});
            elementor.saver.setFlagEditorChange(true);
          }
        }
        DemoImporterPlus.insertActionFlag = true;
        DemoImporterPlus._close();
      });
    }
  }), "import_contact_form7", function import_contact_form7(contact_form, callback) {
    if ('undefined' == typeof contact_form) {
      if (callback && typeof callback == 'function') {
        callback('');
      }
      return;
    }
    $.ajax({
      url: demoImporterVars.ajaxurl,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'demo-importer-plus-import-contactforms',
        contact_forms: contact_form,
        _ajax_nonce: demoImporterVars._ajax_nonce
      },
      beforeSend: function beforeSend() {
        console.groupCollapsed('Importing Contact Form 7');
        DemoImporterPlus._log_title('Importing Contact Form 7..');
      }
    }).fail(function (jqXHR) {
      DemoImporterPlus._log(jqXHR);
      DemoImporterPlus._importFailMessage(jqXHR.status + jqXHR.statusText, 'Import Contact Form 7 Failed!', jqXHR);
      console.groupEnd();
    }).done(function (response) {
      DemoImporterPlus._log(response);
      console.groupEnd();
      if (false === response.success) {
        DemoImporterPlus._importFailMessage(response.data, 'Import Contact Form 7 Failed!');
      } else {
        if (callback && typeof callback == 'function') {
          callback(response);
        }
      }
    });
  }), _defineProperty(_defineProperty(_defineProperty(_DemoImporterPlus, "show_page_popup_from_sites", function show_page_popup_from_sites(e) {
    e.preventDefault();
    if ($(this).hasClass('updating-message')) {
      return;
    }
    $('.demo-import-sitest-result-prev').addClass('import-page').removeClass('import-site');
    DemoImporterPlus.show_page_popup();
  }), "show_pages_by_site_id", function show_pages_by_site_id(site_id, page_id) {
    var sites = demoImporterVars.default_page_builder_sites || [];
    // var data = sites[site_id];

    var data = sites.find(function (site) {
      return site.id == site_id;
    });
    if ('undefined' !== typeof data) {
      var site_template = wp.template('demo-imprt-single-site-preview');
      if (!DemoImporterPlus._getParamFromURL('demo-importer-site')) {
        var url_params = {
          'demo-importer-site': site_id
        };
        DemoImporterPlus._changeAndSetURL(url_params);
      }
      $('#demo-import-plus').hide();
      $('#site-pages').show().html(site_template(data)).removeClass('brizy elementor beaver-builder gutenberg').addClass(demoImporterVars.default_page_builder);
      $('body').addClass('demo-importer-previewing-single-pages');
      $('#site-pages').attr('data-site-id', site_id);
      if (DemoImporterPlus._getParamFromURL('demo-importer-page')) {
        DemoImporterPlus._set_preview_screenshot_by_page($('#single-pages .site-single[data-page-id="' + DemoImporterPlus._getParamFromURL('demo-importer-page') + '"]'));
        // Has first item?
        // Then set default screnshot in preview.
      } else if (page_id && $('#single-pages .site-single[data-page-id="' + page_id + '"]').length) {
        DemoImporterPlus._set_preview_screenshot_by_page($('#single-pages .site-single[data-page-id="' + page_id + '"]'));
      } else if ($('#single-pages .site-single').eq(0).length) {
        DemoImporterPlus._set_preview_screenshot_by_page($('#single-pages .site-single').eq(0));
      }
      if (!$('#single-pages .site-single').eq(0).length) {
        $('.site-import-layout-button').hide();
      }
    }
    // this changes the scrolling behavior to "smooth"
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  }), "_removePluginFromQueue", function _removePluginFromQueue(removeItem, pluginsList) {
    return jQuery.grep(pluginsList, function (value) {
      return value.slug != removeItem;
    });
  }));
  /**
   * Initialize DemoImporterPlus
   */
  $(function () {
    DemoImporterPlus.init();
  });
})(jQuery);
//# sourceMappingURL=demo-importer-plus-admin.js.map

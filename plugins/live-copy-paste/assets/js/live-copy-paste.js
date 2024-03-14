(function (window, document, $, undefined) {
  "use strict";
  var BdThemesLiveCopy = {
    init: function (e) {
      BdThemesLiveCopy.globalVars();
      BdThemesLiveCopy.loadContextMenuGroupsHooks();
    },

    setElementId: function (elements) {
      return (
        elements.forEach(function (item) {
          (item.id = elementorCommon.helpers.getUniqueId()),
            0 < item.elements.length &&
              BdThemesLiveCopy.setElementId(item.elements);
        }),
        elements
      );
    },
    globalVars: function (e) {
      window.lc_ajax_url = bdt_live_copy.ajax_url;
      window.lc_ajax_nonce = bdt_live_copy.nonce;
      window.lc_key = bdt_live_copy.magic_key;
    },
    loadContextMenuGroupsHooks: function () {
      elementor.hooks.addFilter(
        "elements/section/contextMenuGroups",
        function (groups, element) {
          return BdThemesLiveCopy.prepareMenuItem(groups, element);
        }
      );

      elementor.hooks.addFilter(
        "elements/widget/contextMenuGroups",
        function (groups, element) {
          return BdThemesLiveCopy.prepareMenuItem(groups, element);
        }
      );

      elementor.hooks.addFilter(
        "elements/column/contextMenuGroups",
        function (groups, element) {
          return BdThemesLiveCopy.prepareMenuItem(groups, element);
        }
      );

      elementor.hooks.addFilter(
        "elements/container/contextMenuGroups",
        function (groups, element) {
          return BdThemesLiveCopy.prepareMenuItem(groups, element);
        }
      );
    },
    prepareMenuItem: function (groups, element) {
      var index = _.findIndex(groups, function (element) {
        return "clipboard" === element.name;
      });
      groups.splice(index + 1, 0, {
        name: "bdt-live-copy-paste",
        actions: [
          {
            name: "bdt-live-copy",
            title: "Live Copy",
            icon: "eicon-copy",
            callback: function () {
              alert(
                "Oops! The 'Live Copy' button has been deprecated. You can now use Elementor's built-in 'Copy' feature instead. Enjoy the improved functionality!"
              );
            },
          },
          {
            name: "bdt-live-paste",
            title: "Live Paste",
            icon: "eicon-clone",
            callback: function () {
              alert(
                "Oops! The 'Live Paste' button has been deprecated. You can now use Elementor's built-in 'Paste from other site' feature instead. Enjoy the improved functionality!"
              );
            },
          },
        ],
      });
      return groups;
    },
  };
  BdThemesLiveCopy.init();
})(window, document, jQuery, bdtLiveCopyLocalStorage);

/* eslint-disable import/no-extraneous-dependencies */

import 'core-js/modules/es6.promise';
import 'core-js/modules/es6.array.iterator';
import 'core-js/modules/es6.array.for-each';

const config = {
  attributes: true,
};

function getBooleanValue(str) {
  return str === 'true';
}

function padConfirmDialog(ariaExpanded) {
  const dialogs = Array.prototype.slice.call(
    document.querySelectorAll('.confirm-dialog-container-fullscreen'),
  );
  if (dialogs.length > 0) {
    if (ariaExpanded) {
      dialogs.forEach((dialog) => {
        dialog.classList.add('pad-left');
      });
    } else {
      dialogs.forEach((dialog) => {
        dialog.classList.remove('pad-left');
      });
    }
  }
}

function setSidebarExpandedAttributeOnMnetApp(sidebarExpanded) {
  const mnetApp = document.querySelector('#mnet-vue-app');
  mnetApp.setAttribute('data-sidebarexpanded', sidebarExpanded);
}

const callback = function (mutationsList) {
  /* eslint-disable no-restricted-syntax */
  for (const mutation of mutationsList) {
    if (
      mutation.type === 'attributes'
      && mutation.attributeName === 'aria-expanded'
    ) {
      let ariaExpanded = getBooleanValue(
        mutation.target.getAttribute('aria-expanded'),
      );
      const { clientWidth } = mutation.target;
      const dialogContainers = Array.prototype.slice.call(
        document.querySelectorAll('.confirm-dialog-container-fullscreen'),
      );
      if (clientWidth === 0) {
        ariaExpanded = false;
        dialogContainers.forEach((dialogContainer) => {
          dialogContainer.setAttribute('data-sidebar', 'absent');
        });
      } else if (clientWidth > 0) {
        dialogContainers.forEach((dialogContainer) => {
          if (ariaExpanded) {
            dialogContainer.setAttribute('data-sidebar', 'expanded');
          } else {
            dialogContainer.setAttribute('data-sidebar', 'collapsed');
          }
        });
      }

      setSidebarExpandedAttributeOnMnetApp(ariaExpanded);
      padConfirmDialog(ariaExpanded);
    }
  }
};

function onLoad() {
  const wpSideMenuObserver = new MutationObserver(callback);
  const wpCollapseButton = document.querySelector('#collapse-button');
  setSidebarExpandedAttributeOnMnetApp(
    getBooleanValue(wpCollapseButton.getAttribute('aria-expanded')),
  );
  wpSideMenuObserver.observe(wpCollapseButton, config);
}
window.addEventListener('load', onLoad);

window.trackEvent = function (
  category,
  action,
  label = '',
  appendDomain = true,
) {
  let eventLabel = label;
  if (appendDomain) {
    eventLabel += ` - ${window.location.host}`;
  }
  if (typeof window.ga === 'function') {
    window.ga('send', 'event', category, action, eventLabel);
  }
};

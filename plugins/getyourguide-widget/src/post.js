import { addSearchStyle } from './block/Search/Search';
import { classNames, selectors } from './block/config';
import { iframeHandlers } from './block/common/Iframe';

const handleSearchSubmit = () => {
  const searchElements = document.querySelectorAll(`.${classNames.searchForm}`);
  if (!searchElements) {
    return;
  }
  searchElements.forEach((el) => {
    el.addEventListener('submit', (e) => {
      e.preventDefault();
      const qEl = el.querySelector(`.${classNames.searchQueryInput}`);
      const inputEl = el.querySelector(`.${classNames.searchInput}`);
      const button = el.querySelector('button');
      if (!qEl || !inputEl) {
        return;
      }
      qEl.value = inputEl.value;
      el.submit();
      button.style.backgroundColor = '#0079e1';
      button.querySelector('span').innerHTML = 'Loading...';
      button.disabled = true;
    });
  });
};

const doIframeHandlers = () => {
  const iframeContainers = document.querySelectorAll(selectors.iframeContainer);
  if (!iframeContainers) {
    return;
  }
  iframeContainers.forEach(el => iframeHandlers(el));
};

doIframeHandlers();
handleSearchSubmit();
addSearchStyle();

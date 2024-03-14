'use strict';

/**
 * SideMenuPro
 * A JavaScript class for creating customizable side menus.
 *
 * @version 1.0.0
 * @license MIT License
 * @author Dmytro Lobov
 * @url https://wow-estore.com/item/side-menu-pro/
 */

'use strict';

document.addEventListener('DOMContentLoaded', function() {

  const SideMenus = document.querySelectorAll('.side-menu');

  if(SideMenus) {

    SideMenus.forEach(sidemenu => {

      const links = Array.from(sidemenu.querySelectorAll('.sm-link'));
      links.forEach((link) => {
        link.addEventListener('touchend', function(event){
          sideToggleItemOpen(link, event);
        });
      });
    });
  }

  function sideToggleItemOpen(link, event) {
    const item = link.closest('.sm-item');
    const {classList} = item;

    if (!classList.contains('sm-open')) {
      event.preventDefault();
      classList.add('sm-open');
      setTimeout(() => classList.remove('sm-open'), 3000);
    }
  }

});
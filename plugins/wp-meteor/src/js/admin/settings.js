/**
 *   WP Meteor Wordpress Plugin
 *   Copyright (C) 2020  Aleksandr Guidrevitch
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

// IE11 Symbol.iterator
import 'core-js/modules/es.symbol.iterator';
import React from 'react';
import ReactDOM from 'react-dom';
import Ultimate from './includes/ultimate.jsx';
import Simple from './includes/simple.jsx';
import Textarea from './includes/textarea.jsx';
import RegexpTextarea from './includes/regexp-textarea.jsx';
import dispatcher from './includes/dispatcher';

if (!NodeList.prototype[Symbol.iterator]) {
    // IE11 NodeList iterator;
    NodeList.prototype[Symbol.iterator] = [][Symbol.iterator]
}
document.addEventListener("DOMContentLoaded", () => {
    let activeTab;

    jQuery("#tabs").tabs({
        create: (event, ui) => {
            activeTab = '#' + ui.panel.attr('id');
        },
        activate: (event, ui) => {
            activeTab = '#' + ui.newPanel.attr('id');
            if (window.history) {
                history.pushState(null, null, activeTab);
            }
            dispatcher.emit('rerender');
        }
    });

    const tabs = jQuery('#tabs a.tab-handle[href]');

    jQuery(document).on('click', '#tabs a[href]:not(.tab-handle)', (e) => {
        jQuery(tabs).each((index, tab) => {
            if (tab.href === e.target.href) {

                e.preventDefault();
                jQuery('#tabs').tabs("option", "active", index);
                // console.log(tab.href);
            }
        });
    });

    /* react components might emit invalid nodes so we can switch tabs */
    dispatcher.on('invalid', node => {
        const tab = jQuery(node.current).closest('.tab');
        jQuery('#tabs').tabs("option", "active", jQuery('#tabs .tab').index(tab));
    });

    document.querySelector('form').addEventListener('submit', e => {
        dispatcher.emit('submit', e);
    });

    [...document.querySelectorAll('.ultimate')].forEach(el => {
        ReactDOM.render(
            <Ultimate prefix={el.dataset.prefix} title={el.dataset.title} settings={_wpmeteor.blockers[el.dataset.prefix]} />,
            el
        );
    });

    [...document.querySelectorAll('.simple')].forEach(el => {
        ReactDOM.render(
            <Simple prefix={el.dataset.prefix} title={el.dataset.title} settings={_wpmeteor.blockers[el.dataset.prefix]} />,
            el
        );
    });

    [...document.querySelectorAll('.textarea')].forEach(el => {
        ReactDOM.render(
            <Textarea prefix={el.dataset.prefix} title={el.dataset.title} settings={_wpmeteor.blockers[el.dataset.prefix]} />,
            el
        );
    });

    [...document.querySelectorAll('.regexp-textarea')].forEach(el => {
        ReactDOM.render(
            <RegexpTextarea prefix={el.dataset.prefix} title={el.dataset.title} settings={_wpmeteor.blockers[el.dataset.prefix]} />,
            el
        );
    });

});

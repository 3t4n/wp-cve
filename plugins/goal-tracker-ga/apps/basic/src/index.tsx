declare var wp: any;
declare var wpGoalTrackerGa: any;

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import { createRoot } from 'react-dom/client';

import {
  AdjustmentsIcon,
  QuestionMarkCircleIcon,
} from '@heroicons/react/solid';
import classNames from 'classnames';
import { TabContextProvider } from 'ui/src/context/TabContext';
import logoHelper from './assets/images/Logo-helper.svg';
import './main.css';

/*Code goes here
 * Output : build/index.js
 * */

const { __ } = wp.i18n;

const { render, useState } = wp.element;

const { apiFetch } = wp;

const {
  TabPanel,
  Notice,
  Button,
  Card,
  CardHeader,
  CardBody,
  CardDivider,
  CardFooter,
  Spinner,
} = wp.components;

// import {
// 	GetTab,
// 	RenderTab
// } from "./components/tabs";

import { HashRouter, Route, Routes } from 'react-router-dom';

import { PluginFooter } from 'ui';
import PluginNav from 'ui/src/components/nav';

import { GettingStartedGuide } from 'ui';
import CustomEvents from './components/CustomEvents';
import ProPromo from './components/ProPromo';
import Settings from './components/Settings';
import { GeneralSettingsProvider } from 'ui/src/context/GeneralSettingsContext';
import { NavigationProvider } from 'ui/src/context/NavigationContext';
import { PromoContextProvider } from 'ui/src/context/PromoContext';
import HelpSliderProvider from 'ui/src/context/HelpSliderProvider';
import { MigrateToGoalTracker } from 'ui/src/components/MigrateToGoalTracker';

const initialTabs = [
  {
    name: 'Tracker',
    href: '/tracker',
    current: true,
    primary: true,
    hasIssue: false,
    isPromo: false,
    rootTab: false,
  },
  {
    name: 'Migrate',
    href: '/migrate',
    hasIssue: false,
    rootTab: false,
  },
  {
    name: (
      <span
        data-component="tab-title"
        className="text-xl uppercase flex-1 flex items-center"
      >
        <img
          // absolute -top-7 -left-1
          className="w-12 h-12"
          src={logoHelper}
        ></img>
        <span className="mx-4">Track More...</span>
        <span
          className={classNames(
            'bg-slate-500',
            // 'bg-brand-primary',
            'text-white',
            'text-xs px-2 py-1',
            'rounded uppercase',
            'ml-4',
          )}
        >{`PRO`}</span>
      </span>
    ),
    href: '/pro',
    firstTime: true,
    hasIssue: false,
    isPro: true,
    isPromo: true,
    rootTab: false,

    // icon: <AdjustmentsIcon className="h-8 w-8" aria-hidden="true" />,
  },
  {
    name: 'Settings',
    href: '/settings',
    isFirstTime: true,
    hasIssue: false,
    rootTab: false,
    icon: <AdjustmentsIcon className="h-8 w-8" aria-hidden="true" />,
  },
  {
    name: 'Help',
    showTitle: false,
    href: '/help',
    firstTime: true,
    hasIssue: false,
    rootTab: false,
    icon: <QuestionMarkCircleIcon className="h-8 w-8" aria-hidden="true" />,
  },
];

const navLinks = [
  {
    label: 'Click',
    path: '/tracker/click-tracking',
    default: true,
  },
  {
    label: 'Visibility',
    path: '/tracker/visibility-tracking',
    default: false,
  },
  {
    label: 'WooCommerce',
    path: '/tracker/ecommerce-tracking',
    default: false,
  },
  { label: 'Video', path: '/tracker/video-tracking', default: false },
  { label: 'Forms', path: '/tracker/form-tracking', default: false },
];

const getComponentForRootPath = (tabName: string) => {
  switch (tabName) {
    case 'Tracker':
      return <CustomEvents />;
    case 'Settings':
      return <Settings />;
    default:
      return <Settings />;
  }
};

const AddSettings = () => {
  const rootElement = document.getElementById(wpGoalTrackerGa.root_id);
  const initialPrimaryTab = rootElement?.getAttribute('data-primary-tab');

  const componentForRootPath = getComponentForRootPath(initialPrimaryTab ?? '');

  const modifiedInitialTabs = initialTabs.map(tab => ({
    ...tab,
    rootTab: tab.name === initialPrimaryTab ? true : tab.rootTab,
  }));

  return (
    <TabContextProvider initialTabs={modifiedInitialTabs}>
      <GeneralSettingsProvider>
        <NavigationProvider navLinks={navLinks}>
          <PromoContextProvider>
            <HelpSliderProvider>
              <section data-component="PluginMain" className="relative">
                <HashRouter>
                  <PluginNav />
                  <Routes>
                    <Route path="/tracker/*" element={<CustomEvents />} />
                    <Route
                      path={'/migrate'}
                      element={<MigrateToGoalTracker />}
                    ></Route>
                    <Route path={'/pro'} element={<ProPromo />}></Route>
                    <Route
                      path={'/help'}
                      element={<GettingStartedGuide />}
                    ></Route>
                    <Route path={'/settings'} element={<Settings />}></Route>
                    <Route path="/" element={componentForRootPath} />
                  </Routes>
                </HashRouter>
                <PluginFooter></PluginFooter>
              </section>
            </HelpSliderProvider>
          </PromoContextProvider>
        </NavigationProvider>
      </GeneralSettingsProvider>
    </TabContextProvider>
  );
};

import React from 'react';

document.addEventListener('DOMContentLoaded', () => {
  const reactVersion = React.version.split('.').map(Number);
  if (reactVersion[0] >= 18) {
    const rootElement = document.getElementById(wpGoalTrackerGa.root_id);
    if (rootElement) {
      const root = createRoot(rootElement);
      root.render(<AddSettings />);
    }
  } else {
    if (
      'undefined' !== typeof document.getElementById(wpGoalTrackerGa.root_id) &&
      null !== document.getElementById(wpGoalTrackerGa.root_id)
    ) {
      render(<AddSettings />, document.getElementById(wpGoalTrackerGa.root_id));
    }
  }
});

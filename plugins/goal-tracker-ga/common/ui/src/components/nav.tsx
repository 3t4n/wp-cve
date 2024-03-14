import classNames from 'classnames';
import React, { useContext } from 'react';
import { NavLink, useLocation } from 'react-router-dom';
import { PluginHeader, SliderBanner } from 'ui';
import { Tab, TabContext } from 'ui/src/context/TabContext';
import { usePromoContext } from '../context/PromoContext'; // Import the usePromoContext hook
import { isLinkActive } from './sharedFunctions';

const PluginNav: React.FC = () => {
  const location = useLocation();
  const tabsContext = useContext(TabContext);
  const { showPromo } = usePromoContext(); // Replace the showPromo prop with the usePromoContext hook

  if (!tabsContext) {
    throw new Error('ChildComponent must be used within a TabContextProvider');
  }

  const { pathname } = location;
  const splitLocation = pathname.split('#');

  return (
    <header data-component="PluginNav">
      <PluginHeader />
      <div className={classNames('sm:block', '')}>
        <nav
          className={classNames('relative', ' z-50 flex', 'space-x-1')}
          aria-label="Tabs"
        >
          {tabsContext.tabs.map((tab: Tab, tabIdx: number) => (
            <NavLink
              aria-label={tab.name.toString()}
              title={tab.name.toString()}
              to={tab.href}
              key={tab.name.toString()}
              className={classNames(
                'group relative',
                'flex items-center',
                { 'flex-1': tab.primary },
                splitLocation[0].includes(tab.href) ||
                  (tab.rootTab && splitLocation[0] === '/')
                  ? 'bg-white text-brand-primary border-t-2 border-x border-black -mt-2 h-18 focus:border-none'
                  : 'bg-white/60 text-grey-800 h-16',
                'focus:z-10',
                'flex items-center',
                'group relative',
                'border-transparent border-t-4 border-x-2 border-b-0',
                'focus:border-transparent focus:ring-0',
                'active:border-b-0',
                'hover:border-t-4 hover:border-black/90',
                'px-4',
                'text-sm font-medium text-center',
                'rounded-t-lg',
                '',
              )}
              aria-current={
                splitLocation[0].includes(tab.href) ? 'page' : undefined
              }
            >
              {tab.hasIssue && (
                <span className="w-4 h-4 absolute bg-red-600 rounded-full -top-1 -left-2"></span>
              )}

              {tab.icon ? (
                tab.icon
              ) : (
                <span
                  data-component="tab-title"
                  className={classNames('text-xl uppercase flex-1', {
                    'sr-only': tab.showTitle,
                  })}
                >
                  {tab.name}
                </span>
              )}
            </NavLink>
          ))}
        </nav>
        {/* {showPromo && !isLinkActive('#/pro') && <SliderBanner />} */}
      </div>
    </header>
  );
};

export default PluginNav;

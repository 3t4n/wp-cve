import { PlusCircleIcon, PlusIcon } from '@heroicons/react/solid';
import classNames from 'classnames';
import React, { useContext } from 'react';
import { NavLink } from 'react-router-dom';
import { NavLinkItem, NavigationContext } from '../context/NavigationContext';
import { isLinkActive } from './sharedFunctions';

interface EventsTableHeaderProps {
  setAddCustomEventForm?: (value: boolean) => void;
  setAddRecommendedEventForm?: (value: boolean) => void;
}

export const EventsTableHeader: React.FC<EventsTableHeaderProps> = ({
  setAddCustomEventForm,
  setAddRecommendedEventForm,
}) => {
  const { navLinks } = useContext(NavigationContext);

  const renderNavLink = (item: NavLinkItem, index: number) => {
    const isActive =
      isLinkActive(`#${item.path}`) ||
      (item.default && isLinkActive('#/tracker'));

    return (
      <NavLink
        key={index}
        className={classNames(
          isActive
            ? 'bg-brand-600 hover:text-white cursor-default text-white'
            : 'bg-brand-600/20 text-brand-500 hover:bg-brand-primary hover:text-white border-brand-600',
          'text-base 2xl:text-xl',
          index === 0 && 'rounded-l-full',
          index === navLinks.length - 1 && 'rounded-r-full',
          'px-6',
          'py-1',
          'uppercase',
        )}
        to={item.path}
      >
        {item.label}
      </NavLink>
    );
  };

  return (
    <header
      data-component="EventsTableHeader"
      className="flex flex-col xl:flex-row items-center bg-white py-4 px-8 shadow-lg"
    >
      <div className="divide divide-x flex items-center rounded-full border-brand-primary xl:mr-2 shadow-3xl w-full">
        {navLinks.map((item, index) => renderNavLink(item, index))}
      </div>
      <div className="xl:flex-1"></div>
      <fieldset
        data-component="button-group"
        className="space-x-4 mt-8 xl:mt-0 flex items-center justify-end w-full"
      >
        {setAddCustomEventForm && (
          <button
            onClick={() => {
              if (setAddCustomEventForm) {
                setAddCustomEventForm(true);
              }
            }}
            type="button"
            className={classNames(
              'capitalize flex',
              'items-center justify-center',
              'rounded-full',
              'border border-transparent bg-brand-primary',
              'px-4 2xl:py-2 py-1',
              'text-sm font-medium text-white',
              'shadow-sm',
              'hover:bg-brand-600 shadow hover:shadow-xl active:shadow-xl',
              'focus:outline-none focus:ring-2 focus:ring-brand-primary-focus focus:ring-offset-2',
              'transform active:scale-75 hover:scale-105 transition-transform',
            )}
          >
            <PlusIcon className="2xl:h-5 2xl:w-5 h-4 w-4" aria-hidden="true" />
            <span className="hidden 2xl:flex mx-2">{`Add Event`}</span>
            <span className="2xl:hidden mx-2">{`Event`}</span>
          </button>
        )}

        {setAddRecommendedEventForm && (
          <button
            onClick={() => {
              if (setAddRecommendedEventForm) {
                setAddRecommendedEventForm(true);
              }
            }}
            type="button"
            className={classNames(
              'capitalize inline-flex',
              'items-center justify-center',
              'rounded-full',
              'border border-transparent bg-brand-primary',
              'px-4 2xl:py-2 py-1',
              'text-sm font-medium text-white',
              'shadow-sm',
              'hover:bg-brand-600 shadow hover:shadow-xl active:shadow-xl',
              'focus:outline-none focus:ring-2 focus:ring-brand-primary-focus focus:ring-offset-2',
              'transform active:scale-75 hover:scale-105 transition-transform',
            )}
          >
            <span className="hidden 2xl:flex mx-2">{`Add Recommended Event`}</span>
            <span className="2xl:hidden mx-2">{`Recommended Event`}</span>
          </button>
        )}
      </fieldset>
    </header>
  );
};

export default EventsTableHeader;

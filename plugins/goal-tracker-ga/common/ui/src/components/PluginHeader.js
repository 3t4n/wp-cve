import { CalendarIcon } from '@heroicons/react/solid';
import classNames from 'classnames';
import logo from '../assets/images/Plugin-Logo-GoogleAnalyticsGA4-noCode.svg';
import meetMe from '../assets/images/face-square-hr.png';

export function PluginHeader() {
  const showScheduleLink = false
  return (
    <div
      data-component="PluginHeader"
      className={classNames(
        'flex items-center justify-between',
        'pb-5 pt-10 px-4 xl:px-8',
      )}
    >
      {/* <h1 className="text-lg leading-6 font-medium text-gray-1000">Goal Tracker GA</h1> */}
      <img className="w-64 md:w-72 xl:w-96 h-full" src={logo}></img>
      {
        showScheduleLink && (
          <div
            className={classNames(
              'flex flex-1 justify-start text-left',
              'h-14',
              'p-4 pr-2 max-w-fit',
              'rounded-full',
              'bg-transparent md:bg-white md:shadow-lg',
            )}
          >
            <div className="relative flex-1 md:flex max-h-20 items-center">
              <div className="flex flex-1 items-center">
                <img
                  className={classNames(
                    'absolute -left-2',
                    'z-10',
                    // ' -left-10',
                    'mr-2',
                    'h-10 w-10',
                    'rounded-full border-solid border-3 border-black',
                  )}
                  src={meetMe}
                  alt=""
                />
                <p className="pl-10 pr-6 text-base font-bold text-brand-500 hidden lg:block">
                  Need help? Tell us what you think
                </p>
                <p className="pl-10 pr-6 text-lg font-bold text-brand-500 hidden sm:block lg:hidden">
                  Need help?
                </p>
              </div>
              <p className="text-sm md:ml-5">
                <a
                  target="_blank"
                  href="https://savvycal.com/goal-tracker-yuval/c46fb400"
                  className={classNames(
                    'rounded-full border border-transparent',
                    'inline-flex items-center ',
                    'px-4 py-2 text-sm font-medium text-white shadow-sm',
                    'bg-brand-primary hover:bg-indigo-800 hover:text-white',
                    // 'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
                    'transform active:scale-75 hover:scale-105 transition-transform',
                    'hover:ring-2 hover:ring-white hover:ring-offset-2',
                  )}
                >
                  <CalendarIcon className="h-6 w-6 inline" aria-hidden="true" />
                  {/* <svg
                xmlns="http://www.w3.org/2000/svg"
                className="h-4 w-4"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fillRule="evenodd"
                  d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                  clipRule="evenodd"
                />
              </svg> */}

                  <span className="ml-2">Schedule a free call</span>
                </a>
              </p>
            </div>
          </div>
        )}
    </div>

  );
}

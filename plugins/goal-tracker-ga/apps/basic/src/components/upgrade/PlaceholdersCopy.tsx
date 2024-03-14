import classNames from 'classnames';
import illustrationOctobotty from 'ui/src/assets/images/Logo-track-video-white.svg';
import { ArrowRightIcon } from '@heroicons/react/solid';

declare var wpGoalTrackerGa: any;

export default function PlaceholdersCopy() {
  return (
    <article
      className={classNames(
        'flex flex-col flex-1 justify-end w-full',
        'relative',
        // 'gap-y-8',
        // 'gap-x-16',
        'rounded-2xl p-6 lg:p-8',
        'sm:flex sm:items-end',
        'lg:items-end',
        // 'lg:gap-y-32',
        'shadow-xl hover:shadow-2xl',
        'bg-indigo-700',
      )}
    >
      <header className="text-start text-3xl font-bold tracking-tight text-white w-full">
        <img
          className={classNames(
            'object-cover w-28 h-auto mr-2',
            // 'absolute -top-8 -left-8',
          )}
          src={illustrationOctobotty}
        />
        Smart Placeholders
      </header>
      {/* <div className="sm:w-80 sm:shrink lg:w-auto lg:flex-none"> */}
      <div className="flex-1 w-full justify-self-end mt-4 xl:mt-20 mb-8">
        <p className="text-xl font-semibold tracking-tight text-white">
          Become a GA Power User
        </p>
        <div className="mt-2 text-base leading-7">
          <div className="text-lg text-gray-300">
            One of the biggest hassles in Google Analytics is getting the data
            into events.
            <p className="mt-4 text-lg text-gray-300">
              In GTM, this means working with endless variables. Or writing
              JavaScript code to make the data available in the first place.
            </p>
            <p className="mt-4 text-lg text-gray-300">
              But we are WordPress users. We want this to be simple.
            </p>
            <p className="mt-4 text-lg text-gray-300">
              So we created the Placeholders feature in Goal Tracker Pro.
            </p>
            <p className="mt-4 text-lg text-gray-300">
              <i>
                (If you’ve worked with email marketing software - think mail
                merge or liquid templates)
              </i>
            </p>
            <p className="mt-4 text-lg text-gray-300">
              Placeholders allow you to add data from the element, page, or
              session to the event. Here are a few examples:
            </p>
          </div>
          <ul className="px-2 space-y-2 divide-y divide-white/10 my-4  text-gray-300">
            <li>Page Author</li>
            <li>Post Category</li>
            <li>Value Attribute</li>
            <li>Referrer</li>
            <li>Text</li>
            <li>User Role</li>
            <li>Data Attributes</li>
          </ul>
          <div className="mt-2 text-lg text-gray-300">
            This is a real game changer for power users who work with a lot of
            events.
          </div>
        </div>
      </div>
      <footer className="flex items-center">
        {/* <a
      className="text-brand-400 underline mr-8 text-sm hover:text-brand-300 underline-offset-4"
      href=""
    >{`still unsure?`}</a> */}
        <a
          href={wpGoalTrackerGa.upgradeUrl}
          type="button"
          className={classNames(
            'capitalize inline-flex',
            'items-center justify-center',
            'rounded-full',
            'border border-transparent ',
            'bg-white text-brand-primary',
            'px-4 py-2',
            'text-sm font-medium',
            'shadow hover:shadow-xl',
            'transform active:scale-75 hover:scale-110 transition-transform',
            'hover:ring-2 hover:ring-white hover:ring-offset-2',
            'focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2',
          )}
        >
          <span className="mx-2">{`Unlock Placeholders`}</span>
          <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
        </a>
      </footer>
    </article>
  );
}

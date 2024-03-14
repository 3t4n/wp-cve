import classNames from 'classnames';
import illustrationMedia from 'ui/src/assets/images/Logo-track-video-white.svg';
import { ArrowRightIcon } from '@heroicons/react/solid';

declare var wpGoalTrackerGa: any;

export default function VideoCopy() {
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
        'bg-slate-800',
      )}
    >
      <header className="text-start text-3xl font-bold tracking-tight text-white w-full">
        <img
          className={classNames(
            'object-cover w-28 h-auto mr-2',
            // 'absolute -top-8 -left-8',
          )}
          src={illustrationMedia}
        />
        Track Video & Audio
      </header>
      {/* <div className="sm:w-80 sm:shrink lg:w-auto lg:flex-none"> */}
      <div className="flex-1 w-full justify-self-end mt-4 xl:mt-20 mb-8">
        <p className="text-xl font-semibold tracking-tight text-white">
          Are your videos performing well?
        </p>
        <div className="mt-2 text-base leading-7">
          <div className="text-lg text-gray-300">
            Most people underestimate how long it actually takes to create a
            good video (or a podcast).
            <p className="mt-4 text-lg text-gray-300">
              First, there’s the script. Then you have to set up all the gear
              just right and then all the takes when recording. But that’s even
              before you start editing the video. <br />
              (or maybe you spent a lot of money having someone else make the
              video)
            </p>
            <p className="mt-4 text-lg text-gray-300">
              But are the videos on your website delivering?{' '}
            </p>
            <p className="mt-4 text-lg text-gray-300">
              Wouldn’t it be great to see how many people became leads or made a
              purchase after watching a video?{' '}
            </p>
            <p className="mt-4 text-lg text-gray-300">
              Here’s how Goal Tracker Pro can help you:{' '}
            </p>
          </div>
          <ul className="px-2 space-y-2 divide-y divide-white/10 my-4  text-gray-300">
            <li>
              Track all of your embedded <b className="text-white">YouTube</b>{' '}
              videos
            </li>
            <li>
              Track all of your <b className="text-white">Vimeo</b> videos
            </li>
            <li>
              Track <b className="text-white">self hosted videos</b> (uploaded
              videos)
            </li>
            <li>
              Track self hosted <b className="text-white">Audio</b> (Your
              music/samples/podcast recordings)
            </li>
          </ul>
          <div className="mt-2 text-lg text-gray-300">
            Goal Tracker Pro will tell you:
          </div>
          <ul className="px-2 space-y-2 divide-y divide-white/10 my-4  text-gray-300">
            <li>How many people played the video</li>
            <li>Video progress</li>
            <li>How many people finished watching the video</li>
          </ul>
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
          <span className="mx-2">{`I want to track my videos`}</span>
          <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
        </a>
      </footer>
    </article>
  );
}

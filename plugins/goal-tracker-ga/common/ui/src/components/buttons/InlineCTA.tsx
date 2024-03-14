import exp from 'constants';
import classNames from 'classnames';
import { ArrowCircleRightIcon } from '@heroicons/react/solid';
import { PropsWithChildren } from 'react';

export type InlineCTAProps = PropsWithChildren & {
  className?: string;
  isTitle?: boolean;
  ctaURL: string;
};

const InlineCTA: React.FC<InlineCTAProps> = props => {
  return (
    <a
      type="button"
      href={props.ctaURL}
      className={classNames(
        'capitalize inline-flex',
        'items-center justify-center',
        'rounded-full',
        'border border-transparent bg-brand-primary',
        'pl-2 pr-1 py-1',
        'font-medium text-white',
        'shadow-sm',
        'hover:text-white hover:bg-brand-600 shadow hover:shadow-xl active:shadow-xl',
        'focus:outline-none focus:ring-2 focus:ring-brand-primary-focus focus:ring-offset-2',
        'transform active:scale-75 hover:scale-105 transition-transform',
        'focus:text-white',
        props.isTitle ? 'text-xl' : 'text-sm',
        props.className,
      )}
    >
      <span className="mx-2">{`Get Pro`}</span>
      <ArrowCircleRightIcon
        className={classNames('ml-1', props.isTitle ? 'h-8 w-8' : 'h-5 w-5')}
        aria-hidden="true"
      />
    </a>
  );
};

export default InlineCTA;

import React from 'react';
import { Dialog } from '@headlessui/react';
import { XIcon } from '@heroicons/react/outline';
import QuestionMarkCircleIcon from '@heroicons/react/solid/QuestionMarkCircleIcon';

interface FormHeaderProps {
  currentCustomEvent?: any;
  eventType: string;
  onClose: () => void;
  handleHelpClick?: any;
}

const EventsFormHeader: React.FC<FormHeaderProps> = ({
  currentCustomEvent,
  eventType,
  onClose,
  handleHelpClick,
}) => {
  return (
    <header className="py-5 pl-10 pr-10 sticky top-0 shadow">
      <div className="flex items-start justify-between">
        <Dialog.Title className="text-2xl font-medium text-gray-900">
          {currentCustomEvent ? 'Edit' : 'New'} {eventType}
          <button type="button" onClick={handleHelpClick}>
            <QuestionMarkCircleIcon
              className="h-6 w-6 ml-2 inline hover:text-brand-primary"
              aria-hidden="true"
            />
          </button>
        </Dialog.Title>

        <div className="ml-3 flex h-7 items-center">
          <button
            type="button"
            className="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            onClick={onClose}
          >
            <span className="sr-only">Close panel</span>
            <XIcon className="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
      </div>
    </header>
  );
};

export default EventsFormHeader;

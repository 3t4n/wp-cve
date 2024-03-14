import React, {
  Fragment,
  useLayoutEffect,
  useState,
  ComponentType,
} from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { XIcon } from '@heroicons/react/outline';

interface HelpSliderProps {
  title: string;
  open: boolean;
  Component?: ComponentType;

  setOpen: (state: boolean) => void;
}

const HelpSlider: React.FC<HelpSliderProps> = ({
  title,
  Component,
  open,
  setOpen,
}) => {
  return (
    <>
      <Transition.Root show={open} as={Fragment}>
        <Dialog as="div" className="relative z-[999999]" onClose={setOpen}>
          <Transition.Child
            as={Fragment}
            enter="ease-in-out duration-500"
            enterFrom="opacity-0"
            enterTo="opacity-100"
            leave="ease-in-out duration-500"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
          >
            <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
          </Transition.Child>

          <div className="fixed inset-0" />
          <div className="fixed inset-0 overflow-hidden">
            <div className="absolute inset-0 overflow-hidden">
              <div
                className={`pointer-events-none fixed inset-y-0 right-0 flex ${'w-/3'} min-w-[600px] pl-10 transform transition-all ease-in-out duration-500 sm:duration-700`}
              >
                <Transition.Child
                  as={Fragment}
                  enter="transform transition ease-in-out duration-500 sm:duration-700"
                  enterFrom="translate-x-full"
                  enterTo="translate-x-0"
                  leave="transform transition ease-in-out duration-500 sm:duration-700"
                  leaveFrom="translate-x-0"
                  leaveTo="translate-x-full"
                >
                  <Dialog.Panel className="pointer-events-auto flex-1">
                    <div
                      id="event-form"
                      className="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl"
                    >
                      <div
                        data-component="form-wrapper"
                        className="flex min-h-0 flex-1 flex-col "
                      >
                        <header className="py-5 pl-10 pr-10 sticky top-0 shadow">
                          <div className="flex items-start justify-between">
                            <Dialog.Title className="text-2xl font-medium text-gray-900">
                              {title}
                            </Dialog.Title>
                            <div className="ml-3 flex h-7 items-center">
                              <button
                                type="button"
                                className="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                onClick={() => setOpen(false)}
                              >
                                <span className="sr-only">Close panel</span>
                                <XIcon className="h-6 w-6" aria-hidden="true" />
                              </button>
                            </div>
                          </div>
                        </header>
                        <div
                          data-component="main-form"
                          className="overflow-y-scroll bg-gray-100 p-10 flex-1"
                        >
                          <div className="bg-white p-10 rounded-md">
                            <div className="max-w-[650px] min-h-[410px]">
                              {Component}
                            </div>
                          </div>
                        </div>
                      </div>
                      <footer className="flex flex-shrink-0 justify-end px-4 py-4">
                        <button
                          type="button"
                          className="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                          onClick={() => {
                            setOpen(false);
                          }}
                        >
                          Close
                        </button>
                      </footer>
                    </div>
                  </Dialog.Panel>
                </Transition.Child>
              </div>
            </div>
          </div>
        </Dialog>
      </Transition.Root>
    </>
  );
};

export default HelpSlider;

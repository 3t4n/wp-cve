import { PencilIcon, TrashIcon } from '@heroicons/react/solid';
import classNames from 'classnames';
import React, { Fragment, useLayoutEffect, useState } from 'react';
import AddYourFirstCustomEvent from 'ui/src/components/AddYourFirstCustomEvent';
import { EventsTableHeader } from 'ui/src/components/EventsTableHeader';
import { recommendedEvents } from 'ui/src/components/recommendedEvents';
import CustomEventForm from '../../../../apps/basic/src/components/CustomEventForm';
import DeleteModal from './DeleteModal';
import PropsTag from './PropsTag';
import EventsTableContext from '../../../../apps/basic/src/context/EventsTableContext';
import RecommendedEventsListContext from '../../../../apps/basic/src/context/RecommendedEventsList';
import ClickTrackingHelpSection from './ClickTrackingHelpSection';
import { useHelpSliderContext } from '../context/HelpSliderContext';
import VisibilityTrackingHelpSection from './VisibilityTrackingHelpSection';

interface CustomEvent {
  eventName: string;
  selector: string;
  props: Record<string, any>;
  id: string;
  isRecommended?: boolean;
  [key: string]: any;
}

interface EventsTableProps {
  type: string;
  customEvents: CustomEvent[];
  deleteEvent: (eventId: string) => void;
  updateFunction: () => void;
}

export default function EventsTable({
  type,
  customEvents,
  deleteEvent,
  updateFunction,
}: EventsTableProps) {
  const [checked, setChecked] = useState<boolean>(false);
  const [indeterminate, setIndeterminate] = useState<boolean>(false);
  const [selectedCustomEvents, setSelectedCustomEvents] = useState<
    CustomEvent[]
  >([]);
  const [addCustomEventForm, setAddCustomEventForm] = useState<boolean>(false);
  // const [openHelpSection, setOpenHelpSection] = useState<boolean>(false);

  const [addRecommendedEventForm, setAddRecommendedEventForm] =
    useState<boolean>(false);
  const [delModal, setDelModal] = useState<boolean>(false);
  const [currentCustomEvent, setCurrentCustomEvent] = useState<
    CustomEvent | false
  >();
  const [currentRecommendedEvent, setCurrentRecommendedEvent] = useState<
    CustomEvent | undefined
  >();

  const { setOpenHelpSlider, setTitleHelpSlider, setComponent } =
    useHelpSliderContext();

  const handleHelpButtonClick = () => {
    const title = `Tracking your first ${type} event`;

    let component;
    switch (type) {
      case 'click':
        component = ClickTrackingHelpSection;
        break;
      case 'visibility':
        component = VisibilityTrackingHelpSection;
        break;
      default:
        console.error(`Unknown tracking type: ${type}`);
        return;
    }

    setTitleHelpSlider(title);
    setComponent(component);
    setOpenHelpSlider(true);
  };

  useLayoutEffect(() => {
    const isIndeterminate =
      selectedCustomEvents.length > 0 &&
      selectedCustomEvents.length < customEvents.length;
  }, [selectedCustomEvents]);

  function toggleAddCustomEventForm() {
    setAddCustomEventForm(!addCustomEventForm);
  }

  function toggleAll() {
    setSelectedCustomEvents(checked || indeterminate ? [] : customEvents);
    setChecked(!checked && !indeterminate);
    setIndeterminate(false);
  }

  function toggleDeleteModal() {
    setDelModal(!delModal);
  }

  function toggleEditForm(
    e: React.MouseEvent<HTMLButtonElement>,
    customEvent: CustomEvent,
  ) {
    e.preventDefault();
    let current = { ...customEvent };
    current['edit'] = true;
    if (current.isRecommended) {
      setCurrentRecommendedEvent({ ...current });
      setAddRecommendedEventForm(true);
    } else {
      setCurrentCustomEvent({ ...current });
      setAddCustomEventForm(true);
    }
  }

  function hideEditForm() {
    setCurrentCustomEvent(undefined);
  }

  function showDeleteModal(
    e: React.MouseEvent<HTMLButtonElement>,
    customEvent: CustomEvent,
  ) {
    e.preventDefault();
    setCurrentCustomEvent(customEvent);
    setDelModal(true);
  }

  return (
    <EventsTableContext.Provider value={{ type }}>
      <RecommendedEventsListContext.Provider value={recommendedEvents}>
        <div
          data-component="EventsTable"
          className={classNames('pb-6', 'bg-white/50', 'shadow-xl')}
        >
          <CustomEventForm
            eventType="Recommended Event"
            currentCustomEvent={currentRecommendedEvent}
            setCurrentCustomEvent={setCurrentRecommendedEvent}
            updateFunction={updateFunction}
            open={addRecommendedEventForm}
            setOpen={setAddRecommendedEventForm}
          />
          <CustomEventForm
            eventType="Custom Event"
            currentCustomEvent={currentCustomEvent}
            setCurrentCustomEvent={setCurrentCustomEvent}
            updateFunction={updateFunction}
            open={addCustomEventForm}
            setOpen={toggleAddCustomEventForm}
          />
          <EventsTableHeader
            setAddCustomEventForm={setAddCustomEventForm}
            setAddRecommendedEventForm={setAddRecommendedEventForm}
          />
          <div className="mt-8 flex flex-col px-4 lg:px-6">
            <div className="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div
                className={classNames(
                  'inline-block min-w-full ',
                  'align-middle',
                  'py-2 px-4 lg:px-6',
                )}
              >
                <div className="relative overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                  <table className="w-full table-fixed divide-y divide-gray-300">
                    <thead className="bg-white/75">
                      <tr>
                        <th
                          scope="col"
                          className="min-w-[12rem] py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900"
                        >
                          Custom Event Name
                        </th>

                        <th
                          scope="col"
                          className="w-[10rem] relative py-3.5 pl-3 pr-4 sm:pr-6"
                        >
                          <span className="sr-only">Edit</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody className=" bg-white w-full">
                      {customEvents.length == 0 && !addCustomEventForm && (
                        <tr>
                          <td colSpan={5} className="w-full">
                            <AddYourFirstCustomEvent
                              type={type}
                              setOpenHelpSlider={handleHelpButtonClick}
                            />
                          </td>
                        </tr>
                      )}
                      {customEvents.map((customEvent, index) => (
                        <Fragment key={index}>
                          {
                            <tr
                              className={classNames(
                                selectedCustomEvents.includes(customEvent)
                                  ? 'bg-gray-50'
                                  : undefined,
                                'border-b border-gray-400',
                              )}
                            >
                              <td
                                className={classNames(
                                  'px-4 text-base font-medium',
                                  'py-1',
                                  'pt-2',
                                  selectedCustomEvents.includes(customEvent)
                                    ? 'text-gtIndigo-600'
                                    : 'text-gray-900',
                                )}
                              >
                                <div>
                                  <span className="text-xs text-gray-400 mr-2 uppercase">
                                    Event Name
                                  </span>
                                  {customEvent.eventName}
                                </div>
                                <div className="text-sm text-gray-600 my-2">
                                  <span className="text-xs text-gray-400 mr-2 uppercase">
                                    Selector
                                  </span>
                                  {customEvent.selector}
                                </div>
                                <div className="text-xs text-gray-400 mr-2 mt-4 uppercase">
                                  Event Properties
                                </div>
                                <div className="flex flex-start w-full items-center content-between py-4 flex-wrap">
                                  {customEvent.props &&
                                    Object.entries(customEvent.props)
                                      .filter(([key, value]) => key !== 'items')
                                      .map(
                                        (
                                          [key, value], // ignore items prop as it is a complex object
                                        ) =>
                                          value ? (
                                            <PropsTag
                                              key={key}
                                              pKey={key}
                                              pValue={value}
                                              del={''}
                                            ></PropsTag>
                                          ) : (
                                            ''
                                          ),
                                      )}
                                </div>
                              </td>
                              {/* <td className="whitespace-nowrap px-3 text-sm text-gray-500">{customEvent.selector}</td> */}
                              <td className="bg-slate-50/75 py-6 px-2 text-right text-sm font-medium sm:px-4 w-[16rem]">
                                <div className="flex items-center flex-wrap space-x-1">
                                  <button
                                    type="button"
                                    onClick={e =>
                                      toggleEditForm(e, customEvent)
                                    }
                                    className={classNames(
                                      'flex-1',
                                      'hover:shadow-xl flex items-center',
                                      'px-2.5 py-1.5 border border-transparent',
                                      'text-xs font-medium rounded shadow-sm',
                                      'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary-focus',
                                      'text-white',
                                      'bg-brand-primary hover:bg-brand-600',
                                    )}
                                  >
                                    <PencilIcon
                                      className="h-4 w-4 mr-1"
                                      aria-hidden="true"
                                    />
                                    <div className="flex-1">{`Edit`}</div>
                                  </button>
                                  <button
                                    type="button"
                                    onClick={e =>
                                      showDeleteModal(e, customEvent)
                                    }
                                    className={classNames(
                                      'hover:shadow-xl flex items-center',
                                      'px-2.5 py-1.5 border border-transparent',
                                      'text-xs font-medium rounded shadow-sm',
                                      'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary-focus',
                                      'text-white',
                                      'bg-brand-danger hover:bg-brand-danger-hover',
                                    )}
                                  >
                                    <TrashIcon
                                      className="h-4 w-4 mx-1"
                                      aria-hidden="true"
                                    />
                                    <div className="flex-1 sr-only">{`Delete`}</div>
                                  </button>
                                </div>
                              </td>
                            </tr>
                          }
                        </Fragment>
                      ))}
                    </tbody>
                  </table>
                  {delModal && currentCustomEvent && (
                    <DeleteModal
                      open={delModal}
                      toggleDeleteModal={toggleDeleteModal}
                      customEvent={currentCustomEvent.eventName}
                      customEventId={currentCustomEvent.id}
                      deleteEvent={deleteEvent}
                      updateFunction={updateFunction}
                    />
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </RecommendedEventsListContext.Provider>
    </EventsTableContext.Provider>
  );
}

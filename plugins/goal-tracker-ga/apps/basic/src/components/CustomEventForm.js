import { Dialog, Transition } from '@headlessui/react';
import { XIcon } from '@heroicons/react/outline';
import React, { Fragment, useContext, useEffect, useState } from 'react';
import AddOrEditEventContext from '../context/AddOrEditEventContext';
import EventFormContext from '../context/EventsFormContext';
import EventsTableContext from '../context/EventsTableContext';
import RecommendedEventsListContext from '../context/RecommendedEventsList';
import ItemsTable from './ItemsTable';
import PropsEdit from 'ui/src/components/EventsForm/PropsEdit';
import RecommendedEventsCombo from './RecommendedEventsCombo';
import RecommendedProps from './RecommendedProps';
import SelectorInputs from './SelectorInputs';
import EventsFormHeader from 'ui/src/components/EventsForm/EventsFormHeader';
import EventsFormFooter from 'ui/src/components/EventsForm/EventsFormFooter';
import PropertiesTable from 'ui/src/components/EventsForm/PropertiesTable';
import { useHelpSliderContext } from 'ui/src/context/HelpSliderContext';
import CustomEventFormHelpSection from 'ui/src/components/help/CustomEventFormHelpSection';
import RecommendedEventFormHelpSection from 'ui/src/components/help/RecommendedEventFormHelpSection';

const { apiFetch } = wp;

const CustomEventForm = ({
  eventType,
  currentCustomEvent,
  setCurrentCustomEvent,
  updateFunction,
  open,
  setOpen,
}) => {
  const recommendedEvents = useContext(RecommendedEventsListContext);
  const { type } = useContext(EventsTableContext);
  const [openItemsForm, setOpenItemsForm] = useState(false);
  const [selector, setSelector] = useState('');
  const [customEventName, setCustomEventName] = useState('');
  const [recommendedEventName, setRecommendedEventName] = useState('');
  const [eventName, setEventName] = useState('');
  const [propKey, setPropKey] = useState('');
  const [propValue, setPropValue] = useState('');
  const [pProps, setPprops] = useState({});
  const [isRecommended, setIsRecommended] = useState(false);
  const [disableNewParameters, setDisableNewParameters] = useState(false);

  const {
    open: helpSliderOpen,
    setOpenHelpSlider,
    setTitleHelpSlider,
    setComponent,
  } = useHelpSliderContext();

  const handleHelpClick = () => {
    const helpTitle = eventType + ' Help';
    setTitleHelpSlider(helpTitle);
    const helpComponent =
      eventType === 'Recommended Event'
        ? RecommendedEventFormHelpSection
        : CustomEventFormHelpSection;
    setComponent(helpComponent);
    setOpenHelpSlider(true);
  };

  var selectedEventPropsAttr;

  const [isSaving, setIsSaving] = useState(false),
    [hasNotice, setNotice] = useState(false),
    [hasError, setError] = useState(false),
    [needSave, setNeedSave] = useState(false);

  // const SettingNotice = () => (
  //   <Notice
  //     onRemove={() => setNotice(false)}
  //     status={hasError ? 'error' : 'success'}
  //   >
  //     <p>
  //       {hasError && __('An error occurred.', 'wp-goal-tracker-ga')}
  //       {!hasError && __('Saved Successfully.', 'wp-goal-tracker-ga')}
  //     </p>
  //   </Notice>
  // );

  useEffect(() => {
    if (currentCustomEvent && Object.keys(currentCustomEvent).length > 0) {
      setSelector(currentCustomEvent.selector);
      setEventName(currentCustomEvent.eventName);
      setPprops(currentCustomEvent.props);
      setIsRecommended(currentCustomEvent.isRecommended);
      setDisableNewParameters(
        currentCustomEvent.props &&
          Object.keys(currentCustomEvent.props).length >= 25
          ? true
          : false,
      );
      selectedEventPropsAttr = recommendedEvents[currentCustomEvent.eventName];
    } else {
      // Clear the state
      setSelector('');
      setEventName('');
      setPropKey('');
      setPropValue('');
      setPprops({});
      if (eventType === 'Recommended Event') {
        setIsRecommended(1);
      }
    }
  }, [open]);

  async function setCustomEvent(event) {
    event.preventDefault();
    if (event.target.id !== 'event-form') return;
    setIsSaving(true);
    const clicksEvent = {
      type: type,
      selector: selector,
      eventName: eventName,
      props: pProps,
      isRecommended: isRecommended,
    };
    let path =
      wpGoalTrackerGa.rest.namespace +
      wpGoalTrackerGa.rest.version +
      '/set_event';
    if (currentCustomEvent) {
      path += '?ID=' + currentCustomEvent.id;
    }

    let data = await apiFetch({
      path: path,
      method: 'POST',
      data: { type: type, config: clicksEvent },
    });

    if (Object.keys(data).length >= 3) {
      // valid response
      setError(false);
      setIsSaving(false);
      setNeedSave(false);
    } else {
      setIsSaving(false);
      setError(true);
      setNeedSave(true);
    }
    setNotice(true);
    updateFunction();
    setCurrentCustomEvent({});
    setOpen(false);
  }

  // const returnObjectFromPropsArray = propsArray => {
  //   const propsObject = propsArray.reduce(
  //     (obj, item) => Object.assign(obj, { [item.dkey]: item.dvalue }),
  //     {},
  //   );
  //   return propsObject;
  // };

  // const handleDynamicFieldsChange = (index, event) => {
  //   let data = [...props];
  //   data[index][event.target.name] = event.target.value;
  //   setProps(data);
  // };

  const addProp = () => {
    let data = { ...pProps };
    data[propKey] = propValue;
    if (Object.keys(data).length >= 25) {
      setDisableNewParameters(true);
    }
    setPprops(data);
  };

  const setProp = (key, value) => {
    let data = { ...pProps };
    data[key] = value;
    setPprops(data);
  };

  const updateItems = items => {
    let data = { ...pProps };
    data.items = items;
    setPprops(data);
  };

  const setEvent = eventName => {
    selectedEventPropsAttr = recommendedEvents[eventName];
    const customEventProps = [];
    if (selectedEventPropsAttr != null) {
      Object.keys(selectedEventPropsAttr).forEach(function (key) {
        customEventProps[key] = '';
      });
    }
    setIsRecommended(selectedEventPropsAttr ? true : false);
    setEventName(eventName);
    setPprops(customEventProps);
  };

  const deleteProp = key => {
    let data = { ...pProps };
    delete data[key];
    if (Object.keys(data).length < 25) {
      setDisableNewParameters(false);
    }
    setPprops(data);
  };

  return (
    <EventFormContext.Provider
      value={{
        eventNameContext: [eventName, setEventName],
        customEventNameContext: [customEventName, setCustomEventName],
        setEventContext: [setEvent],
        selectorContext: [selector, setSelector],
        eventTypeContext: [eventType, eventType],
      }}
    >
      <AddOrEditEventContext.Provider
        value={{ openItemsForm, setOpenItemsForm }}
      >
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
                <div className="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" />
              </Transition.Child>

              <div className="fixed inset-0" />
              <div className="fixed inset-0 overflow-hidden">
                <div className="absolute inset-0 overflow-hidden">
                  <div
                    className={`pointer-events-none fixed inset-y-0 right-0 flex ${
                      openItemsForm || helpSliderOpen ? 'w-2/3' : 'w-1/2'
                    } min-w-[750px] pl-10 transform transition-all ease-in-out duration-500 sm:duration-700`}
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
                        <form
                          id="event-form"
                          onSubmit={setCustomEvent}
                          className="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl"
                        >
                          <div
                            data-component="form-wrapper"
                            className="flex min-h-0 flex-1 flex-col "
                          >
                            <EventsFormHeader
                              currentCustomEvent={currentCustomEvent}
                              eventType={eventType}
                              onClose={() => setOpen(false)}
                              handleHelpClick={handleHelpClick}
                            />
                            <div
                              data-component="main-form"
                              className="overflow-y-scroll bg-gray-100 p-10 flex-1"
                            >
                              <div className="bg-white w-full p-10 rounded-md">
                                <div className="p-10 min-h-[410px]">
                                  <div
                                    data-component="fieldset-wrapper"
                                    className="mt-6 sm:mt-5 space-y-6 sm:space-y-5"
                                  >
                                    {eventType === 'Recommended Event' ? (
                                      <RecommendedEventsCombo />
                                    ) : (
                                      <div className="">
                                        <fieldset className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                                          <label
                                            htmlFor="selector"
                                            className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2 required"
                                          >
                                            Custom Event Name
                                          </label>
                                          <div className="mt-1 sm:mt-0 sm:col-span-2">
                                            <div className="w-full flex rounded-md shadow-sm">
                                              <input
                                                required
                                                type="text"
                                                name="customEvent"
                                                id="customEvent"
                                                placeholder="custom_event_name  "
                                                maxLength="40"
                                                value={eventName || ''}
                                                onChange={e =>
                                                  setEventName(e.target.value)
                                                }
                                                className="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300"
                                              />
                                            </div>
                                          </div>
                                        </fieldset>
                                      </div>
                                    )}
                                    <SelectorInputs />
                                  </div>
                                  {eventType === 'Custom Event' ||
                                  (eventType === 'Recommended Event' &&
                                    eventName) ? (
                                    <>
                                      {eventType === 'Recommended Event' ? (
                                        <div
                                          data-component="fieldset-wrapper"
                                          className="mt-6 sm:mt-20 space-y-6 sm:space-y-5"
                                        >
                                          <h3 className="text-sm leading-6 font-medium text-gray-600 mt-10 border-b border-gray-200 pb-2 uppercase">
                                            <span className="text-gray-600"></span>{' '}
                                            Event{' '}
                                            <span className="text-gray-600 text-sm uppercase ">
                                              Properties:
                                            </span>
                                          </h3>
                                          {
                                            <>
                                              {Object.entries(pProps).map(
                                                ([key, value]) => (
                                                  <RecommendedProps
                                                    key={key}
                                                    pKey={key}
                                                    pValue={value}
                                                    setProp={setProp}
                                                    required={
                                                      isRecommended
                                                        ? recommendedEvents[
                                                            eventName
                                                          ][key].required
                                                        : false
                                                    }
                                                    type={
                                                      isRecommended
                                                        ? recommendedEvents[
                                                            eventName
                                                          ][key].type
                                                        : 'text'
                                                    }
                                                    placeholder={
                                                      isRecommended
                                                        ? recommendedEvents[
                                                            eventName
                                                          ][key].placeholder
                                                        : ''
                                                    }
                                                  ></RecommendedProps>
                                                ),
                                              )}
                                              {/* </div> */}

                                              {pProps && 'items' in pProps ? (
                                                <ItemsTable
                                                  eventName={eventName}
                                                  eventItems={pProps.items}
                                                  updateFunction={updateItems}
                                                />
                                              ) : (
                                                ''
                                              )}
                                            </>
                                          }
                                        </div>
                                      ) : (
                                        pProps && (
                                          <>
                                            <div
                                              className="mt-2 sm:mt-5 space-y-6 sm:space-y-5"
                                              style={{
                                                display: isRecommended
                                                  ? 'none'
                                                  : 'block',
                                              }}
                                            >
                                              <div className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                                <label
                                                  htmlFor="selectorType"
                                                  className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >
                                                  Additional Properties
                                                </label>
                                                <input
                                                  type="text"
                                                  name="dkey"
                                                  placeholder="Key"
                                                  maxLength="40"
                                                  id="dkey"
                                                  value={propKey}
                                                  onChange={e =>
                                                    setPropKey(e.target.value)
                                                  }
                                                  disabled={
                                                    disableNewParameters
                                                  }
                                                  className="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                />

                                                <input
                                                  type="text"
                                                  name="dvalue"
                                                  placeholder="Value"
                                                  id="dvalue"
                                                  maxLength="100"
                                                  value={propValue}
                                                  onChange={e =>
                                                    setPropValue(e.target.value)
                                                  }
                                                  disabled={
                                                    disableNewParameters
                                                  }
                                                  className="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                />
                                                <div className="flex w-full justify-end items-end col-end-4">
                                                  <button
                                                    type="button"
                                                    onClick={addProp}
                                                    disabled={
                                                      disableNewParameters
                                                    }
                                                    className="inline-flex items-center center w-28 px-4 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-brand-primary hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary-focus"
                                                  >
                                                    Add Property
                                                  </button>
                                                </div>
                                              </div>
                                            </div>
                                            <div
                                              data-component="props-table"
                                              className="flex flex-col bg-gray-50 rounded-50 mt-6 "
                                            >
                                              <PropertiesTable
                                                properties={pProps}
                                                isRecommended={isRecommended}
                                                recommendedEvents={
                                                  recommendedEvents
                                                }
                                                eventName={eventName}
                                                setProp={setProp}
                                                deleteProp={deleteProp}
                                              />
                                            </div>
                                          </>
                                        )
                                      )}
                                    </>
                                  ) : (
                                    ''
                                  )}
                                </div>
                              </div>
                            </div>
                          </div>
                          <EventsFormFooter onCancel={() => setOpen(false)} />
                        </form>
                      </Dialog.Panel>
                    </Transition.Child>

                    {/* Second Backdrop When Items Opens */}
                    {false && openItemsForm && (
                      <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-300"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                      >
                        <Dialog.Overlay className="fixed inset-0 bg-gray-800 bg-opacity-60 backdrop-filter  backdrop-saturate-150" />
                      </Transition.Child>
                    )}
                  </div>
                </div>
              </div>
            </Dialog>
          </Transition.Root>
        </>
      </AddOrEditEventContext.Provider>
    </EventFormContext.Provider>
  );
};

export default CustomEventForm;

const SelectorHelp = () => {
  return (
    <div>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Class or ID Selector
      </div>
      <span className="block pt-5">
        In the "Class or ID Selector" field, enter the CSS selector that
        uniquely identifies the webpage element you wish to track, such as a
        button or link.
        <p className="pt-2">
          Use a period (.) for a class or a hash (#) for an ID.
        </p>
        <p className="pt-2">
          For instance, to track clicks on a button with the ID 'purchase',
          input '#purchase' into this field.
        </p>
        <p className="pt-2">
          This CSS selector enables the plugin to monitor interactions with the
          specified element and report them as events to Google Analytics GA4.
        </p>
      </span>
      <span className="block pt-5 pb-5">
        <a
          className="font-medium text-blue-600 dark:text-blue-500 hover:underline"
          target="_blank"
          href="https://www.wpgoaltracker.com/dtp7"
        >
          Click here to learn more about how to set the Class or ID Selector.
        </a>
      </span>
    </div>
  );
};

export default SelectorHelp;

import Placeholders from 'ui/src/assets/images/Placeholders.png';

const ParametersHelp = () => {
  return (
    <div>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Additional Parameters
      </div>
      <span className="block pt-5">
        The "Additional Properties" section allows you to add extra details to
        your event, which are sent as parameters with the event to GA4.
        <p className="pt-2">
          Each property consists of a key and a value. The key is a simple label
          describing the data, like 'color' or 'level', while the value is the
          actual information, such as 'red' or 'hard'.
        </p>
        <p className="pt-2">
          These properties help you segment and analyze your event data more
          granularly in GA4. For instance, if you're tracking a 'video_play'
          event, additional properties could include 'video_name' as the key and
          the actual name of the video as the value.
        </p>
        <p className="pt-2">
          This level of detail can give you better insights into user behavior.
        </p>
      </span>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Placeholders (Pro Feature)
      </div>
      <span className="block pt-5">
        Goal Tracker for Google Analytics Pro allows you to use placeholders in
        the "Additional Properties" section.
        <p className="pt-2">
          This feature is useful when you want to track dynamic data, such as
          the name of a product or the price of an item.
        </p>
        <p className="italic pt-2">
          If you worked with Liquid Templates this is very similar.
        </p>
        <p className="pt-2">Here are a few examples:</p>
      </span>
      <img className="pt-5" src={Placeholders} />
      <span className="block pt-5 pb-5">
        <a
          className="font-medium text-blue-600 dark:text-blue-500 hover:underline"
          target="_blank"
          href="https://www.wpgoaltracker.com/ciwr"
        >
          Click here to learn more about Placeholders.
        </a>
      </span>
    </div>
  );
};

export default ParametersHelp;

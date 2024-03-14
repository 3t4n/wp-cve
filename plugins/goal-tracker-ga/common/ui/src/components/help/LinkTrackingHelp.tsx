const LinkTrackingHelp = () => {
  return (
    <div>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Link Tracking
      </div>
      <span className="block pt-5">
        Goal Tracker for Google Analytics offers an improved Link Tracking
        feature as an alternative to GA4's Enhanced Analytics. This feature
        allows you to track both internal and external links using the event
        name <code>link_click</code>, making it more convenient and efficient to
        work with compared to the standard GA4 implementation.
      </span>
      <span className="block pt-5">
        To enable the link tracking feature, toggle the Track Links option and
        then select "Track All Links" to track all the links (both internal and
        external) on the website, or "Track External Links" to track internal
        links.
      </span>
      <span className="block pt-5 pb-5">
        <a
          className="font-medium text-blue-600 dark:text-blue-500 hover:underline"
          target="_blank"
          href="https://www.wpgoaltracker.com/tracking-links-with-goal-tracker-for-google-analytics/"
        >
          Click here to learn more about link tracking.
        </a>
      </span>
    </div>
  );
};

export default LinkTrackingHelp;

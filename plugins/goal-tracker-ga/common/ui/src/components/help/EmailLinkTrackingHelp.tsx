const LinkTrackingHelp = () => {
  return (
    <div>
      <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
        Link Tracking
      </div>
      <span className="block pt-5">
        The Email Link Tracking feature in Goal Tracker for Google Analytics
        specifically tracks email links (mailto:) on your website. It uses the
        event name <code>email_link_click</code> to help you monitor and analyze
        user interactions with email links. To enable this feature, you need to
        perform two steps: toggle the feature in the plugin and create a custom
        dimension "email address" in Google Analytics.
      </span>
      <span className="block pt-5">
        Follow these steps to enable Email Link Tracking:
      </span>

      <span className="block pt-5">
        <ol className="list-decimal list-inside">
          <li className="mb-2">
            Navigate to the Goal Tracker for Google Analytics plugin settings in
            your WordPress dashboard
          </li>
          <li className="mb-2">
            Find the "Email Link Tracking" toggle switch and turn it on
          </li>
          <li className="mb-4">
            Sign in to your Google Analytics account and access the "Admin"
            section
          </li>
          <li className="mb-2">
            Under the "Property" column, click on "Custom Definitions" and then
            "Custom Dimensions"
          </li>
          <li className="mb-2">
            Click on "Create Custom Dimension" and name it "email address"
          </li>
          <li className="mb-2">
            Set the "Scope" to "Event" and click on "Create"
          </li>
        </ol>
      </span>
      <span className="block pt-5">
        <p>
          Once you have completed these steps, the plugin will automatically
          track clicks on email links and send the event data to your Google
          Analytics 4 property under the event name{' '}
          <code>email_link_click</code>. The custom dimension "email address"
          will provide additional insights into the email addresses users
          interact with on your website.
        </p>
        <a
          className="pt-5 font-medium text-blue-600 dark:text-blue-500 hover:underline"
          target="_blank"
          href="https://www.wpgoaltracker.com/tracking-email-links-with-goal-tracker-for-google-analytics/"
        >
          Click here to learn more about email link tracking.
        </a>
      </span>
    </div>
  );
};

export default LinkTrackingHelp;

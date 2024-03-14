const ThirdPartyPluginsHelpSection = () => {
  return (
    <div>
      <div>
        <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
          Working with third-party Google Analytics plugins
        </div>
        <span className="block pt-5">
          <p>
            At times, there may be conflicts between our plugin and third-party
            Google Analytics plugins. In such cases, it is advisable to consider
            toggling the "Don't add the "gtag" code snippet" option.
          </p>
          <p>
            When you toggle the option, our plugin will try to work with
            existing tags on the page.
          </p>
          <p>
            However, this means that our plugin is limited in how it can
            configure the integration with Google Analytics. The following
            features will not be available:
          </p>
        </span>
        <span className="block pt-5 pb-5">
          <ol className="list-decimal list-inside ml-4">
            <li className="mb-2">DebugView</li>
            <li className="mb-2">Disabling Page View Tracking</li>
            <li className="mb-2">User Tracking - Pro feature</li>
            <li className="mb-2">Multiple Trackers - Pro feature</li>
          </ol>
        </span>
      </div>
    </div>
  );
};

export default ThirdPartyPluginsHelpSection;

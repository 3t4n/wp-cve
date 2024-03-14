import SelectorHelp from './SelectorHelp';
import RecommendedEvent from 'ui/src/assets/images/RecommendedEvent.png';

const RecommendedEventFormHelpSection = () => {
  return (
    <div>
      <div>
        <div className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
          Recommended Events
        </div>
        <span className="block pt-5">
          Recommended events in GA4 are pre-configured event types that Google
          Analytics has identified as significant across a wide range of
          websites and apps.
          <p className="pt-2">
            These include common interactions like 'login', 'search', or
            'purchase', which many site owners are interested in tracking.
          </p>
          <p className="pt-2">
            By using these standardized events, even if you're not deeply
            familiar with analytics, you can benefit from Google's expertise in
            user behavior tracking.
          </p>
          <p className="pt-2">
            This helps ensure that you're collecting the right data in a format
            that GA4 can use to generate insightful reports, making it easier
            for you to understand and analyze your users' actions.
          </p>
          <p className="pt-2">
            Recommended events have pre-defined parameters that are sent to GA4.
            When you choose an recommended event, we will show you the
            parameters that are associated with that event.
          </p>
        </span>
        <img className="pt-5" src={RecommendedEvent} />
      </div>
      <SelectorHelp />
    </div>
  );
};

export default RecommendedEventFormHelpSection;

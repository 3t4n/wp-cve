import EmailLinkTrackingHelp from './EmailLinkTrackingHelp';
import LinkTrackingHelp from './LinkTrackingHelp';

const CustomEventsTrackingHelpSection = () => {
  return (
    <div>
      <LinkTrackingHelp />
      <EmailLinkTrackingHelp />
    </div>
  );
};

export default CustomEventsTrackingHelpSection;

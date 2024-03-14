import { FieldsetGroup } from 'ui/src/components/FieldsetGroup';
import { Fieldset } from 'ui/src/components/Fieldset';
import { Section } from 'ui/src/components/Section';
import { HeaderTitle } from 'ui/src/components/HeaderTitle';
import { Switch } from '@headlessui/react';
import classNames from 'classnames';
import { useEffect, useState } from 'react';
import EventsTableHeader from 'ui/src/components/EventsTableHeader';

declare var wpGoalTrackerGa: any;
declare var wp: any;
declare var lodash: any;

const { apiFetch } = wp;
const { isEqual } = lodash;

type ContactForm7 = {
  trackFormSubmit: boolean;
  trackInvalids: boolean;
  trackMailSent: boolean;
  trackMailFailed: boolean;
  trackSpam: boolean;
};

interface FormTrackingSettings {
  contactForm7Settings?: ContactForm7;
}

const FormTracking = () => {
  const [
    contactForm7Settings,
    setContactForm7Settings,
  ] = useState<ContactForm7>({
    trackFormSubmit: false,
    trackInvalids: false,
    trackMailSent: false,
    trackMailFailed: false,
    trackSpam: false,
  });

  return (
    <div
      data-component="EventsTable"
      className={classNames('pb-6', 'bg-white/50', 'shadow-xl')}
    >
      <EventsTableHeader />
      <div className="bg-white/75">
        <div className="bg-white/50 p-5 rounded shadow-xl">
          <div
            data-component="SectionContainer"
            className="space-y-8 sm:space-y-5"
          >
            <Section>
              <HeaderTitle
                title={`Contact Form 7`}
                titleHelper={`Enable tracking for Contact Form 7 forms on your website.`}
                // helpComponent={}
                helpTitle={`Connecting with Google Analytics`}
                proLabel={true}
                ctaURL="https://www.wpgoaltracker.com/2qvp"
              />
              <FieldsetGroup className={'opacity-60'}>
                <Fieldset
                  label={'Track Successful Form Submissions'}
                  id=""
                  description="The plugin will track an event only when a form submission is successful, and an email is sent."
                  isPrimary={false}
                ></Fieldset>

                <Fieldset
                  label={'Track Failed Form Validations'}
                  id=""
                  description="Track and event when someone submits an invalid form (mostly missing required fields)."
                  isPrimary={false}
                ></Fieldset>

                <Fieldset
                  label={'Track "Failed To Send Email" Events'}
                  id=""
                  description="When the form submits successfully but Contact Form 7 failed to send out the email."
                  isPrimary={false}
                ></Fieldset>

                <Fieldset
                  label={'Track SPAM'}
                  id=""
                  description="When Contact Form 7 detects possible spam activity."
                  isPrimary={false}
                ></Fieldset>

                <Fieldset
                  label={'Track Form Submit Click (click ≠ success)'}
                  id=""
                  description="Toggling this option will track submit button clicks regardless of the submission status (click ≠ success)."
                  isPrimary={false}
                ></Fieldset>
              </FieldsetGroup>
            </Section>
          </div>
          <footer className="px-5 py-5 bg-gray-100 shadow-2xl -mx-5">
            <div className="flex justify-end space-x-3"></div>
          </footer>
        </div>
      </div>
    </div>
  );
};
export default FormTracking;

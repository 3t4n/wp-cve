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

type WooCommerceTracking = {
  viewItem: boolean;
  addToCart: boolean;
  viewCart: boolean;
  beginCheckout: boolean;
  addShippingInfo: boolean;
  addPaymentInfo: boolean;
  purchase: boolean;
};

interface EcommerceTrackingSettings {
  wooCommerceSettings?: WooCommerceTracking;
}

const EcommerceTracking = () => {
  const [
    wooCommerceSettings,
    setWooCommerceSettings,
  ] = useState<WooCommerceTracking>({
    viewItem: false,
    addToCart: false,
    viewCart: false,
    beginCheckout: false,
    addShippingInfo: false,
    addPaymentInfo: false,
    purchase: false,
  });

  const actions = [
    {
      id: 'viewItem',
      label: 'View Item',
      description: 'Track when a user views an item.',
    },
    {
      id: 'addToCart',
      label: 'Add to Cart',
      description: 'Track when a user adds an item to their cart.',
    },
    // {
    //   id: 'viewCart',
    //   label: 'View Cart',
    //   description: 'Track when a user views the cart.',
    // },
    {
      id: 'beginCheckout',
      label: 'Begin Checkout',
      description: 'Track when a user starts the checkout process.',
    },
    {
      id: 'addShippingInfo',
      label: 'Add Shipping Info',
      description:
        'Track when the user has entered the shipping info in the checkout process.',
    },
    {
      id: 'addPaymentInfo',
      label: 'Add Payment Info',
      description:
        'Track when the user has entered the payment info in the checkout process.',
    },
    {
      id: 'purchase',
      label: 'Purchase',
      description: 'Track when a user purchases an item.',
    },
  ];

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
                title="WooCommerce Tracking"
                titleHelper={`Track WooCommerce events with Google Analytics`}
                // helpComponent={}
                helpTitle={`WooCommerce`}
                beta={true}
                proLabel={true}
                ctaURL="https://www.wpgoaltracker.com/71iv"
              />
              <FieldsetGroup className={'opacity-60'}>
                {actions.map(action => (
                  <Fieldset
                    key={action.id}
                    id={action.id}
                    label={`Track '${action.label}'`}
                    isPrimary={false}
                    description={action.description}
                  ></Fieldset>
                ))}
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

export default EcommerceTracking;

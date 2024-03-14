<script lang="ts" setup>
import { NH1 } from "naive-ui";

const isPro = window.ShopMagic.proEnabled === '1';

function getImage(name: string) {
  if (navigator.language === "pl-PL") {
    return new URL(`../../../assets/${name}-pl.png`, import.meta.url).href;
  }
  return new URL(`../../../assets/${name}.png`, import.meta.url).href;
}

function getUrl() {
  const utm = new URLSearchParams({
    utm_source: "welcome-screen",
    utm_medium: "button",
    utm_campaign: "shopmagic-welcome",
  });

  let url = "https://shopmagic.app/pricing";
  if (navigator.language === "pl-PL") {
    url = "https://www.wpdesk.pl/sklep/shopmagic/";
  }

  return `${url}?${utm.toString()}`;
}
</script>
<template>
  <div class="wrap about-wrap sm-wrap">
    <NH1>{{
      __(
        "ShopMagic - Follow-Up Emails & Marketing Automation for Your WooCommerce Store",
        "shopmagic-for-woocommerce",
      )
    }}</NH1>
    <p class="about-text">
      {{
        __(
          "Start increasing your revenue, customer loyalty and satisfaction with user-friendly emails based on order status.",
          "shopmagic-for-woocommerce",
        )
      }}
    </p>

    <div class="feature-section">
      <h3>
        {{ __("Set up your automated email", "shopmagic-for-woocommerce") }}
      </h3>
      <p>
        {{ __("Each automation may contain three elements:", "shopmagic-for-woocommerce") }}
        <span
          v-html="
            __(
              '<b>Event</b> (e.g. <i>Order Completed</i>), which is a trigger.',
              'shopmagic-for-woocommerce',
            )
          "
        ></span>
        <span
          v-html="
            __(
              `<b>Filter</b> (e.g. <i>Order - Items</i>), which you can use if
        you want to send email to selected group of customers.`,
              'shopmagic-for-woocommerce',
            )
          "
        ></span>
        <span
          v-html="
            __(
              `Finally, <b>Action</b> (e.g. <i>Send Email</i>),
              in which you can create your message to the recipient.`,
              'shopmagic-for-woocommerce',
            )
          "
        ></span>
      </p>
      <p>
        {{ __("Start by creating a new") }}
        <RouterLink :to="{ name: 'automation', params: { id: 'new' } }">
          {{ __("Automation", "shopmagic-for-woocommerce") }}
        </RouterLink>
        {{ __("or choose one of the", "shopmagic-for-woocommerce") }}
        <RouterLink :to="{ name: 'recipes' }">
          {{ __("ready-to-use Recipes", "shopmagic-for-woocommerce") }}
        </RouterLink>
      </p>
      <ol class="sm-step-counter">
        <li>
          <p>
            <span
              v-html="
                __(
                  `If you decide to create an automation from scratch, select
            an <b>Event</b>, which will be a trigger for your automation.`,
                  'shopmagic-for-woocommerce',
                )
              "
            ></span>
            <span
              v-html="
                __(
                  `You can select from a
            variety of events, from <i>New Order</i>, through
            <i>Order Pending</i> to <i>Order Completed</i>`,
                  'shopmagic-for-woocommerce',
                )
              "
            ></span>
            {{
              __(
                "We have covered all of the WooCommerce order statuses.",
                "shopmagic-for-woocommerce",
              )
            }}
          </p>
          <img
            :alt="
              __(
                'Event setter for automation with Order Completed selected',
                'shopmagic-for-woocommerce',
              )
            "
            :src="getImage('event')"
            class="image"
            height="120"
            width="475"
          />
        </li>
        <li>
          <p
            v-html="
              __(
                `Optionally - assign a <b>Filter</b>. Use it if you want to send
            your automation to the selected group of customers only.`,
                'shopmagic-for-woocommerce',
              )
            "
          ></p>
          <img
            :alt="
              __(
                'Filter setter for automation with Order Items selected',
                'shopmagic-for-woocommerce',
              )
            "
            :src="getImage('filter')"
            class="image"
            height="100"
            width="475"
          />
        </li>
        <li>
          <p
            v-html="
              __(
                `Choose an <b>Action</b>. Select an Action type and enter data
            in action fields, as described. Do not forget to Publish your
            automation.`,
                'shopmagic-for-woocommerce',
              )
            "
          ></p>
          <img
            :alt="
              __(
                'Action setter for automation with Send Email selected',
                'shopmagic-for-woocommerce',
              )
            "
            :src="getImage('action')"
            class="image"
            height="140"
            width="475"
          />
        </li>
      </ol>
      <p>
        <b>{{ __("You Got It!", "shopmagic-for-woocommerce") }}</b>
        {{
          __(
            'After you hit "Publish" your automated emails will start flying to your customers\' inboxes.',
            "shopmagic-for-woocommerce",
          )
        }}
      </p>
    </div>
    <div class="about__section is-fullwidth margin-y-2">
      <iframe
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
        frameborder="0"
        height="560"
        src="https://www.youtube-nocookie.com/embed/UIBnaT_peHc?controls=0"
        title="YouTube video player"
        width="1050"
      ></iframe>
    </div>

    <div v-if="!isPro">
      <div class="has-1-columns">
        <h2>
          {{ __("Achieve more with ShopMagic PRO", "shopmagic-for-woocommerce") }}
        </h2>

        <p class="lead-description">
          {{
            __(
              "Sell more in less time and increase your conversion rate with ShopMagic PRO. These add-ons will get you more happy and loyal customers:",
              "shopmagic-for-woocommerce",
            )
          }}
        </p>
      </div>

      <div class="about__section has-3-columns is-fullwidth">
        <div class="column is-edge-to-edge">
          <i class="dashicons sm-dashicon-large dashicons-clock"></i>
          <h3>
            {{ __("Delayed Actions", "shopmagic-for-woocommerce") }}
          </h3>
          <p>
            {{
              __(
                "Delay your emails by minutes, hours, days or weeks after the original event. Schedule your emails to a precise future date.",
                "shopmagic-for-woocommerce",
              )
            }}
          </p>
        </div>

        <div class="column is-edge-to-edge">
          <i class="dashicons sm-dashicon-large dashicons-admin-comments"></i>
          <h3>
            {{ __("Review Requests", "shopmagic-for-woocommerce") }}
          </h3>
          <p>
            {{
              __(
                "Add review requests with direct links to purchased products. Create automated responses for customers who will review your products.",
                "shopmagic-for-woocommerce",
              )
            }}
          </p>
        </div>

        <div class="column is-edge-to-edge">
          <i class="dashicons sm-dashicon-large dashicons-admin-tools"></i>
          <h3>
            {{ __("Manual Actions", "shopmagic-for-woocommerce") }}
          </h3>
          <p>
            {{
              __(
                "Execute manual actions whenever you need them. Send one-time personalized emails to selected group of customers directly from your WordPress dashboard.",
                "shopmagic-for-woocommerce",
              )
            }}
          </p>
        </div>
      </div>

      <div class="about__section has-3-columns is-fullwidth">
        <div class="column is-edge-to-edge">
          <i class="dashicons sm-dashicon-large dashicons-tag"></i>
          <h3>
            {{ __("Customer Coupons", "shopmagic-for-woocommerce") }}
          </h3>
          <p>
            {{
              __(
                "Adds ability to create personalized coupon codes for customers and send them automatically.",
                "shopmagic-for-woocommerce",
              )
            }}
          </p>
        </div>

        <div class="column is-edge-to-edge">
          <i class="dashicons sm-dashicon-large dashicons-filter"></i>
          <h3>
            {{ __("Advanced Filters", "shopmagic-for-woocommerce") }}
          </h3>
          <p>
            {{
              __(
                "Segment your customers. Target a selected group of customers with emails, coupons or discounts. There are more than 20 filters available for you!",
                "shopmagic-for-woocommerce",
              )
            }}
          </p>
        </div>

        <div class="column is-edge-to-edge">
          <i class="dashicons sm-dashicon-large dashicons-cart"></i>
          <h3>
            {{ __("Abandoned Carts", "shopmagic-for-woocommerce") }}
          </h3>
          <p>
            {{
              __(
                "Recover abandoned carts and increase your sales. Create a set of follow-up email reminders. Ready to use, for guests and registered customers.",
                "shopmagic-for-woocommerce",
              )
            }}
          </p>
        </div>
      </div>
      <br />
      <div class="aligncenter margin-y-2">
        <a
          :href="getUrl()"
          class="button button-primary button-hero sm-button-large proButton"
          target="blank"
          >{{ __("Get ShopMagic PRO", "shopmagic-for-woocommerce") }}
        </a>
      </div>
    </div>
  </div>
</template>
<style>
.sm-wrap .sm-step-counter {
  line-height: 1.8;
  margin-left: 4em;
}
.sm-wrap .sm-step-counter li::marker {
  content: "Step #" counter(list-item) ": ";
  font-weight: 800;
}
.sm-wrap .sm-step-counter li p {
  font-size: inherit;
}
.margin-y-2 {
  margin-top: 2em;
  margin-bottom: 2em;
}
.sm-wrap .sm-button-large {
  font-size: 18px !important;
}
.sm-wrap .image {
  width: 50%;
}
.sm-dashicon-large {
  font-size: 50px;
  margin: 24px 0;
  line-height: 1.2;
}
</style>

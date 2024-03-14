# Custom Tables #

### The schema for each custom table is described below

## The Transactions Table:
This table contains a row for each financial transaction that has taken place.

| Table Column  | Table Column's Description |
| ------------- | ------------- |
| id  | The unique id of the row, which auto increments.  |
| user_id  | The id of the user associated with this transaction. All transactions have a user, as users are automatically generated prior to the transaction if none was found.  |
| date_created | This is the date when the row was created. |
| date_paid | This is the date when Stripe told us that the transaction was paid. For credit cards this will be shortly after the date_created, but for ACH bank transfers, this could vary, and even be days later. |
| period_start_date | If a recurring subscription exists for this transaction, this will store the date when the period began. By default, this will be the same as the date_paid. |
| period_end_date | If a recurring subscription exists for this transaction, this will store the end date of the period, which Stripe sends in their webhook. |
| type | A transaction can either be "initial" or "refund". The value "initial" is used even if this is a one-time payment (not a recurring subscription). |
| method | The payment method used to carry out this transaction. This can be "Apple Pay", "basic card", "subscription", and more. Used for historical purposes. |
| page_url | The URL of the page whre this transaction took place. Used for historical purposes. |
| charged_amount | This is the amount that the user was charged. |
| charged_currency | This is the currency in which the user was charged. |
| home_currency | This is the currency of the site's Stripe account's bank account, and the currency into which Stripe converted amount. |
| gateway_fee_hc | This is the amount of the fee that was charged by Stripe (includes application fees), in your home_currency. |
| earnings_hc | This is the amount after fees are subtracted, which Stripe will deposit into the bank account, in the home_currency. |
| charge_id | This is the ID Stripe uses to identify this transaction. |
| refund_id | This value depends on the transaction "type". If this transaction's type is not "refund" but it has been refunded, this contains the row ID which represents the refund transaction, in this table. If this transaction's type is "refund" itself, this contains the ID Stripe uses to identify this transaction. |
| statement_descriptor | This is the text that will show up on the customer's credit card statement describing this transaction. |
| note_with_tithe | The text entered by the payer/user along with their payment. |
| arrangement_id | The ID of the associated arrangement, in the arrangements table. Every transaction has an associated arrangement, even if it is not a recurring arrangement. |
| payment_intent_id | The Stripe ID of the PaymentIntent used to generate this transaction. Renewals for subscriptions do not use PaymentIntents at this time so those will be blank. |
| is_live_mode | Boolean value (1 for true or 0 for false). 1 if this transaction was in Stripe Live Mode. 0 if this transaction was in Stripe Test Mode. |


## The Arrangements Table:
This table contains a row representing an "Arrangement". An Arrangement can also be thought of as a "Subscription" or "Plan", and is essentially a group of data that represents an agreement with the user about when they will next be charged, and how much. That data is then used to generate new Transactions if/when the time of renewal happens. That time of renewal is triggered by Stripe webhooks. Specifically, the invoice.payment_succeeded webhook. Every transaction has an associated Arrangement, even if it does not have a recurring subscription plan attached. In that scenario, an Arrangement exists to say "the agreement with the user does not include any automatic recurring".

| Table Column  | Table Column's Description |
| ------------- | ------------- |
| id  | The unique id of the row, which auto increments.  |
| user_id  | The id of the user associated with this arrangement. |
| date_created | This is the date when the row was created. |
| initial_transaction_id | This is the ID of the transaction which generated this arrangement. |
| interval_count | The number associated with the recurring period. For example, this is the "1" in "1 month". |
| interval_string | The string associated with the recurring period. For example, this is the "month" in "1 month". |
| currency | This is the currency which will be used for any renewal transactions in this arrangement. |
| initial_amount | This is the amount that was paid by the user when this arrangement was initiated. |
| renewal_amount | This is the amount which will be charged to the user when this arrangement automatically recurs. |
| recurring_status | A string representing the status of recurring. Typically this will be "on", "off", or "cancelled". |
| status_reason | A string describing the reason this Arrangement was cancelled. |
| gateway_subscription_id | The Stripe Subscription ID |
| current_period_end | The date this period will end, and will be charged again. |
| is_live_mode | Boolean value (1 for true or 0 for false). 1 if this arrangement is in Stripe Live Mode. 0 if this transaction is in Stripe Test Mode. |

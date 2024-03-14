For Church Tithe WP, here's all of the tables and what they do

The Transactions Table
When the customer clicks the "pay" button, a transaction is created. An arrangement is also created, containing information about the tithe (if it recurs, when, etc).

The Arrangements table
An arrangement is a list of info about the tithe, and any future tithes that might automatically recurr. Recurring tithes will be cancelled in the arrangement.

New Transactions will create Arrangements.
Old Arrangements will be used to create new Transactions in the case of recurring-enabled arrangements.
Refunds will create new Transactions and update the Old/Related Arrangement.

The Forms table (may or may not happen in the future).
The forms table contains all of the tithing forms. These are essentially "products", with specific limitations and requirements on tithing, like who the tithe is for, the minimum tithe amount, etc. 

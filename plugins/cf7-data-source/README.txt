=== Data Source for Contact Form 7 ===
Contributors: codepeople
Donate link: http://cf7-datasource.dwbooster.com
Tags: cf7, contact form 7, contact form 7 db, contact form db, contact form, contact form seven, contact form storage, wpcf7, database, data source
Requires at least: 3.0.5
Tested up to: 6.4
Stable tag: 1.1.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Data Source for Contact Form 7 plugin allows populating the "Contact Form 7" fields (text, email, URL, drop-down menu, ...) with data stored in external data sources, like a database, CSV file, URL parameters, users information, post data, taxonomies, Advanced Custom Fields (ACF), and JSON objects.

== Description ==

Data Source for Contact Form 7 plugin allows populating the "Contact Form 7" fields (text, email, URL, drop-down menu, ...) with data stored in external data sources, like a database, CSV file, URL parameters, users information, post data, taxonomies, Advanced Custom Fields (ACF), and JSON objects.

Data Source for Contact Form 7 includes two new controls in the controls bar of Contact Form 7, recordset and recordset field link. The "recordset" control reads the information from the external data source (a database, a CSV file, or any other data source supported), and the "recordset field link" control for linking the recordsets and form fields to populate them with the recordset data.

A form can include several "recordset" fields, and it is possible to link multiple "recordset field link" controls to the same recordset.

[youtube https://www.youtube.com/watch?v=bcf1i6jvlYE]

How to create the simplest contact form from our practical examples.

[youtube https://www.youtube.com/watch?v=DisVNGHiMe0]

The plugin includes other complementary controls in the controls bar in addition to the "recordset" and "recordset field link". The "Print Form" button for printing the form area, "Data Table" control for inserting an advanced table with the recordset records, "copy to clipboard" functionality for copying the values of the fields into the clipboard, and the "Search box in dropdown menu" functionality to make easy the selection in long dropdown menus.

= Features: =

* Set the information of external data sources available for the Contact Form 7 fields.
* Easy to use, includes two new controls in the controls bar to define the [recordsets](https://cf7-datasource.dwbooster.com/documentation#recordset-control) and the [link fields](https://cf7-datasource.dwbooster.com/documentation#link-control).
* Includes the ["URL Parameters"](https://cf7-datasource.dwbooster.com/documentation#url-parameters) data source to populate the form's fields with the values of the URL parameters.
* Includes the ["Users Information"](https://cf7-datasource.dwbooster.com/documentation#user-information) data source to populate the form's fields with the users' information.
* Includes the ["Posts Information"](https://cf7-datasource.dwbooster.com/documentation#post-data) data source to populate the form's fields with the posts' data, pages, and custom post types (like the WooCommerce products).
* Includes the ["Taxonomy"](https://cf7-datasource.dwbooster.com/documentation#taxonomy) data source to populate the form's fields with taxonomy terms, like categories, posts tags, or any other custom taxonomy.
* Includes the ["Database"](https://cf7-datasource.dwbooster.com/documentation#database) data source to populate the form's fields with the information of a database. Allow defining even complex queries.
* Includes the ["Javascript Function"](https://cf7-datasource.dwbooster.com/documentation#javascript-function) data source to populate the form's fields with the information returned from a Javascript function.
* Allows using the fields' values for filtering the recordset records.
* [Complementary Controls add-on](https://cf7-datasource.dwbooster.com/complementary-controls-addon). Includes additional controls and functionalities, such as the "Print Form" button, the addition of the search box to the drop-down menu, copy to clipboard functionality, and Data Table to display the data source records.

= Features in Premium version: =

* All features of the free version of the plugin.
* Includes the ["Advanced Custom Fields (ACF)"](https://cf7-datasource.dwbooster.com/documentation#acf) data source to fill the form filds with data stored in the Advanced Custom Fields in post, users, comments, taxonomies, widgets, and options.
* Includes the ["CSV"](https://cf7-datasource.dwbooster.com/documentation#csv) data source to populate the form's fields with the data store into a CSV file.
* Includes the ["JSON"](https://cf7-datasource.dwbooster.com/documentation#json) data source to populate the form's fields with the data store into a JSON file. There are hundreds of services whose outputs are JSON objects.
* [PDF Generator add-on](https://cf7-datasource.dwbooster.com/pdf-generator-addon). It generates PDF files with the information collected by the form and attaches them to the notification emails (Supports the "Conditional Fields for Contact Form 7" plugin tags in the PDF file content if the "Conditional Fields for Contact Form 7" plugin is installed on the website).
* [Post Generator add-on](https://cf7-datasource.dwbooster.com/post-generator-addon). It generates new posts (posts, pages, or any custom post) with the information collected by the form.
* [User Registration add-on](https://cf7-datasource.dwbooster.com/user-registration-addon) to convert contact forms into user registration forms.
* [CSV Generator add-on](https://cf7-datasource.dwbooster.com/csv-generator-addon) to populate a CSV file with the information collected by the form.
* [JSON Generator add-on](https://cf7-datasource.dwbooster.com/json-generator-addon) to populate a JSON file with the information collected by the form.
* [Server Side add-on](https://cf7-datasource.dwbooster.com/server-side-addon) to implement server side functions to call from the Recordset controls and get the list of records.

= Data Source Fields =

Data Source for Contact Form 7 includes two new controls in the controls bar of Contact Form 7, [**recordset**](https://cf7-datasource.dwbooster.com/documentation#recordset-control) and [**recordset field link**](https://cf7-datasource.dwbooster.com/documentation#link-control).

The recordset control reads the information from the external data source and makes it available on the form. A recordset field can read one or many records from the data source.

To insert a recordset field in the form, press the **"recordset"** button in the controls bar. This action opens a dialog to define the recordset.

The insertion dialog includes common attributes for all data sources and specific attributes for the data source selected.

The **"recordset field link"** control links a recordset field to other fields in the form to populate them with the recordset data.

To insert a link field in the form, press the **"recordset field link"** button in the controls bar. This action opens a dialog to define the relationship between a recordset and a form's field.

The link dialog includes the attributes to define the relationship between the recordset field and the form's fields.

= Cases of Use =

Get the information of the registered user and populate the form fields for his name and email:

    <label> Your name [text* your-name] </label>

    <label> Your email [email* your-email] </label>

    <label> Subject [text* your-subject] </label>

    <label> Your message (optional) [textarea your-message] </label>

    [cf7-recordset id="cf7-recordset-434" type="user" attributes="first_name, user_email" logged="1"]

    [cf7-link-field recordset="cf7-recordset-434" field="your-name" value="first_name"]

    [cf7-link-field recordset="cf7-recordset-434" field="your-email" value="user_email"]

    [submit "Submit"]

Filling a plain text in the form with the first name and last name of the logged user:

    <label> Hello <span id="first-name"></span> <span id="last-name"></span></label>

    <label> Enter your address [textarea address] </label>

    [cf7-recordset id="cf7-recordset-434" type="user" attributes="first_name, last_name" logged="1"]

    [cf7-link-field recordset="cf7-recordset-434" field="first-name" value="first_name"]

    [cf7-link-field recordset="cf7-recordset-434" field="last-name" value="last_name"]

    [submit "Submit"]

Populates the list of WooCommerce products and get the price of the selected one:

    <label>Products List [select menu-719]</label>

    <label>Product Price [number number-534]</label>

    [cf7-recordset id="cf7-recordset-619" type="database" engine="mysql" query="SELECT ID,post_title,meta_value as price FROM {wpdb.posts} posts, {wpdb.postmeta} meta WHERE posts.post_type='product' AND posts.ID=meta.post_id AND meta.meta_key='_regular_price'"]

    [cf7-link-field recordset="cf7-recordset-619" field="menu-719" value="ID" text="post_title"]

    [cf7-link-field recordset="cf7-recordset-619" field="number-534" value="price" condition="record['ID']=={field.menu-719}"]

    [submit "Submit"]

Using templates to create complex data structures. Displaying the title and excerpt of every published post:

    <div id="posts-list"></div>
    <template id="summary">
    <p style="font-style:bold">{attribute.post_title}</p>
    <p>{attribute.post_excerpt}</p>
    </template>

    [cf7-recordset id="cf7-posts" type="post" attributes="post_title,post_excerpt" condition="post_status='publish' AND post_type='post'"]

    [cf7-link-field recordset="cf7-posts" field="posts-list" value="{template.summary}"]

The form includes a template tag to design a complex data structure. To access the records attributes from the template, use {attribute.attribute-name} format. Ex. {attribute.post_title}

You can use the templates for the fields' values or texts. The format for referring to templates is {template.template-id}. You should replace "template-id" with the id of the template tag. Ex. {template.summary}

    [cf7-link-field recordset="cf7-posts" field="posts-list" value="{template.summary}"]

= Using Javascript to Access the Recordset Data =

The recordset fields trigger the "cf7-recordset" event after receiving the information from the data source, allowing you to access this information with Javascript. In this example, the recordset field reads motivational phrases from a third-party service and displays the first of them into a DIV tag on the form.

[youtube https://www.youtube.com/watch?v=YIpXLR-A014]

= Add ons (Extensions) =

[Complementary Controls add-on](https://cf7-datasource.dwbooster.com/complementary-controls-addon)

[PDF Generator add-on](https://cf7-datasource.dwbooster.com/pdf-generator-addon)

[Post Generator add-on](https://cf7-datasource.dwbooster.com/post-generator-addon)

[User Registration add-on](https://cf7-datasource.dwbooster.com/user-registration-addon)

[CSV Generator add-on](https://cf7-datasource.dwbooster.com/csv-generator-addon)

[JSON Generator add-on](https://cf7-datasource.dwbooster.com/json-generator-addon)

[Server Side add-on](https://cf7-datasource.dwbooster.com/server-side-addon)

== Installation ==

To install the Data Source for Contact Form 7 plugin, follow these steps:

1.	Download the .zip file for the Data Source for Contact Form 7 plugin.
2.	Go to the Plugins section on WordPress.
3.	Press the "Add New" button at the top of the plugins section.
4.	Press the "Upload Plugin" button and select the zipped file downloaded in the first step.
5.	Install and activate the plugin.

== Frequently Asked Questions ==

= Q: Can I populate any field in the form? =

A: Yes, using Link fields, you can use the records in the recordset fields to populate any CF7 field in the form.

= Q: Why the "Recordset Field Link" dialog includes an attribute for value and another for text? =

A: Fields like number, tel, text area, text, etc. require only the "Attribute for value"

However, CF7 includes other controls like drop-down menus, radio buttons, and checkboxes with multiple choices, where every choice requires a value and text. For these fields, should be populated the attribute for text in the dialog.

= Q: What is the condition for filtering attribute in the "Recordset Field Link" dialog? =

A: The recordset control read records from the data source, but you might want to populate the form's fields with only some of these records. The "Condition for filtering" attribute allows filtering the records to use.

= Q: Can I use the values of other fields for filtering? =

A: Yes, you can. To use the values of other fields for filtering, you should use the format: {field.field-name}

For example, {field.email}, {field.first-name}, {field.last-name}

= Q: Can I use the values of javascript variables for filtering? =

A: Yes, you can. Similarly to the form's fields, you can refer to javascript variables using the format: {var.variable-name}

= Q: Why some characters change once the recordset or link field is inserted in the form? =

A: The plugin encodes some characters because they are not supported by the shortcodes, like the square brackets, double quotes, greater than, and less than symbols.

== Screenshots ==

1. Data Source Controls
2. Recordset Insertion Dialog
3. Recordset Field Link Dialog
4. Data Sources List
5. URL Parameters Data Source
6. User Information Data Source
7. Posts Data Source
8. Taxonomies Data Source
9. Database Data Source
10. Advanced Custom Fields (ACF) Data Source
11. CSV Data Source
12. JSON Data Source
13. Javascript Function Data Source
14. Server Side Code Data Source
15. Extensions
16. Complementary Controls
17. Server Side Extension.

== Changelog ==

= 1.1.11 =

* Fixes an issue with wpcf7.cached

= 1.1.10 =

* Fixes a conflict with the latest CF7 update.

= 1.1.9 =

* Removes deprecated JS code.
* Fixes an issue hiding the search box in data table controls.

= 1.1.8 =

* Removes deprecated JS code.

= 1.1.7 =

* Fixes a CSS conflict.

= 1.1.6 =

* Upgrades the Select2 library.

= 1.1.5 =

* Includes a new predefined query.

= 1.1.4 =

* Modifies the forms appearance.
* Implements the JSON Generator extension.

= 1.1.3 =
= 1.1.2 =

* Includes a new predefined query.

= 1.1.1 =

* Implements the new {record.index} constant to use with [cf7-link-field] shortcode.

= 1.1.0 =

* Includes buttons with predefined queries to improve the user experience.

= 1.0.66 =

* Allows to associate custom attributes to the fields like data-attr={property}.

= 1.0.65 =

* Modifies the notice banner.

= 1.0.64 =

* Implements the Javascript Function data source.
* Loads the data in the onkeyup event on fields and not only onchange.
* Implements the cf7_datasource_recordset_reload and cf7_datasource_field_reload to reload the recordset and field values by coding.

= 1.0.63 =

* Fixes a minor issue replacing constants.

= 1.0.62 =

* Modifies the recommended terms while configuring the RecordSet fields to improve the user experience.

= 1.0.61 =

* Modifies the Taxonomy data source to allow filtering by post ids to get terms associated with specific posts.
* Removes duplicate entries from Radio Buttons, Checkbox, and DropDown menu fields.

= 1.0.60 =

* Modifies the data sources filtering process to prevent warnings and notices from third-party plugins from affecting the results.

= 1.0.59 =

* Modifies the add-ons and data sources lists.

= 1.0.58 =

* Implement the callback attribute in the Recordset controls to allow preprocessing of records before assigning them to the controls.

= 1.0.57 =

* Improves the plugin feedback module.

= 1.0.56 =

* Improves the plugin security.

= 1.0.55 =

* Modifies the radio buttons and checkboxes integration.

= 1.0.54 =

* Modifies the Select2 control.

= 1.0.53 =

* Integrates a SQL editor with syntax highlighting to improve the user experience creating their database queries in Database Data Source.
* Modifies the default data source selected in the recordset popup to the user information (The most used option in a contact form).
* Increase the relevance of the plugin controls.

= 1.0.52 =

* Implement the URL Parameters data source.

= 1.0.51 =

* Improves the plugin code and its performance.

= 1.0.50 =

* Removes invalid characters.

= 1.0.49 =

* Improves the plugin code and its performance.

= 1.0.48 =

* Includes the "keep existing options" attribute in the "Recordset Field Link" control to keep the default options in radio buttons, checkboxes, and drop-down menu fields.

= 1.0.47 =

* Modifies the generation of recordset and link fields tags to fix a conflict with WP Rocked and other optimizer plugins.

= 1.0.46 =
= 1.0.45 =

* Improves the plugin's code and security.

= 1.0.44 =

* Fixes an issue determining the current post id.

= 1.0.43 =

* Modifies the DataTable control to trigger an event with the record selected by the user.
* Fixes a minor conflict with the Custom Fields plugin.

= 1.0.42 =

* Implement filtering in the onkeyup event for faster response time.

= 1.0.41 =

* Replaces the input box to define the query with a text area to facilitate the query definition process.
* Corrects a problem with magic quotes.

= 1.0.40 =

* It hides extra blank lines in the forms.

= 1.0.39 =

* Allows accessing complex data structures for the texts and values in the field-link tags.

= 1.0.38 =

* Includes the current post attribute in the post data source to get the post/page information where the Contact Form 7 is inserted.

= 1.0.37 =

* Improves access to the documentation from the plugin interface.

= 1.0.36 =

* Includes a new list of constants to use with the recordset tags, like {post.id}, {post.post_title}, {post.post_status}, {post.post_author}, etc. to access the data of the post where the form is inserted.

= 1.0.35 =

* Includes the 'limit' attribute in the 'recordset field link' control to determine the number of records used to populate the form field.
* Supports templates to fill fields with complex data structures.

= 1.0.34 =

* Implements the To Database add-on to insert, update or delete rows from the database based on the information collected by the forms.

= 1.0.33 =

* Implements support for the get attribute in the WCF7 tags.

= 1.0.32 =

* Allows embedding the records information directly into plain texts.
* Fixes a minor issue when the records do not include the attributes entered to fill the values of the fields.
* Improves the select2 control.

= 1.0.31 =

* Fixes an issue with the character encoding.

= 1.0.30 =

* Improves the plugin behavior.

= 1.0.29 =

* Modifies the PRINT FORM button.

= 1.0.28 =

* Implements integration with Contact Form 7 Multi-Step Forms. Webhead LLC.
* Fixes some minor issues generating dynamic radio buttons and checkboxes.

= 1.0.27 =

* Modifies the Data Table control to adjust the generated table to its container.

= 1.0.26 =

* Implements the Data Table controls as part of the Complementary Controls add-on (Extension).

= 1.0.25 =

* Triggers the "cf7-ds-fill" event when the fields linked to the recordsets are filled.
* Implements the Complementary Controls add-on (Extension).

= 1.0.24 =

* Modifies the add-ons (extensions) integration.

= 1.0.23 =

* Implements the support for add-ons.

= 1.0.22 =

* Applies distinct to records with same properties values.

= 1.0.21 =

* Fixes a conflict with Material Design for Contact Form 7.

= 1.0.20 =

* Remove deprecated code.

= 1.0.19 =

* Includes a new button in the recordset settings for testing the data source definition before inserting the field in the contact form.

= 1.0.18 =

* The plugin opens the Recordset-Field Link dialog dynamically after inserting a Recordset field to reduce the process steps and complexity.

= 1.0.17 =

* Modifies the plugin interface.

= 1.0.16 =

* Improves the database data source connection to require the query only for the website's database.

= 1.0.15 =

* Modifies the taxonomies data source.

= 1.0.14 =

* Improve the plugin interface and user experience.

= 1.0.13 =

* Improves the access to the demos and documentation.

= 1.0.12 =

* Includes the database data source in the free version of the plugin.

= 1.0.11 =

* Includes the path to the error logs files in the plugin interface.

= 1.0.10 =

* Hides the upgrade recommendations for non-administrator users.

= 1.0.9 =

* Triggers events from recordset fields to allow access to the data from javascript.

= 1.0.8 =

* Improves the use experience of the plugin by including access to practical examples.

= 1.0.7 =

* Fixes an issue parsing the javascript variables.

= 1.0.6 =

* Modifies the recordset-field settings to improves the users' experience.

= 1.0.5 =

* Fixes a warning message.

= 1.0.4 =

* Improves the interface and access to support.

= 1.0.3 =

* Include access to functional demos from the plugin's interface.

= 1.0.2 =

* Fixes an issue replacing arrays in data sources.

= 1.0.1 =

* Fixes a compatibility issue with PHP 8.

= 1.0.0 =

* First version released.
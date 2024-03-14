=== Calculated fields for ACF ===
Contributors: wundermatics, nick6352683
Tags: acf, admin
Requires at least: 4.7
Tested up to: 5.9
Requires PHP: 7.0
Stable tag: 1.3.2
License: GPLv2 or later

Simple field math for Advanced Custom Fields.

== Description ==
Adds a formula fields ot Advanced Custom Fields that allows you to perform simple math based on field values. Calculations are updated dynamically via ajax while editing a post on the backend post editor as well as on front end forms.

== Requirements ==
Calculated fields requires Advanced Custom Fields 5.0 or later and works with Pro as well as the standard version.

== Frontend forms ==
Beginning in version 1.2.3, Calculated fields for ACF supports frontend forms created using the acf_form() function (built in to Advanced Custom Fields).

= Formulas =
Calculated fields adds a new setting "Formula" to the the ACF field editor. Formulas supports referring to other fields in the same Field group using the field name. If a formula is added to a sub field inside a repeater field, the name refers to another sub field in the same repeater.

A sub field inside a repeater field can also refer to a parent field using the "parent."" prefix.

A formula can contain the basic mathematical operations: plus (+), minus (-), multiply (*), division (/) and power of (^). Formulas can also contains functions like sin, cos, arcsin, log, ln, sqrt, abs. Parentheses are supported.

If an expression can't be evaluated due to invalid syntax or referring to undefined field names, it will silently return zero.

Examples of valid expressions (note, no equal sign at the beginning):

* fieldA * 2
* fieldA * fieldB
* abs(fieldA) * (2 + sqrt(fieldB))
* amount * parent.price

= Conditional operators =
An expression can also contain a conditional expression that will return either 0 or 1. The supported operators are:

* Equals (==) - Returns 1 when two values are equal, 0 otherwise
* Not equals (!=) - Returns 1 when two values are not equal, 0 otherwise
* Greater than (>) - Returns 1 when the first operand is greater than the second, 0 otherwise
* Greater than or equal (>=) - Returns 1 when the first operand is greater than or equal to the second, 0 otherwise
* Less than (<) - Returns 1 when the first operand is less than the second, 0 otherwise
* Less than or equal (<=) - Returns 1 when the first operand is less than or equal to the second, 0 otherwise

Examples of valid expressions using conditional operators:

* 10 == 10 (returns 1)
* 10 == 2 (returns 0)
* 10 > 2 (returns 1)
* 10 < 2 (returns 0)
* 10 <= 10 (returns 1)

= Rounding functions =
Round a decimal value to the nearest integer. Supported functions are:

* round() - Uses standard mathematical rounding rules to round to nearest integer
* ceil() - Rounds the value to the next higher integer
* floor() - Rounds the value to the next lower integer

Examples of valid expressions using rounding functions:

* round(10.2) (returns 10)
* round(10.9)  (returns 11)
* round(10.888888 * 10) / 10 (returns 10.9)
* ceil(10.2) (returns 11)
* floor(10.2) (returns 10)

Note that the round() function only takes one parameter and always round to an integer. If you need to round to a higher precision, multiply and divide as shown in third example above.

= Group fields (new from 1.2.4) =
Fields defined as part of a group, subfields, can be addressed using the group name as a prefix. Inside the group, a field defined on the parent level can be addressed using the "parent" prefix. See examples below:

Valid formula to use the value of field "foobar" defined inside the group "group2":
* group2.foobar + 10

Valid formula in a field inside a group (assuming the parent has a field named count):
* parent.count * 22

= Array functions for repeater fields =
If repeater fields are used, a calculated field in the parent field group can summarize a specific repeater sub fields using the aggregation functions sum, count, average, min or max. For example, the expression: "Sum(orderlines.amount)"" will return the sum of all the "amount" fields in the repeater field "orderlines".

The available array functions are:

* sum()
* average()
* count()
* min()
* max()

Note that anything after the dot (.) in the aggregate expression is an expression in itself. It's perfectly OK to write a formula like: "Sum(orderlines.price * amount)". This expression will walk over all lines in the "orderlines" repeater field, perform the calculation "price * amount" and return the sum of all lines.

Note that when working with aggregate functions, parentheses can not be used. If you need to aggregate a more complex calculation, you should add an extra field in the repeater group and let the aggregate function work on this extra field.

= Data type and calculation order =
Calculated fields works with the assumption that all fields are defined as numeric in the ACF editor. Using a text field in a formula WILL PRODUCE UNPREDICTABLE RESULTS.

Calculations are made as the custom field is stored to the database. To minimize the impact of performance, Calculated fields rely on field order. A formula field can only refer to fields ordered BEFORE itself. A formula field that refers to a field ordered after it WILL PRODUCE UNPREDICTABLE RESULTS.

== Installation ==
= Minimum requirements =
* PHP 7.3 or newer

Calculated fields will for ACF run on PHP 5.6 or newer, but we highly recommend following the official [WordPress guidelines](https://wordpress.org/about/requirements/) that currently recommends PHP 7.3.

= Install from within WordPress =
* Visit the plugins page within your dashboard and select ‘Add New’
* Search for ‘Calculated Fields for ACF’
* Click 'Install' and wait until the button caption changes to 'Activate'
* Click 'Activate'

= Install manually =
* Download the zip file
* Upload the calculated-fields-for-acf folder from the zip to the /wp-content/plugins/ directory on your server
* Navigate to the Plugins page in WordPress admin and locate the plugin
Click 'Activate'

== Changelog ==
= 1.3.2 =
* Fix Improves input sanitation and output escaping

= 1.3.0 =
* Feature: Support for formatting numeric output using PHP sprintf syntax
* Feature: Optionally let formula return empty string instead of zero
* Fix "empty needle" bug that emits PHP warning messages to the error log in certain field groups

= 1.2.4 =
* Fixes problem with calculations of fields inside a group

= 1.2.3 =
* Fixes problem with calculations not working in front end forms created with acf_form()

= 1.2.2 =
* Fixes issue with operator == always returning false

= 1.2.1 =
* Adds support for conditional operators >=, <=

= 1.2.0 =
* Adds support for conditional operators ==, !=, > and <
* Adds support for rounding functions floor, ceil and round

= 1.1.3 =
* Fixes issue with ajax recalculation not triggered by removing a row from a repeater field
* Fixes issue with ajax recalculation not triggered by changing a dropdown/select field
* Fixes multiple issues related to ACF Blocks. Should work as expected now, but we're considering support for ACF blocks "beta" for now

= 1.1.1 =
* Fixes typo in javascript file that causes more ajax recalculations that needed
* Fixes issue with some PHP versions and empty object keys (https://bugs.php.net/bug.php?id=46600)
* Fixes issue with ACF enumerating rows using alphanumeric keys in some cases (older versions)

= 1.1.0 =
* Adds in-edit recalculations. Calculated fields are updated while still in edit mode
* Adds read only option. All fields can be set as read only in the ACF field editor (not just calculated fields)
* Adds parent. prefix for formulas in sub fields in a repeater field.

= 1.0.1 =
* Fixes issue with complex custom fields types that can't be converted to string/numeric

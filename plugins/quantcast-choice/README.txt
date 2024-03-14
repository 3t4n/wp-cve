=== Quantcast Choice ===
Version: 2.0.8
Contributors: rbaronqc
Tags: GDPR, GDPR Consent, CCPA, ePrivacy, ePrivacy Directive, Quantcast, Quantcast Choice, QC Choice, CMP, Consent Management, Consent Management Platform, TCF v2, TCF v2.0
Requires at least: 4.0
Tested up to: 6.0.2
Stable tag: 2.0.8

The Quantcast Choice plugin implements the [Quantcast Choice TCF v2.0 Consent Tool](https://www.quantcast.com/gdpr/consent-management-solution/?utm_source=wordpress&utm_medium=wp-org&utm_campaign=info&utm_term=tool&utm_content=choice) offering support for GDPR (including Non-IAB vendors), CCPA and ePrivacy Directive and automatically passing consent signals to the Data Layer.

== Description ==

**TCF v2.0 Update**
The Quantcast Choice Plugin is an easy way to add the [Quantcast Choice TCF v2.0 Consent Management Platform (CMP)](https://www.quantcast.com/gdpr/consent-management-solution/?utm_source=wordpress&utm_medium=wp-org&utm_campaign=info&utm_term=tool&utm_content=choice) to your WordPress website.  In addition to adding Quantcast Choice to your website this plugin also makes it easy to push consent signals to the Data Layer and add CCPA support to your website footer.

Quantcast Choice supports GDPR, CCPA and ePrivacy Directive regulation compliance including IAB and custom Non-IAB vendor support.

From your quantcast.com dashboard you have the ability to customise Quantcast Choice:

1. Geo location targeting (Everyone, EAA + UK, United States, etc)
1. IAB vendor support and customisation
1. Non-IAB vendor support and customisation
1. Add Custom Non-IAB vendors
1. CCPA support and customisation
1. CMP display style customisation and themes
1. CMP messaging and links customisation
1. Consent scope
1. Consent configuration
1. And more....

== Installation ==

This section describes how to install the plugin and get it working.

1. Create your [free quantcast.com account](https://www.quantcast.com/signin/register?qcRefer=/protect/sites/newUser?utm_source=wordpress&utm_medium=wp-org&utm_campaign=info&utm_term=desc-create-account&utm_content=choice)
1. Get your [Quantcast Universal Tag ID](https://help.quantcast.com/hc/en-us/articles/360051794614-Quantcast-Choice-TCFv2-GTM-Implementation-Guide-Finding-your-UTID?utm_source=wordpress&utm_medium=wp-org&utm_campaign=info&utm_term=get-utid&utm_content=choice)
1. Upload `quantcast-choice` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to the Quantcast Choice admin page and follow the setup instructions.
1. Check out the [demo video showing the update process](https://vimeo.com/445983282).

== Frequently Asked Questions ==

= What is GDPR? =

The EU General Data Protection Regulation (GDPR) is a comprehensive privacy regulation that will replace the current Data Protection Directive 95/46/EC, with an implementation date of May 25, 2018. In April 2016, after more than four years of negotiation, the European Union approved the GDPR, with the goals of strengthening and harmonizing data protection regulation for individuals across the EU and strengthening the digital economy in the EU. The GDPR is directly applicable to member states without the need for implementing national legislation.

For more information and resources about GDPR, visit the [IAB Europe’s GDPR informational website](https://www.iabeurope.eu/?s=gdpr).

= To whom does the GDPR apply? =

The GDPR applies to any business, whether or not it is based in the EU, that processes the personal data of EU citizens. The GDPR applies to these businesses even if the goods or services that they offer are free.

== Screenshots ==

1. Choice UI - Initial screen
2. Choice UI - More options screen
3. Choice UI - IAB vendors
4. Choice UI - Non-IAB vendors
5. Choice UI - IAB vendor details
6. Dashboard UI - Theme customisation 1
7. Dashboard UI - Theme customisation 2
8. Dashboard UI - Theme customisation 3
9. Dashboard UI - IAB vendors
10. Dashboard UI - Non-IAB vendor list
11. Dashboard UI - Add Non-IAB vendor


== Changelog ==

= 1.0.0 =
* Initial Plugin Release

= 1.1.0 =
* Updated CMP JavaScript

= 1.2.0 =
* Fixing Vendor List, allowing admins to choose which Vendors to include.
* Fixing "Display UI" default value.  Displays for EU visitors only by default.
* Adding option for 2 initial screen custom links.
* Allowing HTML in Vendor and Purpose screen body text.

= 1.2.1 =
* Adding Non-Consent Display Frequency option.

= 1.2.2 =
* Adding Google Personalization option.

= 2.0.0 =
* Removal of all dependancies on jQuery.
* Complete *upgrade to TCV v2.0* that requires a *free* [quantcast.com account](https://www.quantcast.com/gdpr/consent-management-solution/?utm_source=wordpress&utm_medium=website&utm_campaign=tcfv2&utm_content=changelog).
* Complete IAB and Non-IAB vendor management from your quantcast.com dashboard.
* CCPA support. Automatically add CCPA to your website footer along with a 'Do Not Sell My Data' button.
* Automatically push consent signals to the Data Layer for consumption by Google Tag Manager.
* Disable TCFv1 plugin options and hide them once TCFv2 is activated.  The TCF v1 options and code will be removed in a future update as v1 signals will no longer be functional once TCFv1 is deprecated.
* Choice theme creation and management.
* Custom consent popup text and link options.
* See a list of [customisation options available in our help center](https://help.quantcast.com/hc/en-us/sections/360008320934-Customizations-for-TCF-v2?utm_source=wordpress&utm_medium=wp-org&utm_campaign=info&utm_term=customization&utm_content=choice).

= 2.0.1 =
* Minor updates for v1 -> v2 instructions.

= 2.0.2 =
* More minor updates for v1 -> v2 instructions.

= 2.0.3 =
* Bugfix: After the [Choice v17 release](https://help.quantcast.com/hc/en-us/articles/360047357574-Quantcast-Choice-Code-Release-Notes-TCF-v2-0-) Non-IAB vendor data was not being sent to the data layer, i.e. GTM is not firing tags for non iab vendors and no consent is being passed.
* Added a not about the UTID/pCode field for user input clarification, with a note to remove the leading "p-" from the UTID/pCode when added.

= 2.0.4 =
* Bugfix: Removing the .map file references from the public .js and public .css files fixing the JS warning "DevTools failed to load SourceMap: Could not load content. HTTP error: status code 404, net::ERR_HTTP_RESPONSE_CODE_FAILURE" warnings.

= 2.0.5 =
* Update to latest Quantcast Choice Tag.
* Update urls from quantcast.mgr.consensu.org to cmp.quantcast.com
* Return all publisher_consents and purpose_consents ids when GDPR does not apply.
* Automatically remove the leading 'p-' from UTID/pCode entered into the admin UI.
* Removal of deprecated TCFv1 functionality and the 'enable' TCFv2 option.
* Add Google vendor consent data to the data layer push.

= 2.0.6 =
* Bugfix: Push all Non IAB vendor names and ids + Google vendor names and ids to the dataLayer when GDPR is false/does not apply.

= 2.0.7 =
* Update resultToList to use the array value not the index value.

= 2.0.8 =
* Removed the updated for TCFv2 language from the plugin description.
* JS lint warning fix for missing semicolon

# ConvertCalculator for WordPress

**Contributors:** convertcalculator
**Tags:** convertcalculator, calculator, form, calculator form, price quote calculator, product configurator, configurator, calculator builder, product configurator builder,
**Requires at least:** 4.4
**Tested up to:** 6.0.2
**Stable tag:** 1.1.1
**Requires PHP:** 5.4+
**Donate link:** N/A
**License:** GPLv2 or later
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

## Description

ConvertCalculator is the versatile calculator form builder for your WordPress website that let’s your users calculate price quotes, savings, return on investments and a lot more!

Why do you need an embeddable web calculator on your website?
A calculator can help your visitors find the exact price of your product more easily. It can more clearly communicate the value you can provide them. It can help you save time by automating your price quoting process. It helps you sell more and it will save you time in the process.

It is improbable, but should you have difficulty installing or using ConvertCalculator, send the support team an email or chat request and we will be happy to assist.

## Requirements

The plugin requires a ConvertCalculator account. Sign up for free at [http://www.convertcalculator.co](http://www.convertcalculator.co?utm_source=wordpress.org).

## Installation

Installing "ConvertCalculator" can be done either by searching for "ConvertCalculator" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org.
2. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Make sure you have a calculator ready to be embedded. You can build one via [http://app.convertcalculator.co](http://app.convertcalculator.co?utm_source=wordpress.org)
5. Navigate to your calculator (on [http://app.convertcalculator.co](http://app.convertcalculator.co?utm_source=wordpress.org)) and click on "Embed on your website". Copy the calculator id
6. Place the calculator on your site using one of the following methods:

- Place `<?php convertcalculator_add_calculator($calculator_id); ?>` in your template(s). This function call takes up to one required parameter: the `id` parameter, which is the `id` you just copied.
  - Insert a shortcode into page or post content like this `[convertcalculator id="CALC_ID"]`.
  - If you need to use our legacy ["in-page" embed method](https://www.convertcalculator.com/help/embedding/#framed-embed-vs-in-page-embed), add the "type" property to the shortcode, like this: `[convertcalculator id="CALC_ID" type="in-page"]`

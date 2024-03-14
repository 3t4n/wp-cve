# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Hook or constant to prevent inline CSS from being output. If so, add FAQ about it and show example default CSS rules. If not, add an FAQ showing the code snippet to disable output of default inline CSS.
* Filter to override class names used 'oe_textdirection', 'oe_displaynone'
* Consider obscuring "mailto:" as well?
* Have regexp account for possible spaces around email in attrib. i.e. href="mailto: joe@example.com "
* Add help tabs explaining the different obfuscation methods
* Add support for JS ROT13 method?
  * = ROT13 encryption =
  * *How does it work?* The email addresses are displayed using ROT13 encryption (replacing each letter with the 13th letter that follows it). JavaScript is used to decode the strings so that visitors see the email properly. Email scrapers don't typically utilize a JavaScript engine to help determine how text would look onscreen.
  * *Uses CSS?* No.
  * *Uses JavasScript?* Yes, which means if a visitor does not have JavaScript enabled, the emails will appear garbled.
  * *Can visitor copy-n-paste the link from onscreen text without needing to make modifications?* Yes (unless they have JavaScript disabled).
  * *Does this protect emails appearing in mailto: links and within HTML tag attributes?* Yes.
  * *How effective is this?* In the aforementioned experiment, no spam emails were received when using just this technique.
* Add support for CSS before/after technique:
  ```
    <style>
      my-email::after { content: attr(data-domain); }
      my-email::before { content: attr(data-user); }
    </style>
    <my-email data-user="myemail" data-domain="mydomain.com">@</my-email>
  ```
* Prevent obfuscation in various situations:
  * Any HTML tag attribute, except for the href attribute of the a tag.
  * Other HTML tags: `<script>`, `<noscript>`, `<textarea>`, `<head>`, `<xmp>`, HTML comments
* Add means for indicating a given email address shouldn't be obfuscated, e.g. !user@example.com
* Add field to specify additional filters to hook
* Move extended documentation into a separate document
* Add technique to use JQ to improve accessibility of certain techniques (e.g. if doing "user [at] example [dot] com", the JS could unobfuscate that)
* Add technique that is just straight-up JS replacement. Filter text to extract email address and add JS to insert it where it should go.
  `<script>let a = 'user'; let b = 'domain'; document.write( '<a href=\"mailto:' + a + '@' + b + '\">'); document.write(a + '@' + b + '</a>');</script>`
* Add technique that injects screenreader-friendly plaintext output, then JS cleans it up for users with JS, e.g. "user(replace parenthesized text with @)example.com"
* Remove long-deprecated `c2c_email_obfuscator()`
* Remove long-deprecated args to `c2c_obfuscate_email()`

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/obfuscate-email/) or on [GitHub](https://github.com/coffee2code/obfuscate-email/) as an issue or PR).
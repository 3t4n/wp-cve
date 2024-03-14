`Lord of the Files` is intended to be an activate-and-forget affair for most users. You do _not_ need to make any code changes to benefit from its logical/security improvements.

But that said, there are several hooks and settings advanced users might want to take a look at. If you're such a user, read on!


&nbsp;
### Main Features

The `Lord of the Files` plugin does two main things by default:
1. It helps WordPress figure out the *correct* Media Type for uploaded files;
2. If your site allows `svg` uploads — not a WordPress default setting — then `Lord of the Files` will try to sanitize any new ones during the upload process to remove malicious scripts, etc.

These all make sense for most people, and so for most people, this is an _activate and forget_ sort of plugin.


&nbsp;
### Disable GUI

To hide all LotF-related admin pages, including this one, add the following constant to your `wp-config.php`:

```php
const LOTF_HIDE_MENUS = true;
```

(This will not affect the upload-fixing behaviors; it's just a visual thing.)


&nbsp;
### Disable A Feature

A [File Settings](/wp-admin/options-general.php?page=blob-mimes-settings) admin page is provided so that you can turn any or all features On or Off at will.

Nice and easy.

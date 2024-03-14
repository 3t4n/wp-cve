=== Upcasted S3 Offload - AWS S3, Digital Ocean Spaces, Backblaze, Minio and more ===
Contributors: upcasted
Tags: aws s3, s3, minio, amazon s3, digital ocean spaces
Plugin URI: https://upcasted.com/upcasted-s3-offload
Author URI: https://upcasted.com/
Requires at least: 4.9
Tested up to: 5.9.2
Requires PHP: 7.0
Stable tag: trunk

Upcasted S3 Offload helps you migrate files from your Media Library to AWS S3 or S3 compatible object storage providers like DigitalOcean Spaces, Linode Object Storage, Minio, Wasabi, Backblaze, Vultr Object Storage, DreamObjects and more.

== Description ==

Upcasted S3 Offload helps you keep your Media Library files in an AWS S3 bucket or many other S3 compatible storage providers like DigitalOcean Spaces, Linode Object Storage, Minio, Wasabi, Backblaze, Vultr Object Storage, DreamObjects, Clever Cloud, Tebi and more.
This plugin helps your website speed and reduces footprint of your website. It does this by moving the files you add to youe media library to S3 compatible object storage and rewriting file URL so it matches the new path. 
It will help you reduce your web hosting bill since your website will require less space and consume less bandwidth on your website's server.

Our plugin offers you a seamless integration between your WordPress Media Gallery and AWS S3 or S3 compatible storage provider.

## Why should I choose S3 for WordPress plugin?

### No limits
You can migrate unlimited files to S3 storage.

### The tools you need
You can move your files to and from S3, choose from existing buckets, create new buckets from plugins interface (PRO).
Easy to use settings and tools panel with a smart design.

### Increased website performance
All your files will be served from S3 buckets taking load off your server and increasing the speed of your website.
This has a beneficial impact on ranking in search engines like Google;
Also offers a better performance of the website because the media files are loaded from another server than the one your website is hosted on.

### Lowers website footprint
By storing your media library files on S3 storage, your website consumes less space and bandwidth on your server.
By needing less resources you will decrease your website web hosting charges.

### Built-in Security
Credentials are encrypted and after set can't be revealed again.

### Faster and easier website cloning and backup
Since you will not have to worry about migrating your media files anymore it takes less time, space and money to take backups or clone your application. 

## Free features
- Unlimited files: no limits regarding how many files you can move;
- Quick setup;
- (NEW) New column in media library list view that displays where each file is stored (Local storage or S3 Bucket)
- AWS S3 or any other S3 compatible service provider works: Files are uploaded to AWS S3 or S3 compatible storage and delivered to everyone from S3 storage servers;
- Define a custom endpoint: now you can use any S3 compatible storage provider like Linode Object Storage, DigitalOcean Spaces, DreamObjects, Wasabi, Vultr Object Storage, Clever Cloud, Tebi and many more. All you have to do is to define a custom endpoint in the newly added field;
- AWS S3 region selection: you can select a region from AWS available regions. Also you can add your own region if you use an S3 compatible storage provider;
- Keep or delete files: Option to keep the files only on S3, only on website's server or on both servers;
- Only new files will be uploaded to S3. You have to upgrade to PRO before migrating old files to S3 storage;
- File type filter: you can choose which type of files you want moved to S3: Images, Documents, Audio, Video and others;
- View buckets: view already created buckets;
- Select Bucket: use an already created bucket;
- Increased security (encrypted credentials);
- Multipart Uploader: for faster and more stable upload experience;

## PRO features brings you a whole new experience
### All the free features +
- Email support;
- Premium updates;
- Bucket creation tool: you can create a bucket directly from the plugin interface;
- (NEW) Set your own batch size: now you can increase or decrease the number of images transferred to S3 in one request. This will help those who have dedicated servers to upload faster. This option is for advanced users only and should be used with caution;
- File type filter: you can choose which type of files you want moved to S3: Images, Documents, Audio, Video and/or others;
- Migrate certain file types or all files from website's server to S3 and back. When you start the process of migration it runs in the background, and you can leave the page.
- Deliver your files to your visitors through a CDN like Amazon Cloudfront or other
- Support for huge files up to 5Tb (only for upload).

== Frequently Asked Questions ==

= The plugin is not working as expected. What can I do? =

1. Check your plugins and DISABLE all the plugins that alters/enhances/modifies the default WordPress Media Library functionality
2. Check your plugins and DISABLE all the plugins that use of AWS services
3. Verify if Upcasted S3 Offload is working
4. If after these steps Upcasted S3 Offload plugin still doesn't work  please write to us on the support forum, so we can help you.
5. We appreciate all the feedback we can collect. Please write to us about any incompatibility you found, so we can try to solve it.

= Can I use this plugin with S3 compatible storage providers like Linode Object Storage, DigitalOcean Spaces, DreamObjects, Wasabi, Vultr Object Storage? =

Yes, Upcasted S3 Offload can be used with any S3 compatible storage provider.
All you have to do is search for your storage provider custom endpoint and add it to "Define custom endpoint" field in plugin settings.

= Can I keep a copy of the files on my server  =

Yes you can. Go to plugin setting under Media Library > S3 Offload Settings and choose the behavior you want.
You can keep or not a copy of the files on your website's server or on S3 if you migrate back.

= How can I migrate all my media library files to S3? =

You need to upgrade your plan to PRO. You will have tools to move files to and from S3 bucket. You can do that by installing the free version and then press Upgrade under Media Gallery > S3 Offload Settings > Upgrade

= Is Upcasted S3 Offload compatible with WooCommerce? =

Yes, Upcasted S3 Offload by Upcasted is compatible with WooCommerce.

= Is Upcasted S3 Offload compatible with Elementor? =

Yes, Upcasted S3 Offload by Upcasted is compatible with Elementor.

= Is Upcasted S3 Offload compatible with LearnDash? =

Yes, Upcasted S3 Offload by Upcasted is compatible with LearnDash.

= Does this plugin copies or moves local files to AWS S3?  =

Upcasted S3 Offload Plugin by Upcasted moves the files you upload in WordPress Media Library to the S3 bucket chosen after you activated the plugin.


== Installation ==

### To use this plugin you will have to:

= For AWS S3: =
1. Create an AWS S3 account: [How to create an AWS S3 account?](https://upcasted.com/aws-s3-what-is-it-why-does-it-help-you-and-how-to-create-an-aws-account-today/)
2. Generate your "Access key ID" and "Secret access key"
3. Create your IAM credentials and copy them somewhere safe to use in plugin settings

= For other S3 storage providers: =
1. Choose an S3 compatible storage provider and create an account
2. Generate and copy your "Access key ID" and "Secret access key"
3. Find and copy your "custom endpoint" details

### Installation and Configuration:
To configure the plugin all you have to do is to follow these simple steps:
1. Install AWS S3 Offload
2. If you need the extra features upgrade to PRO
3. Go to: Admin dashboard -> Media -> S3 Offload Settings
4. Add your "Access key ID", "Secret access key" and "Region"
5. If you use Linode Object Storage, DigitalOcean Spaces, DreamObjects, Wasabi, Vultr Object Storage or any other S3 compatible storage provider you also need to define a custom endpoint in plugin's setting. You can usually find this in your storage provider documentation.
5. Click on Save Settings
6. Select your already created bucket or create a new one (you can create a new bucket from the plugin's interface if you have a PRO license)
7. Click on Save

All done!

Now you have a seamless integration between your WordPress Media Library and S3 storage.
All the future images you upload in your WordPress Media Library will be automatically be moved to S3 storage and are served to you and to your visitors from S3 servers.


### Helpful articles:
1. [AWS S3 - What is it, why does it help you and how to create an AWS account today?](https://upcasted.com/aws-s3-what-is-it-why-does-it-help-you-and-how-to-create-an-aws-account-today/)
2. [How to create an Amazon S3 bucket and generate your IAM credentials (API key and Secret key) using the new 2022 AWS S3 interface?](https://upcasted.com/create-amazon-s3-bucket-generate-iam-api-key-and-secret-key-new-aws-s3/)

### How it works?
1. After you set the "Mandatory Settings" and select a bucket, the plugin will automatically move all the newly added files to your bucket.
2. If you have a PRO license, you have some extra tools. They will assist you in moving your old files from media library to S3 and backwards.

### How to check if everything is working properly?
1. Set the Mandatory Settings of the plugin below and click "Save Settings"
2. Select your bucket and click "Save"
3. Go to Media Library and upload a file (an image is better for testing purposes)
4. Check to see if you can view the file. If it's an image you should see the preview.
5. Check the URL of that file. You should see the new URL. You can compare it with the URL of an old file.

### How to debug an issue?
1. Edit your wp-config.php file and turn on debug mode and debug log
2. Go to wp-content and search for debug.log if you can find a file with that name already there please rename it to something else or delete it if you don't need the old logs.
3. Repeat the opperation that you were trying to do when you had trouble.
4. Check the contents of debug.log and try to find out what went wrong. On that log you can see all the errors, notices and warnings timestamped in chronological order.
5. Depending on what you see in your debug log, disable your plugins that throw errors one by one and repeat steps 3-4 until is working.
6. This way you will be able to find out if the problem is caused by another plugin.
7. If you can't figure it out, let us know on the forums or go to our website and [write to us.](https://upcasted.com)!

== Screenshots ==
1. Settings panel for Upcasted S3 Offload
2. Settings panel for Upcasted S3 Offload
3. Assistance for debugging and setup guides
4. Added column that indicated if the file is in local storage or in S3 bucket. 

== Changelog ==
= 3.0.2 =
* added indicator to media library list view to view where each file is stored.
* added FAQ section on plugin settings page
* fixed a visual bug for notices
* fixed some remote case when the migrator got stuck because of corrupt media

= 3.0.1 =
* Upgraded: AWS SDK, Guzzle & more
* Security fix
* Fix: Image path was wrong for certain users

= 3.0.0 =
* Upgraded: minimum PHP version to 7.0
* Added: New input "Define custom endpoint". This is necessary only for those who prefer on using other S3 storage providers like Linode Object Storage, DigitalOcean Spaces, DreamObjects, Wasabi, Vultr Object Storage and many more.
* Added: New input "Define batch size". This is useful to those who want to move more or less files in a batch when migrating media files.
* Enhancement: Better error handling
* Enhancement: Rewritten plugin code and optimised logic for better performance and stability
* Enhancement: Now the Region has a select box with all the available AWS S3 regions. For those who prefer S3 compatible storage providers it allows you to put a custom value if needed.
* Removed: In window migration tool with AJAX. That tool was not stable and permitted only a small number of files to be migrated.
* Tested also with the new WordPress 5.9

= 2.2.1 =
* Enhancement: Made file type filter more human-readable

= 2.2.0 =
* Added: Filetype mime filter. Now you can filter the files you upload to AWS my mime type.
* Update: Freemius SDK updated to the latest version

= 2.1.2 =
* Fix: Some users could not delete certain files like .mp3, .mov, .pdf, .mp4

= 2.1.1 =
* Fix: Images were not showing after installing and connecting the plugin.
* The plugin is fully compatible with WordPress 5.6

= 2.1.0 =
!Important - Please update because this version is more reliable
* Fix: now all files are uploaded correctly (PDF and many other work)
* Enhancement: you can now move you files faster than ever. Huge speed increase.

= 2.0.0 =
!Important - Please update because this version is more reliable
* Feature: new uploader so you can upload files faster
* Feature: support for huge files up to 5Tb
* Feature: option to choose if you want or not to keep a copy on website server
* Enhancement: Interface improvements
* Enhancement: Added detailed description for every tool
* [PRO] Fixed: sometimes if the transfer stopped because of an error it could not continue past that attachment and got stuck
* [PRO] Feature: support for CDN
* [PRO] Feature: support for background transfer of the files to and from S3
* [PRO] Feature: option to choose if you want or not to keep a copy on Amazon S3 when migrating files back to server.

= 1.0.3 =
* Added Multipart Uploader, so it can upload large files without any problems

= 1.0.2 =
* !IMPORTANT Fixed some issues with the licensing that made the plugin to malfunction.

= 1.0.1 =
* Renamed the plugin

== Upgrade Notice ==
= 3.0.0 =
Version 3.0.0 brings lots of new changes and updates. This update is recommended for all users but please take notice that we changed the minimum PHP version to 7.0 and in some cases you need to resave our setting for everithing to work properly.

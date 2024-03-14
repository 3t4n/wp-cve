=== WordPress Hosting Benchmark tool ===
Contributors: anton.aleksandrov
Donate link: https://wpbenchmark.io/
Tags: benchmark, test, speed, hosting, performance
Requires at least: 4.0
Tested up to: 6.4
Requires PHP: 5.6
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Benchmark your hosting server CPU, memory and disk, compare with others.

== Description ==

This plugin will help you to test your hosting server. By running unified tests, you can see and compare different servers or hosting platforms. It does not require any special additional software or tools, as all tests are executed from within PHP - we simply measure time, that each test take. 

All tests are grouped and target different aspects or components - CPU, memory, filesystem, database performance, wordpress object cache, network speed. 

From version 1.2 (released on 27.april.2022) reverse connectivity tests are performed - we will connect to you wordpress and measure connection time. Such tests are performed during running local bechmarks and a short time after, so you can analyze, how big impact single heavy task may have on page load time.



== Frequently Asked Questions ==
Q: What is being tested?
A: CPU, memory bandwidth, disk speed, persistent object cache, network download speed.

Q: What should be our considerations, before using this plugin?
A: Plugin will generate large temporary files and run a lot SQL queries. You should make sure, your hosting has at least 500MB of free disk space and that your database server allows large number of queries. All temporary files and database tables will be deleted after benchmark is complete.

== Installation ==

Please use default Wordpress plugin installation method through Wordpress plugin repository.


== Screenshots ==

1. Bechmark results inside Wordpress interface
2. Detailed benchmark results
3. Backward connectivity test results and timings

== Changelog ==

1.4.1  - now using temporary folder under upload folder, as some hosts do not allow writing to plugin's folder.

1.4.0  - added 2 purely mathematical CPU intense benchmarks. Added SQL_NO_CACHE to database tests to avoid using query cache.

1.3.8  - fixed permission bug, that allowed non-admin users to execute plugin. Now only admin will have access to bechmarks.

1.3.7  - fixed CSRF bug and WP nonce check vulnerability reported by patchstack.com, Dhabaleshwar Das.

1.3.6  - temporary table definition fix, extra check for wp_cache_supports function, as several people reported a problem.

1.3.3  - improved object cache benchmark tests.

1.3.2  - fixed multisite support. 

1.3.1  - minor fix for posted data evaluation, that was accidently messing with media uploads.

1.3    - privacy. Now you can opt to not have stats displayed through your results code page or expire these results after certain time. And a few minor visual changes have also been added. 

1.2    - added Wordpress load timing tests. We will try to access your site several times while benchmark test are running and a momement after. For now all tests are executed from server located in Germany. You can see these results, when you click Read More link.

1.1.4  - fixed in object cache testing.

1.1.3  - small internal link fix.

1.1.2  - added option to skip persistent object cache.

1.1.0  - added persisten object cache testing, tuned MySQL and CPU benchmarks.

1.0.1  - disk benchmark fix, added workaround to skip failed tests.

0.9.3 - small adjustements on filesystem tests

0.9  - reviewed testing policy, several tests have been made lighter, so those can now be run at servers with restrictions.

0.8  - removed force selection of MyISAM for database tests. Now the default database engine will be used.

0.7  - added option to run each test just once, instead of 5 times. + minor fixes

0.6  - minor fix and Wordpress version compatability update

0.5  - added history of executed benchmarks

0.4  - minor polishing and WP version compatability check

0.3  - completely rewritten filesystem benchmark tests.

0.23 - small fix in filesystem benchmark tests.

0.22 - small fixes for really low resource servers.

0.21 - added new test - filesystem benchmark writing many small files, adjusted CPU tests

0.2 - Small changes in test routines

0.1 - Initial version

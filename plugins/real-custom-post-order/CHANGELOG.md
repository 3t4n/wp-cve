# Change Log

All notable changes to this project will be documented in this file.
See [Conventional Commits](https://conventionalcommits.org) for commit guidelines.

## 1.3.81 (2024-02-26)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/utils 1.18.1</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Continuous Integration

* readme-to-json parser did no longer work due to missing taxonomy_exists function (CU-8693wju7t)


##### Performance

* allow to parse big objects localized via wp_localize_script lazily (CU-8693n1cc5)
* do no longer use webpackMode eager in favor of inline-require (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)


##### Refactoring

* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.6.2</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Performance

* improve performance by not removing cookie banner from DOM after accepting for better INP in Google PageSpeed (CU-8693n1cc5)


##### Refactoring

* move all consent relevant structures and procedures to @devowl-wp/cookie-consent-management (CU-8693n1cc5)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.7</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Performance

* improve Total Blocking Time in Page Speed Insights by yielding the main thread for TCF cookie banner (CU-8693n1cc5)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.28</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Performance

* improve Total Blocking Time in Page Speed Insights by inlining require statements (CU-8693n1cc5)</details>





## 1.3.80 (2024-02-05)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/utils 1.18.0</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Features

* introduce a new notice when a rate limited request was done to devowl.io backend services (CU-86939q6ce)


##### Maintenance

* use non-docker URL with HTTPS in development environment to not bypass Traefik (CU-86939q6ce)


##### Performance

* save one SQL SELECT query in WordPress admin dashboard (CU-86939q6ce)


##### Refactoring

* move some util methods to @devowl-wp/utils (CU-86939q6ce)</details>





## 1.3.79 (2024-01-25)


### Maintenance

* update to antd@5 (CU-863gku332)


<details><summary>Dependency updates @devowl-wp/utils 1.17.9</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* php error automatic conversion of false to array is deprecated (CU-apv5uu)
* show a notice for successor templates which replace other templates (CU-869372jf7)
* sometimes the WordPress REST API is contacted infinite when WP heartbeat is deactivated and login no longer valid (CU-8693jq17r)


##### Maintenance

* update to antd@5 (CU-863gku332)


##### Performance

* reduce bundle size by replacing sha-1 by a simple hash function (CU-apv5uu)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.6</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.11</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Continuous Integration

* use project ID to read associated merge request for pipeline (CU-apv5uu)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.27</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Maintenance

* update to antd@5 (CU-863gku332)</details>





## 1.3.78 (2024-01-18) (not released)

**Note:** This version of the package has not (yet) been released publicly. This happens if changes have been made in dependencies that do not affect this package (e.g. changes for the development of the package). The changes will be rolled out with the next official update.

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Development dependency update @devowl-wp/continuous-integration 0.6.0</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Bug Fixes

* output ci summary for review application URLs for traefik v2 (CU-2rjtd0)


##### Continuous Integration

* automatically retry to fetch the git repository three times when there is a temporary error (CU-8693j5ngt)
* deploy backends in production to docker-host-6.owlsrv.de (CU-2rjtd0)


##### Features

* introduce public-changelogs command (CU-2mjxz4x)</details>

<details><summary>Development dependency update @devowl-wp/monorepo-utils 0.2.0</summary>


**_Purpose of dependency:_** _Predefined monorepo utilities and tasks._
##### Features

* introduce public-changelogs command (CU-2mjxz4x)</details>





## 1.3.77 (2024-01-04)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/utils 1.17.7</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Build Process

* correctly autoload composer package files autoload.files per plugin (CU-8693dhuhv)</details>





## 1.3.76 (2023-12-21)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/utils 1.17.6</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* fatal error after latest update as WordPress stubs were no longer compatible with WordPress < 6.2 (CU-8693cg7cp)</details>





## 1.3.75 (2023-12-21)


### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)


<details><summary>Dependency updates @devowl-wp/real-utils 1.12.7</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.5</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/composer-licenses 0.1.15</summary>


**_Purpose of dependency:_** _Helper functionalities for your composer project to validate licenses and generate a disclaimer._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.5.1</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Bug Fixes

* correctly check for the SHA of the latest master branch (CU-8693bzjkb)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.5</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/phpcs-config 0.1.14</summary>


**_Purpose of dependency:_** _Predefined functionalities for PHPCS._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/phpunit-config 0.1.12</summary>


**_Purpose of dependency:_** _Predefined functionalities for PHPUnit._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.25</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Bug Fixes

* use correct name for long term caching for extracted CSS files (CU-8693bc0d2)</details>





## 1.3.74 (2023-12-19) (not released)

**Note:** This version of the package has not (yet) been released publicly. This happens if changes have been made in dependencies that do not affect this package (e.g. changes for the development of the package). The changes will be rolled out with the next official update.

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Development dependency update @devowl-wp/continuous-integration 0.5.0</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Continuous Integration

* rotate transaction_ids_by_target_id every 14 days (CU-86937dv6w)
* upload did not work with newer Debian version, disable StrictHostKeyChecking for lftp upload (CU-86937dw3d)


##### Features

* allow to skip publish of packages by regular expression in merge request description with target branch master (CU-8693bzjkb)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.8.1</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* copy files always once and overwrite existing files (CU-8693bq3nh)</details>

<details><summary>Development dependency update @devowl-wp/monorepo-utils 0.1.13</summary>


**_Purpose of dependency:_** _Predefined monorepo utilities and tasks._
##### Bug Fixes

* show skipped publish packages as those in the generated CHANGELOG.md files (CU-8693bzjkb)</details>





## 1.3.73 (2023-12-15)


### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)


<details><summary>Dependency updates @devowl-wp/real-utils 1.12.5</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.3</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* allow to configure capabilities via Activator#registerCapabilities (CU-86938n5gk)
* compatibility with Cloudflare Rocket Loader (CU-86938z54n)


##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.8.0</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* allow to configure branch settings via root package.json instead of hardcoded (CU-86938ba8a)
* respect branch settings in weblate-prune-deleted-branches CLI command (CU-86938ba8a)
* show a hint when a language is in Weblate but not configured in package.json in weblate-status command (CU-86938ba8a)


##### Build Process

* do not expose de@formal and nl@formal to Weblate (CU-86938ba8a)


##### Features

* allow to exclude locales from projects with overrides.excludeLocales in package.json settings (CU-86938ba8a)


##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>





## 1.3.72 (2023-11-28)


### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)


### Testing

* migrate E2E tests to playwright (CU-8692yek74)


<details><summary>Dependency updates @devowl-wp/utils 1.17.2</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.4.5</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)


##### Testing

* introduce @devowl-wp/playwright-utils with smoke test functionality (CU-8692yek74)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.4</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.9</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Bug Fixes

* update Gitlab YAML typings (CU-8692yek74)</details>





## 1.3.71 (2023-11-24)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Development dependency update @devowl-wp/continuous-integration 0.4.4</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Continuous Integration

* show inconsistent translations always in translation status (CU-86932cagc)
* validate production docker compose config on compose YAML changes (CU-86934wg6z)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.9</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* do find propagated string translations from other components when merging a branch to another (CU-86932nwn8)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.8</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Bug Fixes

* also delete skipped pipelines and pipelines of deleted branches</details>





## 1.3.70 (2023-11-22)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/utils 1.17.0</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Features

* introduce batch requests (CU-86930ub71)
* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.8</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* machine translate all unfinished strings as changed strings are not detected with nottranslated (CU-86932nwn8)</details>





## 1.3.69 (2023-11-16)


### Maintenance

* fix non-ASCII characters in POT msg strings (CU-86932nwn8)


<details><summary>Dependency updates @devowl-wp/real-utils 1.12.1</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Maintenance

* fix non-ASCII characters in POT msg strings (CU-86932nwn8)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.16.1</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* compatibility with WP Meteor optimization plugin (CU-86933j1zb)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.7</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* always use auto_source=others in Weblate autotranslate to avoid picking inconsistent strings across projects (CU-86932nwn8)
* do not fuzzy autotranslate machine translated strings (CU-86932nwn8)
* use auto translate others instead of download and upload ZIP when creating feature branch in Weblate (CU-86932nwn8)


##### Reverts

* back to ZIP download/upload as it is faster than autotranslate with others (CU-86932nwn8)</details>





## 1.3.68 (2023-11-07)


### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


### Maintenance

* add de@informal with threshold 100 in continuous localization (CU-2gfb42y)
* minimum required PHP version 7.4 and WP version 5.8 (CU-arvdr3)
* tested up to WordPress 6.4 (CU-8692zwmth)


<details><summary>Dependency updates @devowl-wp/real-utils 1.12.0</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)


##### Maintenance

* add legal-text to some texts (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.16.0</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* remove local language files from built ZIP file and use remote files (CU-861n4ahzb)
* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.6</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Continuous Integration

* show inconsistent translations always in translation status (CU-86932cagc)


##### Maintenance

* machine translated strings should be trusted and not set as fuzzy in Weblate (CU-2gfb42y)</details>





## 1.3.67 (2023-10-27)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Development dependency update @devowl-wp/api 0.5.13</summary>


**_Purpose of dependency:_** _Shared typings for all Node.js backends and frontends._
##### Documentation

* update JSDoc, make some methods private and extend some typings (CU-866avtm7z)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.7</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Continuous Integration

* purge master pipelines after 90 days instead of 360</details>





## 1.3.66 (2023-10-12)


### Build Process

* composer.lock had same content-hash accross some projects (CU-866aybq9e)


### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)


<details><summary>Dependency updates @devowl-wp/real-utils 1.11.13</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.15.13</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* compatibility with latest Swift Performance version (CU-866aybgxm)


##### Maintenance

* drop concurrently package as no longer needed (CU-3cj43t)
* major update apidoc (CU-3cj43t)
* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update tsc-watch immer lint-staged sort-package-json (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)
* remove supports-color, update focusable-selectors react-quill react-codemirror2 js-cookie (CU-3cj43t)
* update Lerna v7 (CU-31956up)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.4.2</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* update Lerna v7 (CU-31956up)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.4</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Maintenance

* major update commander (CU-3cj43t)
* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.3</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/monorepo-utils 0.1.9</summary>


**_Purpose of dependency:_** _Predefined monorepo utilities and tasks._
##### Continuous Integration

* include changelogs from dependencies (CU-2k54tcb)


##### Maintenance

* major update commander (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* update Lerna v7 (CU-31956up)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.6</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Maintenance

* major update commander (CU-3cj43t)
* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/regexp-translation-extractor 0.2.19</summary>


**_Purpose of dependency:_** _Provide a performant translation extractor based on regular expression._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.20</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update tsc-watch immer lint-staged sort-package-json (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>





## 1.3.65 (2023-09-29)


### chore

* review 1 (CU-85ztzbdjt)


### docs

* remove not understandable commit messages from changelog (CU-861n7an31)





## 1.3.64 (2023-09-21)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.63 (2023-09-07)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.62 (2023-09-06)


### chore

* introduce empty i18n:generate:readme NPM script (CU-861n8mnx8)





## 1.3.61 (2023-08-28)


### build

* use @babel/plugin-proposal-class-properties with updated caniuse-lite database (CU-863h37kvr)





## 1.3.60 (2023-08-24)


### refactor

* introduce class names and a scoped stylesheet to Cookie Banner instead of style attribute (CU-2yt81xz)





## 1.3.59 (2023-08-04)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.58 (2023-08-04)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.57 (2023-08-04)


### fix

* language packs could not be downloaded from SVN repository for slugs ending with -lite (CU-861n4ahzb)





## 1.3.56 (2023-08-02)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.55 (2023-08-02)


### chore

* checked compatibility with WordPress 6.3 (CU-861n42pdy)
* review 1 (CU-861n4ahzb)





## 1.3.54 (2023-07-18)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.53 (2023-07-06)


### refactor

* introduce custom ESLint rules ability in @devowl-wp/eslint-config (CU-863gxjbn4)





## 1.3.52 (2023-06-05)


### ci

* technical renaming of German, French, Spanish, Italian and Dutch translations that they contains the formality (CU-2gfb42y)


### fix

* mapping of language files for copying to correct language (CU-2gfb42y)





## 1.3.51 (2023-05-30)


### fix

* automatically clear post cache when ordering is done and fire action (CU-863gucn4j)
* use correct charset and collate in database for newly added database tables (CU-863gtqpz0)





## 1.3.50 (2023-05-22)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.49 (2023-05-21)


### chore

* remove dotenv package (CU-861m6e3mz)


### refactor

* migrate Traefik environment variables to Envkey (CU-861m6e3mz)





## 1.3.48 (2023-05-19)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.47 (2023-05-12)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.46 (2023-05-11)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.45 (2023-04-28)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.44 (2023-04-24)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.43 (2023-04-19)


### refactor

* introduce taskfile.dev Taskfiles (CU-85zrrymj0)





## 1.3.42 (2023-03-24)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.41 (2023-03-21)


### chore

* update dependencies including TypeScript 4.9, antd and eslint (CU-85zrqk9pd)


### refactor

* rename grunt-continuous-localization to continuous-localization and remove grunt dependency (pure bin, CU-85zrrytg6)





## 1.3.40 (2023-03-14)


### chore

* compatibility with WordPress 6.2 (CU-861mfxmc1)
* remove unused dependencies (CU-85zrqj4jp)
* restructure .env and replace Scaleway API keys with new IAM (CU-37q5f2x)





## 1.3.39 (2023-03-01)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.38 (2023-02-28)


### chore

* update wordpress stubs (CU-863g4efkw)


### fix

* invalid JSON int database helper class with the help of JSON5 (CU-863g4efkw)





## 1.3.37 (2023-02-21)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.36 (2023-02-15)


### chore

* streamline docker-compose settings with non-production context (CU-861m5btfw)


### ci

* only run needed containers for WordPress E2E tests (CU-861m5btfw)





## 1.3.35 (2023-01-25)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.34 (2023-01-10)


**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.33 (2022-12-22)


### chore

* review 1 (CU-861m3x1qy)
* update all package.json to resolve release conflicts (CU-382p4kb)


### perf

* remove path_join calls and use trailingslashit instead (CU-861m3qqb7)





## 1.3.32 (2022-12-12)


### docs

* update README contributors





## 1.3.31 (2022-11-18)


### refactor

* rename handleCorruptRestApi function (CU-33tce0y)





## 1.3.30 (2022-11-15)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.29 (2022-11-09)


### refactor

* static trait access (Assets types, CU-1y7vqm6)
* static trait access (Localization i18n public folder, CU-1y7vqm6)
* static trait access (Localization, CU-1y7vqm6)





## 1.3.28 (2022-10-31)


### chore

* compatibility with WordPress 6.1 (CU-32bjn2k)





## 1.3.27 (2022-10-25)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.26 (2022-10-11)


### build

* add webpack as dependency to make it compatible with PNPM (CU-3rmk7b)


### chore

* add new team member to wordpress.org plugin description (CU-2znqfnu)
* introduce consistent type checking for all TypeScript files (CU-2eap113)
* prepare script management for self-hosted Gitlab migrations (CU-2yt2948)
* start introducing common webpack config for frontends (CU-2eap113)
* switch from yarn to pnpm (CU-3rmk7b)


### test

* e2e smoke tests via macro (CU-3rmk7b)
* setup VNC with noVNC to easily create Cypress tests (CU-306z401)





## 1.3.25 (2022-09-21)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.24 (2022-09-21)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.23 (2022-09-20)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.22 (2022-09-16)


### fix

* no entries row should not be draggable (CU-4afhvg)
* reversed order of published date after installation (CU-2yyw56m)





## 1.3.21 (2022-09-06)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.20 (2022-08-29)


### chore

* introduce devowl-scripts binary (CU-2n41u7h)
* introduce for non-flat node_modules development experience (CU-2n41u7h)
* prepare packages for PNPM isolated module mode (CU-2n41u7h)
* rebase conflicts (CU-2n41u7h)


### perf

* drop IE support completely (CU-f72yna)
* permit process.env destructuring to save kb in bundle size (CU-f72yna)


### refactor

* use browsers URL implementation instead of url-parse (CU-f72yna)





## 1.3.19 (2022-08-09)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.18 (2022-06-13)


### chore

* update README.txt title and remove WordPress wording (CU-2kat97y)


### fix

* sanitize input fields where needed (CU-2kat97y)





## 1.3.17 (2022-06-08)


### chore

* minimum required PHP version is now PHP 7.2 (CU-2eanvmc)
* update changelog URL (CU-2adgjqp)


### docs

* compatibility with WordPress 6.0 (CU-2e4yvvt)





## 1.3.16 (2022-04-29)


### chore

* update changelog URL (CU-2chdb51)


### docs

* new contributors for WordPress plugins





## 1.3.15 (2022-04-20)


### chore

* code refactoring and calculate monorepo package folders where possible (CU-2386z38)
* remove React and React DOM local copies and rely on WordPress version (CU-awv3bv)


### refactor

* extract composer dev dependencies to their corresponding dev package (CU-22h231w)
* name traefik environment to staging (CU-22h231w)
* put composer license packages to @devowl-wp/composer-licenses (CU-22h231w)
* rename wordpress-packages and wordpress-plugins folder (CU-22h231w)
* revert empty commits for package folder rename (CU-22h231w)
* use phpunit-config and phpcs-config in all PHP packages (CU-22h231w)





## 1.3.14 (2022-03-15)


### chore

* use wildcarded composer repository path (CU-1zvg32c)





## 1.3.13 (2022-03-01)


### ci

* use Traefik and Let's Encrypt in development environment (CU-1vxh681)





## 1.3.12 (2022-02-11)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.11 (2022-01-31)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.10 (2022-01-25)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.9 (2022-01-17)


### build

* create cachebuster files only when needed, not in dev env (CU-1z46xp8)
* improve build and CI performance by 50% by using @devowl-wp/regexp-translation-extractor (CU-1z46xp8)


### test

* compatibility with Xdebug 3 (CU-1z46xp8)





## 1.3.8 (2021-12-21)


### refactor

* move WordPress scripts to @devowl-wp/wp-docker package (CU-1xw9jgr)





## 1.3.7 (2021-12-15)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.6 (2021-12-01)


### fix

* compatiblity with WordPress 5.9 (CU-1vc94eh)





## 1.3.5 (2021-11-24)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.4 (2021-11-18)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.3 (2021-11-11)


### chore

* remove not-finished translations from feature branches to avoid huge ZIP size (CU-1rgn5h3)





## 1.3.2 (2021-11-03)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.3.1 (2021-10-12)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





# 1.3.0 (2021-09-30)


### build

* allow to define allowed locales to make release management possible (CU-1257b2b)
* copy files for i18n so we can drop override hooks and get performance boost (CU-wtt3hy)
* finalize mojito import, push and pull (CU-f94bdr)


### chore

* prepare for continuous localization with weblate (CU-f94bdr)
* remove language files from repository (CU-f94bdr)


### ci

* introduce continuous localization (CU-f94bdr)


### feat

* translation into Russian (CU-10hyfnv)


### perf

* remove translation overrides in preference of language files (CU-wtt3hy)


### refactor

* grunt-mojito to abstract grunt-continuous-localization package (CU-f94bdr)
* introduce @devowl-wp/continuous-integration





## 1.2.27 (2021-08-31)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.26 (2021-08-20)


### chore

* update PHP dependencies


### fix

* modify composer autoloading to avoid multiple injections (CU-w8kvcq)





## 1.2.25 (2021-08-10)


### refactor

* split i18n and request methods to save bundle size





## 1.2.24 (2021-08-05)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.23 (2021-07-16)


### chore

* update compatibility with WordPress 5.8 (CU-n9dfx9)





## 1.2.22 (2021-06-05)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.21 (2021-05-25)


### chore

* migarte loose mode to compiler assumptions
* polyfill setimmediate only if needed (CU-jh3czf)
* prettify code to new standard
* revert update of typedoc@0.20.x as it does not support monorepos yet
* update cypress@7
* upgrade dependencies to latest minor version


### ci

* move type check to validate stage


### fix

* do not rely on install_plugins capability, instead use activate_plugins so GIT-synced WP instances work too (CU-k599a2)


### test

* make window.fetch stubbable (CU-jh3cza)
* run smoke tests also for chore/patch branches





## 1.2.20 (2021-05-14)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.19 (2021-05-12)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.18 (2021-05-11)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.17 (2021-05-11)


### ci

* push plugin artifacts to GitLab Generic Packages registry (CU-hd6ef6)


### refactor

* create wp-webpack package for WordPress packages and plugins
* introduce eslint-config package
* introduce new grunt workspaces package for monolithic usage
* introduce new package to validate composer licenses and generate disclaimer
* introduce new package to validate yarn licenses and generate disclaimer
* introduce new script to run-yarn-children commands
* move build scripts to proper backend and WP package
* move jest scripts to proper backend and WP package
* move PHP Unit bootstrap file to @devowl-wp/utils package
* move PHPUnit and Cypress scripts to @devowl-wp/utils package
* move technical doc scripts to proper WP and backend package
* move WP build process to @devowl-wp/utils
* move WP i18n scripts to @devowl-wp/utils
* move WP specific typescript config to @devowl-wp/wp-webpack package
* remove @devowl-wp/development package
* split stubs.php to individual plugins' package





## 1.2.16 (2021-03-30)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.15 (2021-03-23)


### build

* plugin tested for WordPress 5.7 (CU-f4ydk2)





## 1.2.14 (2021-03-02)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.13 (2021-02-24)


### chore

* rename go-links to new syntax (#en621h)


### docs

* rename test drive to sanbox (#ef26y8)
* update README to be compatible with Requires at least (CU-df2wb4)





## 1.2.12 (2021-01-24)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.11 (2021-01-18)


### fix

* table header breaks when a column is hidden (CU-cpv9wm)





## 1.2.10 (2021-01-11)


### build

* reduce javascript bundle size by using babel runtime correctly with webpack / babel-loader





## 1.2.9 (2020-12-15)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.8 (2020-12-10)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.7 (2020-12-09)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.6 (2020-12-09)


### chore

* update to cypress v6 (CU-7gmaxc)
* update to webpack v5 (CU-4akvz6)
* updates typings and min. Node.js and Yarn version (CU-9rq9c7)


### fix

* typing error from Node 14 upgrade (CU-9rq9c7)





## 1.2.5 (2020-12-01)


### chore

* update dependencies (CU-3cj43t)
* update to composer v2 (CU-4akvjg)
* update to core-js@3 (CU-3cj43t)


### refactor

* enforce explicit-member-accessibility (CU-a6w5bv)





## 1.2.4 (2020-11-24)


### fix

* compatibility with upcoming WordPress 5.6 (CU-amzjdz)
* use no-store caching for WP REST API calls to avoid issues with browsers and CloudFlare (CU-agzcrp)





## 1.2.3 (2020-11-18)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.2 (2020-11-17)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.1 (2020-11-12)


### ci

* make scripts of individual plugins available in review applications (#a2z8z1)





# 1.2.0 (2020-10-23)


### feat

* route PATCH PaddleIncompleteOrder (#8ywfdu)


### refactor

* use "import type" instead of "import"





## 1.1.10 (2020-10-16)


### build

* use node modules cache more aggressively in CI (#4akvz6)


### chore

* rename folder name (#94xp4g)





## 1.1.9 (2020-10-09)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.1.8 (2020-10-08)


### chore

* **release :** version bump





## 1.1.7 (2020-09-29)


### build

* backend pot files and JSON generation conflict-resistent (#6utk9n)


### chore

* introduce development package (#6utk9n)
* move backend files to development package (#6utk9n)
* move grunt to common package (#6utk9n)
* move packages to development package (#6utk9n)
* move some files to development package (#6utk9n)
* remove grunt task aliases (#6utk9n)
* update dependencies (#3cj43t)
* update package.json scripts for each plugin (#6utk9n)





## 1.1.6 (2020-09-22)


### fix

* import settings (#82rk4n)





## 1.1.5 (2020-09-08)


### fix

* remove List not Sortable hint and allow to sort all views (#7etufj)





## 1.1.4 (2020-08-31)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.1.3 (2020-08-26)


### ci

* install container volume with unique name (#7gmuaa)


### perf

* remove transients and introduce expire options for better performance (#7cqdzj)





## 1.1.2 (2020-08-17)


### ci

* prefer dist in composer install





## 1.1.1 (2020-08-11)


### chore

* backends for monorepo introduced





# 1.1.0 (2020-07-30)


### docs

* optimize plugin description at wordpress.org


### feat

* check support status for Envato license #CU-6pubwg
* introduce dashboard with assistant (#68k9ny)
* WordPress 5.5 compatibility (#6gqcm8)


### fix

* REST API notice in admin dashboard
* usage with woocommerce products (#6pmaj2)





## 1.0.12 (2020-07-02)


### chore

* allow to define allowed licenses in root package.json (#68jvq7)
* update dependencies (#3cj43t)


### fix

* IE11 polyfills (#5whc2c)


### test

* cypress does not yet support window.fetch (#5whc2c)





## 1.0.11 (2020-06-17)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.0.10 (2020-06-12)


### chore

* i18n update (#5ut991)


### test

* OptionStore





## 1.0.9 (2020-05-27)


### build

* improve plugin build with webpack parallel builds


### ci

* use hot cache and node-gitlab-ci (#54r34g)





## 1.0.8 (2020-05-20)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.0.7 (2020-05-12)


### build

* cleanup temporary i18n files correctly


### fix

* correctly enqueue dependencies (#52jf92)





## 1.0.6 (2020-04-27)


### chore

* add hook_suffix to enqueue_scripts_and_styles function (#4ujzx0)


### docs

* animated Real Custom Post Order logo for wordpress.org


### test

* automatically retry cypress tests (#3rmp6q)





## 1.0.5 (2020-04-20)

**Note:** This package (@devowl-wp/real-custom-post-order) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.0.4 (2020-04-16)


### docs

* update link to changelog





## 1.0.3 (2020-04-16)


### build

* move test namespaces to composer autoload-dev (#4jnk84)
* reduce bundle size by ~25% (#4jjq0u)
* scope PHP vendor dependencies (#4jnk84)


### chore

* create real-ad package to introduce more UX after installing the plugin (#1aewyf)
* rename real-ad to real-utils (#4jpg5f)
* update to Cypress v4 (#2wee38)


### ci

* correctly build i18n frontend files (#4jjq0u)
* run package jobs also on devops changes


### fix

* link to Real Custom Post Order (#5ygvhw)


### style

* reformat php codebase (#4gg05b)


### test

* adjust E2E tests due to translation changes
* avoid session expired error in E2E tests (#3rmp6q)





## 1.0.2 (2020-03-31)


### chore

* update dependencies (#3cj43t)


### ci

* use concurrency 1 in yarn disclaimer generation


### docs

* replace screenshot in wordpress.org description


### style

* run prettier@2 on all files (#3cj43t)


### test

* added E2E tests (#46mact)
* configure jest setupFiles correctly with enzyme and clearMocks (#4akeab)
* generate test reports (#4cg6tp)





## 1.0.1 (2020-03-23)


### build

* initial release of WP Real Custom Post Order plugin (#46ftef)

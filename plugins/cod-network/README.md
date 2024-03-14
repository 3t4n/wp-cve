# COD Network For WooCommerce
<p style="display:flex;justify-content:center;">
<img src="https://img.shields.io/github/v/tag/NextmediaMa/codnetwork-for-woocommerce?label=Latest%20version"/>
</p>

# Description
COD Network is a project 
# Setup

**Prerequisites:**
- A Wordpress environment in order to test the plugin
- WooCommerce plugin installed and activated
- svn *(only required if you are planing to push code)*

**Steps:**

1. Clone the repository locally:
```bash
git clone https://github.com/NextmediaMa/codnetwork-for-woocommerce && cd codnetwork-for-woocommerce
```
2. Since the plugin ships with the vendor, there is no need to run `composer install`.
3. Run `npm i`

# Publishing a new version

Whether it's a bug fix, improvement or a new feature, this pipeline should be followed in order
to ensure quality code + making sure the process to take that code to production is seamless.

## Before merging
 
- Every PR is to be a release, minor or major, it's up to the contributor and the reviewer.

## Versioning

- Make sure you've updated the version the following:
    - `version` in the header of the plugin, can be found at the phpdoc in `cod.network.php`
    - `Stable tag` & `Version` in `readme.txt`
- Write down a changelog in `changelog.txt` by following the same format as the others.
    - Latest changelog is always on top
- Replace the latest changelog in `readme.txt` by the one you just wrote.
- After merging create a tag in github, this is technically not important, but it'll help us
  keep a matching versioning in both github and the plugin repo in Wordpress.

## Deploying

After your PR is merged someone with access can update the remote plugin repository in order
for your changes to take effect.

### How to deploy

1. Create a directory where our plugin code will live:
```bash
mkdir Subversion && cd Subversion
```
2. Pull the plugin:
```bash
svn co http://svn.wp-plugins.org/codnetwork-for-woocommerce && cd codnetwork-for-woocommerce
```
3. Copy your previously created Github tag code into `trunk` dir.
4. Run `svn status`, if there is any file there with a `?` next to it (`?       trunk/delete.me`),
   it means that aren't inversion control yet, to fix that you can add them using:
```bash
svn add trunk/delete.me
```
Or batch add them using:
```bash
svn add trunk/*
```

*Note: It might thrown some warning about how some files are already under version control, you can just ignore those*
5. We now have to create a tag for our new plugin release by running the following:
```bash
svn mkdir tags/0.0.1 && svn copy trunk/* tags/0.0.1
```

*Note: Change `0.0.1` with your version.*

6. Lastly it's time to push our changes to the remote repository, to do so, we run the following:
```bash
svn commit -m "Release 0.0.1"
```

*Note: You'll be asked to authenticate in order to push these changes, so you can't do that
unless you were a part of the dev team.*
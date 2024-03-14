# Review Stream

WordPress plugin for Grade.us review stream.

## Background

Github repo: https://github.com/gradeus/reviewstream-wordpress

We do all of our work in this git repo, but we nee to publish changes to a separate [Wordpress Plugin Repo](https://plugins.svn.wordpress.org/review-stream) (using SVN like it's 2003). Use the credentials for gradeus WordPress.org account.

Wordpress plug in site: https://wordpress.org/plugins/review-stream

```text
u/n: gradeus
p/w:
email: wordpress@grade.us (email list / Google group)
```

## Workflow

1. Do your work in the git repo.
1. There are three types of version updates, plugin upversion, WordPress release, or both.
    1. If you need to upversion the plugin, there are three files you need to edit. Keep in mind that this needs to be updated in the trunk AND OPTIONALLY in the tag folder of the SVN repository.
       1. `~/reviewstream.php` line 6
       2. `~/reviewstream.php` line 33
       3. `~/readme.txt`
    2. When a new WordPress version is going to be released, we need to update the `Tested up to:` line with the new WP version in the following files:
       1. `~/readme.txt`
       2. `~/RELEASE_NOTES.md`
1. once the new version is ready and on main branch, checkout the main branch and use git archive to create a compressed version of the repository using `git archive -o ../gradeus-wp-latest.zip HEAD`.
1. Unzip the created file, cd into the folder, delete Git(hub) related files with `rm -rf .github .gitignore`, now you can use the files in the folder to be copied to the `trunk` and `tags/vX.Y.Z` directories.
1. Publish to the subversion repo. See [here for svn help](https://kinsta.com/blog/publish-plugin-wordpress-plugin-directory/).

### Example

```sh
brew install svn
svn co https://plugins.svn.wordpress.org/review-stream .
svn status
cd tags
mkdir 1.6
cd ..
# somehow copy the latest release to the correct versioned folder in tags
svn add tags/*
svn ci -m 'release to 1.6.0' --username=gradeus
```

## Troubleshooting

Wordpress version updates might make the plugin unavailable if updating the readme.txt file with the new WordPress version number is not done in time.

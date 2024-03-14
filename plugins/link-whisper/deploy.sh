#! /bin/bash
# A modification of Dean Clatworthy's deploy script as found here: https://github.com/deanc/wordpress-plugin-git-svn
# The difference is that this script lives in the plugin's git repo & doesn't require an existing SVN repo.

#Instructions
# Update 'NEWVERSION1' with correct version number
#1. Open up command line utility
#2. cd into the directory that this script is located in
#3. deploy this script
# ./deploy.sh
#3. Enter github password twice and magic happens

# main config
PLUGINSLUG="link-whisper"
CURRENTDIR=`pwd`
MAINFILE="link-whisper.php" # this should be the name of your main php file in the wordpress plugin

# plugin path config
PLUGINPATH="$CURRENTDIR" # this file should be in the base of your git repository
PLUGINPATHFORMATTED="${PLUGINPATH/ /*}"
PLUGINPATHFORMATTED="${PLUGINPATHFORMATTED/ /*}"

# svn config
SVNPATH="$PLUGINPATHFORMATTED/svn" # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNPATH2="$CURRENTDIR/svn" # path to a temp SVN repo. No trailing slash required and don't add trunk.
SVNURL="https://plugins.svn.wordpress.org/link-whisper" # Remote SVN repo on wordpress.org, with no trailing slash
SVNUSER=`grep "^Username" /c/xampp2/htdocs/text.txt | awk '{ print $NF}'`
SVNPASS=`grep "^Password" /c/xampp2/htdocs/text.txt | awk '{ print $NF}'`

# Let's begin...
echo ".........................................."
echo
echo "!Preparing to deploy"
echo
echo ".........................................."
echo


# Check version in readme.txt is the same as plugin file after translating both to unix line breaks to work around grep's failure to identify mac line breaks
NEWVERSION1=`grep "^Stable Tag" $PLUGINPATHFORMATTED/readme.txt | awk '{ print $NF}'`
CURRENTVERSION=`grep -i "Version:" $PLUGINPATHFORMATTED/$MAINFILE | awk -F' ' '{print $NF}' | tr -d '\r'`

echo "$PLUGINPATH";
echo "$PLUGINPATHFORMATTED";
echo "$PLUGINPATHFORMATTED/$MAINFILE";

echo "readme.txt version: $NEWVERSION1"
echo "$MAINFILE version: $CURRENTVERSION"


if [ "$NEWVERSION1" != "$CURRENTVERSION" ]; then
	echo "Version in readme.txt & $MAINFILE don't match. Exiting....";
	sleep 20
	exit 1;
fi



echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH
sleep 5


echo 'Change directories into svn repo'
cd $SVNPATH

echo "Clean up SVN in case there's been an issue ..."
svn cleanup
sleep 5


echo 'Deleting contents of trunk...'
svn rm trunk --force
echo 're-add trunk file to prepare for cp command'
mkdir "$SVNPATH2/trunk/"
sleep 5

echo "Copy plugin contents to /svn/trunk/ minus svn..."
cd ../
cp -r `ls -A | grep -v "svn"` $SVNPATH/trunk/
sleep 5

echo "Adding trunk files to sv"
cd $SVNPATH
svn add trunk
sleep 5

echo "Updating ignore file"
cd $SVNPATH

echo "Ignoring additional files"
svn propset svn:ignore "deploy.sh
README.md
composer.json
codeception.yml
.travis.yml
Gruntfile.js
.idea
*.yml
*.phar
.git
*.exe
*.bat
*.sh
tests
assets/tests
assets/lang
.gitignore" "$SVNPATH2/trunk/"


sleep 5

echo "Creating new SVN tag"
svn copy trunk/ tags/$CURRENTVERSION/
sleep 5
echo "Ignoring additional files"
svn propset svn:ignore "deploy.sh
README.md
composer.json
codeception.yml
.travis.yml
Gruntfile.js
.idea
*.yml
*.phar
.git
*.exe
*.bat
*.sh
tests
assets/tests
.gitignore" "$SVNPATH2/tags/$CURRENTVERSION"

echo "Commiting changes to SVN"
ls
svn commit --username="$SVNUSER" --password="$SVNPASS" -m "Tagging version $CURRENTVERSION" --no-auth-cache --force-log
sleep 5

echo "*** FIN ***"
sleep 9999

set -e

CURRENTDIR=`pwd`
SVNPATH="/tmp/mantissvn"
SVNURL="http://plugins.svn.wordpress.org/mantis-ad-network"
README=`grep "^Stable tag:" README.txt | awk -F' ' '{print $NF}'`
PHP=`grep "Version:" mantis.php | awk -F' ' '{print $NF}'`
BRANCH=`git rev-parse --abbrev-ref HEAD`
TAG=`git describe --abbrev=0 --tags`

git fetch

LOCAL=`git rev-parse HEAD`
REMOTE=`git rev-parse @{u}`

if [ "$README" != "$PHP" ]; then echo "Version in README.txt & mantis.php don't match. Exiting...."; exit 1; fi

if [ "$BRANCH" != "master" ]; then echo "You must be on the master branch."; exit 1; fi

if [[ `git status --porcelain` ]]; then echo "You must commit local git changes."; exit 1; fi

if [ "$LOCAL" != "$REMOTE" ]; then echo "Your local commit version does not match remote"; exit 1; fi

if [ "$TAG" != "$README" ]; then echo "You must tag the version in git first"; exit 1; fi

git ls-remote origin refs/tags/$README >/dev/null 2>&1

if [ ! $? -eq 0 ]; then
	echo "Git tag has not been pushed to remote"
fi

grep "= $PHP =" README.txt >/dev/null 2>&1

if [ ! $? -eq 0 ]; then
	echo "Forgot to add revision log entry"
fi

rm -rf $SVNPATH

echo "Checking out plugin"
svn co --non-recursive $SVNURL $SVNPATH

cd $SVNPATH
svn update trunk/
svn update --depth=immediates tags/

cd $CURRENTDIR

echo "Exporting the HEAD of master from git to the trunk of SVN"

rm -rf $SVNPATH/trunk/*

git checkout-index -a -f --prefix=$SVNPATH/trunk/

cd $SVNPATH

cd trunk

svn st | grep ! | cut -d! -f2| sed 's/^ *//' | sed 's/^/"/g' | sed 's/$/"/g' | xargs svn rm

svn st | grep ^? | sed 's/?    //' | xargs svn add

svn commit -m "Preparing for $PHP release"

if [ $? -eq 0 ]; then
	echo "Creating new SVN tag and committing it"

	svn copy $SVNURL/trunk/ $SVNURL/tags/$PHP/ -m "Tagging version $PHP"
fi

echo "Removing temporary directory $SVNPATH"
rm -rf $SVNPATH/

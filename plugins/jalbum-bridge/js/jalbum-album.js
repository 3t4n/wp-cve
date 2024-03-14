/* 
 *	The Album model
 * 
 *	(c) Laszlo Molnar, 2015, 2017, 2020
 *	Licensed under Creative Commons Attribution-NonCommercial-ShareAlike 
 *	<http://creativecommons.org/licenses/by-nc-sa/3.0/>
 *
 *	requires: jQuery, laza.util
 *
 *	tree
 */

 
/*
 * 	Constants
 */
 
;
var J = {
		// jAlbum variables
		ALBUM:					'album',				// jAlbum's album data 
		FOLDERS: 				'folders',				// Folders array
		NAME: 					'name',					// FileName of the item
		PATH: 					'path',					// Path to file
		THUMB: 					'thumb',				// Thumbnail properties object
		IMAGE: 					'image',				// Image properties object
		WIDTH: 					'width',				// Width
		HEIGHT: 				'height',				// Height
		ORIGINAL: 				'original',				// Original object
		OBJECTS: 				'objects',				// Objects array (not folders)
		FILEDATE: 				'fileDate',				// File modified date
		COMMENT: 				'comment',				// Comment
		TITLE: 					'title',				// Title
		COUNTERS: 				'counters',				// Counters object
		DEEPCOUNTERS: 			'deepCounters',			// Deepcounters object
		FILESIZE: 				'fileSize',				// File size (int)
		CATEGORY: 				'category',				// Category
		KEYWORDS: 				'keywords',				// Keywords
		RATING:					'rating',				// Rating
		CAMERA: 				'camera',				// Camera data object
		VIDEO: 					'video',				// Video data object
		DURATION:				'duration',				// Video duration
		FPS:					'fps',					// Video frame per sec.
		// extra vars
		LEVEL: 					'level',				// Folder depth level
		PATHREF: 				'pathRef',				// Path from root
		PARENTREF: 				'parentRef',			// Path to parent from root
		RELPATH: 				'relPath',				// Relative path from currentFolder to this folder 
		FOLDERTITLE:			'folderTitle',			// Folder title derived from template
		IMAGECAPTION: 			'imageCaption',			// Image caption derived from template
		THUMBCAPTION: 			'thumbCaption',			// Thumbnail caption derived from template
		PHOTODATA: 				'photodata',			// Formatted photo data
		LOCATION: 				'location',				// GPS location
		REGIONS:				'regions',				// Regions (face tags)
		SHOP:					'shop',					// Global or individual shop options
		EXTERNAL:				'external',				// External (link or content)
		PROJECTIONTYPE:			'projectionType',		// 3D image projection type (equirectangular)
		DATES:					'dates',				// Dates object
		ADDED:					'added',				// Added date
		TAKENDATE:				'takenDate',			// Date taken
		MODIFIEDDATE:			'modifiedDate',			// Date modified
		DATERANGE:				'dateRange',			// Date range (folders)
		MOSTPHOTOS: 			'mostphotos',			// Mostphotos ID
		FOTOMOTOCOLLECTION:		'fotomotoCollection',	// Fotomoto collection type
		SOUNDCLIP:				'soundClip',			// Attached soundclip (mp3)
		PANORAMA:				'panorama',				// To be treated as panorama?
		FILTERS:				'filters',				// Filters array (global or folders)
		SORT:					'sort',					// Sort array (global or folders)
		VISITORRATING:			'visitorRating',		// Ratings from jAlbum
		OBJ: 					'obj',					// Store item as data attr of an element: el.data(J.OBJ)  
		LOADCOUNTER:			'loadcounter',			// Load counter array by category
		TOTAL:					'total',				// Total items loaded
		FOLDERINDEX:			'folderindex'			// Numbering folders: 0 ... N 
	};
	
var JCAMERA = [
		'aperture',
		'exposureTime',
		'originalDate',
		'cameraModel',
		'location',
		'focusDistance',
		'focalLength35mm',
		'cameraMake',
		'resolution',
		'isoEquivalent',
		'flash',
		'focalLength'
	];

/*
 *	Album object :: use 
 *		myAlbum = new Album({settings});
 *		or
 *		myAlbum = new Album();
 *		myAlbum.init({settings});
 */

var Album = function($, options) {
	
	var instance,
	
		settings = {
				// Name of the tree file
				treeFile: 				'tree.json',
				// Name of the folder data
				dataFile: 				'data1.json',
				// Name of the master file
				deepDataFile:			'deep-data.json',
				// index file name
				indexName: 				'index.html',
				//Folder image file name
				folderImageFile:		'folderimage.jpg',
				// Folder image dimensions
				folderImageDims:		[ 1200, 800 ],
				// Folder thumbnail file name 
				folderThumbFile:		'folderthumb.jpg',
				// Folder thumbnail dimanesions
				folderThumbDims:		[ 600, 420 ],
				// Thumbnail dimensions
				thumbDims:				[ 240, 180 ],
				// Name of the thumbs folder
				thumbsDir: 				'thumbs',
				// Name of the slides folder
				slidesDir: 				'slides',
				// Name of the hires folder
				hiresDir:				'hi-res',
				// Default poster images
				audioPoster:			'audio.poster.png',
				videoPoster:			'video.poster.png',
				// Path to root from current folder (eg. "../../")
				rootPath: 				'',
				// Relative path to the current folder (eg. "folder/subfolder") 
				relPath: 				'',
				// Loading the whole data tree
				loadDeep:				false,
				// Lazy load :: loads folder data only when requested or at the time of initialization
				lazy: 					true,
				// Possible object types
				possibleTypes:			[ 'folder', 'webPage', 'webLocation', 'image', 'video', 'audio', 'other' ]					
			},
			
		// Texts translated
		text = getTranslations({
				and:						'and',
				from:						'From {0}'
			}),
		
		// Global variables
		// URL of to top level album page (null => we're in the album's subfolder, using the relative "rootPath") 
		albumPath = null,
		// Absolute URL of the top level page
		absolutePath,
		// Cache buster
		cacheBuster = '',
		// The container for the entire tree
		tree = {},
		// Collection of all album paths in order to be able to store only the references
		paths = [],
		// Path to current folder
		currentFolder,
		// Collection the JSON promises
		defer = [],
		// Album ready state: tree.json and data1.json is loaded
		ready,
		// Deep ready: all the data structure is ready
		deepReady,
		// Is ready?
		isReady = () => (ready),
		// Is ddep ready?
		isDeepReady = () => (deepReady),
		
		/***********************************************
		 *					Debug
		 */
		 
		// Logging
		
		// Logging
		scriptName = 'jalbum-album.js',
	
		log = function(txt) {
				if (console && txt && DEBUG) {
					if (txt.match(/^Error\:/i)) {
						console.error(scriptName + ' ' + txt);
					} else if (txt.match(/^Warning\:/i)) {
						console.warn(scriptName + ' ' + txt);
					} else if (txt.match(/^Info\:/i)) {
						console.info(scriptName + ' ' + txt);
					} else {
						console.log(scriptName + ' ' + txt);
					}
				}
			},
					
		// Returns the whole internal tree object
		
		getTree = () => (tree),
			
		// Returns paths array :: debug 
		
		getPaths = () => (paths),
		
		// Utility functions
		getFilenameFromURL = (n) => ( decodeURIComponent(n.slice(n.lastIndexOf('/') + 1)) ),
		
		// File name without extension
		getBasename = (o) => { 
				var m = getItemName(o).match(/^(.+)\./); 
				return m? m[1] : '';
			},
				
		// File extension
		getExtension = (o) => { 
				var m = getItemName(o).match(/\.(\w+)$/); 
				return m? m[1] : '';
			},
				
			
		/***********************************************
		 *					Type check
		 */
		 
		// Image?
		isImage = (o) => (o.hasOwnProperty(J.CATEGORY) && o[J.CATEGORY] === 'image'),
			
		// Audio?
		isAudio = (o) => (o.hasOwnProperty(J.CATEGORY) && o[J.CATEGORY] === 'audio'),
			
		// Video?
		isVideo = (o) => (o.hasOwnProperty(J.CATEGORY) && o[J.CATEGORY] === 'video'),
			
		// Folder?
		isFolder = (o) => (o.hasOwnProperty(J.LEVEL)),
			
		// Ordinary object?
		isLightboxable = (o) => (!o.hasOwnProperty(J.FOLDERINDEX) && 
				o.hasOwnProperty(J.CATEGORY) && 
				'image.video.audio.other'.indexOf(o[J.CATEGORY]) !== -1),
	
		// Is this the current folder?
		isCurrentFolder = (o) => (o === currentFolder),
			
		/***********************************************
		 *					Access
		 */
		 
		// Returns path separated with separator
		
		makePath = function() {
			
				if (arguments.length) {
					var p = arguments[0];
					
					for (var i = 1; i < arguments.length; i++) {
						if (arguments[i]) {
							if (arguments[i] === '/') {
								p = '/';
							} else if (arguments[i][0] === '/') {
								p += (p.slice(-1) === '/')? arguments[i].slice(1) : arguments[i];
							} else {
								p += (p.slice(-1) === '/')? arguments[i] : ('/' + arguments[i]);
							}
						}
					}
						
					return p;
				}
				
				return '';
			},
			
		// Get folder from URL
		
		getAbsoluteFolderPath = function(path) {
			
				if (typeof path === UNDEF) {
					return window.location.href.substring(0, window.location.href.lastIndexOf('/'));
				} else if (path.match(/^https?\:\/\//i)) {
					// Absolute
					return path;
				} else if (path[0] === '/') {
					// Site root relative
					return window.location.origin + path;
				} else {
					var url = window.location.href;
					// Find last '/'
					url = url.substring(0, url.lastIndexOf('/'));
					// Go back until ../
					while (path.startsWith('../')) {
						url = url.substring(0, url.lastIndexOf('/', url.length - 2));
						path = path.slice(3);
					}
					return url + path;
				}
			},
			
		// Getting path reference either by searching or adding as new
		// 0 => current folder
		
		getPathRef = function(p) {
			
				if (typeof p === UNDEF || !p) {
					// Current folder
					return 0;
				}
			
				if (p.slice(-1) !== '/') {
					// Sanitizing paths: adding extra slash at the end 
					p += '/';
				}
				
				var idx = paths.indexOf(p);
				
				if (idx >= 0) {
					// Found => return
					return idx + 1;
				}
				
				// Add new
				return paths.push(p);
			},
		
		// Fixing path containing ../ part(s)
		
		fixUrl = function(p) {
				var i, 
					j, 
					t = this + '';
					
				while ((i = t.indexOf('../')) > 0) {
					if (i === 1 || (j = t.lastIndexOf('/', i - 2)) === -1) {
						return t.substring(i + 3);
					}
					t = t.substring(0, j) + t.substring(i + 2);
				}
				
				return t;
			},
			
		// Get album's root folder
		
		getAlbumPath = function() {
			
				if (albumPath) {
					return albumPath;
				}
				
				var p = window.location.pathname,
					l = currentFolder[J.LEVEL];
				
				do {
					p = p.substring(0, p.lastIndexOf('/'));
					l = l - 1;
				} while (l >= 0);
				
				return p;
			},
			
		// Getting folder path for an object (absolute or relative)
		
		getPath = (o) => {
				if (o.hasOwnProperty(J.PATHREF) && o[J.PATHREF]) {
					return albumPath? 
						makePath(albumPath, paths[o[J.PATHREF] - 1])
						:
						paths[o[J.RELPATH] - 1];
				}
				return albumPath || '';
			},
			
		// Gets the path to item - Relative path
		
		getItemPath = function(o) {
				var p = getPath(o),
					c = o[J.CATEGORY] || 'folder';
				
				if (c === 'folder') {
					return p;
				} else if (c === 'video') {
					return p + o[J.VIDEO][J.PATH];
				} else if (c === 'audio' || c === 'other' || o.hasOwnProperty(J.ORIGINAL)) {
					return p + o[J.ORIGINAL][J.PATH];
				} else if (c === 'image') {
					return p + o[J.IMAGE][J.PATH];
				} else if (c === 'webPage') {
					return p + o[J.NAME];
				} else {
					// webLocation
					return o[J.PATH];
				}
			},
			
		// Returns link to object in the real album
		
		getLink = function(o) {
			
				if (typeof o !== UNDEF) {
					
					switch (o[J.CATEGORY]) {
						
						case 'folder':
							
							return getPath(o);
							
						case 'webLocation':
							
							return o[J.PATH];
							
						case 'webPage':
							
							return makePath(getPath(o), o[J.PATH]);
							
					}
					
					return makePath(getPath(o), '#img=' + o[J.PATH]);
				}
				
				return '';
			},
							
		// Get HREF path
		/*
		getAbsolutePath = function(o) {
				var c = o[J.CATEGORY] || 'folder';
				
				if (c === 'webLocation') {
					return o[J.PATH];
				}
				
				var p = window.location.href
				
				return getPath(o) + ((c === 'folder')? '' : o[J.PATH]);
			},
		*/	
		// Get path to item from root
		
		getRootPath = (o) => (getPath(o) + o[J.NAME]),
			
		// Get the pointer to a folder in a tree from path reference number
		
		getPointer = function(n) {
			
				if (typeof n !== 'number' || n <= 0) {
					// root
					return tree;
				}
				
				n--;
				
				if (n > paths.length) {
					log('Error: out of bounds path reference (' + n + ')!');
					return null;
				}
				
				return getFolder(paths[n]);
			},
				
		// Returns folder object in a folder by name
		/*
		getFolderObject = function(folder, name) {
				if (folder.hasOwnProperty(J.FOLDERS)) {
					return folder[J.FOLDERS].find(function(f) { return f[J.PATH] === name; }); 
				}
				return null;
			},
		*/
		// Returns folder object by full path
		
		getFolder = function(path) {
				
				if (typeof path === UNDEF) {
					return null;
				} else if (!path.length) {
					return tree;
				}
				
				var folder = tree,
					p = path.split('/'),
					level;
					
				for (level = 0; level < p.length; level++) {
					if (folder.hasOwnProperty(J.FOLDERS) &&
						(folder = folder[J.FOLDERS].find(function(f) { 
								return f[J.PATH] === p[level]; 
							}))) {
						// Found, carry on
						continue;
					}
					return null;
				}
				
				return (level === p.length)? folder : null;
			},
		
		// Getting the parent
		
		getParent = function(o) {
			
				if (typeof o === UNDEF) {
					o = currentFolder;
				}
				
				if (o === tree) {
					// At top level
					return null;
				}
				
				// Getting current folder or the parent in case of a folder
				var p = getPointer(o[J.PARENTREF] || o[J.PATHREF]);
				
				// Avoid endless loops
				return (p === o)? null : p;
			},
			
		// Getting an item from full path, waits for folder to load if not present
		
		getItem = function(path, doneFn) {
				
				if (typeof doneFn !== 'function') {
					return;
				}
				
				var _getItem = function(folder, name, doneFn) {
							if (folder.hasOwnProperty(J.OBJECTS)) {
								var o = folder[J.OBJECTS].find(function(o) { 
										return o[J.NAME] === name;
									});
								doneFn.call(o);
							}
						};
						
				if (!path) {
					// Tree
					doneFn.call(tree);
					
				} else if (path.endsWith('/')) {
					// Folder
					doneFn.call(getFolder(path));
					
				} else {
					// An object
					var i = path.lastIndexOf('/'),
						folder = getFolder(path.substring(0, i)),
						name = path.substring(i + 1);
					
					if (folder) {
						// Exists
						if (folder.hasOwnProperty(J.OBJECTS)) {
							// Objects already loaded
							_getItem(folder, name, doneFn);
							
						} else {
							// Have to wait until objects loaded
							loadData(folder, function(folder) {
									_getItem(folder, name, doneFn);
								});
						}
					}
				}
				
				return null;
			},
		
		// Current folder object
		
		getCurrentFolder = () => (currentFolder),
			
		// Returns all objects in a folder
		
		getObjects = () => (currentFolder.hasOwnProperty(J.OBJECTS)? currentFolder[J.OBJECTS] : []),
			
		// Returns only the lightboxable items
		
		getImages = function() {
			
				var items = [];
			
				if (currentFolder && currentFolder.hasOwnProperty(J.OBJECTS)) {
					
					currentFolder[J.OBJECTS].forEach(o => {
							if (isLightboxable(o)) {
								items.push(o);
							}
						});
				}
							
				return items;
			},
		
		// Returns only the folders
		
		getFolders = function() {
				
				if (currentFolder) {
					if (currentFolder.hasOwnProperty(J.FOLDERINDEX)) {
						// Using deep-data: every folder is in OBJECTS, FOLDERINDEX has indexes to OBJECTS
						var f = [];
						currentFolder[J.FOLDERINDEX].forEach(i => {
								f.push(currentFolder[J.OBJECTS][i]);
							});
						return f;
					} else if (currentFolder.hasOwnProperty(J.FOLDERS)) {
						return currentFolder[J.FOLDERS];
					} 
				}
							
				return [];
			},
			
		/***********************************************
		 *				Properties
		 */
		 
		// Album make date/time in UTC
		
		getMakeDate = () => (new Date(tree[J.FILEDATE])),
			
		// Album title
		
		getAlbumTitle = () => (tree[J.TITLE] || tree[J.NAME]),
			
		// Filename for images, originalname for other
		
		getItemName = (o) => (
				(o[J.CATEGORY] === 'video')?
					getFilenameFromURL(o[J.VIDEO][J.PATH])
					:
					(o.hasOwnProperty(J.ORIGINAL)? 
						getFilenameFromURL(o[J.ORIGINAL][J.PATH])
						:
						o[J.NAME]
					)
				),
		
		// Level?
		
		getLevel = (o) => {
				o = o || currentFolder;
				return o.hasOwnProperty(J.LEVEL)? o[J.LEVEL] : getLevel(getParent(o));
			},
			
		// Title
		
		getTitle = (o) => ((o || currentFolder)[J.TITLE] || ''),
			
		// Name
		
		getName = (o) => ((o || currentFolder)[J.NAME] || ''),
			
		// Name
		
		getLabel = (o) => (((o || currentFolder)[J.NAME] || '').replace(/\.\w+$/, '').replace(/_/g, ' ')),
			
		// Title or name for ALT tags
		
		getAlt = (o) => (getTitle(o) || getName(o)),
		
		// Comment
		
		getComment = (o) => ((o || currentFolder)[J.COMMENT] || ''),
			
		// Path to an object as HTML page with hash
		/*
		getItemLink = function(o) {
				var p = makePath(getPath(o || currentFolder), settings.indexName);
				
				return p + (isLightboxable(o[i])? ('#img=' + escape(o[J.NAME])) : '';
			},
		*/
		// Thumbnail path
		
		getThumbPath = function(o) {
				var t = o[J.THUMB][J.PATH];
				
				if (isFolder(o)) {
					// Folder thumbnails are moved down one level
					t = t.replace(o[J.PATH] + '/', '');
				}
				
				return makePath(getPath(o), t);
			},
			
		// Image path
		
		getImagePath = (o) => (makePath(getPath(o), o.hasOwnProperty(J.LEVEL)? 
					o[J.THUMB][J.PATH].replace(settings.thumbsDir + '/', settings.slidesDir + '/' ) 
					: 
					o[J.IMAGE][J.PATH]
				)
			),
			
		// Theme image path
		
		getThemeImagePath = (o) => (makePath(getPath(o), settings.folderImageFile)),
			
		// Original path
		
		getOriginalPath = (o) => ((o.hasOwnProperty(J.ORIGINAL))? makePath(getPath(o), o[J.ORIGINAL][J.PATH]) : null),
			
		// Poster path for audio and video files
		
		getPosterPath = function(o) {
				var c = o[J.CATEGORY],
					ip = o[J.IMAGE][J.PATH];
					
				if ((c === 'audio' || c === 'video') && 
					!ip.startsWith(settings.slidesDir + '/')) {
					/* custom icon for audio or video */
					return makePath(rootPath, 'res', settings[c + 'Poster']);
				}
				
				return makePath(getPath(o), o[J.IMAGE][J.PATH]);
			},
			
		// Get optimum sized representing image
		
		getOptimalImage = function(o, dim) {
				var p = getPath(o[J.RELPATH]),
					c = o[J.CATEGORY] || 'folder';
				
				if (c === 'folder') {
					return makePath(p, 
								(dim[0] > settings.folderThumbDims[0] || 
								 dim[1] > settings.folderThumbDims[1])? 
									settings.folderImageFile 
									: 
									settings.folderThumbFile
							);
				} else {
					return makePath(p,
								(dim[0] > settings.thumbDims[0] || 
								 dim[1] > settings.thumbDims[1])? 
								  	o[J.IMAGE][J.PATH] 
								  	: 
								  	o[J.THUMB][J.PATH]
							);
				}
			},
			
		// Original or source path
		
		getSourcePath = (o) => makePath(getPath(o), 
						(o.hasOwnProperty(J.ORIGINAL)? o[J.ORIGINAL][J.PATH] : o[J.IMAGE][J.PATH])),
			
		// Absolute path to an object as HTML page
		
		getAbsoluteItemPath = (o) => makePath(absolutePath, getItemPath(o)),
			
		// Get video duration in ms
		
		getVideoDuration = function(o) {
				var v = o[J.VIDEO],
					d,
					m;
				
				if (!v || !v.hasOwnProperty(J.DURATION)) {
					return null;
				}
				
				m = v[J.DURATION].match(/(\d{2})\:(\d{2})\:(\d{2})\.(\d+)/);
				
				return m?
						parseInt(m[4]) + parseInt(m[3]) * 1000 + parseInt(m[2]) * 60000 + parseInt(m[1]) * 3600000
						:
						null;
			},
			
		// Has shop options?
		
		hasShop = (o) => {
				var p = getInheritedPropertyObject(o || tree, J.SHOP);
				
				return p && (p['usePrice'] || p['options'] !== '-');
			},
			
		// Has map location?
		
		hasLocation = (o) => (o.hasOwnProperty(J.LOCATION) ||
				(o.hasOwnProperty(J.CAMERA) && o[J.CAMERA].hasOwnProperty(J.LOCATION))),
			
		// Get location in NN.NNN,NN.NNN format (Lat,long)
		
		getLocation = (o) => (
				o.hasOwnProperty(J.LOCATION)? 
					o[J.LOCATION]
					:
					(
						(o.hasOwnProperty(J.CAMERA) && o[J.CAMERA].hasOwnProperty(J.LOCATION))?
							(o[J.CAMERA][J.LOCATION]['lat'] + ',' + o[J.CAMERA][J.LOCATION]['long']) 
							:
							null
					)
			),
			
		// Get the lowest price of an item
		
		getPriceRange = function(o) {
				var p = getInheritedPropertyObject(o || tree, J.SHOP);
				
				if (p && p['options'] !== '-' && p['showPriceRange']) { 
					var	opt = p.options.split('::'),
						min = Number.MAX_VALUE,
						max = Number.MIN_VALUE;
					
					if (opt.length > 1) {
						for (var i = 0; i < opt.length; i++) {
							min = Math.min(parseFloat(opt[i].split('=')[1].split('+')[0]), min);
						}
						if (p.showPriceRange === 'minmax') {
							for (var i = 0; i < opt.length; i++) {
								max = Math.max(parseFloat(opt[i].split('=')[1].split('+')[0]), max);
							}
							return toCurrency(min, p['currency']) + '&ndash;' + toCurrency(max, p['currency']);
						}
						return text.from.template(toCurrency(min, p['currency']));
					} else {
						return toCurrency(opt[0].split('=')[1].split('+')[0], p['currency']);
					}
				}
				
				return '';
					
			},
			
		getCurrency = () => ( getRootProperty(J.SHOP)['currency'] || 'EUR' ),
			
		// Counting folders recursively (current folder included)
		
		getDeepFolderCount = function(f) {
				var c = 0,
					f = (typeof f === UNDEF)? currentFolder : f;
				
				if (f.hasOwnProperty(J.FOLDERS)) {
					
					if (f.hasOwnProperty(J.DEEPCOUNTERS) && f[J.DEEPCOUNTERS].hasOwnProperty(J.FOLDERS)) {
						
						c = f[J.DEEPCOUNTERS][J.FOLDERS];
						
					} else {
						
						for (var i = 0, l = f[J.FOLDERS].length; i < l; i++) {
							c += getDeepFolderCount(f[J.FOLDERS][i]);
						}
						
						if (!f.hasOwnProperty(J.DEEPCOUNTERS)) {
							f[J.DEEPCOUNTERS] = {};
						}
						
						f[J.DEEPCOUNTERS][J.FOLDERS] = c;
					}
				}
				
				return c + 1;
			},
			
		// Getting folder count with max level
			
		getFolderCount = function(folder, levels) {
				var maxLevel = folder[J.LEVEL] + (levels || 0),
					_getCount = function(f) {
							var c = 0;
							
							if (f.hasOwnProperty(J.FOLDERS) && f[J.LEVEL] <= maxLevel) {
								// Count only within max levels
								for (var i = 0, l = f[J.FOLDERS].length; i < l; i++) {
									c += _getCount(f[J.FOLDERS][i]);
								}
							}
							
							return c + 1;
						};
						
				return _getCount(folder);
			},
			
		/*****************************
		 *		Generic property
		 */
		 
		// Returns a property from the root level
		
		getRootProperty = (a) => (tree.hasOwnProperty(a)? tree[a] : null),

		// Retrieving an Object property of an element with fallback to upper level folders
		
		getInheritedPropertyObject = function(o, a) {
				var p = {};
				
				do {
					if (o.hasOwnProperty(a)) {
						p = $.extend(true, {}, o[a], p);
					}
				} while (o = getParent(o));
				
				return (Object.getOwnPropertyNames(p)).length? p : null;
			},
						
		// Retrieving a single property of an element with fallback to upper level folders
		
		getInheritedProperty = function(o, a) {
				
				if (a.indexOf('.') >= 0) {
					a = a.split('.');
					
					if (a[0] === 'album') {
						return getRootProperty(a[1]);
					}
					
					do {
						if (o.hasOwnProperty(a[0])) {
							return $.extend(true, {}, o[a[0]][a[1]]);
						}
					} while (o = getParent(o));
					
					return null;
				}
					
				do {
					if (o.hasOwnProperty(a)) {
						return $.extend(true, {}, o[a]);
					}
				} while (o = getParent(o));
				
				return null;
			},
			
		// Returns a property (normal or inherited way)
		
		getProperty = function(o, a, inherit) {
				var r;
			
				if (inherit) {
					r = getInheritedProperty(o, a);
				} else if (a.indexOf('.') > 0) {
					a = a.split('.');
					r = (o.hasOwnProperty(a[0]))? o[a[0]][a[1]] : null;
				} else if (o.hasOwnProperty(a)) {
					r = o[a];
				}
				
				return $.extend(true, {}, r);
			},
		
		// Returns an Object property (normal or inherited way)
		
		getPropertyObject = (o, a, inherit) => ( 
				inherit? getInheritedPropertyObject(o, a) : (o.hasOwnProperty(a)? $.etxend(true, {}, o[a]) : null)
			),
					
		// Returns the next folder
		
		_getNextFolder = function(folder) {
				if (typeof folder === UNDEF) {
					folder = currentFolder;
				}
				
				var parent = getParent(folder);
				
				if (parent) {
					var n;
					if (parent.hasOwnProperty(J.FOLDERINDEX)) {
						n = parent[J.FOLDERINDEX].findIndex(function(i) { return parent[J.OBJECTS][i] === folder; });
						if (i < parent[J.FOLDERINDEX].length) {
							return parent[J.OBJECTS][parent[J.FOLDERINDEX][n + 1]];
						}
					} else if (parent.hasOwnProperty(J.FOLDERS)) {
						n = parent[J.FOLDERS].findIndex(function(f) { return f === folder; });
						if (n < parent[J.FOLDERS].length) {
							return parent[J.FOLDERS][n + 1];
						}
					}
				}
				
				return null;
			},
		
		// Returns the previous folder
		
		_getPreviousFolder = function(folder) {
				if (typeof folder === UNDEF) {
					folder = currentFolder;
				}
				
				var parent = getParent(folder);
				
				if (parent) {
					var i;
					if (parent.hasOwnProperty(J.FOLDERINDEX)) {
						i = parent[J.FOLDERINDEX].findIndex(function(i) { return parent[J.OBJECTS][i] === folder; });
						if (i > 0) {
							return parent[J.OBJECTS][parent[J.FOLDERINDEX][i + 1]];
						}
					} else if (parent.hasOwnProperty(J.FOLDERS)) {
						i = parent[J.FOLDERS].findIndex(function(f) { return f === folder; });
						if (i > 0) {
							return parent[J.FOLDERS][i + 1];
						}
					}
				}
				
				return null;
			},
				
		// Get next folder's first image
		
		getNextFoldersFirstImage = function(ready) {
				var img,
					folder = _getNextFolder(),
					
					_getFirstImage = function(folder) {
							if (folder.hasOwnProperty(J.OBJECTS)) {
								for (var o = folder[J.OBJECTS], i = 0, l = o.length; i < l; i++) {
									if (isLightboxable(o[i])) {
										return o[i];
									}
								}
							}
						};
				
				if (folder) {
					
					if (folder.hasOwnProperty(J.OBJECTS)) {
						ready.call(getFirstImage());
					} else {
						loadData(folder, function() {
								ready.call(_getFirstImage());
							});
					}
				}
				
				return null;
			},
		
		// Get previous folder's last image
		
		getPreviousFoldersLastImage = function(ready) {
				var img,
					folder = _getPreviousFolder(),
					
					_getLastImage = function(folder) {
							if (folder.hasOwnProperty(J.OBJECTS)) {
								for (var o = folder[J.OBJECTS], i = o.length - 1; i >= 0; i--) {
									if (isLightboxable(o[i])) {
										return o[i];
									}
								}
							}
						};
			
				
				if (folder) {
					
					if (folder.hasOwnProperty(J.OBJECTS)) {
						ready.call(getFirstImage());
					} else {
						loadData(folder, function() {
								ready.call(_getLastImage());
							});
					}
				}
				
				return null;
			},
			
		// Collect items from folder
		// folder = start folder
		// levels = depth below the start folder
		// max = maximum number
		// sortBy = sort criteria ( dateTaken|fileDate|dateAdded|fileSize|name )
		// sortOrder = 1: ascending 0: descending
		// quick = 	true: stops when enough element has been gathered, 
		//			false: loads all folders before it selects the "max" elements based on sort
		
		collectNItem = function(options) {
				
				//log('getItems(' + options + ')');
				if (typeof options === UNDEF || !options.hasOwnProperty('ready')) {
					return;
				}
				
				var options 		= $.extend({
											folder:			'',				// Root folder
											levels:			0,				// Current folder only
											include:		'images',		// Items to include
											max:			0,				// No limit
											sortBy:			'original',		// No sort			
											sortOrder:		0				// Ascending
										}, options),
					images 			= [],
					folders			= [],
					folder			= getFolder(options.folder),
					foldersToLoad 	= options.levels? getFolderCount(folder, options.levels) : 1,
					foldersLoaded	= 0,
					needsDeepData	= !folder && options.levels > 1 && tree.hasOwnProperty(J.FOLDERS),
					useImages		= options.include.indexOf('images') !== -1,
					useFolders		= options.include.indexOf('folders') !== -1,
				
					_addItems = function(folder) {
							// Adding images in a folder
							if (!folder || !folder.hasOwnProperty(J.OBJECTS)) {
								return;
							}
							
							folder[J.OBJECTS].forEach(o => {
									if (isLightboxable(o)) {
										images.push(o);
									}
								});
						},
					
					_addFolder = function(folder) {
						
							if (useFolders) {
								folders.push(folder);
							}
							
							if (useImages) {
								// Adding one folder
								loadData(folder, _addItems);
							}
							
							if (folder[J.LEVEL] <= maxLevel && folder.hasOwnProperty(J.FOLDERS)) {
								// recursive to subfolders
								folder[J.FOLDERS].forEach(f => _addFolder(f));
							}
							
							foldersLoaded++;
						},
						
					_readyDeep = function() {
						
							_addFolder(folder);
							
							setTimeout(_finished, 20);
						},
						
					_loadAll = function() {
						
							// Starting a new promise collect
							defer = [];
							
							// Deep data already loaded or data1.json is enough
							_addFolder(folder);
							
							// Allow some time to add the first defer
							setTimeout(function() {
									if (defer.length) {
										$.when.apply($, defer).done(_finished);
									} else {
										_finished();
									}
								}, 20);						
						},
						
					_finished = function() {
						
							if (options.max && options.quick && (images.length + folders.length) >= options.max ||  
								(foldersLoaded >= foldersToLoad)) { 
							
								_arrangeItems();
								
								if ($.isFunction(options.ready)) {
									options.ready.call(images, options);
								}
								
							} else {
								setTimeout(_finished, 50);
								return;
							}
						},
						
					_arrangeItems = function() {
							
							// Ordering images
							switch (options.sortBy) {
								
								case 'original':
									
									if (options.sortOrder === -1) {
										// Random
										if (useFolders) {
											folders.sort(() => (0.5 - Math.random()));
										}
										if (useImages) {
											images.sort(() => (0.5 - Math.random()));
										}
									} else if (options.sortOrder === 1) {
										// Descending
										if (useFolders) {
											folders.reverse();
										}
										if (useImages) {	
											images.reverse();
										}
									}
									
									break;
									
								case J.FILEDATE:
								case J.ADDED:
								case J.TAKENDATE:
								case J.MODIFIEDDATE:
									
									if (options.sortOrder) {
										if (useFolders) {
											folders.sort((a, b) => (b[J.FILEDATE]) - a[J.FILEDATE]);
										}
										if (useImages) {
											images.sort((a, b) =>
												((	b.hasOwnProperty(J.DATES)? b[J.DATES][options.sortBy] : b[J.FILEDATE]) - 
													a.hasOwnProperty(J.DATES)? a[J.DATES][options.sortBy] : a[J.FILEDATE]));
										}
									} else {
										if (useFolders) {
											folders.sort((a, b) => (a[J.FILEDATE]) - b[J.FILEDATE]);
										}
										if (useImages) {
											images.sort((a, b) =>
												(( 	a.hasOwnProperty(J.DATES)? a[J.DATES][options.sortBy] : a[J.FILEDATE]) - 
													b.hasOwnProperty(J.DATES)? b[J.DATES][options.sortBy] : b[J.FILEDATE]));
										}
									}
									
									break;
									
								case J.NAME:
									
									if (options.sortOrder) {
										if (useFolders) {
											folders.sort((a, b) =>
												('' + b[J.NAME]).localeCompare('' + a[J.NAME]));
										}
										if (useImages) {
											images.sort((a, b) =>
												('' + b[J.NAME]).localeCompare('' + a[J.NAME]));
										}
									} else {
										if (useFolders) {
											folders.sort((a, b) =>
												('' + a[J.NAME]).localeCompare('' + b[J.NAME])); 
										}
										if (useImages) {
											images.sort((a, b) =>
												('' + a[J.NAME]).localeCompare('' + b[J.NAME])); 
										}
									}
									
									break;
									
								case J.FILESIZE:
									
									if (options.sortOrder) {
										if (useImages) {
											images.sort((a, b) => 
												(b[J.FILESIZE] - a[J.FILESIZE]));
										}
									} else {
										if (useImages) {
											images.sort((a, b) =>
												(a[J.FILESIZE] - b[J.FILESIZE]));
										}
									}
									
									break;
							}
							
							// Concatenating folders and images arrays
							if (useFolders) {
								if (useImages) {
									if (options.include === 'folders,images') {
										images = folders.concat(images);
									} else {
										images = images.concat(folders);
									}
								} else {
									images = folders;
								}
							}
								
							// Chopping the extra items
							if (options.max && options.max < images.length) {
								images = images.slice(0, options.max);
							}
						};
				
				if (!options.hasOwnProperty('quick')) {
					options.quick = options.max && options.sortBy !== 'original';
				}
				
				maxLevel = folder[J.LEVEL] + options.levels;
				
				random = options.sortOrder === -1;
				if (random) {
					settings.sortBy = 'original';
				}
					
				if (needsDeepData && !deepReady) {
					
					// Loading deep data, falling back to recursive data1.json on error
					loadDeep(_readyDeep, _loadAll);
					
				} else {
					
					_loadAll();
				}
			},
			
		// Collect items by date
		// range = past n days
		//			or start-end
		// range, start = start ... start + range
		// range, end = end - range ... end
		// Where start and end are days since 1900-01-01
		
		collectByDate = function(options) {
			
				//log('collectByDate(' + options + ')');
				if (typeof options === UNDEF || !options.hasOwnProperty('range') || !options.hasOwnProperty('ready')) {
					return;
				}
				
				var options 		= $.extend({
											sort:			true,
											reverse:		false,
											reference:		'dateTaken',
											depth: 			'current' 		// 'tree' | 'current' | 'subfolders'
										}, options),
					items 			= [],
					start,
					end, 
					foldersToLoad 	= (options.depth === 'current')? 1 : getDeepFolderCount((options.depth === 'tree')? tree : currentFolder),
					foldersLoaded	= 0,
					needsDeepData	= options.depth === 'tree' && tree.hasOwnProperty(J.FOLDERS) ||
									  options.depth === 'subfolders' && currentFolder.hasOwnProperty(J.FOLDERS) && currentFolder[J.LEVEL] < 3,
				
					
					_findByDate = function(folder) {
							
							// Find images that fall into the date range
							
							if (!folder || !folder.hasOwnProperty(J.OBJECTS)) {
								return;
							}
							
							folder[J.OBJECTS].forEach(o => {
									if (isLightboxable(o) &&
										(d = o[J.DATES]) && 
										(d = d[options.reference]) && 
										(d >= start) && (d <= end)) {
										items.push(o);
									}
								});
														
							foldersLoaded++;
						},
					
					_addFolder = function(f) {
							var folder = (typeof f === UNDEF)? tree : f;
							
							// Adding one folder
							loadData(folder, _findByDate);
							
							if (options.depth !== 'current' && folder.hasOwnProperty(J.FOLDERS)) {
								// recursive to subfolders
								folder[J.FOLDERS].forEach(f => _addFolder(f));
							}
						},
						
					_readyDeep = function() {
						
							_addFolder((options.depth === 'tree')? tree : currentFolder);
							
							setTimeout(_finished, 20);
						},
						
					_loadAll = function() {
						
							// Starting a new promise collect
							defer = [];
							
							// Deep data already loaded or data1.json is enough
							_addFolder((options.depth === 'tree')? tree : currentFolder);
							
							// Allow some time to add the first defer
							setTimeout(function() {
									if (defer.length) {
										$.when.apply($, defer).done(_finished);
									} else {
										_finished();
									}
								}, 20);						
						},
						
					_finished = function() {
						
							if (foldersToLoad > foldersLoaded) {
								setTimeout(_finished, 20);
								return;
							}
							
							_arrangeItems();
							
							if ($.isFunction(options.ready)) {
								options.ready.call(items, options);
							}
						},
						
					_arrangeItems = function() {
							
							// Ordering items
							
							if (options.sort) {
								var d1, d2;
								if (options.reverse) {
									items.sort((a, b) => (b[J.DATES][options.reference] - a[J.DATES][options.reference]));
								} else {
									items.sort((a, b) => (a[J.DATES][options.reference] - b[J.DATES][options.reference]));
								}
							}
							
							if (options.max && options.max < items.length) {
								items = items.slice(0, options.max);
							}
						};
				
				
				// options.start and options.end are days since 1990-01-01, range is number of days
				if (options.hasOwnProperty('end')) {
					end = options.end * ONEDAY_S;
				}
				
				if (options.hasOwnProperty('start')) {
					start = options.start * ONEDAY_S;
				}
				
				if (options.hasOwnProperty('range')) {
					if (start !== null) {
						end = start + options.range * ONEDAY_S;
					} else if (end !== null) {
						start = end - options.range * ONEDAY_S;
					} else {
						// Up to now
						end = Math.round(new Date() / 1000);
						start = end - options.range * ONEDAY_S;
					}
				}
				
				if (start === null) {
					start = 0;
				}
				
				if (end === null) {
					Math.round(new Date() / 1000);
				}
								
				if (needsDeepData && !deepReady) {
					
					// Loading deep data, falling back to recursive data1.json on error
					loadDeep(_readyDeep, _loadAll);
					
				} else {
					
					_loadAll();
				}
			},
		
		/*
		 *	Collecting search results
		 *
		 *	fields: 	fields to watch
		 *	types:		all or comma separated list of allowed types ('image|audio|video|...)
		 *	depth:		where to collect ('tree' | 'current' | 'subfolders')
		 *	exact:		exact search (or conjunctive)
		 *	max:		maximum number of results
		 */
		
		collectItems = function(options) {
			
				//log('collectItems(' + set + ')');
				if (typeof options === UNDEF || !options.hasOwnProperty('terms')) {
					return;
				}
				
				var options 		= $.extend({
												fields: 		'creator,keywords,title,comment,name',
												types:			'all',
												depth: 			'current', 		// 'tree' | 'current' | 'subfolders'
												exact: 			false
											}, options),
					items 			= [], 
					fields 			= options.fields.split(/,\s?/), 
					fieldslength 	= fields.length,
					exact			= new Array(fieldslength),
					terms,
					termslength,
					conjunctive 	= false,
					allTypes		= options.types === 'all',
					types 			= {},
					foldersToLoad 	= (options.depth === 'current')? 1 : getDeepFolderCount((options.depth === 'tree')? tree : currentFolder),
					foldersLoaded	= 0,
					needsDeepData	= options.depth === 'tree' && tree.hasOwnProperty(J.FOLDERS) ||
									  options.depth === 'subfolders' && currentFolder.hasOwnProperty(J.FOLDERS) && currentFolder[J.LEVEL] < 3,
										
					_searchItem = function(o, cat) {
						
							var found = 0;
								
							for (var i = 0, f, p; i < fieldslength; i++) {
								
								// Category specific field?
								
								if (fields[i].length > 1) {
									// e.g. "folder:title"
									if (fields[i][0] !== cat) {
										continue;
									}
									f = fields[i][1];
								} else {
									f = fields[i][0];
								}
								
								if (JCAMERA.indexOf(f) && o.hasOwnProperty(J.CAMERA)) {
									// camera data
									p = o[J.CAMERA][f];
									if (typeof p === UNDEF) {
										p = o[f];
									}
								} else {
									p = o[f];
								}
									
								if (typeof p !== UNDEF && p != null) {
									// Has such property
									
									if ($.isArray(p)) {
										// Array: e.g. keywords[]
										p = p.join(' ');
									} else {
										p = p + '';
									}
									
									if (f === 'comment' || f.endsWith('Caption')) {
										p = p.stripHTML();
									}
									
									// log('search:' + terms + ' in:' + s + ' exact:' + exact[f] + ' ==> ' + s.searchTerm(terms, exact[f], conjunctive)); 
									if (p.searchTerm(terms, exact[f], conjunctive)) {
										found++;
									}
								}
							}
							
							
							if ((conjunctive && (found === termslength)) || found) {
								// all terms found
								items.push(o);
							}
							
						},
						
					_searchFolder = function(folder) {
							/*
							if (DEBUG) {
								log('Searching folder "' + folder[J.NAME] + '" ' + (folder[J.OBJECTS]? folder[J.OBJECTS].length : 0) + ' items');
							}
							*/
							if (!folder) {
								return;
							}
							
							if (folder !== tree && (allTypes || types['folder'])) {
								// Folders but not the top level
								_searchItem(folder, 'folder');
							}
							
							if (folder.hasOwnProperty(J.OBJECTS)) {
								// Objects
								var cat;
								folder[J.OBJECTS].forEach(o => {
										cat = o[J.CATEGORY];
										if (allTypes || types[cat]) {
											_searchItem(o, cat);
										}
									});
							}
							
							foldersLoaded++;
						},
				
					_addFolder = function(f) {
							var folder = (typeof f === UNDEF)? tree : f;
							
							// Adding one folder
							loadData(folder, _searchFolder);
							
							if (options.depth !== 'current' && folder.hasOwnProperty(J.FOLDERS)) {
								// recursive to subfolders
								folder[J.FOLDERS].forEach(f => _addFolder(f));
							}
						},
						
					_readyDeep = function() {
						
							_addFolder((options.depth === 'tree')? tree : currentFolder);
							
							setTimeout(_finished, 20);
						},
						
					_loadAll = function() {
						
							// Starting a new promise collect
							defer = [];
							
							// Deep data already loaded or data1.json is enough
							_addFolder((options.depth === 'tree')? tree : currentFolder);
							
							// Allow some time to add the first defer
							setTimeout(function() {
									if (defer.length) {
										$.when.apply($, defer).done(_finished);
									} else {
										_finished();
									}
								}, 20);						
						},
						
					_finished = function() {
						
							if (foldersToLoad > foldersLoaded) {
								setTimeout(_finished, 20);
								return;
							}
							
							_arrangeItems();
							
							if ($.isFunction(options.ready)) {
								options.ready.call(items, options);
							}
						},
						
					_arrangeItems = function() {
							if (options.max && options.max < items.length) {
								items = items.slice(0, options.max);
							}
						};
				
				if (options.terms[0] === '"' && options.terms[options.terms.length - 1] === '"') {
					// Exact search with quotes: "something exact"
					terms = options.terms.substring(1, options.terms.length - 1);
					if (options.exact === false) {
						// Change only if no excplicit exact spec.  
						options.exact = true;
					}
				} else {
					// No quotes
					if (options.exact === false) {
						// Any word
						terms = options.terms.replace(/\s+/g, ",");
						
						if (~terms.indexOf(',' + text.and + ',')) {
							// Conjunctive search
							terms = terms.replace(new RegExp(',' + text.and + ',', 'gi'), ',');
							conjunctive = true;
						}
					} else {
						// Exact by request
						terms = options.terms.trim();
					}
				}	
				
				terms = options.exact? [ terms ] : removeEmpty(terms.split(/,\s?/));
				termslength = terms.length;
				
				for (var i = 0, f; i < fieldslength; i++) {
					fields[i] = fields[i].split(':');
					f = fields[i][1] || fields[i][0];
					exact[f] = (typeof options.exact === 'string')? (options.exact.indexOf(f) >= 0) : options.exact;
				}
				
				if (!allTypes) {
					
					if (settings.types.charAt(0) === '-') {
						// Negative
						settings.possibleTypes.forEach(t => {
								if (settings.types.indexOf(t) === -1) {
									types[t] = true;
								}
							});
					} else {
						// Positive
						settings.types.split(/,\s?/).forEach(t => { types[t] = true; });
					}
				}
				
				if (needsDeepData && !deepReady) {
					
					// Loading deep data, falling back to recursive data1.json on error
					loadDeep(_readyDeep, _loadAll);
					
				} else {
					
					_loadAll();
				}
									
			},
		
		// Tag cloud
		
		collectTags = function(options) {
			
				//log('collectTags(' + set + ')');
					
				var options 		= $.extend({
											fields: 	'creator,keywords,folder:title,webLocation:title',
											types:		'all',	
											depth: 		'current', 			// 'tree' | 'current' | 'subfolders'
											exact:		'creator,keywords,name'
										}, options),
					tags 			= [], 
					fields 			= $.isArray(options.fields)? options.fields : options.fields.split(/,\s?/), 
					fieldslength 	= fields.length,
					sortByName 		= options.sort === 'name',
					allTypes 		= options.types === 'all',
					types			= {},
					exact			= {},
					
					// Add tags collected from an item
					// tags = [ 'tag', cnt, 'TAG' ]
					
					_addTags = function(newTags) {
							var newTags = newTags.split('^');
							
							for (var i = 0, found = false, l = newTags.length; i < l; i++) {
								
								if (newTags[i].length < 3) {
									continue;
								}
								
								tag = newTags[i].toUpperCase();
								found = false;
								
								for (var j = 0, tl = tags.length; j < tl; j++) {
									if (tag === tags[j][2]) {
										tags[j][1]++;
										found = true;
										break;
									}
								}
								
								if (!found) {
									tags.push([ newTags[i], 1, tag ]);
								}
							}
						},
					
					// Collects tags from an item
					
					_collectTags = function(o, cat) {
							var ctags = '^',
								ctagsuc = '^',			// Uppercase for comparison
							
								add = function(tag, field) {
										
										if (!tag) {
											return;
										}
										
										var t, 
											ta;
											
										if (exact[field]) {
											ta = [ tag.toString() ];
										} else {
											if (field === 'comment' || field.endsWith('Caption')) {
												tag = tag.stripHTML();
											}
											//ta = tag.split(/\W+/);
											ta = tag.split(/[\s,_\.\?\!\-\(\)\[\]]/);
											ta = removeEmpty(ta);
										}
										
										for (var i = 0, l = ta.length, fnd = false; i < l; i++) {
										
											t = ta[i].trim();
											
											if (t.length <= 2) {
												// Empty or too short
												continue;
											}
											
											if (ctagsuc.indexOf('^' + t.toUpperCase() + '^') === -1) {
												ctags += t + '^';
												ctagsuc += t.toUpperCase() + '^';
											}
										}
									};
							
							for (var i = 0, f, p, keys = ''; i < fieldslength; i++) {
								if (fields[i].length > 1) {
									if (fields[i][0] !== cat) {
										continue;
									}
									f = fields[i][1];
								} else {
									f = fields[i][0];
								}
								
								
								if (JCAMERA.indexOf(f) && o.hasOwnProperty(J.CAMERA)) {
									// camera data
									p = o[J.CAMERA][f];
									if (typeof p === UNDEF) {
										p = o[f];
									}
								} else {
									p = o[f];
								}
									
								if (typeof p !== UNDEF && p != null) {
									//log(o['name'] + '[' + f + '] = ' + o[f] + ' (' + ($.isArray(o[f])? 'array':(typeof o[f])) + ')');
									if ($.isArray(p)) {
										for (var j = 0; j < p.length; j++) {
											add(p[j], f);
										}
									} else {
										add(p, f);
									}
								}
							}
							
							//log(ctags);
							if (ctags.length > 1) {
								_addTags(ctags);
							}
						},
					
					// Collect tags from all objects in a folder
					
					_addItems = function(folder) {
					
							// Adds fields from objects array
							if (!folder) {
								return;
							}
							
							if (folder !== tree && (allTypes || types['folder'])) {
								// Current folder
								_collectTags(folder, 'folder');
							}
							
							if (folder.hasOwnProperty(J.OBJECTS)) {
								// Ordinary objects
								for (var i = 0, o = folder[J.OBJECTS], cat; i < o.length; i++) {
									if (o[i].hasOwnProperty(J.CATEGORY)) {
										cat = o[i][J.CATEGORY];
										if (allTypes || types[cat]) {
											_collectTags(o[i], cat);
										}
									}
								}
							}
						},
				
					// Queues one folder to collect tags  
					
					_addFolder = function(folder) {
							
							// Adds one folder
							
							loadData(folder, _addItems);
							
							if (options.depth !== 'current' && folder.hasOwnProperty(J.FOLDERS)) {
								// recursive to subfolders
								for (var i = 0, l = folder[J.FOLDERS].length; i < l; i++) {
									_addFolder(folder[J.FOLDERS][i]);
								}
							}
						},
						
					// Arrange the tags when ready
					
					_arrangeTags = function() {
							if (options.sort) {
								tags.sort(function(a, b) {
									return sortByName? ('' + a[2]).localeCompare('' + b[2]) : (b[1] - a[1]);	
								});
							}
							if (options.max && options.max < tags.length) {
								tags = tags.slice(0, options.max);
							}
						};
				
				// Starting a new promise collect
				
				defer = [];
				
				// Gathering fields to collect from
				
				for (var i = 0, f; i < fieldslength; i++) {
					fields[i] = fields[i].split(':');
					f = fields[i][1] || fields[i][0];
					exact[f] = (typeof options.exact === 'string')? (options.exact.indexOf(f) >= 0) : options.exact;
				}
				
				// Creating object types array too look for
				if (!allTypes) {
					for (var i = 0, t = settings.types.split(/,\s?/); i < t.length; i++) {
						types[t[i]] = true;
					}
				}
				
				// Adding folder(s)
				
				_addFolder((options.depth === 'tree')? tree : currentFolder);
					
				if ($.isFunction(options.ready)) {
				
					if (defer.length) {
						$.when.apply($, defer).done(function() {
							_arrangeTags();
							options.ready.call(tags, options);
						});
					} else {
						_arrangeTags();
						options.ready.call(tags, options);
					}
				}
						
			},
		
		// Processing template for an object
		
		processTemplate = function(template, o, removeEmpty) {
			
				var remove = (typeof removeEmpty !== UNDEF)? removeEmpty : false,
					o = o || currentFolder,
					i0,
					i1,
					m,
					v,
					getKey = k => ((k === 'label')? getLabel(o) : stringVal(o[k]));
				
				if (template && template.indexOf('${') > 0) {
				
					while (m = template.match(/\$\{([\w\.|]+)\}/)) {
						if (m[1].indexOf('|') > 0) {
							// ${var1|var2} fallback format
							for (var i = 0, k = m[1].split('|'); i < k.length; i++) {
								if (v = getKey(k[i])) {
									// Found
									break; 
								}
							}
						} else {
							// Single variable
							v = getKey(m[1]);
						}
						
						if (v === null && remove) {
							// Remove empty HTML tags
							i0 = m.index - 1;
							i1 = i0 + m[0].length;
							
							if (i0 > 0 && template[i0] === '>' && i1 < (sb.length - 1) && template[i1] === '<') {
								
								i0 = template.lastIndexOf('<', i0);
								i1 = template.indexOf('>', i1);
								
								if (i0 >= 0 && i1 >= 0) {
									template = template.slice(0, i0) + template.slice(i1);
									continue;
								}
							}
						}
						// Replacing or removing variable
						template = template.slice(0, m.index) + (v || '') + template.slice(m.index + m[0].length);
					}
				}
		
				return template;
			},
			
		// Adding level, parent pointers and relative paths for easier navigation
		
		addExtras = function() {
			
				var add = function(o, level, path, parentRef) {
						var fp = level? makePath(path, o[J.PATH]) : '',
							pr = level? getPathRef(fp) : 0;
						
						// Level
						o[J.LEVEL] = level;
						
						// Category
						if (!o.hasOwnProperty(J.CATEGORY)) {
							o[J.CATEGORY] = 'folder';
						}
						
						if (level) {
							// Parent reference
							o[J.PARENTREF] = parentRef;
						}
						
						// Folder path reference
						o[J.PATHREF] = pr;
						
						// Relative path
						if (!albumPath) {
							// if we're inside the album (providing simpler paths)
							if (!level) {
								// In the top level page
								o[J.RELPATH] = pr;
							} else if (o !== currentFolder) {
								if ((fp + '/').indexOf(path) === 0) {
									// subfolder
									o[J.RELPATH] = getPathRef(fp.substring(path.length + 1));
								} else {
									// other branch
									o[J.RELPATH] = getPathRef(settings.rootPath + fp);
								}
							}
						}
						
						if (o[J.THUMB][J.PATH].startsWith(o[J.PATH] + '/' + settings.thumbsDir)) {
							// Fixing tree.json anomaly
							o[J.THUMB][J.PATH] = o[J.THUMB][J.PATH].slice(o[J.PATH].length + 1);
						}
						
						// Recursive to subfolders
						if (o.hasOwnProperty(J.FOLDERS)) {
							for (var i = 0, l = o[J.FOLDERS].length; i < l; i++) {
								add(o[J.FOLDERS][i], level + 1, fp, pr);
							}
						}
					};
				
				add(tree, 0, '', 0);
			},
		
			
		/*****************************************************************
		 * 			Loading tree.json from the top level folder
		 */
		
		loadTree = function(doneFn) {
			
				//log('loadTree() :: ' + settings.rootPath + settings.treeFile);
				var src = makePath((albumPath || relPath), settings.treeFile) + cacheBuster;
				
				return $.getJSON(src)
					.done(function(d) {
						// Tree loaded
						
						tree = d;
						// log('... tree loaded'); 
						
						// Initializing the load counters
						tree[J.LOADCOUNTER] = {};
						tree[J.LOADCOUNTER][J.TOTAL] = 0;
						
						for (var i = 0; i < settings.possibleTypes.length; i++) {
							tree[J.LOADCOUNTER][settings.possibleTypes[i]] = 0;
						}
				
						// Getting the pointer to the current folder
						currentFolder = getFolder(settings.relPath);
						
						if (currentFolder === null) {
							if ($.isFunction(settings.fatalError)) {
								settings.fatalError.call(this, 'noSuchFolder', settings.relPath);
							}
						}
						
						// Adding extra variables
						addExtras();
						
						// Calling "done" function
						if ($.isFunction(doneFn)) {
							doneFn.call(this);
						}
					})
					.fail(function(jqxhr, status, error) {
							
						if ($.isFunction(settings.fatalError)) {
							settings.fatalError.call(this, 'databaseAccessDenied', src);
						}
						
						// Calling "done" function
						if ($.isFunction(doneFn)) {
							doneFn.call(this);
						}
					});
			},
			
		// Copying missing folder properties
		
		copyFolderProps = function(d, folder) {
				if (!folder) {
					return;
				}
				for (var prop in d) {
					// Assigning folder variables 
					if (prop !== J.OBJECTS && prop !== J.ALBUM && !folder.hasOwnProperty(prop)) {
						folder[prop] = d[prop];
					}
				}
			},
			
		// Copying Objects array
		
		copyObjects = function(d, folder, deep) {
			
				// Copy Objects
				if (d.hasOwnProperty(J.OBJECTS)) {
					// Ensure it exists
					folder[J.OBJECTS] = [];
					
					for (var i = 0, o, j = 0, l = d[J.OBJECTS].length; i < l; i++) {
						
						o = d[J.OBJECTS][i];
						tree[J.LOADCOUNTER][o[J.CATEGORY]]++;
						tree[J.LOADCOUNTER][J.TOTAL]++;
						
						if (o[J.CATEGORY] === 'folder') {
							// Folder
							
							if (!folder[J.FOLDERS]) {
								folder[J.FOLDERS] = [];
							}
							
							copyFolderProps(o, folder[J.FOLDERS][j]);
							
							if (deep) {
								copyObjects(o, folder[J.FOLDERS][j], true);
							}
							
							// Storing only the reference index (avoid duplication)
							o = {};
							o[J.FOLDERINDEX] = j;
							j++;
							
						} else {
							// Not folder
							// Adding absolute and relative paths
							o[J.PATHREF] = folder[J.PATHREF];
							o[J.RELPATH] = folder[J.RELPATH];
						}
						
						folder[J.OBJECTS].push(o);
					}
				}
			},
			
		// Loading one folder's detailed data from data1.json
		
		loadData = function(folder, doneFn) {
				
				// Couldn't identify/find a folder
				if (!folder) {
					//log('Error: loadData("null")!');
					return;
				}	
				
				// Loading the folder's objects
				if (folder.hasOwnProperty(J.OBJECTS)) {
					// already loaded
					if ($.isFunction(doneFn)) {
						doneFn.call(this, folder);
					}
					return true;
						
				} else {
					// we need to load it
					var src = makePath(getPath(folder), settings.dataFile) + cacheBuster;
					
					// building defer array to be able to check the full load
					if (!defer) {
						defer = [];
					}
					// Cache buster with ?makeDate
					defer.push($.getJSON(src)
							.done(function(d) {
								//log("data loaded for: " + f[J.NAME]);
								
								// Copying the folder's missing properties
								copyFolderProps(d, folder);
								copyObjects(d, folder);
								
								if ($.isFunction(doneFn)) {
									doneFn.call(this, folder);
								}
								
							}).fail(function(jqxhr, status, error) {
								log('Error loading folder data for "' + src + '": ' + status + ', ' + error);
								if ($.isFunction(doneFn)) {
									doneFn.call(this, folder);
								}
							}));
				}
			},
		
		// Loading data for a single folder
		
		loadFolder = function(folder, deep) {
			
				//log('loadFolder("' + f[J.NAME] + '")');
				loadData(folder);
				
				if (deep && folder.hasOwnProperty(J.FOLDERS)) {
					
					for (var i = 0, l = folder[J.FOLDERS].length; i < l; i++) {
						loadFolder(folder[J.FOLDERS][i]);
					}
				}
			},
			
		// Load deep data structure
		
		loadDeep = function(doneFn, failFn) {
				var ins = new Date(),
					src = makePath((albumPath || rootPath), settings.deepDataFile) + cacheBuster;
				
				return $.getJSON(src)
				
					.done(function(d) {
							
							if (DEBUG) {
								log('Deep data loaded: ' + ((new Date()) - ins) + 'ms' + ' total: ' + tree[J.LOADCOUNTER][J.TOTAL] + ' objects');
								ins = new Date();
							}
							
							copyObjects(d, tree, true);
						
							if (DEBUG) {
								log('Deep data objects are ready: ' + ((new Date()) - ins) + 'ms' + ' total: ' + tree[J.LOADCOUNTER][J.TOTAL] + ' objects');
							}
							
							deepReady = true;
							
							if ($.isFunction(settings.deepReady)) {
								settings.deepReady.call(this);
								settings.deepReady = null;
							}
							
							if ($.isFunction(doneFn)) {
								doneFn.call(this);
							}
							
						}).fail(function() {
							
							deepReady = false;
							
							if (DEBUG) {
								log('Error loading deep data: "' + src + '".');
							}
							
							if ($.isFunction(settings.deepReady)) {
								settings.deepReady.call(this);
								settings.deepReady = null;
							}
							
							if ($.isFunction(failFn)) {
								failFn.call(this);
							}
							
						});
			},
			
		// Initializing
		
		init = function(set) {
			
				if (instance) {
					return instance;
				}
				
				instance = new Date();
				
				if (typeof set !== UNDEF) {
					$.extend(settings, set);
				}
				
				ready = deepReady = false;
				
				if (settings.hasOwnProperty('albumPath')) {
					
					// Initializing by absolute URL
					albumPath = settings.albumPath;
					
					// Sanitizing URL
					if (albumPath.slice(-1) !== '/') {
						albumPath += '/';
					}
					
				} else {
					// Inside album (in a folder)
					albumPath = (settings.rootPath === '.')? '' : settings.rootPath;
				}
				
				absolutePath = getAbsoluteFolderPath(albumPath);
				
				if (settings.hasOwnProperty('makeDate')) {
					cacheBuster = '?' + settings.makeDate;
				}
				
				// Loading the folder's Objects: current or all
				
				var treeReady = function() {
					
						defer = [];
						
						// Loading current folder (+ deep folders?)
						loadFolder(settings.lazy? currentFolder : tree, !settings.lazy);
						
						// has subfolders: waiting for AJAX requests to be completed
						$.when.apply($, defer).done(function() {
								var d = new Date();
								
								if (DEBUG) {
									log(defer.length + ' folder(s) loaded: ' + (d - instance) + 'ms');
								}
								
								ready = true;
								defer = null;
								//current = (currentFolder && currentFolder.hasOwnProperty(J.OBJECTS))? 0 : null;
								
								if ($.isFunction(settings.ready)) {
									settings.ready.call(this);
									settings.ready = null;
								}
								
								if (settings.loadDeep && tree.hasOwnProperty(J.FOLDERS)) {
									// Loading deep data only in structured albums 
									loadDeep();
								} else {
									// Flat album: calling deep ready immediately
									if ($.isFunction(settings.deepReady)) {
										settings.deepReady.call(this);
										settings.deepReady = null;
									}
								}
							});
					};
				
				// Loading tree.json
				
				return loadTree(treeReady);
						
			};
		

	
	//console.log("Album initialized!");
	
	if (options) {
		log('new Album(' + JSON.stringify(options) + ');');
		init(options);
	}
	
	return {
			//init: 							init,
			isReady:						isReady,
			isDeepReady:					isDeepReady,
			
			// Debug
			getTree: 						getTree,
			getPaths: 						getPaths,
			
			// Type checking
			isImage: 						isImage,
			isAudio: 						isAudio,
			isVideo: 						isVideo,
			isLightboxable: 				isLightboxable,
			isCurrentFolder: 				isCurrentFolder,
			
			// Access
			getAlbumPath:					getAlbumPath,
			getPath:						getPath,
			getItemPath:					getItemPath,
			getLink:						getLink,
			getRootPath:					getRootPath,
			getFolder: 						getFolder,	
			getParent: 						getParent,
			getItem:						getItem,
			getCurrentFolder: 				getCurrentFolder,
			getObjects: 					getObjects,
			getImages: 						getImages,
			getFolders:						getFolders,
			
			// Properties
			getMakeDate: 					getMakeDate,
			getAlbumTitle: 					getAlbumTitle,
			getItemName:					getItemName,
			getExtension:					getExtension,
			getLevel: 						getLevel,
			getTitle: 						getTitle,
			getName: 						getName,
			getLabel: 						getLabel,
			getAlt:							getAlt,
			getComment: 					getComment,
			getThumbPath: 					getThumbPath,
			getImagePath: 					getImagePath,
			getThemeImagePath:				getThemeImagePath,
			getOriginalPath:				getOriginalPath,
			getPosterPath: 					getPosterPath,
			getOptimalImage:				getOptimalImage,
			getSourcePath: 					getSourcePath,
			getAbsoluteItemPath: 			getAbsoluteItemPath,
			getVideoDuration:				getVideoDuration,
			hasShop: 						hasShop,
			hasLocation: 					hasLocation,
			getLocation: 					getLocation,
			getPriceRange:					getPriceRange,
			getCurrency:					getCurrency,
			getDeepFolderCount:				getDeepFolderCount,
			
			// Generic property
			getRootProperty: 				getRootProperty,
			getInheritedPropertyObject:		getInheritedPropertyObject,
			getInheritedProperty:			getInheritedProperty,
			getProperty: 					getProperty,
			getPropertyObject:				getPropertyObject,
			
			getNextFoldersFirstImage:		getNextFoldersFirstImage,
			getPreviousFoldersLastImage:	getPreviousFoldersLastImage,
			
			// Search
			collectNItem:					collectNItem,
			collectByDate: 					collectByDate,
			collectItems: 					collectItems,
			collectTags: 					collectTags,
			
			processTemplate:				processTemplate
				
		};
		
};
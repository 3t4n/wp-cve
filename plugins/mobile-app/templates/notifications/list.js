/* @version 3.3 */

async function getJSON(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open( 'GET', url, true );
	xhr.responseType = 'json';
	xhr.onload       = function() {
		var status = xhr.status;
		if (status == 200) {
			callback( null, xhr.response );
		} else {
			callback( status );
		}
	};
	xhr.send();
	return 1;
};

function createItemContent( article ) {
	// Return a DOM element here.
	var thumb;
	var wrap              = ons.createElement( '<div class="article-list__wrap"></div>' );
	var list_item_classes = [ 'article-list__article' ];
	// create article fields.
	var date_html = '';

	if ( article['formatted_date'] ) {
		date_html = '<div class="date' + ( article.url ? ' with-link' : '' ) + '">' + article.formatted_date + '</div>';
	} else if ( article['time'] || '' ) {
		var date  = new Date( 1000 * article.time );
		date_html = '<div class="date' + ( article.url ? ' with-link' : '' ) + '">' + date.toLocaleDateString() + '</div>';
	}
	var title_html = '';
	if ( article.title || '') {
		title_html = '<h2>' + article.title + '</h2>';
	}
	var text_html = '';

	if ( article.msg || '') {
		text_html = '<div>' + article.msg + '</div>';
	}
	// put fields in desired order.
	var main_content = [ title_html, text_html, date_html ];

	// Article text fields.
	var content = ons.createElement( '<div class="article-list__content">' + main_content.join( '' ) + '</div>' );
	wrap.append( content );

	// Article element.
	var listItem = ons.createElement( '<ons-list-item class="' + list_item_classes.join( ' ' ) + '" tappable data-ml-url="' + (article.url || '') + '"></ons-list-item>' );
	listItem.append( wrap );

	return listItem;
}

function renderList( data ) {
	var spinner = document.getElementById( 'loading-more' ),
	posts       = data['history'].length,
	list        = document.getElementById( 'article-list' );

	for ( var i = 0; i < posts; i++ ) {
		var article = createItemContent( data['history'][i] );

		article.addEventListener(
			'click',
			function () {
				console.log( 'clicked', this );
				var url = this.getAttribute( 'data-ml-url' );
				if ( url ) {
					window.location = url;
				}
			}
		);

		list.appendChild( article );
		rendered++;
	}
	document.querySelectorAll( '.article-list__article.is-placeholder' ).forEach( e => e.parentNode.removeChild( e ) );
	list.classList.add( 'rendered' );
	spinner.style.display = 'none';
}

async function getHistory( offset, params) {
	var spinner           = document.getElementById( 'loading-more' );
	spinner.style.display = 'block';
	return new Promise(
		function (resolve, reject) {
			getJSON(
				canvas_list.endpoint + '?' + ( params ? params + '&' : '' ) + 'offset=' + offset,
				function( err, data ) {
					if ( err != null ) {
						// console.log( 'Something went wrong: ' + err );
						spinner.style.display = 'none';
						resolve( 0 );
					} else {
						renderList( data );
						mlPostsData = mlPostsData.concat( data['history'] );
						data['history'].map( (e) => mlFirstData.push( e.id ) );
						var loaded            = data['history'].length;
						spinner.style.display = 'none';
						resolve( loaded );
					}
				}
			)
		}
	);
}

async function getNewlyPublishedHistory(offset, params) {
	return new Promise(
		function ( resolve, reject ) {
			getJSON(
				canvas_list.endpoint + ( params ? paramd + '&' : '?' ) + 'offset=' + offset,
				function( err, data ) {
					if ( err != null ) {
						// console.log( 'Something went wrong: ' + err );
						// spinner.style.display = 'none';
						resolve( 0 );
					} else {

						var newPosts = {
							posts: [],
							ids: [],
						}
						var j        = 0;
						if ( mlFirstData.length ) {
							for ( var i = 0; i < data.history.length; i++ ) {
								if ('undefined' !== typeof( data.history[ i ].id ) ) {
									if ( mlFirstData.indexOf( data.history[ i ].id ) < 0 ) {
										newPosts.posts.push( data.history[ i ] );
										newPosts.ids.push( data.history[ i ].id );
									}
								}
							}
						} else {
							// add all new content.
							for ( var i = 0; i < data.history.length; i++ ) {
								if ('undefined' !== typeof( data.history[ i ].id ) ) {
									newPosts.posts.push( data.posts[ i ] );
									newPosts.ids.push( data.history[ i ].id );
								}
							}
						}
						mlFirstData = newPosts.ids.concat( mlFirstData );
						mlPostsData = newPosts.posts.concat( mlPostsData );

						var posts_count = newPosts.posts.length,
						list            = document.getElementById( 'article-list' );

						for ( var i = posts_count - 1; i >= 0; i-- ) {
							var article = createItemContent( newPosts.history[i] );

							article.classList.add( 'new-item' );

							article.addEventListener(
								'click',
								function () {
									console.log( 'clicked', this );
									var url = this.getAttribute( 'data-ml-url' );
									if ( url ) {
										window.location = url;
									}
								}
							);
							list.insertBefore( article, list.firstChild );
						}

						// fade in new articles
						setTimeout(
							function() {
								document.querySelectorAll( '.article-list__article.new-item' ).forEach(
									e => {
                                    if ( e.classList.contains( 'new-item' ) ) {
                                        e.classList.remove( 'new-item' );

                                        e.style.height = '0px';
                                        setTimeout(
                                        function () {
                                            e.style.height = 'auto';
                                        },
                                        100
                                        );
                                    } else {
                                    e.style.height = '0px';
                                    container.addEventListener(
												'transitionend',
												function () {
													e.classList.add( 'new-item' );
												},
											{
												once: true
													}
											);
                                    }
									}
								);
							},
							100
						);

						var loaded = newPosts.posts.length;
						resolve( loaded );
					}
				}
			)
		}
	);
}

function fetchNewlyPushedNotifications() {
	getNewlyPublishedHistory( 0, '' )
	.then(
		function (more) {
			loaded += more;
		}
	);
}

// check for new items once every 60 seconds
function throttle(fn, timeout) {
	var timer = null;
	return function () {
		if ( ! timer) {
			timer = setTimeout(
				function() {
					fn();
					timer = null;
				},
				timeout
			);
		}
	};
}

var mlCheckNewArticlesInterval;

function mlScrollList() {
	if ( document.querySelector( '.page__content' ).scrollTop == 0 ) {
		clearInterval( mlCheckNewArticlesInterval );
		fetchNewlyPushedNotifications();
		mlCheckNewArticlesInterval = setInterval(
			function() {
				fetchNewlyPushedNotifications();
			},
			60000
		);
	} else {
		clearInterval( mlCheckNewArticlesInterval );
	}
}

document.querySelector( '.page__content' ).addEventListener(
	'scroll',
	function() {
		throttle( mlScrollList(), 500 );
	}
);



/* Main code */

var mlPostsData = [];
var mlFirstData = [];
var loaded      = 0;
var rendered    = 0;

var noMorePosts = false;


document.addEventListener(
	"DOMContentLoaded",
	function( event ) {

		document.querySelector( "body" ).dispatchEvent( new Event( 'scroll' ) );

		var page = document.getElementById( 'load-more-page' );

		page.onInfiniteScroll = function (done) {
			if ( ! noMorePosts) {
				noMorePosts = true;
				getHistory( loaded, '' ).then(
					function (more) {
						if (more === 0) {
							noMorePosts = true;
						} else {
							noMorePosts = false;
						}
						loaded += more;
						document.getElementById( 'loading-more' ).style.display = 'none';
						done(); // Important!
					}
				);
			} else {
				// end of posts animation / effect.
				document.getElementById( 'loading-more' ).style.display = 'none';
			}
		};

		var load_history = function() {
			page.onInfiniteScroll(
				function(){
					if ( loaded === 0 ) {
						document.querySelector( "body" ).innerHTML = '<h3 style="margin: 20px;">No notifications available yet</h3>';
					} else {
						var c = document.getElementById( 'article-list' );
						var d = document.getElementById( 'load-more-page' );
						if (c.clientHeight < d.clientHeight) {
							load_history();
						}
					}
				}
			);
		}
		load_history();

		// Pull hook.
		var pullHook = document.getElementById( 'pull-hook' );

		if (ons.platform.isIOS()) {
			pullHook.classList.add( 'ios' );
			document.body.classList.add( 'ml-ios' );
		} else {
			pullHook.classList.add( 'android' );
			document.body.classList.add( 'ml-android' );
		}

		pullHook.addEventListener(
			'changestate',
			function (event) {
				var message = '';

				switch (event.state) {
					case 'initial':
						message = 'Pull to refresh';
						break;
					case 'preaction':
						message = 'Release';
						break;
					case 'action':
						message = 'Loading...';
						break;
				}

				pullHook.innerHTML = message;
			}
		);

		pullHook.onAction = function (done) {
			window.location.reload();
			setTimeout( done, 3000 );
		};

	}
);

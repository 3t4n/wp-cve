//console.profile("Processing page");
//console.time("Page loading");

;(function (window) {
  var transitions = {
      'transition': 'transitionend',
      'WebkitTransition': 'webkitTransitionEnd',
      'MozTransition': 'transitionend',
      'OTransition': 'otransitionend'
    },
    elem = document.createElement('div');

  for(var t in transitions){
    if(typeof elem.style[t] !== 'undefined'){
      window.transitionEnd = transitions[t];
      break;
    }
  }
  if (!window.transitionEnd) window.transitionEnd = false;
})(window);

//polyfills
Number.isInteger = Number.isInteger || function(value) {
	return typeof value === 'number' &&
		isFinite(value) &&
		Math.floor(value) === value;
};

jQuery.fn.textWidth = function(_text, _font){//get width of text with font.  usage: $("div").textWidth();
  var fakeEl = jQuery('<span>').hide().appendTo(document.body).text(_text || this.val() || this.text()).css('font', _font || this.css('font')),
      width = fakeEl.width();
  fakeEl.remove();
  return width;
};

jQuery.fn.autoresize = function(options){//resizes elements based on content size.  usage: $('input').autoresize({padding:10,minWidth:0,maxWidth:100});
  options = jQuery.extend({padding:10,minWidth:0,maxWidth:10000}, options||{});
  var $t = jQuery(this);
  $t.on('input', function() {
    $t.css('width', Math.min(options.maxWidth,Math.max(options.minWidth,$t.textWidth() + options.padding)));
  }).trigger('input');
  return this;
}

var FlowFlowApp = ( function($) {
    
  var $ = jQuery;

  // streams model, view and collection declaring
  var StreamModel;
  var StreamModelsCollection;
  var StreamView;

  // rows model, view and collection declaring
  var StreamRowModel;
  var StreamRowModelsCollection;
  var StreamRowView;

  // instances declaring
  var Streams;
  var streamRowModels;
  var streamModels;

  // Feeds MVC
  var FeedsModel;
  var FeedsView;
  var feedsModel, feedsView;

  var templates = window.ff_templates;

  var sessionStorage = window.sessionStorage || {getItem: function(){return false}, setItem: function(){}};

  var transitionEnd = window.transitionEnd;

  var alphabet = 'abcdefghijklmnopqrstuvwxyz';

  var ua = navigator.userAgent.toLowerCase();
  var isWebkit = /safari|chrome/.test(ua);
  var isMobile = /android|blackBerry|iphone|ipad|ipod|opera mini|iemobile/i.test(ua);
  var isIE = /msie|trident.*rv\:11\./.test(ua);
  var isFF = /firefox/.test( ua );
	
  var vars = window [ window[ 'l' + alphabet[0] + '_plugin']['slug_' + 'down'] + '_' + 'v' + alphabet[0] + 'r' + 's' ];
  var plugin = window[ 'l' + alphabet[0] + '_plugin']['slug_' + 'down'];
  var la_plugin_slug_down = plugin;

  var FlowFlow = {
    savedView : sessionStorage.getItem( 'ff_stream' ) || 'list',
    $body: null,
    $streamsContainer : null,
    $sources : null,
    $list : null,
    $streamsList : null,
    $errorPopup : $('<div id="error-popup"></div>'),
    $html : null,
    $content : null,
    $tabList : null,
    $boostWidget : null,
    $cloudStatusEl : null,
    $tabs: null,
    $overlay : null,
    $previewStyles : null,
    $form: null,
    $popupBanner: null,
    editor: null,
    clip: null,
    activeTabIndex: parseInt( sessionStorage.getItem('as_active_tab') || 0 ),
    boostPlan: null,
    availableBoosts: null,

    renderBoostsUI: function ( model ) {

        var self = this;

        var data = {
            'action': la_plugin_slug_down + '_get_boosts',
            security: vars.nonce
        };
        
        this.$cloudStatusEl = $( '#ff-cloud-status' );

        // add dynamic boosts block

        if ( ! this.$boostWidget ) {

            this.$boostWidget = $( '<div id="ff-boost-widget" class="ff-boosts--not-available ff-hide"><a class="" href="#addons-tab" id="ff-boost-link"><i class="flaticon-rocket" style="display: inline-block;"></i> Boost</a><span class="ff-boost-info">In use: <span id="ff-boost-in-use">-</span>&nbsp;&nbsp;&nbsp;Expire on: <span id="ff-boost-exp">-</span></span><a href="#addons-tab" class="ff-pseudo-link">Get Boosts!</a></div>' );

            this.$sources.find( '#feeds-list-section' ).append( this.$boostWidget );

            this.$boostSmartElement = this.$boostWidget.find( '#ff-boost-link' );

            this.$boostSmartElement.on( 'mouseenter', function () {
	            if ( self.drake ) self.$sources.addClass( 'ff-droparea--active' );
            }).on( 'mouseleave', function () {
                if ( self.drake && ! self.drake.dragging ) self.$sources.removeClass( 'ff-droparea--active' );
            })

        }
        
        if ( !! window.boostsActivated !== true ) { // convert to boolean
            data[ 'not_active' ] = true;
        }
        
        var boostsRequest = $.post( vars.ajaxurl, data ).done( function( res ) {

            if ( res.error == 'not_allowed' ) {
                return;
            }

            var subscription;
            var nextPaymentDate;
            var months = [ 'Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec' ];
            var inUse;
            
            try {
	            subscription = JSON.parse( res );
            } catch ( e ) {
                alert( 'We are sorry but something went wrong with boost activation. Please contact via social-streams.com/contact to sort this out.')
                return;
            }

            self.subscription = subscription;

            if ( subscription.state === 'active' ) {

                self.$boostWidget.removeClass( 'ff-boosts--not-available' );

                inUse =  ( _.filter( model.get( 'feeds' ), function( feed ){ return feed.boosted == 'yep' }) ).length;
	            nextPaymentDate = new Date( subscription.next_payment.date );
                self.availableBoosts = subscription.boosts - inUse;

                if ( self.availableBoosts > 0 ) {
                    self.$boostSmartElement.html( self.availableBoosts + ' boost' + ( self.availableBoosts != 1 ? 's' : '' )).addClass( 'ff-item__draggable' );
                }

                self.$boostWidget.find( '#ff-boost-in-use' ).html( inUse );
                self.$boostWidget.find( '#ff-boost-exp' ).html( nextPaymentDate.getDate() + ' ' + months[ nextPaymentDate.getMonth() ] + ' ' + nextPaymentDate.getFullYear() );

                // custom plan
	            if ( subscription.plan === $( 'boosts_custom_plan' ).val() ) {
		            $( '.boosts_custom .boost_custom__plan_txt' ).html( 'Plan ID ' + subscription.subscription_id + ' activated.')
		            $( '.boosts_custom .boost_custom__plan_actions' ).html( '<a href="/wp-admin/admin-ajax.php?action=flow_flow_cancel_subscription" class="ff-pseudo-link">Cancel subscription</a>')
	            }
                
                // init drag n drop in any case after re-render

                if ( self.drake ) self.drake.destroy();

                self.drake = dragula( [ document.getElementById( 'ff-boost-widget' ) ], {
                    revertOnSpill: true,
                    isContainer: function (el) {
                        return el.classList.contains( 'feed-row' );
                    },
                    copy: true
                } );

                self.drake.on( 'drag', function ( item, _source ) {
                    console.log( 'drag start' )

                    if ( item.id === 'ff-boost-link' && self.availableBoosts > 0 )  {

                        var available = self.availableBoosts;

                        // switch to feeds tab

                        // no need
                        // if ( ! $('#sources-tab').is( '.active' ) ) $('#sources-tab').trigger( 'click' );

                        self.$sources.addClass( 'ff-droparea--active' );

                        item.innerHTML = '1 Boost';

                        setTimeout( function () {
                            item.innerHTML = ( available - 1 > 0 ? available - 1 : '<i class="flaticon-rocket" style="display: inline-block;"></i>' ) + ' boost' + ( available - 1 > 1 ? 's' : '' );
                            if ( available - 1 < 1 ) {
                                self.$boostSmartElement.removeClass( 'ff-item__draggable' );
                            } else {
                                self.$boostSmartElement.addClass( 'ff-item__draggable' );
                            }
                        }, 0)

                    } else if ( _source.classList.contains( 'feed-row' ) && item.classList.contains( 'td-info' ) && item.querySelector( '.ff-item__draggable' ) ) { // remove boost from feed case


                        self.$boostWidget.addClass( 'ff-droparea-widget--active' );


                    } else { // if target not boost icon then cancel
                        
                        self.drake.cancel()
                        
                    }
                })

                self.drake.on( 'over', function ( item, _lastDropTarget, _source ) {
                    console.log( 'drag over' );

                    if ( item.id === 'ff-boost-link' ) {
                        $( _lastDropTarget ).addClass( 'ff-droparea' );
                    } else {
                        $( _lastDropTarget ).addClass( 'ff-droparea-widget' );
                    }

                })

                self.drake.on( 'out', function ( item, _lastDropTarget, _source ) {
                    console.log( 'drag out' );
                    if ( item.id === 'ff-boost-link' ) {
                        $( _lastDropTarget ).removeClass( 'ff-droparea' );
                    } else {
                        $( _lastDropTarget ).removeClass( 'ff-droparea-widget' );
                    }
                })

                self.$sources.find( '.hilite-boost' ).off( 'mouseenter mouseleave' ).on( 'mouseenter', function () {
                    self.$boostWidget.addClass( 'ff-droparea-widget--active' );
                } ).on( 'mouseleave', function () {
                    if ( ! self.drake.dragging ) self.$boostWidget.removeClass( 'ff-droparea-widget--active' );
                })

                self.drake.on( 'drop', function ( item, _lastDropTarget, _source, _dropTargetChild ) {
                    console.log( 'drag drop' )
                    self.$sources.removeClass( 'ff-droparea--active' );
                    self.$boostWidget.removeClass( 'ff-droparea-widget--active' );
                    // _lastDropTarget null if not container
                    
                    var sync, found;

                    if ( ! _lastDropTarget || _lastDropTarget.classList.contains( 'feed-boosted' ) ) {
	                    self.drake.cancel();
	                    self.showNotification( 'Feed is already boosted.<i class="flaticon-error"></i>' )
	                    return;
                    }
	
	                if ( _lastDropTarget.getAttribute( 'data-network' ) === 'wordpress' ) {
		                self.drake.cancel();
                        self.showNotification( 'Boosting WP feeds is not currently possible.<i class="flaticon-error"></i>' )
		                return;
	                }

                    if ( _lastDropTarget.classList.contains( 'feed-row' ) && self.availableBoosts > 0 ) {
	
	                    var $t = $( _lastDropTarget );
	                    var uid = $t.data('uid');
	                    var feeds = model.get('feeds');
	                    var type = $t.data('network');
		
	                    var $view =  model.view.$popup.find('[data-uid="' + uid + '"]');
	                    if ( ! $view.length ) {
		                    $view = $( _.template(templates[ type + 'View'])({
			                    uid: uid
		                    }) );
		                    model.view.$el.find( '#feed-views' ).append( $view );
		                    // set values
		                    model.view.setInputsValue( uid );
	                    }
		                   
	                    var $channeling = $view.find('input[name="' + uid + '-boosted"]');
	                    var checked = $channeling.is(':checked');
	
	                    if ( checked ) return; // already boosted
	
	                    var streams = streamRowModels.models;
	                    var _feeds, found;
	                    sync = true;
	
	                    // check if feed in self-hosted stream
	
	                    for ( var i = 0, len = streams.length; i < len; i++ ) {
		                    _feeds = streams[ i ].get( 'feeds' );
		                    found = _.find( _feeds, function ( feed ) {
			                    return feed.id == uid
		                    })
		
		                    if ( found && streams[ i ].get( 'cloud' ) == 'nope' ) { // feed is connected to self-hosted stream
			
			                    self.drake.cancel();
		                     
			                    var alert = FlowFlow.popup( 'You\'re trying to boost feed that is connected to self-hosted stream #' + streams[ i ].get( 'id' ) + ( streams[ i ].get( 'name' ) ? ' "' + streams[ i ].get( 'name' ) + '"' : '' ) + '. It\'s not possible to have boosted feeds in self-hosted stream. Please disconnect feed from stream first and create cloud stream for it. Or go to settings of this stream and activate CLOUD switcher on SOURCE tab', 'neutral', 'alert' );
			                    sync = false;
			
			                    alert.then( function () {
				
			                    });
		                    }
	                    }
	
	                    if ( !sync ) return;
	
	                    feeds[ uid ]['boosted'] = 'yep';
	                    $channeling.prop('checked', true).trigger( 'change' );
	
	                    // ajax will update this
	                    // self.availableBoosts--;
	
	                    model.view.saveViaAjax();
                     

                    } if ( _source.classList.contains( 'feed-row' ) && item.classList.contains( 'td-info' ) && _lastDropTarget.id == 'ff-boost-widget' ) { // remove boost from feed case
		
			            var streams = streamRowModels.models, found;
			            var sync = true;
		                var uid = $( _source ).data('uid');
        
                        for ( var i = 0, len = streams.length; i < len; i++ ) {
                            feeds = streams[ i ].get( 'feeds' );
                            found = _.find( feeds, function ( feed ) {
                                return feed.id == uid
                            })
            
                            if ( found ) {
                                
                                // show first found
	                            var alert = FlowFlow.popup( 'You are about to remove boost from feed that is connected to cloud stream ID #' + streams[ i ].get( 'id' ) + ( streams[ i ].get( 'name' ) ? ' "' + streams[ i ].get( 'name' ) + '"' : '' ) + '. Cloud streams can\'t contain self-hosted feeds. Please disconnect feed from stream first.', 'neutral', 'alert' );
	                            sync = false;
	
	                            alert.then( function () {
                             
	                            });
                            }
                        }
                        
                        if ( sync ) applyChange();
				                    
                        function applyChange() {
                         
	                        // increment boosts in UI
	                        self.$boostSmartElement.html( self.availableBoosts + 1 + ' boost' + ( self.availableBoosts + 1 != 1 ? 's' : '' ) );
	
	                        // remove from feed
	                        var $t = $( _source );
	                        var uid = $t.data('uid');
	                        var type = $t.data('network');
	                        var feeds = model.get('feeds');
	
	
	                        var $view =  model.view.$popup.find('[data-uid="' + uid + '"]');
	                        if ( ! $view.length ) {
		                        $view = $( _.template(templates[ type + 'View'])({
			                        uid: uid
		                        }) );

		                        model.view.$el.find( '#feed-views' ).append( $view );
		                        // set values
		                        model.view.setInputsValue( uid );
	                        }
	
	                        var $channeling = $view.find('input[name="' + uid + '-boosted"]');
	                        var checked = $channeling.is(':checked');
	
	                        feeds[ uid ]['boosted'] = 'nope';
	                        $channeling.prop( 'checked', false ).trigger( 'change', { triggeredFromList: true } );
	                        // todo cancel change event
	
	                        model.view.saveViaAjax();
                        }

                    } else {
                        //self.drake.cancel()
                    }
                })

                self.drake.on( 'cancel', function ( item, _lastDropTarget, _source ) {
                    console.log( 'drag cancel' );
                    self.$sources.removeClass( 'ff-droparea--active' );
                    self.$boostWidget.removeClass( 'ff-droparea-widget--active' );

                    if ( item.id === 'ff-boost-link' && self.availableBoosts > 0 ) {
                        var initialHtml = item.innerHTML;
                        self.$boostSmartElement.html( initialHtml );
                    }

                })
	
	            // Helpscout support
	
	            if ( FlowFlow.subscription && FlowFlow.subscription.plan_id ) {
		
		            !function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});

		            window.Beacon('init', '914f3047-2aa4-4bcf-85bc-bcb58513f670');

		            window.Beacon('config', {
		            	"display" : {style : 'manual'},
			            "messaging": {
				            contactForm: {
					            showName: true,
				            },
			            },
			            "labels": {
				            responseTime: 'To fasten support please provide page URL and temporary admin access if possible',
				            messageConfirmationText: 'It usually takes 1-2 business days for us to process your request.',
				            noTimeToWaitAround: 'No time to wait around? We usually respond in timely manner'
			            }
		            })
		
		            //window.Beacon("navigate", "/previous-messages/");
		            
		            $( '#support-cont' ).html('<span id="support-btn" class="admin-button blue-button button-add">Create a ticket</span> <a id="support-btn-2" class="ff-pseudo-link">All tickets</a>')
		            
		            $( '#support-btn' ).on( 'click', function () {
		            	
			            window.Beacon( 'reset' );
			
			            window.Beacon( 'navigate', "/ask/message/" );
			
			            window.Beacon( 'identify', {
				            'user_id':           FlowFlow.subscription.user_id,
				            'email':             FlowFlow.subscription.user_email,
				            'Plugin':            'Flow-Flow Lite',
			            });
		
			            window.Beacon( 'session-data', {
				            'boosts':            FlowFlow.subscription.boosts,
				            'user_id':           FlowFlow.subscription.user_id,
				            'Email':             FlowFlow.subscription.user_email,
				            'Plugin':            'Flow-Flow Lite',
			            });
			
			            window.Beacon( 'open' );
		            })
		            
		            $( '#support-btn-2' ).on( 'click', function () {
		            	
			            window.Beacon( 'reset' );
			
			            window.Beacon("navigate", "/previous-messages/");
			
			            window.Beacon( 'identify', {
				            'user_id':           FlowFlow.subscription.user_id,
				            'email':             FlowFlow.subscription.user_email,
				            'Plugin':            'Flow-Flow Lite',
			            });
			
			            window.Beacon( 'session-data', {
			            	'boosts':            FlowFlow.subscription.boosts,
				            'user_id':           FlowFlow.subscription.user_id,
				            'Email':             FlowFlow.subscription.user_email,
				            'Plugin':            'Flow-Flow Lite',
			            });
			
			            window.Beacon( 'open' );
		            })
	            } else {
	            
	            }
	            
	            
            } else {
	            $( '#support-cont' ).html( 'To access premium support please <a class="ff-pseudo-link" href="#addons-tab">activate BOOSTS</a> or <a target="_blank" href="http://goo.gl/g7XQzu">upgrade to PRO</a> first' );
	            self.availableBoosts = 'not_active';
            }
            
	        self.$cloudStatusEl.html( 'Your server can connect to cloud OK <span class="cache-status-ok"></span>' );

            self.$boostWidget.removeClass( 'ff-hide' );

        }).error( function () {
         
	        self.$cloudStatusEl.html( 'It seems your server can\'t connect to cloud <span class="cache-status-error"></span>' );
	        
        })
        
        return boostsRequest;
        
    },

    renderBoostPricingTable: function ( plans, boosts ) {
        
        var self = this;
        var active = false;
        
        // sort plans by order prop
        
        plans = _.sortBy( plans, 'order' );
        
        // replacing placeholders
        var html = ''
        for ( var i = 0, len = plans.length; i < len; i++ ) {
            html += ff_templates.pricing_table_item;
	     }
	     
	    // adding custom plan card if it's not active plan and not found in plans
	    // todo detect better if not custom is active
    
	    if ( ! boosts.plan_id || plans.length < 5 ) {
		    html += ff_templates.pricing_table_item;
	    }
	    
	    $( '#boosts .pricing-table' ).html( html )
	
	    $( '.pricing-table__item' ).filter( '[data-plan]' ).each( function ( index, el ) {

            var $t = $( this );
            var plan, feature, id;
            var featuresHtml = '';
            
            plan = plans[ index ];
            
            if ( plan ) {
                
                id = plan.id;
    
                $t.attr( 'data-id', id );
                $t.attr( 'data-plan', plan.name.toLowerCase() );
                
	            $t.find( 'h2' ).html( plan.name )
                $t.find( '.pricing-table__item_price').html( plan.recurring_price.USD );
	            
	            // price with PRO
	
	            $t.find( '.pricing-table__item_price_per').after( $('<span class="pricing-table__item_price_pro">$' + ( plan.recurring_price.USD - ( plan.boosts == 5 ? 4 : 5 ) ).toFixed(2) + ' with PRO</span>') );
                
                // features
                
                for ( var i = 0, len = plan.options.length; i < len; i++ ) {
                    feature = plan.options[ i ];
                    featuresHtml += '<li>' + feature + '</li>';
                }
                
                $t.find( '.pricing-table__content ul' ).html( featuresHtml );
                
                
                if ( id == boosts.plan_id && boosts.state === 'active' ) { // active plan
                    $t.addClass( 'pricing-table__active' );
                    active = true;
                }
                
                $t.find( '.extension__cta--secured' ).each( function () {
    
                    var $a = $( this );
                    var hr = $a.attr( 'href');
    
                    // todo siteurl
                    $a.attr( 'href', hr + '?intent=' + id + ( $a.is( '.green-button') ? '' : '&cancel=1') + '&domain=' + encodeURIComponent( location.href + '&subscription=1') );
                 
                    if ( id == boosts.plan_id && boosts.state === 'active' ) { // active plan
                        // add cancel link
                        //$a.attr( 'href', boosts.cancel_url );
                    }
                    
                }).mousedown( function () {
                 
                    var $a = $( this );
                    var hr = $a.attr( 'href');
                    var coupon = $('#boosts_coupon').val();
                    // todo replace existing coupon
                    if ( coupon && hr.indexOf( 'coupon' ) == -1 ) {
                        $a.attr( 'href', hr + '&coupon=' + coupon)
                    }
                    
                })
	
            } else {
	            $t.attr( 'data-plan', 'custom' );
	            $t.find( 'h2' ).html( 'Need more?' )
	            $t.find( 'h3' ).html( 'From $50<span class="pricing-table__item_price_pro">&nbsp;</span>' );
	            $t.find( '.pricing-table__content ul' ).html( '<li>50+ boosts</li><li>Instagram proxy</li><li>Priority support</li>' );
	            $t.find( '.extension__cta--secured' ).html( 'Enter plan ID' );
	            $t.find( '.pricing-table__btn span' ).html( '<a target="_blank" href="mailto:hello@social-streams.com?subject=I%20Need%2050%2b%20Boosts">Request plan</a>' )
            }
	
	        $t.removeClass( 'pricing-table__placeholder' ).find( '.pricing-table__placeholder-content' ).remove();

        })
        
        if ( active ) {
	        self.$body.find( '[data-plan="standard"]' ).removeClass( 'pricing-table__best' )
        } else {
	        self.$body.find( '[data-plan="standard"]' ).addClass( 'pricing-table__best' )
        }
	
	    // show boosts FAQ
	    if ( $( '#addons-tab' ).is( '.active' ) && location.href.indexOf( 'subscription=1' ) != -1 ) {
		    setTimeout( function () {
			    $( '.boosts-link' ).trigger( 'click' );
		    }, 100)
	    }
    },

    makeOverlayTo: function (op, classN) {
      this.$html.removeClass('popup_visible');
      this.resetScrollbar();
      if ( op === 'show' ) {
        this.$overlay.addClass((classN ? classN + ' ' : '') + 'loading')
      } else {
        this.$overlay.removeClass();
      }
    },

    init: function () {

      var self = this;
      
	  this.$html = $('html');
      this.$body = $('body');
      this.$streamsContainer = $('#streams-cont');
      this.$sources = $('#sources-list');
      this.$list = this.$streamsContainer.find('#streams-list tbody');
      this.$streamsList = $('#streams-list-section');
      this.$form = $('#flow_flow_form');
      this.$overlay = $('#fade-overlay');
      this.$popupBanner = $('#ff-popup-banner');
      this.$content = $('.section-contents');
      this.$tabList = $('.section-tabs');
      this.$tabs = this.$tabList.find('li');

      // execute immediately
      this.$html.addClass('ff-browser-' + (isWebkit ? 'webkit' : isIE ? 'ie' : isFF ? 'ff' : '') + (window.WP_FF_admin ? ' ff-wp' : ' ff-standalone') + (window.isCompact ? ' ff-compact-admin' : '') + ' ff-' + vars.m );

      this.setupModelsAndViews();
      this.setupTabsAndContainer();
      this.attachGlobalEvents();
      
      FlowFlow.popup = this.initPopup();

      //this.initClipBoard();
      
    },

    createBackup: function (e) {

      var data = {
        'action': 'create_backup',
         security: vars.nonce
      };

      FlowFlow.makeOverlayTo('show');

      $.post( vars.ajaxurl, data).done(function( res ){
        if ( res.error == 'not_allowed' ) {
            var promise = FlowFlow.popup('Nay! You have no permissions to do this, please contact admin.', false, 'alert');
            FlowFlow.makeOverlayTo('hide');
            return;
        }
        location.reload();
      })

    },

    restoreBackup: function (e) {
      var promise = FlowFlow.popup('Are you sure?');
      var self = this;
      promise.then(function success(){
        var data = {
          action: 'restore_backup',
          id: $(self).closest('tr').attr('backup-id'),
          security: vars.nonce
        }
        FlowFlow.makeOverlayTo('show');

        $.post( vars.ajaxurl, data ).done(function( data ){
          if ( data.error == 'not_allowed' ) {
              var promise = FlowFlow.popup('Nay! You have no permissions to do this, please contact admin.', false, 'alert');
              FlowFlow.makeOverlayTo('hide');
              return;
          }
          sessionStorage.setItem('as_view_mode', 'list');
          sessionStorage.setItem('as_active_tab', 0);
          location.reload();
        })
      }, function fail () {})
    },

    deleteBackup: function () {
      var promise = FlowFlow.popup('Are you sure?');
      var self = this;

      promise.then(function success(){
        var data = {
          action: 'delete_backup',
          id: $(self).closest('tr').attr('backup-id'),
          security: vars.nonce
        }
        FlowFlow.makeOverlayTo('show');

        $.post( vars.ajaxurl, data ).done(function( res ){
          if ( res.error == 'not_allowed' ) {
              var promise = FlowFlow.popup('Nay! You have no permissions to do this, please contact admin.', false, 'alert');
              FlowFlow.makeOverlayTo('hide');
              return;
          }
          location.reload();
        })
      }, function fail () {})
    },

    initPopup: function () {
      // Alert popup

      var $popup = $('.cd-popup');
      //open popup
      FlowFlow.$form.on('click', '.cd-popup-trigger', function(event){
        event.preventDefault();
        $popup.addClass('is-visible');
        $(document).on('keyup', escClose);
      });

      $popup.find('#cd-button-yes').on('click', function(e){
        e.preventDefault();
        $popup.data('defer') && $popup.data('defer').resolve();
        $popup.removeClass('is-visible');

      })
      $popup.find('#cd-button-no, .cd-popup-close').on('click', function(e){
        e.preventDefault();
        $popup.data('defer') && $popup.data('defer').reject();
        $popup.removeClass('is-visible');

      })

      //close popup
      $popup.on('click', function(event){
        if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
          event.preventDefault();
          $(this).removeClass('is-visible');
          $(document).off('keyup', escClose);
        }
      });

      function escClose(event) {
        if(event.which=='27'){
          $popup.data('defer') && $popup.data('defer').reject();
          $popup.removeClass('is-visible');
        }
      }

      function popup ( text, neutral, type, buttons ) {
        var defer = $.Deferred();

        if ( !neutral ) $popup.removeClass( 'is-neutral' );

        if ( type !== 'alert' ) {
          $popup.removeClass( 'is-alert' );
          $popup.find('.cd-buttons li:last-child a').html( 'Yes' );
          
          if ( buttons ) {
	          $popup.find('.cd-buttons li:first-child a').html( buttons.left );
	          $popup.find('.cd-buttons li:last-child a').html( buttons.right );
          }
        } else {
          $popup.find('.cd-buttons li:last-child a').html('OK')
        }

        $popup.data( 'defer', defer );
        $popup.find( 'p' ).html( text || 'Are you sure?' );
        $popup.addClass( 'is-visible' + ( neutral ? ' is-neutral' : '') + ( type === 'alert' ? ' is-alert' : '' ) );

        $(document).on( 'keyup', escClose );
        return defer.promise();
      }
      //close popup when clicking the esc keyboard button
      $( document ).on( 'keyup', function( event ){
        if( event.which == '27' ){
          $popup.removeClass('is-visible');
        }
      });

      return popup;
    },

    setupModelsAndViews : function () {

      var self = this;
      var savedScrollState = sessionStorage.getItem('as_scroll');
      var $htmlAndBody = $('html, body');

      for (var i = 0, len = window.streams.length; i < len; i++) {
        streamRowModels.add(window.streams[i]);
      }

      $('#streams-list tbody tr').not('.empty-row').each(function(){
        var $t = $(this);
        var view = new StreamRowView({model: streamRowModels.get($t.attr('data-stream-id')), el: this});
      });

      if ( this.savedView !== 'list' && streamRowModels.get(this.savedView) ) {
        this.makeOverlayTo('show');
        streamRowModels.get(this.savedView).view.edit().then(function(id){

          if (savedScrollState) {
            $htmlAndBody.scrollTop(savedScrollState);
          }

          if ( ! self.$html.is('.boosts_popup_visible, .streams_popup_visible' ) ) self.makeOverlayTo('hide');

          setTimeout(function () {
            if (sessionStorage.getItem('s' + id + '-tab') && streamModels.get(id)) {
              streamModels.get(id).view.$el.find('.view-tabs [data-tab="' + sessionStorage.getItem('s' + id + '-tab') + '"]').trigger('click')
            }
          },0)

          setTimeout(function(){

            self.$streamsContainer.addClass('transition--enabled');

            if (savedScrollState) {
              $htmlAndBody.scrollTop(savedScrollState);
            }

          }, 800);
        });
      } else  {
        this.savedView = 'list';
        this.switchToView('list');
        this.makeOverlayTo('hide');
        if (savedScrollState) {
          $htmlAndBody.scrollTop(savedScrollState);
        }
        setTimeout(function(){
          self.$streamsContainer.addClass('transition--enabled');
          if (savedScrollState) {
            $htmlAndBody.scrollTop(savedScrollState);
          }
        }, 800);
      }

      // feeds init moved to async
    
      feedsModel = new FeedsModel();
      window.feedsModel = feedsModel;
      feedsView = new FeedsView({model: feedsModel, el: self.$form.find('#sources-list')[0]});
      
    },

    tabsCursor: (function () {
      var $cont;
      var $tabs;
      var $sections;
      var $cursor;
      var id;
      var moveCursor;

      function init ($el, id) {
        this[id] = {};
        var streamTabs = this[id];
        streamTabs.$el = $el;
        streamTabs.id = id;
        streamTabs.$tabs = streamTabs.$el.find('.view-tabs');
        streamTabs.$cursor = streamTabs.$tabs.find('.tab-cursor');
        streamTabs.$sections = streamTabs.$el.find('.section[data-tab]');
        moveCursor = moveCursor.bind(this);
        //console.log('activating tabs', this);
        setupActive.call(this, id);
        // attachEvents.call(this, $el);

        streamTabs.$tabs.find('li').on( 'click', function () {
          var val = $(this).data('tab');
          var $active = $(this);
          streamTabs.$tabs.find('.section-active-tab').removeClass('section-active-tab');
          $active.addClass('section-active-tab');
          streamTabs.$sections.removeClass('active-section').filter('[data-tab="' + val + '"]').addClass('active-section')
          FlowFlow.setHeight(streamTabs.id);
          moveCursor($active, streamTabs.id);
          sessionStorage.setItem('s' + streamTabs.id + '-tab', val); // todo grace-s
        })
      }

      function setupActive (id) {
        var $active = this[id].$tabs.find('li:not(".tab-cursor")').first();
        this[id].$tabs.find('li:not(".tab-cursor")').first().addClass('section-active-tab');
        this[id].$sections.first().addClass('active-section');
        FlowFlow.setHeight(id);
        setTimeout(function(){
          moveCursor($active, id);
        },0)
      }

      function moveCursor ($active, id) {
        var w = $active.outerWidth();
        var pos = $active.position();
        this[id].$cursor.css({'left' : pos.left + 'px', minWidth: w + 'px'})
      }

      return {
        initFor: init
      }
    })(),

    attachGlobalEvents : function () {

      var self = this;

      var $backupsForm = this.$form.find('#backup-settings');

      this.$streamsContainer.find( '.button-add' ).on( 'click', function() {
        
        var model, view;
	
        FlowFlow.checkScrollbar();
        FlowFlow.setScrollbar();
        FlowFlow.$html.addClass( 'streams_popup_visible popup_visible' );
	
	    self.$streamsContainer.find( '.streams-popup' ).on( 'click', function ( e ) {
	        
	        var $target = $( e.target );
	        var type;
	        
            // close
            
	        if ( ! $target.closest( '[data-stream-type]' ).length || $target.is('a') ) {
	         
		        FlowFlow.$html.removeClass( 'streams_popup_visible popup_visible' );
		        FlowFlow.resetScrollbar();
		
		        self.$streamsContainer.find( '.streams-popup' ).off( 'click' );
            }
            
            else {
		        //  create streams
                
                if ( $target.is( 'a' ) ) return;
		
		        type = $target.closest( '[data-stream-type]' ).data( 'streamType' );
	
	            if ( !self.$streamsContainer.find('#stream-view-new').length ) {
		
		            model = new StreamModel( { cloud: ( type === 'cloud' ? 'yep' : 'nope' ) } );
		            view = new StreamView({model: model});
		            streamModels.add(model);
		            view.$el.addClass('stream-view-new');
		            self.$streamsContainer.append(view.$el);
		
		            view.saveViaAjax().then(function ( stream ) {
			
			            if ( stream.error ) {
				            self.$streamsContainer.find('#stream-view-new').remove();
				            streamModels.remove( model );
				            self.switchToView('list');
			            } else {
				            setTimeout(function(){
					            self.switchToView( stream.id, type );
					
					            setTimeout( function () {
						            view.$el.find('.input-not-obvious input').focus()
					            }, 400)
				            },0)
			            }
		            });
	            }
            }
        });
   
      });
	
	
        this.$streamsContainer.find( '.tutorial-link' ).on( 'click', function() {
            
            FlowFlow.checkScrollbar();
            FlowFlow.setScrollbar();
            FlowFlow.$html.addClass( 'popup_visible tutorial_popup_visible' );
        
            self.$streamsContainer.find( '.tutorial-popup' ).on( 'click', function ( e ) {
                
                var $target = $( e.target );
            
                // close
            
                if ( ! $target.closest( '.popup-content-wrapper' ).length || $target.is( '.popupclose' ) || $target.is('a') ) {
                
                    FlowFlow.$html.removeClass( 'popup_visible tutorial_popup_visible' );
                    FlowFlow.resetScrollbar();
                
                    self.$streamsContainer.find( '.tutorial-popup' ).off( 'click' );
                } else {
                
                }
            } )
        
        } )
	
	    this.$form.find( '.boosts-link' ).on( 'click', function() {
		    FlowFlow.checkScrollbar();
		    FlowFlow.setScrollbar();
		    FlowFlow.$html.addClass( 'popup_visible boosts_popup_visible' );
		
		    self.$form.find( '.boosts-popup' ).on( 'click', function ( e ) {
			
			    var $target = $( e.target );
			
			    // close
			
			    if ( ! $target.closest( '.popup-content-wrapper' ).length || $target.is( '.popupclose' ) || $target.is('a') ) {
				
				    FlowFlow.$html.removeClass( 'popup_visible boosts_popup_visible' );
				    FlowFlow.resetScrollbar();
				
				    self.$streamsContainer.find( '.boosts-popup' ).off( 'click' );
			    } else {
				
			    }
		    } )
	    })

      this.$form.find('#streams-tab').on('click', function () {
        if (self.$form.is('.stream-view-visible') && self.activeTabIndex === 0) {
          self.switchToView('list');
        }
      });

      self.$tabs.on( 'click' , function() {
        var index = self.$tabs.index( this );
        var $t = $( this );

        if ($t.is('#suggestions-tab')) {
          /*
           window.open('http://goo.gl/forms/HAJ95k8kAI');
           */
          self.insertFeedbackForm();
        }

        self.$tabList.add( self.$content ).find( '.active' ).removeClass( 'active' );
        $t.add( self.$content.find( '.section-content:eq(' + index + ')' ) ).addClass( 'active' );

        if (index !== 0) {
          self.$form.removeClass('stream-view-visible');
        } else {
          if (self.$form.find('#streams-cont [data-view-mode="streams-update"].view-visible').length) {
            self.$form.addClass('stream-view-visible');
          }
        }

        self.activeTabIndex = index;
        sessionStorage.setItem('as_active_tab', index);

        return false;
      });

      $backupsForm.on('click', '.create_backup', this.createBackup);
      $backupsForm.on('click', '.restore_backup', this.restoreBackup);
      $backupsForm.on('click', '.delete_backup', this.deleteBackup);

      this.$form.on('click', '.admin-button.submit-button', function (e) {
        var $t = $(this);
        var $contentInput;
        var $cont;
        var $licenseCont;
        var invalid, promise;
        var opts = {
          doReload: false,
          doSubscribe: false
        }

        // validate activation form
        if ($t.is('#user-settings-sbmt')) {
          $licenseCont = $('#envato_license');

          if ($licenseCont.is('.plugin-activated')) {
            promise = self.popup('Are you sure?');
            promise.then(function success(){
              $licenseCont.find('input').val('');
              $licenseCont.find(':checkbox').prop('checked', false);
              opts.doReload = true;
              submitForm(opts);
            }, function(){
              // do nothing
            });
            return;
          } else {
            // validation
            if (!self.validateEmail($licenseCont.find('#company_email').val())) {
              $licenseCont.find('#company_email').addClass('validation-error');
              invalid = true;
            }

            if (!self.validateCode($licenseCont.find('#purchase_code').val())) {
              $licenseCont.find('#purchase_code').addClass('validation-error');
              invalid = true;
            }

            if (invalid) {
              return;
            } else {
              opts.attemptToActivate = true;
              opts.doReload = true;
            }
          }
        }

        if ($t.is('#user-settings-sbmt-2')) {
          $('#news_subscription').prop('checked', true);
          opts.doReload = true;
          opts.doSubscribe = true;
        }

        submitForm(opts);

        function submitForm(opts) {
          $t.addClass('button-in-progress');
          self.makeOverlayTo('show');
          $t.closest('form').trigger('submit', opts);
          sessionStorage.setItem('section-submit', $t.attr('id'));
        }
      });

      this.$form.on('click', 'a[href*="#"]', function (e) {
        if ( this.hash && this.href.indexOf( location.host ) != -1 ) {
	        self.$form.find(this.hash).trigger( 'click' );
	        return false
        }
      })

      this.$form.on('submit', function(e, opts){
        //			console.time('submit')
        e.preventDefault();

        var serialized, data;
        var $inputs = self.$form.find('.section-content').not('#streams-cont, #campaigns-cont, #sources-cont').find(':input');
        //Serialize form as array
        serialized = $inputs.serializeArray();
        //trim values
        for(var i =0, len = serialized.length;i<len;i++){
          serialized[i]['value'] = $.trim(serialized[i]['value']);
        }

        //turn it into a string if you wish
        serialized = $.param(serialized);

        $inputs.filter('input[type=checkbox]:not(:checked)').each(
            function () {
              if (name != 'mod-roles') {
                serialized += '&' + encodeURIComponent(this.name) + '=nope';
              }
            })

        data = {
          action: la_plugin_slug_down + '_ff_save_settings',
          settings: serialized,
          doSubcribe: opts.doSubscribe,
          security: vars.nonce
        };

        $.post( vars.ajaxurl, data, function( response ) {
          console.log('Got this from the server: ' , response )
          var $fb_token, $submitted;
          if ( response == -1 || response.error ) {
              var promise = FlowFlow.popup('Nay! Something went wrong, if it repeats please contact support.', false, 'alert');
              FlowFlow.makeOverlayTo('hide');
              return;
          }
          else{
            // Do something on success
            console.log(response.settings)
            if (typeof response === 'string' && response.indexOf('curl')) {
              var promise = FlowFlow.popup('Please set DISABLE CURL_FOLLOW_LOCATION setting to YES under General tab', false, 'alert');
              FlowFlow.makeOverlayTo('hide');
              return;
            }

            if (opts.attemptToActivate && response.activated !== true) {
              alert(response.activated);
              self.makeOverlayTo('hide');
              return;
            }

            $fb_token = $('input[name="flow_flow_fb_auth_options[facebook_access_token]"]').parent();
            if (response.fb_extended_token == false){
              $fb_token.find('.desc').remove();
              $fb_token.find('textarea').remove();
              $fb_token.append('<p class="desc fb-token-notice" style="margin: 10px 0 5px; color: red !important">! Extended token is not generated, Facebook feeds might not work</p>');
              $fb_token.removeClass('fb-empty');
            }
            else if (response.settings.flow_flow_fb_auth_options.facebook_access_token == response.fb_extended_token){

            }
            else {
              if (response.settings && response.settings.flow_flow_fb_auth_options && response.settings.flow_flow_fb_auth_options.facebook_access_token == '') {
                $fb_token.addClass('fb-empty');
              } else {
                if (response.fb_extended_token && !$fb_token.find('textarea').length) {
                  $fb_token.find('.desc').remove();
                  $fb_token.append('<p class="desc" style="margin: 10px 0 5px">Generated long-life token, it should be different from that you entered above then FB auth is OK</p><textarea disabled rows=3>'  + response.fb_extended_token + '</textarea>');
                }
                $fb_token.removeClass('fb-empty');
              }
            }

            if (!opts.doReload) self.makeOverlayTo('hide');

            $submitted = $('#' + sessionStorage.getItem('section-submit'));
            $submitted.addClass('updated-button').html('<i class="flaticon-check_mark" data-action="edit"></i>&nbsp;&nbsp;Updated');
            $submitted.removeClass('button-in-progress');

            setTimeout( function () {
              $submitted.html('Save changes').removeClass('updated-button');
            }, 2500);
          }

          if (opts.doReload) location.reload();

        }, 'json' ).fail( function( d ){
          console.log( d.responseText );
          console.log( d );
          alert('Error occurred. ' + d.responseText);
          self.makeOverlayTo('hide');
        });

        return false
      });

      this.$form.on('keydown', 'input', function (e){
        var $t = $(this);
        if ($t.is('.validation-error')) {
          $t.removeClass('validation-error');
        }
        if (e.which == 13) {
          //console.log('enter')
        }
      });

      this.$form.find('#facebook_use_own_app').on( 'change', function(){
        var $t = $(this);
        var $p = $t.closest('dl');
        var checked = this.checked;

        $p.find('dd, dt').not('.ff-toggler').find('input')[ checked ? 'removeClass' : 'addClass' ]('disabled')
        $p[ checked ? 'addClass' : 'removeClass' ]('ff-own-app');
        $('#facebook-auth')[this.checked ? 'hide' : 'show']();

      }).trigger( 'change' );

      this.$form.find('.extension__cta--disabled').on( 'click', function(e){
        e.preventDefault();
      });

      $( window ).on( 'onbeforeunload', function (e) {
        sessionStorage.setItem('as_scroll', $('body').scrollTop() || $('html').scrollTop());
      });

      this.$errorPopup.on('mouseleave', function(e){
        self.$errorPopup.removeClass('visible')
      })
	
	  this.$form.find('#boosts .extension__cta.grey-button').on( 'click', function ( e ) {
	  	
		  e.preventDefault();
		  var hr = this.href;
		  
		  var promise = FlowFlow.popup( 'You are about to cancel subscription. All active boosted feeds and cloud streams will become self-hosted. Are you sure?');
		  
		  promise.then(function yes(){
		        window.location.replace( hr )
		  }, function no ( reason ) {
		  
		  })
	  });
	
	  this.$popupBanner.on( 'click', function () {
		  self.$popupBanner.removeClass( 'banner-visible' );
      } )

      this.$form.on( 'click', '.show-debug', function ( e ) {
          e.preventDefault();
          $('#debug-info').toggle();
      })
      
      // show popup tutorial
	  if ( window.FF_Cookie ) {
	      
        if ( window.FF_Cookie.get( 'ff_first_time' ) ) {
            $( '.tutorial-first-time' ).hide();
        } else {
	        $( '.tutorial-link' ).trigger( 'click' );
	        window.FF_Cookie.set( 'ff_first_time', '1', { expires: 356 });
	        // add notice to refresh token
	        $('#ff-auth-tab').addClass('errors-present').find( 'i' ).replaceWith( '<i class="flaticon-error"></i>' );
	        $('#auth-settings .ff-notice').show();
        }
      
      }
      
      if ( location.hash == '#extra' ) {
	      $( '#addons-tab' ).trigger( 'click' );
      }
      
      // coupon
      $('.coupon-apply').on( 'click', function () {
          $( '#general-settings-sbmt' ).trigger( 'click' );
      })
      $('.coupon-clear').on( 'click', function () {
          $( '#boosts_coupon' ).val( '' );
          $( '#general-settings-sbmt' ).trigger( 'click' );
      })
	
     
    $( '#boosts').on( 'click', '[data-plan="custom"] .extension__cta--secured', function ( e ) {
    
        e.preventDefault();
        
        var prompt = self.popup( '<label>Enter plan ID</label>&nbsp;&nbsp;&nbsp;<input id="boosts_custom_plan_id" name="boosts_custom_plan_id" type="text">', 'neutral', false, { right: 'Go To Payment', left: 'cancel'} )
	    
        prompt.then( function yes ( value ) {
            
               var id = $( '#boosts_custom_plan_id' ).val();
               var coupon = $('#boosts_coupon').val();
               
               window.location.replace( 'https://social-streams.com/boosts/?intent=' + id + '&domain=' + encodeURIComponent( location.href + '&subscription=1')  +  ( coupon ? '&coupon=' + coupon : '' ) );
            
         }, function cancel ( reason ) {
    
        })
    })

      this.initFacebookAuth();
      this.initFoursquareAuth();
      this.initInstagramAuth();
    },
	  
	showNotification: function ( html ) {
		
		var timeout = this.$popupBanner.data( 'timeout' );
  
		this.$popupBanner.find( 'div' ).html( html ).end().addClass( 'banner-visible' );
		
		if ( timeout ) { // current
			clearTimeout( timeout );
		}
		
		// new
		timeout = setTimeout( function () {
			FlowFlow.$popupBanner.removeClass( 'banner-visible' );
		}, 8000 );
		
		this.$popupBanner.data( 'timeout', timeout );
	},

    backUrl: vars.ajaxurl + '?action=flow_flow_social_auth',

    initFacebookAuth: function () {
      //https://www.facebook.com/dialog/oauth

      var f = "https://flow.looks-awesome.com/service/auth/facebook-instagram.php?" + $.param({
            back: this.backUrl
          });
      $("#facebook-auth").on( 'click', function(){
        var $t = $(this);
        if ($(this).html() === 'Log Out') {
          $('#facebook_access_token').val('');
          $('#fb-auth-settings-sbmt').trigger( 'click' );
          $("#facebook-auth").html('Connect');
          return
        }
        document.location.href = f;
      });
	
	    $("#fb-refresh-token").on( 'click', function(){
		    document.location.href = f;
	    });

      if ($('#facebook_access_token').val() !== '') {
        $("#facebook-auth").html('Log Out')
      }
    },

    initFoursquareAuth: function () {

      var j = "https://foursquare.com/oauth2/authenticate?" + $.param({
            client_id: "22XC2KJFR2SFU4BNF4PP1HMTV3JUBSEEVTQZCSCXXIERVKA3",
            redirect_uri: "http://flow.looks-awesome.com/service/auth/foursquare.php?back=" + this.backUrl,
            response_type: "code"
          });

      $("#foursquare-auth").on( 'click', function(){
        var $t = $(this);
        if ($(this).html() === 'Log Out') {
          $('#foursquare_access_token').val('');
          $('#fq-auth-settings-sbmt').trigger( 'click' );
          $("#foursquare-auth").html('Authorize');
          return
        }
        document.location.href = j;
      });

      if ($('#foursquare_access_token').val() !== '') {
        $("#foursquare-auth").html('Log Out')
      }

      if ($('#foursquare_client_id').val() === '') {
        var $par = $('#foursquare_client_id').parent();
        $par.add($par.prev('dt').first()).hide();
      }
      if ($('#foursquare_client_secret').val() === '') {
        var $par = $('#foursquare_client_secret').parent();
        $par.add($par.prev('dt').first()).hide();
      }
    },

    initInstagramAuth: function () {

      //http://stackoverflow.com/questions/7131909/facebook-callback-appends-to-return-url/7297873#7297873
      if (window.location.hash && window.location.hash == '#_=_') {
        window.location.hash = '';
      }

      var h = "https://api.instagram.com/oauth/authorize/?" + $.param({
            client_id: "94072d7b728f47b68bc7fc86767b3ebe",
            redirect_uri: "http://social-streams.com/services/auth/instagram.php?back=" + this.backUrl,
            response_type: "code",
            scope: "basic public_content"
          });

      $("#inst-auth").on( 'click', function(){
        var $t = $(this);
        if ($(this).html() === 'Log Out') {
          $('#instagram_access_token').val('');
          $('#inst-auth-settings-sbmt').trigger( 'click' );
          $("#inst-auth").html('Authorize');
          return
        }
        document.location.href = h;
      });

      if ($('#instagram_access_token').val() !== '') {
        $("#inst-auth").html('Log Out');
      }
    },

    checkScrollbar : function () {
      this.bodyIsOverflowing = document.body.scrollHeight > document.body.clientHeight
      this.scrollbarWidth = this.measureScrollbar();
    },

    setScrollbar : function () {
      var bodyPad = parseInt((this.$body.css('padding-right') || 0), 10)
      if (this.bodyIsOverflowing) {
          this.$body.css('padding-right', bodyPad + this.scrollbarWidth);
          this.$popupBanner.css('margin-right', bodyPad + this.scrollbarWidth);
      }
    },

    resetScrollbar : function () {
      this.$body.css('padding-right', '');
	  this.$popupBanner.css('margin-right', '');
    },

    measureScrollbar : function () { // thx walsh
      var scrollDiv = document.createElement('div')
      scrollDiv.className = 'ff-modal-scrollbar-measure'
      this.$body.append(scrollDiv)
      var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
      this.$body[0].removeChild(scrollDiv);
      return scrollbarWidth;
    },

    switchToView: function (view, cloud) {

      var self = this;
      this.$streamsContainer.find('.view-visible').removeClass('view-visible');
      this.setHeight(view);
      // setTimeout(function(){
        if (view === 'list') {
          self.$streamsContainer.find('#streams-' + view).addClass('view-visible');
          self.$form.removeClass('stream-view-visible');
        } else {
          self.$streamsContainer.find('#stream-view-' + view).addClass('view-visible' + ( cloud == 'yep' ? '' : ' ff-l' ) );
          
          if ( cloud !== 'yep' ) {
	          self.$streamsContainer.find('#stream-view-' + view).find( '.ff-feature [type="checkbox"]').prop( 'checked', false );
          }
          
          self.$form.addClass('stream-view-visible');
        }
      // },0)

        console.log('switch to view', view)

      sessionStorage.setItem('ff_stream', view);
    },

    setHeight : function (id) {
      var self = this;

      var heights = [];
      var maxH;
      //
      if (id && !isNaN(parseInt(id))) {
        self.$streamsContainer.find('#stream-view-' + id + ', #streams-list').each(function(){
          heights.push($(this).outerHeight());
        });
      } else {
        self.$streamsContainer.find('.section-stream[data-view-mode="streams-update"], #streams-list').each(function(){
          heights.push($(this).outerHeight());
        });
      }

      maxH = Math.max.apply(Math, heights);
      self.$streamsContainer.css('minHeight', maxH);
    },

    setupTabsAndContainer: function () {
      var self = this;
      var $activeTab;

      $activeTab = $('.section-tabs li:eq(' + this.activeTabIndex +')');

      $activeTab.add('.section-content:eq(' + this.activeTabIndex + ')').addClass('active');
      if ($activeTab.is('#suggestions-tab')) this.insertFeedbackForm();

      // moderation

      setTimeout(function () {
        if (!$('[name="mod-roles"]:checked').length) {
          $('#mod-role-administrator').prop('checked', true);
        }
      },0)


      if ( this.activeTabIndex !== 0 ) {
        this.makeOverlayTo('hide');
      }

      $('body').append(this.$errorPopup)
               .append('<div class="content-popup"><div class="content-popup__container"><div class="content-popup__content"></div><div class="content-popup__close"></div></div></div>');
      
        // add pricing table html
        // assume it's 5
        $( '#boosts .pricing-table' ).html( ff_templates.pricing_table_item + ff_templates.pricing_table_item + ff_templates.pricing_table_item + ff_templates.pricing_table_item + ff_templates.pricing_table_item )
      
      this.$html.addClass('page-loaded');
      $('.wrapper').css('opacity', 1);
    },

    insertFeedbackForm: function insertFeedbackForm() {
      if (!insertFeedbackForm.inserted) {

        $('#feedback').append('<iframe src="https://docs.google.com/forms/d/1yB8YrR4FTU8UeQ9oEWN11hX8Xh-5YCO5xv6trFPVUlg/viewform?embedded=true" width="760" height="500" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>');

        insertFeedbackForm.inserted = true;
      }
    },

    randomString: function (length, chars) {
      var result = '';
      for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
      return result;
    },

    getRandomId: function () {
      return this.randomString(1, alphabet) + this.randomString(1, alphabet) + new Date().getTime().toString().substr(8);
    },

    addCSSRule: function (sheet, selector, rules) {
      //Backward searching of the selector matching cssRules
      if (sheet && sheet.cssRules) {
        var index=sheet.cssRules.length-1;
        for(var i=index; i>0; i--){
          var current_style = sheet.cssRules[i];
          if(current_style.selectorText === selector){
            //Append the new rules to the current content of the cssRule;
            rules=current_style.style.cssText + rules;
            sheet.deleteRule(i);
            index=i;
          }
        }
        if(sheet.insertRule){
          sheet.insertRule(selector + "{" + rules + "}", index);
        }
        else{
          sheet.addRule(selector, rules, index);
        }
        return sheet.cssRules[index].cssText;
      }
    },

    validateEmail: function (val) {
      return /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,20}$/.test(val);
    },

    validateCode: function (val) {
      return /^[a-z0-9]+\-[a-z0-9]+\-[a-z0-9]+\-[a-z0-9]+\-[a-z0-9]+$/.test(val);
    }
  }

  StreamModel = Backbone.Model.extend({
    defaults: function () {
      return {
        "name":                  "",
        "cloud":                 "nope",
        "mod":                   "nope",
        "order":                 "compareByTime",
        "posts":                 "30",
        "days":                  "",
        "page-posts":            "15",
        "cache":                 "yep",
        "cache_lifetime":        "10",
        "gallery":               "nope",
        "gallery-type":          "classic",
        "private":               "nope",
        "hide-on-desktop":       "nope",
        "hide-on-mobile":        "nope",
        "max-res":               "nope",
        "show-only-media-posts": "nope",
        "titles":                "nope",
        "hidemeta":              "nope",
        "hidetext":              "nope",
        "heading":               "",
        "headingcolor":          "rgb(59, 61, 64)",
        "subheading":            "",
        "subheadingcolor":       "rgb(114, 112, 114)",
        "hhalign":               "center",
        "bgcolor":               "rgb(240, 240, 240)",
        "filter":                "nope",
        "filtercolor":           "rgb(205, 205, 205)",
        "mobileslider":          "nope",
        "viewportin":            "yep",
        "width":                 "260",
        "margin":                "20",
        "layout":                "masonry",
        "theme":                 "classic",
        "gc-style":              "style-1",
        "upic-pos":              "timestamp",
        "upic-style":            "round",
        "bradius":               "15",
        "icon-style":            "label1",
        "cardcolor":             "rgb(255, 255, 255)",
        "namecolor":             "rgb(59, 61, 64)",
        "textcolor":             "rgb(131, 141, 143)",
        "linkscolor":            "rgb(94, 159, 202)",
        "restcolor":             "rgb(131, 141, 143)",
        "shadow":                "rgba(0, 0, 0, 0.05)",
        "bcolor":                "rgba(240, 237, 231, 0.4)",
        "talign":                "left",
        "icons-style":           "outline",
        "cards-num":             "3",
        "scrolltop":             "yep",
        "c-desktop":             "5",
        "c-laptop":              "4",
        "c-tablet-l":            "3",
        "c-tablet-p":            "2",
        "c-smart-l":             "2",
        "c-smart-p":             "1",
        "s-desktop":             "15",
        "s-laptop":              "15",
        "s-tablet-l":            "10",
        "s-tablet-p":            "10",
        "s-smart-l":             "15",
        "s-smart-p":             "15",
        "m-c-desktop":           "5",
        "m-c-laptop":            "4",
        "m-c-tablet-l":          "3",
        "m-c-tablet-p":          "2",
        "m-c-smart-l":           "2",
        "m-c-smart-p":           "1",
        "m-s-desktop":           "15",
        "m-s-laptop":            "15",
        "m-s-tablet-l":          "10",
        "m-s-tablet-p":          "10",
        "m-s-smart-l":           "15",
        "m-s-smart-p":           "15",
        "j-h-desktop":           "260",
        "j-h-laptop":            "240",
        "j-h-tablet-l":          "220",
        "j-h-tablet-p":          "200",
        "j-h-smart-l":           "180",
        "j-h-smart-p":           "160",
        "j-s-desktop":           "0",
        "j-s-laptop":            "0",
        "j-s-tablet-l":          "0",
        "j-s-tablet-p":          "0",
        "j-s-smart-l":           "0",
        "j-s-smart-p":           "0",
        "c-r-desktop":           "2",
        "c-r-laptop":            "2",
        "c-r-tablet-l":          "2",
        "c-r-tablet-p":          "2",
        "c-r-smart-l":           "1",
        "c-r-smart-p":           "1",
        "c-c-desktop":           "5",
        "c-c-laptop":            "4",
        "c-c-tablet-l":          "3",
        "c-c-tablet-p":          "3",
        "c-c-smart-l":           "3",
        "c-c-smart-p":           "3",
        "c-s-desktop":           "0",
        "c-s-laptop":            "0",
        "c-s-tablet-l":          "0",
        "c-s-tablet-p":          "0",
        "c-s-smart-l":           "0",
        "c-s-smart-p":           "0",
        "c-autoplay":            "",
        "c-arrows-always":       "yep",
        "c-arrows-mob":          "nope",
        "c-dots":                "yep",
        "c-dots-mob":            "nope",
        "wallwidth":             "",
        "wallvm":                "20",
        "wallhm":                "0",
        "wallcomments":          "yep",
        "g-ratio-w":             "1",
        "g-ratio-h":             "2",
        "g-ratio-img":           "1/2",
        "g-overlay":             "nope",
        "m-overlay":             "nope",
        "css":                   "",
        "feeds":                 [],
        "template":              ['image', 'header', 'text', 'meta'],
        "tv":                    "nope",
        "tv-int":                "5",
        "tv-logo":               "",
        "tv-bg":                 "",
        "big":                   "nope"
      }
    },
    initialize: function() {
      console.log('initialize Stream Model', this);
      // this.set('feeds', []);
    },
    save: function(isNew){
      var self = this;
      var feedsData;
      var $params = {
        emulateJSON: true,
        data: {
          action: isNew ? la_plugin_slug_down + '_create_stream' : la_plugin_slug_down + '_save_stream_settings',
          stream: this.toJSON(),
          security: vars.nonce
        },
      };
      // legacy feeds to JSON
      if (typeof $params.data.stream.feeds !== 'string') {
        $params.data.stream.feeds = JSON.stringify($params.data.stream.feeds);
      }

      if ($params.data.stream.errors) delete $params.data.stream.errors;

      return Backbone.sync( 'create', this, $params ).done( function( serverModel ){
        if ( serverModel && serverModel.error ) {
          var promise = FlowFlow.popup( serverModel.error == 'not_allowed' ? 'Nay! You have no permissions to do this, please contact admin.' : 'Nay! Something went wrong, please contact support', false, 'alert');
          FlowFlow.makeOverlayTo('hide');
          return;
        }
        if ( serverModel && serverModel['id'] ) {
          self.set( 'id', serverModel['id'] )
        }
        /*for (var prop in serverModel) {
          if (prop === 'feeds' && typeof serverModel[prop] !== 'object') serverModel[prop] = JSON.parse(serverModel[prop])
          self.set(prop, serverModel[prop])
        }*/
      }); // always 'create' because we can't use CRUD request names, only POST
    },
    fetch: function(){
      var $params = {
        emulateJSON: true,
        data: {
          'action': la_plugin_slug_down + '_get_stream_settings',
          'stream-id': this.get('id'),
          'security': vars.nonce
        }
      };
      return Backbone.sync( 'read', this, $params ).done(function ( res ) {
         if ( res.error ) {
             var promise = FlowFlow.popup( res.error == 'not_allowed' ? 'Nay! You have no permissions to do this, please contact admin.' : 'Nay! Something went wrong, please contact support', false, 'alert');
             setTimeout(function(){FlowFlow.switchToView('list')}, 1000);
             return;
         }
      })
    },
    destroy: function() {
      var self = this;
      var $params = {
        emulateJSON: true,
        type: 'POST',
        data: {
          'action': la_plugin_slug_down + '_delete_stream',
          'stream-id': this.get('id'),
          'security': vars.nonce
        }
      };
      return Backbone.sync( 'delete', this, $params ).done(function( stream ){
        if ( stream && stream.error ) {
          var promise = FlowFlow.popup( stream.error == 'not_allowed' ? 'Nay! You have no permissions to do this, please contact admin.' : 'Nay! Something went wrong, please contact support', false, 'alert');
          FlowFlow.makeOverlayTo('hide');
          return;
        }
        self.collection.remove( self );
      })
    },
    urlRoot: vars.ajaxurl,
    url: function () {
      return this.urlRoot;
    }
  });

  StreamRowModel = Backbone.Model.extend({
    defaults: function () {
      return {
        'name' : '',
        'status' : 'ok',
        'cloud' : 'nope',
        'layout' : 'masonry',
        'feeds' : []
      }
    },
    initialize: function() {
            console.log('initialize Stream Row Model', this);
    },
    destroy: function() {
      var self = this;
      var $params = {
        emulateJSON: true,
        type: 'POST',
        data: {
          'action': la_plugin_slug_down + '_delete_stream',
          'stream-id': this.get('id'),
          'security': vars.nonce
        }
      };
      return Backbone.sync( 'delete', this, $params ).done(function( stream ){
        if ( stream && stream.error ) {
            var promise = FlowFlow.popup( stream.error == 'not_allowed' ? 'Nay! You have no permissions to do this, please contact admin.' : 'Nay! Something went wrong, please contact support', false, 'alert');
            FlowFlow.makeOverlayTo('hide');
            return;
        }
        self.collection.remove( self );
      })
    },
    clone: function() {
      var self = this;
      var $params = {
        emulateJSON: true,
        type: 'POST',
        data: {
          'action': la_plugin_slug_down + '_clone_stream',
          'stream': this.toJSON(),
          'security': vars.nonce
        }
      };
      return Backbone.sync( 'create', this, $params ).done( function( stream ){
        if ( stream && stream.error ) {
            var promise = FlowFlow.popup( stream.error == 'not_allowed' ? 'Nay! You have no permissions to do this, please contact admin.' : 'Nay! Something went wrong, please contact support', false, 'alert');
            FlowFlow.makeOverlayTo('hide');
            return;
        }
        streamRowModels.add( stream );
      })
    },
    urlRoot: vars.ajaxurl,
    url: function () {
      return this.urlRoot;
    }
  });

  StreamModelsCollection = Backbone.Collection.extend({
    model: StreamModel
  });
  StreamRowModelsCollection = Backbone.Collection.extend({
    model: StreamRowModel
  });
  streamModels = new StreamModelsCollection();
  streamRowModels = new StreamRowModelsCollection();

  StreamRowView = Backbone.View.extend({
    model: StreamRowModel,
    tagName: "tr",
    template:  _.template( templates.streamRow ),
    className: "stream-row",
    ajaxPages: null,
    events: {
      "click .flaticon-tool_edit, .td-name": "edit",
      "click .flaticon-tool_delete": "destroy",
      "click .flaticon-tool_clone": "clone",
      "mouseenter .hint-block": "getShortcodePages",
      "mouseleave .hint-block": "cancelGetShortcodePages",
      "click span.shortcode": "selectShortcode"
    },

    initialize: function() {

      this.model.on('change', function(){
        console.log('render row model on change', arguments)
        this.render()
      }, this);

      this.model.view = this; // we can work with models collection now

      this.hideFeeds();
    },

    rendered: false,

    render: function( changed ) {
      var changed, status;

      var feeds = this.model.get('feeds');
      var boosted = 0;
      var type; // default;
      
      var cloud = this.model.get('cloud');
      
      if ( cloud == 'yep' ) {
	      type = '<span class="stream-cloud-info"><span class="highlight hilite-boost"><i class="flaticon-cloud"></i></span> <span class="highlight">Cloud</span></span>';
	  } else {
	      type = '<span class="highlight">Self-Hosted</span>'; // default;
      }
      
      if (!this.rendered) {
        console.log('render row view', this.model);
	
	    status = this.model.get('status');

        this.$el.html(this.template({
          id: this.model.get('id') || 'new',
          name: stripslashes(this.model.get('name')) || 'Unnamed',
          status: parseInt( status ) || status === 'ok' ? 'ok' : 'error',
          type: type,
          feeds: this.getFeedsStr( feeds )
        }));
        this.$el.attr('data-stream-id', this.model.get('id') || 'new');
        this.rendered = true;
      } else if (this.model.changed && !_.isEmpty(this.model.changed)) {
        console.log('changing row view', this.model);
        changed = this.model.changed;

        if (changed.hasOwnProperty('id')) {
          this.$el.find('.shortcode').html('[ff id="' + changed.id + '"]')
        }
        if (changed.hasOwnProperty('feeds')) {
          this.$el.find('.td-feed').html(this.getFeedsStr( feeds ));
        }
        if (changed.hasOwnProperty('layout')) {
          this.$el.find('.td-type').html( type );
        }
        if (changed.hasOwnProperty('status')) {
          this.$el.find('[class*=cache-status]').removeClass().addClass('cache-status-' + changed.status);
        }
        if (changed.hasOwnProperty('name')) {
          this.$el.find('.td-name').html(changed.name || 'Unnamed');
        }
        if (changed.hasOwnProperty('cloud')) {
          this.$el.find('.td-type').html( type );
        }
      }

      this.hideFeeds();
    },

    hideFeeds: function(){
      var _this = this;
      setTimeout(function () {
        var $cell = _this.$('.td-feed')
        var cellWidth = $cell.get(0).offsetWidth - 100 // reserve space for "+ N more" badge
        var $feeds = _this.$('i', $cell)
        var feedsWidth = 0
        var hiddenCount = 0

        if($feeds.length === 0) return

        $.each($feeds, function(i, feed){
          feedsWidth += 26
          $(feed).show()

          if(cellWidth < feedsWidth){
            $(feed).hide()
            hiddenCount++
          }
        })

        $cell.find('.link-more').remove();
        if(cellWidth < feedsWidth){
          $cell.append('<span class="link-more" data-action="edit">+ ' + hiddenCount + ' more')
        }
      }, 4)
    },

    getFeedsStr: function (feeds) {
      var result = '';

      if (typeof feeds === 'string') {
        feeds = JSON.parse(feeds);
      }

      if (!feeds || !feeds.length) return '<span class="highlight-grey">No Feeds</span>';

      for (var i = 0, len = feeds.length; i < len; i++) {
          result += '<i class="flaticon-' + feeds[i]['type'] + '"></i>'
      }

      return result || '<span class="highlight-grey">No Feeds</span>';
    },

    edit: function(e, viewStays) {
      console.log('row edit', this);
      var defer = $.Deferred();

      var self = this, model, request;

      var id = this.model.get('id');

      if (!id) {
        alert('Something went wrong, please try to reload page')
      }

      if (!FlowFlow.$streamsContainer.find('#stream-view-' + id).length) {

        this.$el.addClass('stream-loading');

        model = new StreamModel({id: id});

        request = model.fetch();
        request.done(
            function (response, status, xhr) {
              var view, attribute, value;
              if (response.feeds && typeof response.feeds === 'string') {
                response.feeds = JSON.parse(response.feeds);
              }

              for (attribute in response) {
                value = response[attribute];
                model.set(attribute, typeof value === 'string' ? stripslashes(value) : value)
              }

              console.log('new StreamView')
              view = new StreamView({model: model});
              streamModels.add(model);

              FlowFlow.$streamsContainer.append(view.$el);

              self.$el.removeClass('stream-loading');

              defer.resolve(id);

              setTimeout(function () {
                if (!viewStays) FlowFlow.switchToView( id, model.get('cloud') );
              },100)

            }
        ).fail (function () {
          alert('Something went wrong, please try to reload page')
          self.$el.removeClass('stream-loading');
          defer.reject();
        })

      } else {
        if (!viewStays) FlowFlow.switchToView( id, this.model.get('cloud') );
        defer.resolve(id);
      }

      return defer.promise()
    },
    destroy: function() {
      var promise = FlowFlow.popup('Just checking for misclick. Delete stream?');
      var self = this;

      promise.then(function(){
        var id = self.model.get('id');
        var request = self.model.destroy();
        FlowFlow.makeOverlayTo('show');

        request.done(function( stream ){
          if ( stream && stream.error ) return;
          self.remove();
          if (streamRowModels.length === 0) {
            FlowFlow.$list.append(templates.streamRowEmpty);
          }
        }).always(function(){
          FlowFlow.makeOverlayTo('hide');
        }).fail(function(){
          alert('Something went wrong, please try to reload page');
        })
      },function(){})
    },
    clone: function() {
      var self = this;
      var request = self.model.clone();

      FlowFlow.makeOverlayTo('show');
      request.done(function(stream){
        var model = streamRowModels.get(stream.id)
        var view = new StreamRowView({model: model});
        FlowFlow.$list.append(view.$el);
        view.render();
      }).always(function(){
        FlowFlow.makeOverlayTo('hide');
      }).fail(function(){
        alert('Something went wrong, please try to reload page');
      })
    },

    getShortcodePages: function() {

        var id = this.model.get('id');

        var data = {
            action: la_plugin_slug_down + '_get_shortcode_pages',
            stream: id,
            security: vars.nonce
        }

        var $hint = this.$el.find( '.shortcode-pages' );

        $hint.html( '<span>.</span><span>.</span><span>.</span>' );

        this.ajaxPages = $.post( vars.ajaxurl, data ).done(function( res ){
            console.log( res );
            var pages = '';

            var data = JSON.parse( res );
            var page;

            if ( !data.length ) {
                $hint.html( 'No pages found' );
                return
            }

            for ( var i = 0, len = data.length; i < len; i++ ) {
                page = data[ i ];
                pages += '<a href="' + page.url + '" target="_blank">' + page.post_title + '</a><br>';
            }

            $hint.html( pages );
        })
	        .fail(function() {
		        $hint.html( 'Something went wrong, please report error.' );
	        })
    },

      cancelGetShortcodePages: function () {
        if ( this.ajaxPages ) this.ajaxPages.abort();
          this.$el.find( '.shortcode-pages' ).html('');
      },

    selectShortcode: function(e){
      var el = e.target;
      var doc = window.document, sel, range;
      if (window.getSelection && doc.createRange) {
        sel = window.getSelection();
        range = doc.createRange();
        range.selectNodeContents(el);
        sel.removeAllRanges();
        sel.addRange(range);
      } else if (doc.body.createTextRange) {
        range = doc.body.createTextRange();
        range.moveToElementText(el);
        range.select();
      }
    }
  });

  StreamView = Backbone.View.extend({
    tagname: "div",
    template:  _.template(templates.stream),
    className: "section-stream",
    streams: [],
    rowModel: null,
    rowView: null,
    currentId: 'new',
    $preview: null,
    events: {
      "click .admin-button.submit-button": "saveViaAjax",
      "change input, textarea": "updateModel",
      "input [type=range]": "updateModel",
      "colorpicker-change input": "updateModel",
      "change select:not(.stream-streams__select select)": "updateModel",
      "click .disabled-button": "disableAction",
//    "click .stream-streams__item": "showPreview",
      "click .stream-feeds__item": "detachFeed",
      "click .stream-feeds__block": "displayFeedsSelect",
      "click .stream-feeds__btn": "connectFeed",
      "change [id^=stream-layout]": "changeDesignMode",
      "click [id^=stream-layout]": "validateDesignModeChange",
      "change .input-not-obvious input": "saveName",
      "keyup .input-not-obvious input": "saveName",
      "change .design-step-2 select[id*=align]" : "previewChangeAlign",
      "change .design-step-2 select[id*=icons-style]" : "previewChangeIconsLook",
      "change .design-step-2 select[id*=upic-pos]" : "previewChangeUpic",
      "change .design-step-2 select[id*=upic-style]" : "previewChangeCorners",
      "change .design-step-2 select[id*=icon-style]" : "previewChangeIcon",
      "keyup .design-step-2 input[id*=bradius]" : "previewChangeBradius",
      "keyup .design-step-2 [id*=width]" : "previewChangeWidth",
      "change .layout-compact select[id*=compact-style]" : "previewChangeCompact",
      //"change .style-choice select[id*=gc-style]" : "previewChangeStyle",
      "change .theme-choice input" : "previewChangeTheme"
    },

    initialize: function() {
      //this.listenTo(this.model, "change", this.render);
      var self = this;
      var rowModel, rowView;
      var rendered = this.rendered;

      this.model.view = this;

      this.render();

      this.model.listenTo(this, 'changeModel', function (data){
        // console.log('changeModel event', data);
        self.model.set(data.name, data.val);
      })

      if (this.model.isNew()) {

      } else {
        this.rowModel = streamRowModels.get(this.model.get('id'));
        console.log('binding models..')
        this.bindModels();
      }

      this.$preview = this.$el.find('.preview .ff-stream-wrapper');

      self.on('preview-update', function () {
        var $item = self.$preview.find('.ff-item')
        if ($item.find('.ff-item-cont').children().first().is('.ff-item-meta')) {
          $item.addClass('ff-meta-first')
        } else {
          $item.removeClass('ff-meta-first')
        }
      });

    },

    bindModels: function () {
      var self = this;

      this.model.listenTo(feedsModel, 'change', function(changedModel){
        var streamFeeds = this.get('feeds');
        var allFeeds = feedsModel.get('feeds');
        var changedFeeds = changedModel.get('feeds_changed');
        var triggerRender = false, indexToDelete = -1;

        _.each(streamFeeds, function (feed, index) {
          var changed = changedFeeds[feed.id];
            if (changed) {
              if (changed['state'] === 'changed') {
                streamFeeds[index] = allFeeds[feed.id];
                triggerRender = true;
              } else if (changed['state'] === 'deleted') {
                indexToDelete = index;
                triggerRender = true;
              }
            }
        });

        if (indexToDelete > -1) streamFeeds.splice(indexToDelete, 1);

        if (triggerRender) {
          this.view.renderConnectedFeeds();
        }

        console.log('stream listening to feedsModel');
      }, this);

      this.rowModel.listenTo(this.model, 'stream-saved', function (model) {
        var attrs = self.model.attributes;
        for (var prop in attrs) {
          if (self.rowModel['attributes'][prop] !== undefined) {
            if (typeof attrs[prop] === 'object') {
              self.rowModel.set(prop, _.clone(attrs[prop]));
            } else {
              self.rowModel.set(prop, attrs[prop]);
            }
          }
        }
      })
    },

    render: function() {
      
      var id = this.model.get('id');
      var self = this;
      console.log('render stream view');

      if ( !this.rendered || !this.currentId ) {
        this.$el.attr('data-view-mode', 'streams-update').attr('id', 'stream-view-' + (id || 'new'));

        this.$el.html(this.template({
          id: id || 'new',
          plugin_url: window.plugin_url,
          header: id && id != 'new' ? 'Stream #' + id : 'Creating...',
          version: window.plugin_ver,
          TV: templates.tv ? _.template(templates.tv)({id:id}) : '',
          TVtab: templates.tvTab || ''
        }))

        setTimeout(function () {
          self.$el.find(".input-not-obvious input").autoresize({padding:1,minWidth:56,maxWidth:400});
        })
        FlowFlow.tabsCursor.initFor(this.$el, id);

        setTimeout(function () {
          self.$preview = self.$el.find('.preview .ff-stream-wrapper');

          self.configDesign();
          self.applySavedTemplate();
          self.trigger('preview-update');
        },0)
          
          this.setupCloudToggle();
      }
    
      this.setInputsValue();
      this.renderConnectedFeeds();

      this.currentId = id;
      this.rendered = true;

      $(document).trigger('stream_view_built', this.$el);

    },

    saveName: function (e) {

      var val = e.target.value;
      var type = e.type;
      var oldval

      if (/*e.type === 'change' ||*/ e.type === 'keyup' && e.keyCode == 13) {
        this.saveViaAjax();
      }
    },

    saving: false,

    configDesign: function () {
      
      console.log('config design and cpickers');
      var self = this;
      this.$el.find('input[type="range"]').on('mouseup', function() {
        this.blur();
      }).on('change input', function () {
          var $t = $(this);
          var name = this.name.indexOf('-r-') + 1 ? 'row' : 'column';
          var $v = $t.data('el') ? $t.data('el') : $t.next('.range-value');

          if (!$v) {
              $v = $t.parent().find('.range-value');
              $t.data('el', $v)
          }

          $v.html(this.value + ' ' + name + (this.value > 1 ? 's' : ''));
          $t = null;
      }).trigger( 'change' )/*.rangeslider()*/;

      this.$el.find('input[data-color-format]').ColorPickerSliders( this.colorPickersConfig );

      // trigger changes
      this.$el.find('[id^=stream-layout]:checked, select[id*=upic-pos], select[id*=upic-style], select[id*=icon-style], select[id*=icons-style], .design-step-2 select[id*=align]').trigger( 'change' );
      this.$el.find('.design-step-2 input[id*=bradius]').trigger( 'keyup' );
      
      // make preview sortable
      this.$el.find('.ff-item-cont').sortableCustom({
        handle: '.ff-item__draggable',
        animation: 200,
        mimicBg: true,
        draggable: '.ff-item__draggable',
        onUpdate: function () {
          var template = [];
          var $preview = self.$el.find('.ff-item-cont');

          $preview.children().each(function () {
            var role = $(this).data('template');
            if (role) template.push(role);
          })

          self.model.set('template', template);

          $preview.find('.ff-label-wrapper').insertAfter($preview.find('.ff-item-meta'));

          self.trigger('preview-update');

        }
      })
    },

    applySavedTemplate: function () {
      var template = this.model.get('template');
      var i, len;
      var $cont = this.$el.find('.ff-item-cont');

      var detached = {
        'header': $cont.find('[data-template="header"]').detach(),
        'text': $cont.find('[data-template="text"]').detach(),
        'image': $cont.find('[data-template="image"]').detach(),
        'meta': $cont.find('[data-template="meta"]').detach(),
      }

      for ( i = 0, len = template.length; i < len; i++ ) {
         $cont.append( detached[template[i]] );
      }

      $cont.find('.ff-label-wrapper').insertAfter( detached.meta );
      $cont.find('> .ff-item-bar').appendTo($cont);
    },
	
    setupCloudToggle: function () {
     
        var self = this;
	    var cloud = this.model.get( 'cloud' );
	    var id = this.model.get( 'id' );
	    
	    
		this.$el.find( '.section[data-tab="source"]' ).append( '<label for="stream-' + id + '-boosted" class="switcher cloud-switcher"><input id="stream-' + id + '-boosted" class="switcher cloud-switcher" type="checkbox" name="stream-' + id + '-cloud" value="yep"> <div><div></div></div></label><div class="ff-feeds-counter"><span class="ff-feeds-counter__loaded">.</span>/<span class="ff-feeds-counter__total">.</span> feeds<br><span class="dots-loading">boosted</span></div>' );
	 
		// this.$el.find( '#stream-' + id + '-boosted' ).prop( 'checked', cloud == 'yep' );
	
	    this.$el.find( '[for=stream-' + id + '-boosted]' ).on( 'click', function ( e ) {
	        
	        e.preventDefault();
	        
	        var $t = $( this );
	        var $inp = $t.find( 'input' );
		    var currentStreamFeeds = self.model.get('feeds');
		    
	        if ( ! $inp.is( ":checked" ) ) { // intent to boost all feeds
		        
		        if ( FlowFlow.availableBoosts !== null ) {
		            
		            if ( FlowFlow.availableBoosts == 'not_active' ) {
		             
			            FlowFlow.popup( 'No available boosts to access cloud service, please go to Extra tab for more info', 'neutral', false, { right: 'Learn more', left: 'cancel'} )
				            .then( function yes (value) {
					            
					            $( '#addons-tab' ).trigger( 'click' );
				            }, function cancel (reason) {
					
				            })
                    }
                    else if ( FlowFlow.availableBoosts < currentStreamFeeds.length ) {
			
			            FlowFlow.popup( 'Not enough available boosts, please free up boosts from other feeds or upgrade plan on Extra tab', 'neutral', false, { right: 'Go to extra', left: 'cancel'} )
				            .then( function yes (value) {
					            $( '#addons-tab' ).trigger( 'click' );
				            }, function cancel (reason) {
					
				            })
                    
                    } else {
                    
			            // then check if feeds in other streams
			            // cancel if found
			            var streams = streamRowModels.models;
			            var found;
			            var cancelCloudChange;
			            var streamFeeds;
			            
			            for ( var i = 0, len = streams.length; i < len; i++ ) {
			                
			                if ( streams[ i ].cloud == 'yep' ) continue;
			                if ( streams[ i ].id == id ) continue; // current stream
				
				            streamFeeds = streams[ i ].get( 'feeds' );
				            
				            found = _.find( streamFeeds, function ( streamFeed ) {
					            return _.find( currentStreamFeeds, function ( currentStreamFeed ) {
						            return currentStreamFeed.id == streamFeed.id;
					            } )
				            })
				
				            if ( found ) { // this feed in other stream
					            // show first found
					            var alert = FlowFlow.popup( 'One of this stream feeds ' + (found.content ? '("' + found.content + '")' : '' ) + ' is also connected to other stream (Stream #' + streams[ i ].get( 'id' ) + ( streams[ i ].get( 'name' ) ? ' "' + streams[ i ].get( 'name' ) + '"' : '' ) +  '). Feed can\'t be in cloud and self-hosted stream simultaneously. Please disconnect feed from other stream first.', 'neutral', false, { right: 'Learn more', left: 'close'} )
						            .then( function yes (value) {
							            $( '#addons-tab' ).trigger( 'click' );
						            }, function cancel (reason) {
							
						            });
					
					            cancelCloudChange = true;
					
					            break;
				            }
			            }
			            
			            // сheck if WP feeds
                        var wpFeed = _.find( currentStreamFeeds, function ( feed ) {
                            return feed.type == 'wordpress';
                        })
			
			            if ( wpFeed ) {
				            // show first found
				            var alert = FlowFlow.popup( 'One of this stream feeds has Wordpress source. WordPress feeds can\'t be in cloud currently. Please disconnect feed from stream first.', 'neutral', false, { right: 'Learn more', left: 'close'} )
					            .then( function yes (value) {
						            $( '#addons-tab' ).trigger( 'click' );
					            }, function cancel (reason) {
						
					            });
				
				            cancelCloudChange = true;
				
			            }
			            
			            if ( ! cancelCloudChange ) {
				
				            var alert = FlowFlow.popup( 'You are about to enable cloud service for this stream, aka boosting all connected feeds. This process will be run in background, please wait for confirmation notification. Time to wait depends on number of connected feeds. Please don\'t reload browser', 'neutral', false, { right: 'ENABLE CLOUD', left: 'cancel'} )
					            .then( function yes (value) {
						            // todo https://www.webniraj.com/2018/10/08/making-ajax-calls-sequentially-using-jquery/
						
						            var delay = 0;
					                var $loaded = self.$el.find( '.ff-feeds-counter__loaded' );
                     
						            self.$el.addClass( 'toggling-cloud' );
						            self.$el.find( '.ff-feeds-counter__total' ).html( currentStreamFeeds.length );
						            
						            var requests = [];
						
						            $loaded.html( 0 ).data( 'current', 0);
						            
                                    _.each( currentStreamFeeds, function ( feed ) {
                                    	
	                                    // initiate boost for feeds
	                                    var $view =  feedsView.$popup.find('[data-uid="' + feed.id + '"]');
	                                    if ( ! $view.length ) {
		                                    $view = $( _.template(templates[ feed.type + 'View'])({
			                                    uid: feed.id
		                                    }) );
		                                    feedsView.$el.find( '#feed-views' ).append( $view );
		                                    // set values
		                                    feedsView.setInputsValue( feed.id );
	                                    }
	
	                                    var $channeling = $view.find('input[name="' + feed.id + '-boosted"]');
                                     
	                                    var allFeeds = feedsModel.get( 'feeds' );
	
	                                    var request = $.Deferred();
	                                    requests.push( request );
	                                    setTimeout( function () {
	                                    	
		                                    allFeeds[ feed.id ]['boosted'] = 'yep';
		                                    //allFeeds[ feed.id ]['enabled'] = 'yep';
		                                    
		                                    $channeling.prop('checked', true ).trigger( 'change' ); // trigger change
	                                        
                                            feedsView.saveViaAjax();
                                            
                                            var current = $loaded.data( 'current');
                                            
                                            $loaded.html( current + 1 ).data( 'current', current + 1 );
		                                    
	                                        request.resolve( feed );
	                                        
	                                    }, delay)
	
	                                    delay += 5000;
                                    })
                                    
                                    if ( currentStreamFeeds == 0 ) {
                                    
                                    }
						
						            $.when.apply( $, requests ).then( function () {
						                
						                var args = Array.prototype.slice.call( arguments );
						                console.log( args, requests );
						                
							            self.model.set( 'cloud', 'yep' );
							            $inp.prop( 'checked', true );
							            
							            self.saveViaAjax().done( function () {
								            
								            self.$el.find( '.dots-loading' ).removeClass( 'dots-loading' )
                                            
                                            setTimeout( function () {
	                                            self.$el.removeClass( 'toggling-cloud ff-l' );
                                            }, 3000)
							            });
							            
                                    } );
                  
					            }, function cancel (reason) {
						            //$t.prop( 'checked', false );
					            });
                        }
			
                    }
                    
                } else {
	                FlowFlow.showNotification( 'Connection with cloud wasn\'t established, please try later or contact us.<i class="flaticon-error"></i>' );
	                $inp.prop( "checked", false );
                }
		
            
            } else { // make all feeds regular
	           
		        var alert = FlowFlow.popup( 'You are about to disable cloud service for this stream, aka removing boosts from all connected feeds. To not overload your server with updating of possibly big amount of feeds they will be disabled, please enable LIVE for each feed individually on FEEDS tab.', 'neutral', false, { right: 'DISABLE CLOUD', left: 'cancel'} )
			        .then( function yes (value) {
				
				        var delay = 0;
				        // save cloud prop in stream or do this later?
				        self.model.set( 'cloud', 'nope' );
				        self.$el.addClass( 'ff-l' );
				        $inp.prop( 'checked', false );
				        
				        self.saveViaAjax();
				
				        _.each( currentStreamFeeds, function ( feed ) {
					        // initiate boost for feeds
					        var $view =  feedsView.$popup.find('[data-uid="' + feed.id + '"]');
					        
					        if ( ! $view.length ) {
						        $view = $( _.template(templates[ feed.type + 'View'])({
							        uid: feed.id
						        }) );
						        feedsView.$el.find( '#feed-views' ).append( $view );
						        // set values
						        feedsView.setInputsValue( feed.id );
					        }
					
					        var $channeling = $view.find('input[name="' + feed.id + '-boosted"]');
					        
					        var allFeeds = feedsModel.get( 'feeds' );
					
					        setTimeout( function () {
						        allFeeds[ feed.id ]['boosted'] = 'nope';
						        $channeling.prop('checked', false ).trigger( 'change' ); // trigger change
						        feedsView.saveViaAjax();
					        }, delay)
					
					        delay += 500;
				        })
				
				
			        }, function cancel (reason) {
				        // $t.prop( 'checked', true );
			        });
            }

	    })
    },

    renderConnectedFeeds: function () {

      var feeds = this.model.get('feeds');
      var $cont = this.$el.find('.stream-feeds__list');
      var feed, name, fullName;
      var items = '';
      if (!feeds) return;
      for (var i = 0, len = feeds.length; i < len; i++) {
        feed = feeds[i];
        name = feed.content;
        fullName = name;

        if (!name && feed.type === "wordpress") {
          name = feed['category-name'] || feed['wordpress-type'];
        }

        if (feed.type === "rss" ) {
          if (feed['channel-name']) name = feed['channel-name'];
        } else if (feed.type === "twitter" && feed['timeline-type'] === 'list_timeline') {
          name += ' - ' + feed['list-name'];
        }

        if (name.length > 13) name = name.substr(0, 13) + '...';
        items += '<span data-tooltip="' + capitaliseFirstLetter( feed.type ) + ' - ' + stripslashes( fullName )  + ' - ID: ' + feed.id + '" data-id="' +  feed.id +'" class="stream-feeds__item stream-feeds__' + feed.type +  (feed.errors && feed.errors.length ? ' stream-feeds__error' : '') + '"><i class="stream-feeds__icon flaticon-' + feed.type + '"></i>' + stripslashes( name ) + '</span>';
      }
      $cont.html('').append(items).closest('.stream-feeds').removeClass('stream-feeds--connecting');
    },

    connectFeed: function (e) {
      var self = this;

      var $t = $(e.target).closest('.stream-feeds__btn');
      var streamFeeds = this.model.get('feeds');
      var allFeeds = feedsModel.get('feeds');
      var feed;
      var val;
      var boostedFeeds = _.filter( streamFeeds , function( feed ){ return feed.boosted == 'yep' });
      var promise;

      if ($t.is('.stream-feeds__close')) {
        $t.closest('.stream-feeds').removeClass('stream-feeds--connecting')
        return;
      }

      val = this.$el.find('.stream-feeds select :selected').val();

      feed = allFeeds[ val ];

      if ( !feed ) return;

      // if cloud stream and regular feed warn user
      // disabled for now
      
      if ( false && feed.boosted == 'nope' && boostedFeeds.length == streamFeeds.length ) {
          console.log('add regular feed');
          promise = FlowFlow.popup( 'You are about to add regular feed to Cloud stream, this will make it Self-Hosted. Are you sure?', 'neutral' );
          promise.then(function(){
              saveFeeds();
          },function(){
              $t.closest('.stream-feeds').removeClass('stream-feeds--connecting');
          });
      } else {
          saveFeeds();
      }

      function saveFeeds() {
          // double check it doesn't exist already
          if ( !_.find( streamFeeds, function(e){ return e.id === val }) ) {
              streamFeeds.push( feed );
          }

          FlowFlow.makeOverlayTo( 'show' );

          var request = self.model.save();

          request.done(function(serverModel){
              self.model.trigger('stream-saved');
              self.renderConnectedFeeds();
          }).always(function(){
              FlowFlow.makeOverlayTo('hide');
          });
      }
    },

    displayFeedsSelect: function () {

      var self = this;

      var connectedFeeds = this.model.get( 'feeds' );
      var availableFeeds = _.clone( feedsModel.get( 'feeds' ) );

      var isEmpty = _.isEmpty( availableFeeds ), isEmptyAfterFilter;
      var connectedFeedsIDs = {};
	
      var isCloudStream = this.model.get( 'cloud' ) == 'yep';
		
	  var i, len, feed;

      var $select = this.$el.find( '.stream-feeds select' );
      var options = '';
      var name;

      // create IDs map
      _.each( connectedFeeds, function ( el ) { connectedFeedsIDs[ el.id ] = true; } );

      // filter connected and boosted / regular type for appropriate stream type
      availableFeeds = _.filter( availableFeeds, function ( feed ) {
        var isFeedBoosted = feed.boosted == 'yep';
        var includeThisFeed = true;
        
        if ( isCloudStream && ! isFeedBoosted ) {
            includeThisFeed = false;
        }
        else if ( ! isCloudStream && isFeedBoosted ) {
            includeThisFeed = false;
        }
        
        return !connectedFeedsIDs[ feed.id ] && includeThisFeed;
      })
      
      isEmptyAfterFilter = _.isEmpty( availableFeeds );

      if ( isEmpty || isEmptyAfterFilter ) {
        // var msg = isEmpty ? 'It seems there are no available feeds for this type of stream. Go to Feeds tab?' : 'You connected all feeds already. Go to Feeds tab?';
        var msg ='It seems there are no available feeds for this type of stream. Go to Feeds tab?';
        var promise = FlowFlow.popup( msg, 'neutral' );

        promise.then(function(){
          FlowFlow.$form.find('#sources-tab').trigger( 'click' )
        },function(){});

        return
      }

      for (i = 0, len = availableFeeds.length; i < len; i++) {
        feed = availableFeeds[i];
        name = feed.content;
        if (!name && feed.type == "wordpress") {
          name = capitaliseFirstLetter(feed['category-name'] || 'Posts');
        }
        options += '<option value="' + feed.id + '">' + capitaliseFirstLetter(feed.type) + ' - ' + name + ' - ' + feed.id  + '</option>';
      }

      $select.html('').append(options).closest('.stream-feeds').addClass('stream-feeds--connecting');
      $select.chosen("destroy");
      $select.chosen();
    },

    detachFeed: function (e) {
      var promise = FlowFlow.popup('Disconnect feed from stream?', 'neutral');
      var self = this;
      var $t = $(e.target).closest('span');
      var id = $t.data('id');

      e.stopPropagation();

      promise.then(
          function success () {
            self.model.set('feeds', _.filter(self.model.get('feeds'), function(el){return el.id != id}));
            FlowFlow.makeOverlayTo('show');

            var request = self.model.save();
            request.done(function(serverModel){
              self.model.trigger('stream-saved');
              $t.remove();
            }).always(function(){
              FlowFlow.makeOverlayTo('hide');
            });
          },
          function fail () {

          }
      )
    },

    disableAction: function (e) {
      e.stopImmediatePropagation()
    },

    setInputsValue: function () {
      // console.log('set inputs value');
      var $input;
      var id = this.model.get('id');
      var attrs = this.model.attributes;
      var val;
      var optVal;
      var selector;
      var name;
      for ( name in attrs ) {
        //if (/s.+?\-f/.test(name)) continue;
        selector = '[name="stream-' + id + '-' + name + '"]';
        $input = this.$el.find( selector );
        val = typeof attrs[name] === 'object' ? JSON.stringify( attrs[name] ) : attrs[name];
        if ($input.length > 1) { // assume checkbox group
          optVal = attrs[name];
          if (typeof optVal === 'object') {
            $input.each(function(){
              var $t = $( this );
              if (!this.disabled) $t.prop('checked', optVal[this.value]);
            });
            optVal = null;
          } else {
            $input.each(function(){
              var $t = $( this );
              if (!this.disabled) $t.prop('checked', $t.val() == optVal);
            });
          }
        }
        else if ($input.is(':radio') || $input.is(':checkbox')) {
          $input.each(function(){
            var $t = $( this );
            if ( !this.disabled ) $t.prop( 'checked', attrs[name] === 'yep' );
          });
        } else {
          $input.val(val ? stripslashes(val.toString()) : '');
        }
      }

    },

    changeDesignMode: function (e) {
      var val = e.currentTarget.value;
      var self = this;
      var $p = $(e.currentTarget).closest('.section');
      
      $p.removeClass(function(index, cls) {
          return cls.match(/\w+-layout-chosen/)[0];
      }).addClass(val + '-layout-chosen').find('.section-settings').removeClass('settings-section__active').end()
          .find('.settings-' + val).addClass('settings-section__active');
      setTimeout(function () {
          FlowFlow.setHeight(self.model.get('id'));
      },0);
    },
	  
	  validateDesignModeChange: function (e) {
		  var val = e.currentTarget.value;
		  if ( val != 'masonry' && this.model.get('cloud') == 'nope' ) {
			  e.preventDefault();
			  return;
		  }
	  },

    previewChangeAlign: function (e) {
      var val = e.target.value;
      var $preview = $(e.target).closest('dl').find('.preview .ff-stream-wrapper');
      $preview.css('text-align', val);
    },

    previewChangeUpic: function (e) {
      var val = e.target.value;
      this.$preview.removeClass('ff-upic-timestamp ff-upic-centered ff-upic-centered-big ff-upic-off').addClass('ff-upic-' + val);
    },

    previewChangeCorners: function (e) {
      var val = e.target.value;
      this.$preview.removeClass('ff-upic-round ff-upic-square').addClass('ff-upic-' + val);
      if ( val == 'square' ) {
	      this.$el.find( '.upic-style-toggle' ).hide();
      } else {
	      this.$el.find( '.upic-style-toggle' ).show();
      }
    },

    previewChangeIcon: function (e) {
      var val = e.target.value;
      this.$preview.removeClass('ff-sc-label1 ff-sc-label2 ff-sc-stamp1 ff-sc-off').addClass('ff-sc-' + val);
    },
    
    previewChangeBradius: function (e) {
	  var val = e.target.value;
	  
	  this.$preview.find('.picture-item__inner, .ff-img-holder img').css( 'borderRadius', val + 'px' );
    },

    previewChangeIconsLook: function (e) {
      var val = e.target.value;
      this.$preview.removeClass('ff-fill-icon ff-outline-icon').addClass('ff-' + val + '-icon');
    },

    previewChangeStyle: function (e) {
      var val = e.target.value;
      var $preview = $(e.target).closest('dl').find('.preview .ff-stream-wrapper');
      var cls = $preview.attr( 'class' );

      if (/flat/.test(cls)) {
        this.revert($preview);
        this.reformat($preview, val);
      }

      $preview.removeClass(function() {
        return cls.match(/ff-style-[1-9]/)[0];
      }).addClass('ff-' + val);
    },

    previewChangeTheme: function (e) {
      var val = e.target.value;
      var $cont = $(e.target).closest('.design-step-2');

      $cont.find('.style-choice').hide();
      $cont.find('.' + val + '-style').show();
    },

    previewChangeWidth: function (e) {
      var val = e.target.value;
      var $preview = $(e.target).closest('.design-step-2').find('.classic-style .preview, .flat-style .preview');

      val = parseInt(val);

      if (isNaN(val)) return;

      $preview.find('.ff-item').css('width', val + 'px')
    },

    reformat: function  ($stream, style) {
      $stream.find('.ff-item').each(function(i,el){
        var $el = $(el);
        var $img = $el.find('.ff-img-holder');
        var $meta;

        if (/[12]/.test(style)) {
          $meta = $el.find('.ff-item-meta');
          $el.find('.ff-item-cont').prepend($meta);

          if (!$img.length) {
            if (style === 'style-1') {
              $meta.append($meta.find('.ff-userpic'));
            }
            $el.addClass('ff-no-image')
          } else {
            $el.addClass('ff-image')
          }
        } else if (style === 'style-3') {
          $el.prepend($el.find('.ff-icon'));
        }

        $el.addClass('ff-' + (!$img.length ? 'no-' : '') +'image');

        $el.prepend($img);
      })
    },

    revert: function ($stream) {
      $stream.find('.ff-item').each(function(i,el){
        //console.log('revert',el)
        var $el = $(el);
        var $cont = $el.find('.ff-item-cont');

        $cont.append($cont.find('h4'));
        $cont.append($cont.find('.ff-img-holder'));
        $cont.append($cont.find('p'));
        $cont.append($cont.find('.ff-item-meta'));

        $el.find('.ff-userpic').append($el.find('.ff-icon'))
      })
    },

    colorPickersConfig : {
      previewontriggerelement: true,
      previewformat: 'rgba',
      flat: false,
      color: 'rgb(255, 88, 115)',
      customswatches: 'card_bg',
      swatches: [
        '#c0392b',
        'a3503c',
        '925873',
        '927758',
        '589272',
        '588c92',
        '2bb1c0',
        '2b8ac0',
        'e96701',
        'c02b74',
        '000000',
        '4C4C4C',
        'CCCCCC',
        'F0F0F0',
        'FFFFFF'
      ],
      order: {
        hsl: 1,
        opacity: 2,
        preview: 3
      },
      onchange: function(container, color) {
        var $preview = container.data('preview');
        var sel = container.data('prop').replace(/-\d+/, '');
        var $targ = $preview.find('[data-preview*="' + sel + '"]');
        var $inp = container.data('input');
        var prop = $inp.attr('data-prop');
        var pre = '';
        $inp.trigger('colorpicker-change');

        if (!$targ.length) return;

        if (prop === 'box-shadow') pre = '0 1px 4px 0 ';
        $targ.each(function(){
          var $t = $(this);
          //console.log(this, $t.attr('data-overrideProp') || prop)
          var col = color.tiny.toRgbString();
          $t.css($t.attr('data-overrideProp') || prop, pre + col)
        });
      }
    },

    goBack: function() {
      FlowFlow.switchToView('list');
    },
    
    updateModel: function(event) {
      var $t = $(event.target);
      var val = $t.val();
      var name = $t.attr('name');
      var $group;

      if (!name ) {
        return;
      }

      if ($t.is(':radio')) {
        val = $t.is(':checked') ? ($t.attr('value') || 'yep') : 'nope'
      }

      if ($t.is(':checkbox')) {
        $group = this.$el.find('[name="' + name + '"]');
        if ($group.length > 1) {
          // group
          val = {};
          $group.each(function () {
            var cbVal = this.value;
            if (this.checked) val[cbVal] = 'yep';
          });
        } else {
          val = $t.is(':checked') ? 'yep' : 'nope';
        }
      }

      this.trigger('changeModel', {name: name.replace('stream-' + (this.model.get('id') || 'new') + '-', ''), val: val })
    },

    saveViaAjax: function ( e ) {

      if (this.saving) return;
      else this.saving = true;
      console.log('save stream');

      if ( e ) e.stopImmediatePropagation();

      var self = this;
      var wasEmptyList = streamRowModels.length === 0;
      var $t = $(e ? e.target : '');
      var isNew = this.model.isNew();

      // validation in popup
      if ($t.is('[id^=networks-sbmt]')) {
          if (!this.validateFeedCfg($t)) return false;
      }

      FlowFlow.makeOverlayTo('show');
      $t.addClass('button-in-progress');

      var promise = this.model.save(isNew);

      promise.done(function(serverModel){

        if (serverModel.error) return;

        FlowFlow.makeOverlayTo('hide');

        self.render();

        if (isNew) {
          self.rowModel = new StreamRowModel( { cloud : serverModel.cloud });
          self.rowView = new StreamRowView({model: self.rowModel});
          streamRowModels.add(self.rowModel);

          FlowFlow.$list.append(self.rowView.$el);
          self.bindModels();
        } else {
          self.$el.removeClass('stream-view-new');
        }

        self.rowModel.set('id', serverModel.id);
        self.model.trigger('stream-saved');

        if (wasEmptyList) {
          FlowFlow.$list.find('.empty-row').remove();
        }

        sessionStorage.setItem('ff_stream', serverModel.id);

        $t.addClass('updated-button').html('<i class="flaticon-check_mark" data-action="edit"></i>&nbsp;&nbsp;Updated');
        $t.removeClass('button-in-progress');

        setTimeout( function () {
          $t.html('Save changes').removeClass('updated-button');
        }, 2500);
      }).fail(function(){
        alert('Something went wrong. Please try to reload page. If this repeats please contact support at https://social-streams.com/contact/')
      }).always(function () {
        self.saving = false;
      });

      return promise;
    },

    showPreview: function (e) {
      var $t = $(e.target);
      var id = $t.data('id');
      FlowFlow.makeOverlayTo('show');
      $.get( vars.ajaxurl, {
        'action' :  'flow_flow_show_preview',
        'stream-id' : id
      }).success(function(response){
        var $popup = $('.content-popup');
        var $body = $('body');
        FlowFlow.makeOverlayTo('hide');

        $body.css('overflow', 'hidden');
        $popup.off(transitionEnd).addClass('is-visible').find('.content-popup__content').html(response);

        if (FlowFlow.$previewStyles) {
          FlowFlow.$previewStyles.appendTo('head');
        }

        $popup.on('click', function(event){
          if( $(event.target).is('.content-popup__close') || $(event.target).is('.content-popup') ) {
            event.preventDefault();
            var self = this;
            $(this).removeClass('is-visible');
            $popup.off('click');
            $popup.on(transitionEnd, function(){
              $popup.find('.content-popup__content').html('').off(transitionEnd);
              $body.find('.ff-slideshow').remove();
              if (!FlowFlow.$previewStyles) {
                FlowFlow.$previewStyles = $('#ff_style, #ff_ad_style');
              }
              FlowFlow.$previewStyles.detach();
            })
            $body.css('overflow', '');
          }
        });
      }).fail(function(){
        FlowFlow.makeOverlayTo('hide');

        alert('Something went wrong. Please try again after page refresh')
      })
    },
  });

  // Feeds MVC

  FeedsModel = Backbone.Model.extend({
    defaults: function () {
      return {
        "feeds": {},
        "feeds_changed": {}
      }
    },
    
    initialize: function() {
      console.log('initialize Feeds Model', this);
    },
    
    save: function( isNew ) {
      var self = this;
      var $params = {
        emulateJSON: true,
        data: {
          action: la_plugin_slug_down + '_save_sources_settings',
          model: this.toJSON(),
          security: vars.nonce
        }
      };

      // filter and send only changed

      var feed;
      var feeds = $params.data.model.feeds;
      var feedsChanged = $params.data.model.feeds_changed;
      var feedsToSend = {};
      var created, changed = {}, id; // created can be only single;

      for (feed in feedsChanged) {
        id = feedsChanged[feed]['id'];
        feedsToSend[id] = feeds[id];
        if (feedsChanged[feed]['state'] === 'created') {
          created = feedsChanged[feed];
        }
      }
      $params.data.model.feeds = feedsToSend;
	
	  var newObj = {};
   
	  // move last to first
      if ( created ) {
        
        var array = $.map( _.clone( feeds ), function(value, index) {
            return [value];
        });
        
        if ( array.length > 1 ) {
	        // move last to first
	        array.unshift(array.pop());
	
	        for (var i = 0; i < array.length; ++i) {
		        newObj[array[i]['id']] = array[i];
	        }
	
	        self.set('feeds', newObj);
        }

      }

      /**/
      return Backbone.sync( 'create', this, $params ).done(function( serverModel ){

        if ( serverModel && serverModel.error ) {
            var promise = FlowFlow.popup( serverModel.error == 'not_allowed' ? 'Nay! You have no permissions to do this, please contact admin.' : 'Nay! Something went wrong, please contact support', false, 'alert');
            FlowFlow.makeOverlayTo('hide');
            return;
        }

        if (self.isNew() && serverModel && serverModel['id']) {
          self.set('id', serverModel['id']);
        }
        // todo in next updates update stream status when error resolved in feed

        var id;
        if (serverModel && serverModel['feeds']) {
          for (var feed in serverModel['feeds']) {
            id = serverModel['feeds'][feed]['id'];
            feeds[id] = serverModel['feeds'][feed];
          }
        }

      });
    },
    urlRoot: vars.ajaxurl,
    url: function () {
      return this.urlRoot;
    }
  });

  FeedsView = Backbone.View.extend({
	renderedFirstTime: false,
    paginator: null,
    updateCycle: null,
    currentPage: parseInt( sessionStorage.getItem('ff_feeds_page') || 1) ,
    $popup: null,
    $tab: null,
    feedChanged: false,
    showErrorFeedsOnly: false,
    errorsPresent: false,
    events: {
      "click .submit-button": "saveViaAjax",
	  "keyup input, textarea": "catchEnter",
	  "keyup #feeds-search": "filterFeedsByName",
	  "click .button-add": "addFeedStepOne",
      "click .flaticon-tool_edit, .td-feed": "editFeed",
      "click .flaticon-tool_more": "toggleDropDown",
      "mouseleave .controls": "popupLeave",
      "click [data-action='filter']": "filterFeed",
      "click [data-action='cache']": "resetFeedCache",
      "click [data-action='check']": "checkStreams",
      "click .flaticon-tool_delete": "deleteFeed",
      "click .tr-error": "hideError",
      "click .button-go-back": "goBackToFeedChoice",
      "click .networks-list > li": "createFeedView",
      "click .popup .button-cancel-action, .popupclose": "closeFeedPopup",
      "click .ff-toggle-display": "toggleErrorFeeds",
      "click .ff-search-display": "toggleFilterInput",
      "keyup [data-action='add-filter']": "addFilter",
      "click [data-action='delete-filter']": "deleteFilter",
      "change .feed-view input": "updateModel",
      "change .feed-view select": "updateModel",
      "change td .switcher": "toggleFeed",
      "mouseenter .td-status": "showErrorIfPresent",
      "mouseleave .td-status": "hideError",
    },

    initialize: function() {
      var self = this;
      this.model.view = this;

      this.$tab = $('#sources-tab');
	  this.$popup = this.$el.find('.popup');
	  
      this.render();
      this.renderedFirstTime = true;
  
	  // todo broadcast to boost element
      this.model.listenTo(this, 'changeFeedInModel', function (data){
        console.log('changeFeedInModel event', data);
        var feeds = self.model.get('feeds');
        var feed = feeds[data.id];
        if (feed) {
          feed[data.name] = data.val;
        }
      })
    },

    render: function() {
      
      var self = this;
      var views = '';
      var filterViews = '';
      var feeds = this.model.get('feeds');
      var savedPage;
      var changed = this.model.get( 'feeds_changed' ), prop, state;
      
      console.log( 'RENDER VIEWS', JSON.stringify( changed ) )

      if ( ! this.renderedFirstTime ) {
	
	      var data = {
		      action: la_plugin_slug_down + '_sources',
		      security: vars.nonce
	      }
       
	      var sourcesRequest = $.post( vars.ajaxurl, data ).done( function( res ) {
		
		      var feeds;
		
		      try {
			      feeds = JSON.parse( res );
		      }
		      catch ( e ) {
			      console.log( 'Error parsing feeds JSON' );
			      return;
		      }
		      
		      if ( _.isEmpty( feeds ) ) feeds = {};
		      
		      // set feeds
              self.model.set( 'feeds', feeds );
		      
		      _renderUI( feeds );
		
		      self.renderedFirstTime = true;
		
		      // if nothing changed it's first time or hard re-render
              console.log( 'renderBoostsUI first time')
		      var boostsRequest = FlowFlow.renderBoostsUI( self.model );
		
		      $( document ).trigger( 'feeds-loaded', feeds )
		
			  $.when( boostsRequest ).then( function onSuccess ( boostsData ) {
			
			      var boosts;
					
			      try {
				      
				      boosts = JSON.parse( boostsData );
				
			      } catch ( e ) {
				      console.log( 'boosts data parsing error', e );
				      return;
			      }

                  var plansRequest = $.get( boosts_server_url + 'flow-flow/ff?action=plans' + ( FlowFlow.subscription && FlowFlow.subscription.plan_id ? '&active_plan=' + FlowFlow.subscription.plan_id  : '' ) );
						
			      $.when( plansRequest ).then( function onSuccess ( plansData ) {
			       
				      var plans =  plansData;
				      FlowFlow.renderBoostPricingTable( plans, boosts );
				      
			      }, function onError () {
			       
				      console.log( 'plans UI error', arguments );
				      
			      })
			
			
		      }, function onError () {
			      console.log( 'boosts UI error', arguments );
		      })
        
	      })
          
          // listen to nav events
	
	      $( document ).on( 'list-nav', function ( e, data ) {
              console.log( data );
              if ( data && data.page && data.page != self.currentPage ) {
	              self.currentPage = data.page;
	              self.renderFeedsList( changed, self.showErrorFeedsOnly );
	              self.savePage( data.page );
              }
	      })
       
      } else {
	
          // render UI
	
	      _renderUI( feeds, changed );

      }
	
	    function _renderUI( feeds, changed ) {
		
		    if ( ! changed ) {
			    // render all
			    /*
			    _.each( feeds, function ( feed ) {
				    if (!feed.type) {
					    return;
				    }
				    views += _.template(templates[feed.type + 'View'])({
					    uid: feed.id
				    });
				    filterViews += _.template(templates['filterView'])({
					    uid: feed.id
				    });
			    });
			
			    self.$el.find( '#feed-views' ).html( views );
			    self.$el.find( '#filter-views' ).html( filterViews );
			    */
			
		    } else {
			    _.each( changed, function ( feed ) {
				    // if created or changed, views already exist
				    // if deleted
				    if ( feed.state == 'deleted' ) {
					    self.$el.find( '.feed-view[data-uid="' + feed.id + '"], .feed-view[data-filter-uid="' + feed.id + '"]' ).remove();
				    }
			    });
		    }
		
		    // set pages
		    var index = 0;
		
		    _.each( feeds, function ( feed, id, feedsHash ) {
          
		        var page = Math.floor(index / 8);
		        feed.page = page + 1;
		        index++;
		    });
		    
		    self.renderFeedsList( changed, self.showErrorFeedsOnly );
		
		    self.setInputsValue();
		
		    // self.addFeedErrors();
	
		    if ( self.errorsPresent ) {
			    self.$tab.addClass('errors-present');
		    } else {
			    self.$tab.removeClass('errors-present');
		    }
		
		    self.paginator = self.initPaginator( self.currentPage );
		
		    console.log('current page', self.currentPage )
            
            if ( changed ) {
                if ( _.find( changed , function ( item ) { return item.state === 'created' } ) ) {
	                self.currentPage = 1;
                }
            }
		
		    if ( self.currentPage ) {
			    self.paginator.paginate( typeof self.currentPage === 'number' ? self.currentPage : 1 );
			    // self.currentPage = false;
		    } else {
			    savedPage = parseInt( sessionStorage.getItem('ff_feeds_page') || 1) ;
			    if ( savedPage > 1 ) {
				    self.paginator.paginate( savedPage );
				    self.currentPage = savedPage;
				    sessionStorage.setItem( 'ff_feeds_page', -1 ); // one time
			    }
		    }
	    }

    },
      
      startUpdateCycle: function ( id ) {
	    
	      var self = this;
	
	      var updateCycle = setTimeout( function () {
	          requestPosts( id );
          }, 5000 );
	
	      function requestPosts ( id ) {
		
		      var data = {
			      action: la_plugin_slug_down + '_sources',
			      security: vars.nonce,
                  id: id
		      }
		
		      $.post( vars.ajaxurl, data ).done( function( feed ) {
		       
			      var serverFeed;
			      // current client feeds
			      var feeds = self.model.get( 'feeds' );
			
			      try {
				      serverFeed = JSON.parse( feed );
			      }
			      catch ( e ) {
				      console.log( 'Error parsing serverFeed JSON' );
				      return;
			      }
			      
			      // update feeds model individually
			      feeds[ serverFeed.id ] = serverFeed;
			
			      var changed = self.model.get( 'feeds_changed' );
			      var renderQueue = false;
			      var state = changed[ id ] && changed[ id ][ 'state' ];
			      
			      console.log( serverFeed )
			      console.log( JSON.stringify( changed ) )
         
			          
                  if ( serverFeed [ 'status' ]  == 1 || serverFeed [ 'status' ]  == 0 ) { // feed resolved
            
                      // delete specific feed from changed hash because its status resolved
                      delete changed[ id ];
            
                      renderQueue = true;
            
                      clearTimeout( updateCycle );
                      updateCycle = null;
                      
                  } else {
                      
                      // recursion recursion
		              updateCycle = setTimeout( function () {
		                  requestPosts( id );
	                  }, 10000 );
                  }
			
                  if ( renderQueue ) {
                      self.render();
	                  console.log( 'renderBoostsUI changed ')
	                  FlowFlow.renderBoostsUI( self.model );
	
	                  if ( serverFeed [ 'status' ] != 1 ) {
		                  console.log( 'feed status requestPosts', serverFeed [ 'status' ] )
	                  }
	
	                  FlowFlow.showNotification( ( serverFeed [ 'status' ]  == 1  ? 'Yay' : 'Something went wrong' ) + '! Feed "<span>' + ( feeds[ id ].type === 'wordpress' ? ( feeds[ id ]['category-name'] || feeds[ id ]['wordpress-type'] ) : feeds[ id ][ 'content' ] ).toUpperCase() + '</span>" ' + ( serverFeed [ 'status' ]  == 1  ? 'was successfully' : 'was' ) + ' ' + ( state == 'deleted' ? 'deleted' : ( state == 'created' ? 'created' : 'updated' ) ) + ( serverFeed [ 'status' ]  == 1  ? '' : ' with errors' ) + '<i class="flaticon-' + ( serverFeed [ 'status' ]  == 1  ? serverFeed [ 'type' ].toLowerCase() : 'error' )  + '"></i>' );

                  }
                  
		      } )
		
	      }
      },
	
	  renderFeedsList: function ( changed, errorsOnly, searchTerm ) {

		  var feedsListStr = '';
		  var feeds = this.model.get('feeds');
		  var self = this;
		  
		  console.log('renderFeedsList changed', JSON.stringify( changed ));
		  
		  if ( ! _.isEmpty( feeds ) ) {
			
			  _.each( feeds, function ( feed ) {
				
				  var uid, enabled, status, lastUpdate;
				  var $feed, $error, info, prop, ikey, ival, interval;
				  var settings = {};
				
				  // if it's in changed hash we are waiting for status resolve
				  var isChanged = changed && changed[ feed.id ];
				  // console.log( 'RENDER FEED', isChanged, feed )
				  
				  if ( errorsOnly ) {
					  if ( _.isArray( feed.errors ) && _.isEmpty( feed.errors) ) {
						  return;
					  } else {
					  	// pass error feed so it renders when only error feeds are requested
					  }
				  }
				  else if ( searchTerm && searchTerm.length > 2 ) {
				      // filter out all feeds that doesn't match search term
					  if ( feed.content.indexOf( searchTerm ) == -1 ) {
					  	  return;
					  }
				  }
				  else if ( feed.page && feed.page != self.currentPage ) { // filter out all feeds not belonging to current page
					  return
				  }
				
				  uid = feed.id;
				
				  info = '';
				
				  if ( feed['boosted'] === 'yep' ) settings['boosted'] = feed['boosted'];
				
				  if ( feed['type'] === 'rss' ) {
					  settings['content'] = feed['channel-name'] || feed['content'];
				  }
				  else if ( feed['content'] ) {
					  settings['content'] = feed['content'];
				  } else {
					  settings['content'] = feed['category-name'] || feed['wordpress-type'];
				  }
				  if ( feed['timeline-type'] ) settings['timeline-type'] = feed['timeline-type'];
				  if ( feed['mod'] === 'yep' ) settings['mod'] = feed['mod'];
				
				  settings['id'] = 'ID: ' + feed['id'];
				
				  // todo refactor this crap
				  for ( prop in settings ) {
					  ikey = capitaliseFirstLetter( prop.replace(' timeline', '').replace('_', ' ').replace('-', ' ').replace('timeline ', '')  );
					  ival = stripslashes( settings[prop] );
					  if ( prop !== 'content' ) ival = capitaliseFirstLetter ( ival );
					  if ( prop === 'mod' ) ival = 'moderated';
					
					  if ( !ival ) continue;
					
					  ival = ival.replace('_timeline', '').replace('http://', '').replace('https://', '');
					  if (ival.length > 20) {
						  ival = ival.substring(0, 20) + '...';
					  }
					  if ( ikey === 'Boosted' && ival === 'Yep' ) {
					      info += '<span class="highlight hilite-boost ff-item__draggable"><i class="flaticon-rocket" style="display: inline-block;"></i></span>';
                      } else {
						  info = info + '<span class="highlight' + ( ikey === 'Id' ? ' highlight-id' : '' ) + '">' + ival + '</span>';
                      }
				  }
				  //
				
				  if (feed.cache_lifetime == 5) {
					  interval = 'Every 5 min';
				  } else if (feed.cache_lifetime == 30) {
					  interval = 'Every 30 min';
				  } else if (feed.cache_lifetime == 60) {
					  interval = 'Every hour';
				  } else if (feed.cache_lifetime == 120) {
					  interval = 'Every 2 hours';
				  } else if (feed.cache_lifetime == 360) {
					  interval = 'Every 6 hours';
				  } else if (feed.cache_lifetime == 1440) {
					  interval = 'Once a day';
				  } else if (feed.cache_lifetime == 10080) {
					  interval = 'Once a week';
				  }
				
				  
				  status = isChanged ? '<span class="cache-status-waiting">' : ( (feed.status == 1) ? '<span class="cache-status-ok">' : '<span class="cache-status-error">' );
				  lastUpdate = feed.last_update && feed.last_update !== 'N/A' ? (feed.last_update  + ' (' + interval + ')') : 'N/A';
				  
				  feedsListStr = feedsListStr +
					  '<tr data-uid="' + uid + '" data-network="' + feed.type + '" class="feed-row ' + ( feed.enabled == 'yep' ? 'feed-enabled' : 'feed-disabled' ) + ( isChanged ? ' feed-waiting-status' : '' ) + ( feed.boosted == 'yep' ? ' feed-boosted' : '' ) + '">' +
					  '<td class="controls"><div class="loader-wrapper"><div class="throbber-loader"></div></div><i class="flaticon-tool_more"></i><ul class="feed-dropdown-menu"><li data-action="filter">Filter feed</li><li data-action="cache">Rebuild cache</li><li data-action="check">Connections</li></ul><i class="flaticon-tool_edit"></i> <i class="flaticon-tool_delete"></i></td>' +
					  '<td class="td-feed"><i class="flaticon-' + feed.type + '"></i>' + /*capitaliseFirstLetter(feed.type) +*/ '</td>' +
					  '<td class="td-status">' + status + '</span></td>' +
					  '<td class="td-info">' + info + '</td>' +
					  '<td class="td-last-update">' + lastUpdate + '</td>' +
					  '<td class="td-enabled"><label for="feed-enabled-' + uid + '"><input ' + ( feed.enabled == 'yep' ? 'checked' : '' ) + ' id="feed-enabled-' + uid + '" class="switcher" type="checkbox" name="feed-enabled-' + uid + '" value="yep"><div><div></div></div></label></td>' +
					  '</tr>';
				
				
				  if ( isChanged ) {
					  console.log( feed )
				  }
			  });
			
		  } else {
			  feedsListStr = '<tr><td  class="empty-cell" colspan="6">Please add at least one feed</td></tr>';
		  }
		
		  this.$el.find('table.feeds-list tbody').html( feedsListStr );
		  
		  this.addFeedErrors();
	  },
	
	  renderFilters: function( uid ){
	    
          var $excludeList = $('.filter-labels[data-type="exclude"]');
          var $includeList = $('.filter-labels[data-type="include"]');
          var exclude = this.model.get('feeds')[uid]['filter-by-words'];
          var include = this.model.get('feeds')[uid]['include'];

          if (exclude == undefined) exclude = '';
          if (include == undefined) include = '';

          var excludeArr = exclude == '' ? [] : exclude.split(',');
          var includeArr = include == '' ? [] : include.split(',');

          $excludeList.html('');
          $includeList.html('');

          excludeArr.forEach(function (item, i) {
              var $label =
                  '<li class="filter-label">' + item +
                  '<i data-action="delete-filter" data-id="' + uid + '" data-type="exclude" data-content="' + item + '" class="flaticon-feed_type_user"></i>' +
                  '</li>';
              $excludeList.append($label);
          })

          includeArr.forEach(function (item, i) {
              var $label =
                  '<li class="filter-label">' + item +
                  '<i data-action="delete-filter" data-id="' + uid + '"  data-type="include" data-content="' + item + '" class="flaticon-feed_type_user"></i>' +
                  '</li>';
              $includeList.append($label);
          })
      },

      addFilter: function (e) {
          if (e.which != 13) return;

          var $field = $(e.target);
          var id = $field.data('id');
          var type = $field.data('type');
          var content = $field.val();
          var $list = $('[data-filter-uid="' + id + '"] .filter-labels[data-type="' + type + '"]');
          var $holder = $('[data-filter-uid="' + id + '"] [data-type="filter-' + type + '-holder"]');
          var filters = $holder.val() == "" ? [] : $holder.val().split(',');
          var $label =
              '<li class="filter-label">' + content +
              '<i data-action="delete-filter" data-id="' + id + '" data-type="' + type + '" data-content="' + content + '" class="flaticon-feed_type_user"></i>' +
              '</li>';

          if(filters.indexOf(content) == -1){
              filters.push(content);
              $list.append($label);
              $holder.val(filters.join(','));

              if(type == 'exclude'){
                  this.model.attributes.feeds[id]['filter-by-words'] = filters.join(',');
              }else{
                  this.model.attributes.feeds[id]['include'] = filters.join(',');
              }

              $holder.trigger('change');
          }

          $field.val('');
      },

      deleteFilter: function(e){
          var $el = $(e.target);
          var id = $el.data('id') || $el.closest('.feed-view').data('filter-uid');
          var type = $el.data('type');
          var content = $(e.target).data('content');
          var $label = $(e.target).closest('li');
          var $holder = $('[data-filter-uid="' + id + '"] [data-type="filter-' + type + '-holder"]');
          var filters = $holder.val() == "" ? [] : $holder.val().split(',');

          filters.forEach(function (item, i) {
              if(item == content.replace(/\\/g, '')) filters.splice(i, 1);
          })

          $holder.val(filters.join(','));
          if(type == 'exclude'){
              this.model.attributes.feeds[id]['filter-by-words'] = filters.join(',');
          }else{
              this.model.attributes.feeds[id]['include'] = filters.join(',');
          }

          console.log(this.model.attributes);
          $holder.trigger('change');

          $label.remove();
      },

    toggleFeed: function (e) {
        var $t = $(e.target);
        var $row = $t.closest('[data-uid]');
        var uid = $row.data('uid');
        var type = $row.data( 'network' );
        
        var $view =  this.$popup.find('[data-uid="' + uid + '"]'), $filterView;
        if ( ! $view.length ) {
            $view = $( _.template(templates[ type + 'View'])({
                uid: uid
            }) );
	        $filterView = $( _.template(templates['filterView'])({
		        uid: uid
	        }));
	        this.$el.find( '#feed-views' ).append( $view );
	        this.$el.find( '#filter-views' ).append( $filterView );
        }
        
        // set values
        this.setInputsValue( uid );
        
        var $channeling = $view.children('input:hidden');
        //feeds[uid]['enabled'] = e.target.checked ? 'yep' : 'nope';
        $channeling.val(e.target.checked ? 'yep' : 'nope').trigger( 'change' );
        this.saveViaAjax();
    },

    savePage: function ( page ) {
      sessionStorage.setItem('ff_feeds_page', page );
    },

    addFeedErrors: function () {
		
      var feeds = this.model.get('feeds');
      var self = this;

      self.errorsPresent = false;

      _.each(feeds, function (feed) {
        var errors = feed.errors;
        var id = feed.id;
        var $feed, $error;
	
        // render only current page
	    // CHANGED: render all because of filters
        // if ( feed.page && feed.page != self.currentPage ) return
        
        if ( errors ) {
          if (typeof errors !== 'object') {
            
              try {
                  errors = JSON.parse(errors);
              } catch (e) {
                  console.log( e.message )
              }
          }

          if (!errors.length) return;

          $feed = self.$el.find('tr[data-uid="' + id + '"]');
          $error = $('<span class="cache-status-error"></span>');
          $error.data('err', errors);
          $feed.find('.td-status').html('').append($error).parent().addClass('tr-error');
          self.errorsPresent = true;
        }
      });
    },

    setInputsValue: function ( feedId ) {

      var feeds = this.model.get('feeds');
      var self = this;

      if (typeof feeds !== 'object') feeds = JSON.parse(feeds);
      
      _.each(feeds, function (feed) {
        var uid, name, $input, val;

        uid = feed.id;
	
	    if ( feedId && feedId != uid ) return;
	
	
	      for ( name in feed ) {

          if ( name === 'id' || name === 'type' ) continue;

          $input = self.$el.find('[name="' + uid + '-' + name + '"]');

          if ($input.is(':radio') || $input.is(':checkbox')) {

            $input.each(function(){
              var $t = $( this );
              if ($t.val() == feed[name]) $t.prop('checked', true);
            });

          } else {
            val = feed[name];
            $input.val(val ? stripslashes(val.toString()) : '');
          }
        }
      });
    },

    initPaginator: function () {
	
	    var feeds = this.model.get('feeds');
	
        var $list = this.$el.find('#feeds-list');
        if ( $list.jPages()) $list.jPages('destroy');
	  // todo destroy?
	    var feedsArr = Object.keys( feeds );
	    
      if (feedsArr.length > 8) {
        this.$el.addClass('jp-visible');
      } else {
        this.$el.removeClass('jp-visible');
      }

      return this.$el.find("div.holder").jPages({
			startPage: this.currentPage,
			items: feedsArr,
			containerID : "feeds-list",
			previous : "←",
			next : "→",
			perPage : 8,
			delay : 0,
			animation : 'yes'
      }).data('jPages');
    },
    
    hideError: function (e) {
      var $rel = $(e.relatedTarget);
      if ($rel.is('#error-popup')) return;
      FlowFlow.$errorPopup.removeClass('visible');
    },

    addFeedStepOne: function(e){
      this.$popup.removeClass('add-feed-step-2').addClass('add-feed-step-1');
      FlowFlow.checkScrollbar();
      FlowFlow.setScrollbar();
      FlowFlow.$html.addClass('popup_visible');
      this.$popup.on('click', this.popupClick);
    },

    editFeed: function (e) {
      var $t = $(e.target);
      var uid = $t.closest('[data-uid]').data('uid');
      var $popup = this.$popup;
      var feed = this.model.get('feeds') ? this.model.get('feeds')[uid] : null
	  var network = feed && feed.type;
   
	  var $view = $popup.find('.feed-view[data-uid=' + uid + ']');
	  var $filterView = $popup.find('.feed-view[data-filter-uid=' + uid + ']');
	
	    if ( ! $view.length && ! $filterView.length ) {
	      $view = $( _.template(templates[feed.type + 'View'])({
		      uid: uid
	      }) );
	
	      $filterView = $( _.template(templates['filterView'])({
		      uid: uid
	      }));
	      this.$el.find( '#feed-views' ).append( $view );
	      this.$el.find( '#filter-views' ).append( $filterView );

      }

	  // set values
	  this.setInputsValue( uid );
      
      // popup scroll
      $popup.find('.feed-view-visible').removeClass('feed-view-visible');
      $popup.find('.feed-view[data-uid=' + uid + ']').addClass('feed-view-visible');
      $popup.addClass('add-feed-step-2');

      $popup.find('.feed-popup-controls').hide();
      if (feed && feed.enabled === 'nope') {
        $popup.find('.feed-popup-controls.enable').show();
      } else {
        $popup.find('.feed-popup-controls.edit').show();
      }

      FlowFlow.checkScrollbar();
      FlowFlow.setScrollbar();
      FlowFlow.$html.addClass( 'popup_visible' );
      
      $popup.on( 'click', this.popupClick );
	
	    // add notice about auth
	    var notice = '<div class="ff-notice">Access token is required for %network% feeds, please add on <a href="#ff-auth-tab" class="ff-pseudo-link">AUTH tab</a></div>';
	
	    switch ( network ) {
		    case 'facebook': {
			    if ( ! $( '#facebook_access_token' ).val() ) {
				    $view.addClass( 'auth-required' ).find( 'h1' ).after( notice.replace( '%network%', 'Facebook' ) )
			    }
			    break;
		    }
		    case 'twitter': {
			    if ( ! $( '#oauth_access_token' ).val() ) {
				    $view.addClass( 'auth-required' ).find( 'h1' ).after( notice.replace( '%network%', 'Twitter' ) )
			    }
			    break;
		    }
		    case 'youtube': {
			    if ( ! $( '#google_api_key' ).val() ) {
				    $view.addClass( 'auth-required' ).find( 'h1' ).after( notice.replace( '%network%', 'Youtube' ) )
			    }
			    break;
		    }
		    case 'instagram': {
			    if ( ! $( '#facebook_access_token' ).val() ) {
				    $view.addClass( 'auth-required' ).find( 'h1' ).after( '<div class="ff-notice">If you plan to use official Instagram API by Facebook, access token is required, please add on <a href="#ff-auth-tab" class="ff-pseudo-link">AUTH tab</a></div>' )
			    }
			    break;
		    }
	    }
		
	  // store all input values to revert them on popup closing without save
      
      var initialSettings = {};
	
      $view.find( 'input, select' ).each( function ( i, el ) {
        var $el = $( this );
	
	    if ( $el.is(':radio') ) {
		    if ( $el.is(':checked') ) {
			    initialSettings[ this.name ] = $el.val();
		    }
	    } else if ( $el.is(':checkbox')) {
		    if ( $el.is(':checked') ) {
			    initialSettings[ this.name ] = 'yep';
		    } else {
			    initialSettings[ this.name ] = 'nope';
		    }
	    }
	    else {
		    initialSettings[ this.name ] = $.trim( $el.val() );
	    }
      })
      
      $view.data( 'initialSettings', initialSettings );
      
    },

    popupClick: function (e) {
	    var $t = $(e.target)
        if ($t.is('.popup' )) $('.active .popup .popupclose').trigger( 'click' );
        if ($t.is('.ff-pseudo-link' )) {
            $('.active .popup .popupclose').trigger( 'click' );
            $('#ff-auth-tab').trigger( 'click' );
        }
    },

    filterFeed: function (e) {
		var $t = $(e.target);
		var uid = $t.closest('[data-uid]').attr('data-uid');
		var $popup = this.$popup;
		$popup.find('.feed-view-visible').removeClass('feed-view-visible');
	    var feed = this.model.get('feeds') ? this.model.get('feeds')[uid] : null;
		var network = feed && feed.type;
	
	    var $view = $popup.find('.feed-view[data-uid=' + uid + ']');
		var $filterView = $popup.find('.feed-view[data-filter-uid=' + uid + ']');
		
		if ( ! $view.length && ! $filterView.length ) {
			$view = $( _.template(templates[network + 'View'])({
				uid: uid
			}) );
			
			$filterView = $( _.template(templates['filterView'])({
				uid: uid
			}));
			this.$el.find( '#feed-views' ).append( $view );
			this.$el.find( '#filter-views' ).append( $filterView );
		}
		
		$popup.find('.feed-view[data-filter-uid=' + uid + ']').addClass('feed-view-visible');
		$popup.addClass('add-feed-step-2');
		
		$popup.find('.feed-popup-controls').hide();
		$popup.find('.feed-popup-controls.edit').show();
		FlowFlow.checkScrollbar();
		FlowFlow.setScrollbar();
		FlowFlow.$html.addClass('popup_visible');
		$popup.on('click', this.popupClick);

		this.renderFilters(uid)
    },

    resetFeedCache: function (e) {
      var $t = $(e.target);
      var uid = $t.closest('[data-uid]').attr('data-uid');
      var changed = this.model.get('feeds_changed');
      var feeds = this.model.get('feeds');
      
      changed[ uid ] = _.clone( feeds[ uid ] );
	  changed[ uid ][ 'state' ] = 'reset_cache';
	  
      this.saveViaAjax();
    },

	checkStreams: function (e) {

		var $t = $(e.target);
		var str = '';

		var streams = streamRowModels.models;
		var streamFeeds, found;
		var id = $t.closest('[data-uid]').attr('data-uid');

		//
		for ( var i = 0, len = streams.length; i < len; i++ ) {

			streamFeeds = streams[i].get( 'feeds' );
			found = _.find( streamFeeds, function (feed) {
				return feed.id == id
			})

			if ( found ) {

				if ( str ) str += '<br>'
				str += streams[i].get( 'name' ) + ' #' + streams[i].id
				// reset
				found = '';
			}
		}

		if ( ! str ) str = 'not yet connected to any streams';

		FlowFlow.popup( 'Feed is connected to:<br>' + str , 'neutral', 'alert');
	},

    toggleDropDown: function (e) {
      var self = this;
      var $t = $(e.target);
      var $cont = $t.closest('td');
      var isOpened = $cont.data('popup') === 'opened';

      $('td.open').removeClass('open').data('popup', '');
      if ( isOpened ) {
        $cont.removeClass('open');
        $cont.data('popup', '');
        //FlowFlow.$body.off('click', this.popupMoreClick);
      } else {
        setTimeout(function () {
          $cont.addClass('open');
          $cont.data('popup', 'opened');
          //FlowFlow.$body.on('click', self.popupMoreClick);
        }, 0)
      }
    },

    popupMoreClick: function (e) {
      var $t = $(e.target)
      if (!$t.closest('.feed-dropdown-menu').length) {
        $('td.open').removeClass('open').data('popup', '');
        FlowFlow.$body.off('click', this.popupMoreClick);
      }
    },

    popupLeave: function () {
      $('td.open').removeClass('open').data('popup', '');
    },

    deleteFeed: function (e) {
      var promise = FlowFlow.popup('Do you want to permanently delete this feed?');
      var $t = $( e.currentTarget );
      var self = this;
      promise.then(function success(){
        var uid = $t.closest('[data-uid]').attr('data-uid');
        var modelFeeds = self.model.get('feeds');
        var changed = self.model.get('feeds_changed');

	    changed[ uid ] = _.clone( modelFeeds[ uid ] );
	    changed[ uid ][ 'state' ] = 'deleted';
	    
	    if ( changed[ uid ][ 'boosted' ] === 'yep' ) {
		    FlowFlow.availableBoosts++;
		    FlowFlow.$boostSmartElement.html( FlowFlow.availableBoosts + ' boost' + ( FlowFlow.availableBoosts != 1 ? 's' : '' ) );
        }

        if (self.paginator._items.length - 1 < self.paginator.options.perPage * self.paginator._currentPageNum - (self.paginator.options.perPage - 1) ) {
          self.currentPage = self.paginator._currentPageNum - 1 || 1;
        } else {
          self.currentPage = self.paginator._currentPageNum;
        }
        delete modelFeeds[uid];

        self.saveViaAjax();

      }, function fail () {})
    },

    closeFeedPopup: function (e) {
		FlowFlow.$html.removeClass('popup_visible');
		FlowFlow.resetScrollbar();
		
		var $popup = this.$popup;
		var id;
	    var feeds = this.model.get('feeds');
	    var changed = this.model.get('feeds_changed');
	    
	    var $view = $popup.find('.feed-view-visible');
        
        $popup.off('click', this.popupClick);
        
        var $fresh = $popup.find('.feed-view-dynamic');
        
        if ( $fresh.length && this.feedChanged ) {
        	
            id = $fresh.data('uid');
            
            delete changed[ id ];
            delete feeds[ id ];
            
            $fresh.remove();
            
            if (this.model.get('feeds').length) this.model.get('feeds').pop();
        }
        
        setTimeout(function () {
            $popup.removeClass('add-feed-step-1 add-feed-step-2');
        }, 400);
        
        this.feedChanged = false;
        
        // restore settings and revert model
	
	    var initialSettings = $view.data( 'initialSettings' ) || {};
     
	    $view.find( 'input, select' ).each( function ( i, el ) {
		    var $el = $( this );
		
		    if ( $el.is( ':radio') ) {
			    if ( initialSettings[ this.name ] ==  $el.val() ) {
			        $el.prop( 'checked', true ).trigger( 'change' );
			    }
		    } else if ( $el.is(':checkbox') ) {
		        
		        if ( initialSettings[ this.name ] ==  'yep' ) {
		            $el.prop( 'checked', true ).trigger( 'change' );
		        } else {
			        $el.prop( 'checked', false ).trigger( 'change' );
                }
		    }
		    else {
			    $el.val( initialSettings[ this.name ] ).trigger( 'change' );
		    }

	    })
	
	    $view.data( 'initialSettings', '' );
	    
	    // delete from changed obj
	    
	    delete changed[ $view.data('uid') ];
    },

    showErrorIfPresent: function (e) {

      var $error = $(e.currentTarget).find('.cache-status-error'), errorStr = '';
      if (!$error.length) return;
      var errorData = $error.data('err');

      if (errorData && errorData[0]) {
        errorData = errorData[0];
      } else {
        return;
      }

      var messages = errorData['message'];

      if (messages) {
        if ($.isArray(messages)) {
          for (var i = 0, len = messages.length; i < len;i++) {
            if (i > 0) errorStr += '<br>';

            errorStr += messages[i]['msg'];
          }
        } else if (typeof messages === 'object') {
          if (messages['msg']) {
            errorStr += messages['msg'];
          } else {
            try {
              errorStr += JSON.stringify(messages)
            } catch (e) {
              errorStr += 'Unknown error. Please ask for support <a href="https://social-streams.com/contact/">here</a>'
            }
          }
        } else if (typeof messages === 'string')  {
          errorStr += messages
        }
      } else if (errorData['msg']) {
        errorStr += errorData['msg'];
      } else if  ($.isArray(errorData)) {
        if (errorData[0].msg) {
          errorStr += errorData[0].msg;
        } else {
          try {
            errorStr += JSON.stringify(errorData[0])
          } catch (e) {
            errorStr += 'Unknown error. Please <a href="https://social-streams.com/contact/">submit ticket</a> and send access'
          }
        }
      } else {
        try {
          errorStr += JSON.stringify(errorData)
        } catch (e) {
          errorStr += 'Unknown error. Please <a href="https://social-streams.com/contact/">submit ticket</a> and send access'
        }
      }

      var offset = $error.offset();
      FlowFlow.$errorPopup.addClass('visible').css({top: offset.top - 20, left: offset.left + 30});

      if (errorData.type === 'facebook' && errorStr.indexOf('Application request limit') + 1) {
        errorStr += '. Check <a href="http://docs.social-streams.com/article/133-facebook-app-request-limit-reached" target="_blank">more info</a>'
      }
      else if (errorStr.toLowerCase().indexOf('bad request') + 1) {
          errorStr += '<br><br>Check <a href="https://docs.social-streams.com/article/55-400-bad-request" target="_blank"> info</a>'
      }

      FlowFlow.$errorPopup.html('<h4>App received next error message for this feed:</h4><p>' + errorStr + '</p>')
    },

    goBackToFeedChoice: function (e) {
      var $t = $(e.target);
      var $popup = this.$popup;
      var feeds = this.model.get('feeds');
      var $view = $popup.find('.feed-view-dynamic')
      var uid = $view.data('uid')

      $popup.removeClass('add-feed-step-2').addClass('add-feed-step-1');
      $popup.find('.feed-view-dynamic').remove();

      if (this.feedChanged) {
        delete feeds[uid];
        this.feedChanged = false;
      }
    },

    createFeedView: function (e) {
      var $t = $( e.currentTarget );
      var $popup = this.$popup;
      var network = $t.data('network');
      var fid = FlowFlow.getRandomId();
      var compiled = _.template(templates[network + 'View'])({uid: fid});
      var filterViewCompiled  = _.template(templates['filterView'])({uid: fid});
      var $view = $( compiled );
      var $filterView = $( filterViewCompiled );
      
      var isLockedFeed = network.indexOf( 'gram' ) == -1 && network.indexOf( 'book' ) == -1 && network.indexOf( 'erest' ) == -1 && network.indexOf( 'twi' ) == -1;
	
	  if ( vars.m == 'l' && isLockedFeed ) {
      	return;
      }
      
      if ( vars.m == 'p' && isLockedFeed && !! window.boostsActivated ) { // allow for boost only
	      $view.find( '.boosted-switcher input' ).prop( 'checked', true ).attr( 'disabled', true ).closest('dd').addClass( 'ff-boosted-only' ).prev().addClass( 'ff-boosted-only' );
      }
      
      $popup.find('.feed-view-visible').removeClass('feed-view-visible');
      $view.addClass('feed-view-visible').add($filterView).addClass('feed-view-dynamic').data('fid', fid);
      $popup.removeClass('add-feed-step-1').addClass('add-feed-step-2');
      $popup.find('.feed-popup-controls').hide();
      $popup.find('.networks-content .feed-popup-controls.add').show();
      $popup.find('#feed-views').prepend($view);
      $popup.find('#filter-views').prepend($filterView);
      
      // focus on content field
      $view.find( '[name=' + fid + '-content]' ).focus();
      
      // add notice about auth
      var notice = '<div class="ff-notice">Access token is required for %network% feeds, please add on <a href="#ff-auth-tab" class="ff-pseudo-link">AUTH tab</a></div>';
      
      switch ( network ) {
          case 'facebook': {
              if ( ! $( '#facebook_access_token' ).val() ) {
	              $view.addClass( 'auth-required' ).find( 'h1' ).after( notice.replace( '%network%', 'Facebook' ) )
              }
	          break;
          }
          case 'twitter': {
              if ( ! $( '#oauth_access_token' ).val() ) {
	              $view.addClass( 'auth-required' ).find( 'h1' ).after( notice.replace( '%network%', 'Twitter' ) )
              }
	          break;
          }
          case 'youtube': {
              if ( ! $( '#google_api_key' ).val() ) {
	              $view.addClass( 'auth-required' ).find( 'h1' ).after( notice.replace( '%network%', 'Youtube' ) )
              }
	          break;
          }
	      case 'instagram': {
		      if ( ! $( '#facebook_access_token' ).val() ) {
			      $view.addClass( 'auth-required' ).find( 'h1' ).after( '<div class="ff-notice">If you plan to use official Instagram API by Facebook, access token is required, please add on <a href="#ff-auth-tab" class="ff-pseudo-link">AUTH tab</a></div>' )
		      }
		      break;
	      }
      }

      this.feedChanged = true;
      this.addFeedInModel( $view, fid );

      if ($view.attr('data-feed-type') === 'wordpress') {
        $view.find('input:radio').first().trigger( 'change' );
      }
    },

    updateModel: function ( event, triggeredFromList ) {

      var $t = $( event.currentTarget );
      var val = $t.val();
      var name = $t.attr('name');
      var $group;
      var $view = $t.closest('.feed-view');
      var id = $view.data('uid') || $view.data('filter-uid');
      var isFresh = $view.is('.feed-view-dynamic');
      var modelFeeds = this.model.get('feeds');
      var changed = this.model.get('feeds_changed');
      
      var self = this;
      
      if ( !name ) {
        return;
      }
	
	  var streams, feeds, found;
      var currProp;
      var cancelUpdatingModel = false;
      var cancelBoostChange = false;
      currProp = name.replace(id + '-', '');
      var boostInputChecked;

      if ( currProp == 'boosted' && ! triggeredFromList ) {
         
         boostInputChecked = $t.is(':checked');
         
         if ( ( boostInputChecked && modelFeeds[ id ][ 'boosted' ] == 'yep' ) || ( ! boostInputChecked && modelFeeds[ id ][ 'boosted' ] == 'nope' ) ) {
             // do nothing, it means it was changed programmatically from yep to yep, or nope to nope
         } else {
	         // check if boosts available, checked AND (not number OR less than 1)
	         if ( boostInputChecked && ( ! Number.isInteger( FlowFlow.availableBoosts ) || parseInt( FlowFlow.availableBoosts ) < 1 ) ) {
		
		         FlowFlow.popup( 'No boosts available, please remove from other feeds to free up boosts or upgrade your plan', 'neutral', 'alert' );
		
		         // revert if not available
		         $t.prop( 'checked', false );
		         cancelBoostChange = true;
	         }
	
	         if ( boostInputChecked ) { // set to enable boost on save
		
		         // boosts available UI decreased
		         if ( ! cancelBoostChange ) FlowFlow.availableBoosts--;
		
	         } else { // set to disable boost on save
		
		         streams = streamRowModels.models;
		
		         // alert if try to disable boost for feed in cloud stream
		         for ( var i = 0, len = streams.length; i < len; i++ ) {
			         feeds = streams[ i ].get( 'feeds' );
			         found = _.find( feeds, function ( feed ) {
				         return feed.id == id
			         })
			
			         if ( found ) { // this feed in
				         // show first found
				         var alert = FlowFlow.popup( 'You are about to remove boost from feed that is connected to cloud stream ID #' + streams[ i ].get( 'id' ) + ( streams[ i ].get( 'name' ) ? ' "' + streams[ i ].get( 'name' ) + '"' : '' ) +  '. Cloud streams can\'t contain self-hosted feeds. Please disconnect feed from stream first.', 'neutral', 'alert' );
				
				         $t.prop( 'checked', true );
				
				         cancelUpdatingModel = true;
				         cancelBoostChange = true;
				
				         break;
			         }
		         }
		
		         // because if cancelled, input is set to checked false
		         if ( ! cancelBoostChange ) FlowFlow.availableBoosts++;
	         }
	
	         FlowFlow.$boostSmartElement.html( FlowFlow.availableBoosts + ' boost' + ( FlowFlow.availableBoosts != 1 ? 's' : '' ) );
	
         }
        
      }
	
      if ( ! cancelUpdatingModel ) applyChange ();

      function applyChange () {
	
	      changed[ id ] = _.clone( modelFeeds[ id ] );
	      changed[ id ][ 'state' ] = isFresh ? 'created' : 'changed';
	      
	      if ($t.is(':radio')) {
		      val = $t.is(':checked') ? ($t.attr('value') || 'yep') : 'nope'
	      }
	
	      if ($t.is(':checkbox')) {
		      $group = self.$el.find('[name="' + name + '"]');
		      if ($group.length > 1) {
			      // group
			      val = {};
			      $group.each(function () {
				      var cbVal = this.value;
				      if (this.checked) val[cbVal] = 'yep';
			      });
		      } else {
			      val = $t.is(':checked') ? 'yep' : 'nope'
		      }
	      }
	
	      self.trigger('changeFeedInModel', {name: name.replace(id + '-', ''), val: val, id: id })
      }

    },

    addFeedInModel: function ( $view, id, errors ) {

      var obj = {};
      var modelFeeds = this.model.get('feeds'), freshFeeds;
      
      $view.find(':input').each(function (i, el) {
        var $t = $(this);
        var name = $t.attr('name');
        name = name.replace(/\w\w\d+?\-/, '');
        if ($t.is(':radio')) {
          if ($t.is(':checked')) {
            obj[name] = $t.val();
          }
        } else if ($t.is(':checkbox')) {
          if ($t.is(':checked')) {
            obj[name] = 'yep';
          } else {
            obj[name] = 'nope';
          }
        }
        else {
          obj[name] = $.trim($t.val());
        }
      });

      obj['id'] = id;
      obj['type'] = $view.data('feed-type');
      obj['include'] = $view.parent().next().find('input[name="include"]').val() || '';
      obj['filter-by-words'] = $view.parent().next().find('input[name="filter-by-words"]').val() || '';
      if (errors) obj['errors'] = errors;
      if (modelFeeds) {
        modelFeeds[id] = obj;
      } else {
        freshFeeds = {};
        freshFeeds[id] = obj;
        this.model.set('feeds', freshFeeds);
      }
    },

    validateFeedCfg: function ($t) {
      var $cont = $t.closest('.networks-content').find('.feed-view-visible');
      var $contentInput = $cont.find('input[name$=content]');
      $cont.find(':input').removeClass('validation-error');

      if ($contentInput.length && !$contentInput.val()) {
        setTimeout(function(){$contentInput.addClass('validation-error');},0);
        $('html, body').animate({
          scrollTop: $contentInput.offset().top - 150
        }, 300);
        return false;
      }
      return true;
    },

    toggleErrorFeeds: function (e) {
	    
      var $list = this.$el.find('#feeds-list');
      var $t = $(e.currentTarget);
	    var feeds = this.model.get('feeds');
		var changed = this.model.get('feeds_changed');
	    var feedsArr = Object.keys( feeds );
	
	    
      if ( this.showErrorFeedsOnly ) {
	    $list.find( '.tr-error-empty' ).remove();
        // $list.removeClass('show-only-errors');
        this.showErrorFeedsOnly = false;
        $t.html('Show only error feeds');
	
	    this.renderFeedsList( changed, this.showErrorFeedsOnly );
	      if (feedsArr.length > 8) {
	      	this.$el.addClass('jp-visible');
	      }
      } else {
        // $list.addClass('show-only-errors');
        if ( ! $list.has( '.feed-row:visible' ).length ) {
            // no errors
            $list.append( '<tr class="tr-error tr-error-empty"><td colspan="6">Yay! No error feeds.</td></tr>' );
        }
        $t.html('Show all feeds');
        this.showErrorFeedsOnly = true;
        
        this.renderFeedsList( changed, this.showErrorFeedsOnly );
	
	    this.$el.removeClass('jp-visible');
      }

      return false;
    },
	
	  toggleFilterInput: function ( e ) {
		var $hidden = $('.search-container:hidden');

		if ( $hidden.length ) {
			$hidden.show().find( 'input' ).focus()
		} else {
			$('.search-container').hide()
		}
	  },

	  filterFeedsByName: function ( e ) {

		  var feeds = this.model.get('feeds');
		  var changed = this.model.get('feeds_changed');
		  var feedsArr;
		  var $t = $(e.target);

		  var val = $t.val();

		  if ( val && val.length > 2 ) {
			  // feedsArr = Object.keys( feeds );
			  this.renderFeedsList( changed, this.showErrorFeedsOnly, val );
			  this.$el.removeClass('jp-visible');
		  } else {
			  feedsArr = Object.keys( feeds );
			  this.renderFeedsList( changed, this.showErrorFeedsOnly );
			  if (feedsArr.length > 8) {
				  this.$el.addClass('jp-visible');
			  }
		  }

	  },

    catchEnter: function( event ) {
	
	    var $t = $(event.target);
	    var $view = $t.closest('.feed-view');
		
      if( event.keyCode == 13 && ! $view.is( '.filter-feed' ) ) {
          $( event.target ).closest( '.networks-content' ).find( '.feed-popup-controls:visible .submit-button' ).trigger( 'click' );
      }
    
    },

    saveViaAjax: function ( e, dontSavePage )  {

      if ( e ) e.stopImmediatePropagation();

      var self = this;
      var $t = $( e ? e.target : '' );
      var model = this.model;
      var isNew = model.isNew();
      var uid;
      var feeds = model.get( 'feeds' );
	  var changed = model.get( 'feeds_changed' );
      var $enabled;
	
	  var id, state;

      // validation in popup
      if ( $t.is( '[id^=feed-sbmt]' ) ) {
        if ( !this.validateFeedCfg( $t ) ) return;
      }

      // store page
      if ( !dontSavePage ) this.currentPage = this.paginator._currentPageNum;
      
      uid = this.$el.find('.feed-view-visible').data( 'uid' );

      // catching and broadcasting event from toggler in list
	    // what's this????
      if ( e && uid && feeds[uid] && feeds[uid]['enabled'] === 'nope' ) {
        $enabled = this.$popup.find('[data-uid="' + uid + '"] > input:hidden');
        $enabled.val( 'yep' ).trigger( 'change' );
      }

      var saveFeedsRequest = this.model.save( isNew );
	
	  FlowFlow.makeOverlayTo( 'hide' );
	
	  // we don't wait for response to render UI
	  self.render();
	
	  saveFeedsRequest.done(
	      
	        function ( response ) {
		
		     var feeds = model.get( 'feeds' ); // because model save rewrites feeds property
		     var changed = model.get( 'feeds_changed' );
		     var id;

		     for ( id in changed ) {
		      
			     state = changed[ id ][ 'state' ];
			     
			     if ( response.feeds && ! response.feeds[ id ] ) continue;
			     
			     if ( state && state != 'deleted' ) {
			      
				     if ( response.feeds[ id ][ 'status' ] == 2 ) {
					
					     if ( state === 'created' ) {
						
						     self.$el.find( '.feed-view-dynamic' ).removeClass( 'feed-view-dynamic' );
						     self.currentPage = 1;
						     self.startUpdateCycle( id );
						
					     }
					     else if ( state === 'changed' ) {
						
						     self.startUpdateCycle( id );
						
					     }
					     else if ( state === 'reset_cache' ) {
						
						     self.startUpdateCycle( id );
						
					     }
					     else if ( state === 'deleted' ) { // just delete
						
						     // delete specific feed from changes hash
						     delete changed[ id ];
					     }
					
				     } else { // success 1 or error 0
					
					     // delete specific feed from changed hash because its status resolved
					     delete changed[ id ];
					     
					     // update feed in model for rendering
                         feeds[ id ] = response.feeds[ id ];
					
                         if ( response.feeds[ id ] [ 'status' ] != 1 ) {
	                         console.log( 'feed status saveFeedsRequest', response.feeds[ id ] [ 'status' ] )
                         }
                         
					     FlowFlow.showNotification( ( response.feeds[ id ] [ 'status' ]  == 1  ? 'Yay' : 'Something went wrong' ) + '! Feed "<span>' + ( feeds[ id ].type === 'wordpress' ? ( feeds[ id ]['category-name'] || feeds[ id ]['wordpress-type'] ).toUpperCase() : feeds[ id ][ 'content' ] ).toUpperCase() + '</span>" ' + ( response.feeds[ id ] [ 'status' ]  == 1  ? 'was successfully' : 'was' ) + ' ' + ( state == 'deleted' ? 'deleted' : ( state == 'created' ? 'created' : 'updated' ) ) + ( response.feeds[ id ] [ 'status' ]  == 1  ? '' : ' with errors' ) + '<i class="flaticon-' + ( response.feeds[ id ] [ 'status' ]  == 1  ? feeds[ id ][ 'type' ].toLowerCase() : 'error' )  + '"></i>' );
					
					     self.render();
					
					     FlowFlow.renderBoostsUI( self.model );
					
				     }
				     
                 } else { // deleted feed
			         
			         if ( changed [ id ]) {
				         FlowFlow.showNotification( 'Feed "<span>' + ( changed[ id ].type === 'wordpress' ? ( changed[ id ]['category-name'] || changed[ id ]['wordpress-type'] ).toUpperCase() :  ( changed[ id ][ 'content' ] || '' )).toUpperCase() + '</span>"'  + ' was successfully deleted<i class="flaticon-' + ( changed[ id ] && changed[ id ][ 'type' ] ? changed[ id ][ 'type' ].toLowerCase() + '"' : '' ) + '"></i>' );
                     }
				
				     // delete specific feed from changed hash because feed itself was deleted
				     delete feeds[ id ];
				     delete changed[ id ];
				     
				     self.render();
			         
                 }
			     
			
		     }
	     
	     })
      .fail(
         function ( res ) {
	
	         FlowFlow.showNotification( ( res && res.responseJSON && res.responseJSON.error ? res.responseJSON.error : 'Something went wrong. Please try again or ask support.<i class="flaticon-error">' ) );
	
	         self.render();
	     }
      )
	  
	  // notification banner
	  for ( id in changed ) {
    
        state = changed[ id ][ 'state' ];
    
        if ( state !== 'deleted' ) {
       	
	        FlowFlow.showNotification( 'Getting data for "<span>' + ( feeds[ id ].type === 'wordpress' ? ( feeds[ id ]['category-name'] || feeds[ id ]['wordpress-type'] ) : feeds[ id ][ 'content' ] ).toUpperCase() + '</span>" in background, you\'ll be notified when resolved<i class="flaticon-' + feeds[ id ][ 'type' ].toLowerCase()  + '"></i>' );
        }
        
	  }

      return saveFeedsRequest;
    }
  });

  // expand/collapse section module

  var sectionExpandCollapse = (function($) {

    if (!window.Backbone) return {init: function(){}}

    var storage = window.localStorage && JSON.parse(localStorage.getItem('ff_sections')) || {};

    var SectionsModel = Backbone.Model.extend({
      initialize: function() {
        if (storage[this.id]) {
          this.set('collapsed', storage[this.id]['collapsed']);
        } else {
          storage[this.id] = {collapsed: {}}
        }
        this.on('change', function(){
          if (window.localStorage) {
            storage[this.id]['collapsed'] = this.get('collapsed');
            window.localStorage.setItem('ff_sections', JSON.stringify(storage))
          }
        })
      },
      defaults : function () {
        return {
          'collapsed' : {}
        }
      }
    });

    var SectionsView =  Backbone.View.extend({
      initialize: function() {
        this.model.view = this;
        this.$sections = this.$el.find('> .section');
        this.render();
      },
      render: function(){
        var self = this;
        // add class if collapsed
        self.$sections.each(function(){
          var $t = $(this);
          var index = self.$sections.index(this);
          $t.append('<span class="section__toggle flaticon-arrow-down"></span>');

          if (self.model.get('collapsed')[index]) $t.addClass('section--collapsed');
        })
      },
      events: {
        "click .section__toggle": "toggle"
      },
      toggle: function (e) {
        console.log('Voi la!');
        var $section = $(e.target).closest('.section');
        var isCollapsed = $section.is('.section--collapsed');
        var index = this.$sections.index($section);
        var collapsed = _.clone(this.model.get('collapsed'));

        if (isCollapsed) {
          $section.removeClass('section--collapsed');
          collapsed[index] = 0;
        } else {
          $section.addClass('section--collapsed');
          collapsed[index] = 1;
        }
        this.model.set('collapsed', collapsed);

        $(document).trigger('section-toggle', this.model.get('id'));
      },
      $sections: null
    });

    var globalDefaults = {

    };

    function init (opts) {
      var settings = $.extend(globalDefaults, opts);

      var model = new SectionsModel(settings);
      var view = new SectionsView({model: model, el: settings.$element});

      return view;
    }

    return {
      init: init
    };
  })( jQuery )

  // global shortcuts
  window.sectionExpandCollapse = sectionExpandCollapse;

  return {
    'init' : function () {
      var self = this;
      var controller = FlowFlow.init.apply( FlowFlow, arguments);

      self.init = function(){return self}
      return self;
    },
    'FlowFlow' : FlowFlow,
    'Model' : {
      'StreamRow' : {
        'collection' : streamRowModels,
        'Class' : StreamRowModel
      },
      'Stream' : {
        'collection' : streamModels,
        'Class' : StreamModel
      },
      'Feeds' : {
        'collection' : feedsModel,
        'Class' : FeedsModel
      }
    },
    'View' : {
      'StreamRow' : {
        'Class' : StreamRowView
      },
      'Stream' : {
        'Class' : StreamView
      },
      'Feeds' : {
        'view' : feedsView
      }
    },
    sectionExpandCollapse : sectionExpandCollapse,
    popup: FlowFlow.popup
  }
})(jQuery)

jQuery(document).on('html_ready', function(){
  var app = FlowFlowApp.init();
  // legacy, compatibility, todo change in add-on
  window.confirmPopup = app.FlowFlow.popup;
});

function capitaliseFirstLetter (string)
{
  return string ? string.charAt(0).toUpperCase() + string.slice(1) : '';
}


function stripslashes (str) {
  if ( !str ) return;
  str = str.replace(/\\'/g, '\'');
  str = str.replace(/\\"/g, '"');
  str = str.replace(/\\0/g, '\0');
  str = str.replace(/\\\\/g, '\\');
  return str;
}

function stripSlashesObjProps (obj) {
  for (var prop in obj) {
    if (typeof obj[prop] === 'string') obj[prop] = stripslashes(obj[prop]);
  }
  return obj
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars;
}
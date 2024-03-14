/* globals page_info: false */
;( function( $ ) {
	if ( 'undefined' === typeof page_info ) {
		return false;
	}

	/**
	 * Track Course time.
	 */
	var Tracker = function() {
		this.post_id   = page_info.post_id; 
		this.course_id = page_info.course_id;
		this.security  = page_info.security;
		this.ajax_url  = page_info.ajax_url;
		this.frequency = page_info.frequency;
		this.time     = 0; // This is for tracking time during unexpected exists.( onpageunload ).
	
		$( window ).on( 'beforeunload', { tracker: this }, this.unscheduledUpdate );
	};

	Tracker.prototype.startTimer = function() {
		var self = this;
		this.timer = setInterval( function() {
			self.time++;
		}, 1000 );
	};
	Tracker.prototype.resetTimer = function() {
		clearInterval( this.timer );
		this.time = 0;
	};

	Tracker.prototype.scheduleUpdate = function() {
		var self = this;
		setInterval( function() {
			self.sendUpdate( self.time );
		}, 1000 * this.frequency );
	};

	Tracker.prototype.unscheduledUpdate = function( evnt ) {
		var tracker = evnt.data.tracker;
		tracker.sendUpdate( tracker.time );
	};

	Tracker.prototype.sendUpdate = function( spent_time ) {
		var self = this;
		jQuery.post(
			this.ajax_url,
			{
				'action'    : 'add_time_entry',
				'post_id'   : this.post_id,
				'course_id' : this.course_id,
				'security'  : this.security,
				'time_spent': spent_time
			}
		).done( function( result ) {
			self.resetTimer();
			self.startTimer();
		} );
	};

	$( document ).ready( function() {
		const tracker = new Tracker();
		tracker.startTimer();
		tracker.scheduleUpdate();
	} );
})( jQuery );
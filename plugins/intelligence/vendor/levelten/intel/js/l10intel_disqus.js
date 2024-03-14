var _l10iq = _l10iq || [];

function L10iDisqus(_ioq) {
    var ioq = _ioq;
    var io = _ioq.io;
    this.apiUrl = 'https://disqus.com/api/3.0/';

    io('log', "l10iDisqusTracker.init()");

  this.triggerComment = function (comment) {
    var action = "Disqus", ga_event, json_data, json_params, timestamp, commentSubmit;
    if (comment.text.length > 40) {
      action = action + ": " + comment.text.substring(0, 35) + '...';
    }
    else {
      action = action + ": " + comment.text;
    }
    ga_event = {
	  'eventCategory': "Comment!",
	  'eventAction': action,
      'eventLabel': window.location.pathname.substring(1) + "#comment-" + comment.id,
      'eventValue': io('get', 'config.scorings.events.disqus_comment', 0),
  	  'nonInteraction': false
    };
    io('event', ga_event);

    timestamp = io('getTime');
    commentSubmit = io('mergeVarEventContext', ga_event);
    commentSubmit.id = comment.id;
    commentSubmit.text = comment.text;
    commentSubmit.type = 'disqus';
    commentSubmit.submitted = timestamp;

    json_data = {
      'value': commentSubmit,
      'valuemeta': {'_updated': timestamp},
      'return': {commentid: comment.id, keys: timestamp, type: 'disqus'}
    };
    json_params = {
      'keys': timestamp,
      'type': 'session',
      'namespace': 'commentSubmit'
    };

    io('triggerCallbacks', 'saveCommentSubmitAlter', json_params, json_data, {}, {});
    io('getJSON', 'var/merge', json_params, json_data, function (data) { io('disqus:submitComment', data); });
  };
  
  this.submitComment = function submitComment(data) {
    io('triggerCallbacks', 'saveCommentSubmitPostSubmit', data['return']);
  };
}

_l10iq.push(['providePlugin', 'disqus', L10iDisqus, {}]);

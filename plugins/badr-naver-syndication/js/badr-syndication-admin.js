//var badrSyndication = badrSyndication || {};// set in naver-syndication-admin.php, wp_localize_script

jQuery( document ).ready(function( $ ){

	badrSyndication.admin = {
		
		htError : {
			'000' : 'OK',
			'024' : '인증 실패하였습니다.',
			'028' : 'OAuth Header 가 없습니다.',
			'029' : '요청한 Authorization값을 확인할 수 없습니다.',
			'030' : 'https 프로토콜로 요청해주세요.',
			'061' : '잘못된 형식의 호출 URL입니다.',
			'063' : '잘못된 형식의 인코딩 문자입니다.',
			'071' : '지원하지 않는 리턴 포맷입니다.',
			'120' : '전송된 내용이 없습니다. (ping_url 필요)',
			'121' : '유효하지 않은 parameter가 전달되었습니다.',
			'122' : '등록되지 않은 사이트 입니다.',
			'123' : '1일 전송 횟수를 초과하였습니다.',
			'130' : '서버 내부의 오류입니다. 재시도 해주세요.',
			'999' : '액세스 토큰값이 없습니다.',
			'998' : '생성된 신디케이션 문서가 정해진 포맷에 맞지 않습니다.',
			'997' : '네이버신디케이션API서버로 부터 수신된 문서를 파싱하는데 문제가 있습니다.',
			'996' : '정합성 체크를 위해서는 DOM, libxml 설치가 필요합니다.'
		},
		elLoadingImg: '<img src="' + badrSyndication.plugin_url + 'img/loadingAnimation.gif" width="208" />',
		nCurrentPage: 1,
			
	  init: function( elButton ) {
			this.wlButton = $( elButton ).on( 'click', $.proxy(this.setEvent, this) );
	  },
	  // 리턴되는 메시지가 문장일 경우 그대로 출력
	  dispMessage: function( result, b ) {
		if ( result.match(/^[\d]{3}$/gi) ) result = this.htError[result];
		 this.wlResult.html( result );
		 this.wlButton.prop("disabled", b);
	},
	
	  setEvent: function( e ) {
			e.preventDefault();
			if( !this.checkInput() ) return;
			var el = e.target || e.srcElement;
		  	if( typeof( this[el.name] ) == 'function' ) this[el.name]( el );
	  },

	  configCheck: function( el ) {
		  var self = this, link = badrSyndication.ajax_url + '?action=' + el.name;
		  this.wlResult = $('#configCheckResult');
		  if( $('#configCheckValidator').prop( 'checked') ) link += '&dovalidate=1';
		  $.ajax( {
			  url:link,  //badrSyndicationAdmin::sendPagePing 에 전달되는 url에 ping_url파라미터가 없을 경우 포스트리스트를 받아온다
			  beforeSend: function() { 
				  self.wlButton.prop('disabled', true);
				  self.wlResult.css('display', 'block');
				  self.setLeaveFlag(true);
			  }
		  })
		  .done(function( result ){
			  //self.wlButton.prop('disabled', false);
			  //self.dispMessage( result );
			  self.sendPagePing( link, result );
		  });
	  },

	  /**
	   * badrSyndicationAdmin::sendResetPing api.badr.kr 에서 인덱스된 리스트를 받아온다.
	   * @since 0.8 url admin-ajax.php?action=sendResetPing
	   * @param el object Element object 
	   */
	  getIndexed: function( el ) {
		  var self = this, link = badrSyndication.ajax_url + '?action=' + el.name;
		  this.wlResult = $('#getIndexedResult');
		  $.ajax( {
			  url:link, 
			  beforeSend: function() { 
				  self.wlButton.prop('disabled', true);
				  self.wlResult.html(self.elLoadingImg).css('display', 'block');
			  }
		  })
		  .done(function( result ){
			  if(parseInt(result) > 1) {
				  self.indexedTotalPage =  parseInt(result / 100 + 1);
				  self.getIndexedProcSave( link, result );
			  } else {
				  self.wlResult.html('수신된 목록이 없습니다');
			  }
		  });
	  },

	  getIndexedProcSave: function( link ) {
			if (this.nCurrentPage > this.indexedTotalPage) {
				this.wlButton.prop("disabled", false);
				this.wlResult.html('done');
				return;
			}
			this.wlResult.html(this.nCurrentPage + '페이지를 저장하고 있습니다...');
			this.sendIndexDelPing(this.nCurrentPage);
			
			this.nCurrentPage += 1;
			link += '&start=' + this.nCurrentPage;		

			//this.setAjaxContent('sending ping for ' + ping_url);
			var self = this;
			$.ajax( link )
			.done(
				function() {
					self.getIndexedProcSave(link);
				});
			//this.nCurrentPage = 1; //post ping을 위해서 초기화
	  },
	   
	  sendIndexDelPing: function( page ) {
		  var self = this, link = badrSyndication.ajax_url + '?action=getIndexed&page=' + page;
			$.ajax({
				url: link,
				async: false
			})
			.done(
				function( result ) {
					if( result == '000') self.wlResult.html(this.nCurrentPage + '페이지를 저장하고 있습니다...');
					else self.wlResult.html(self.htError[result]);
				});
	  },
	
	  setLeaveFlag: function( b ) {
		  this.ajax_doing = b;  
	  },
	//badrSyndicationAdmin::sendPagePing 에 전달되는 url에 ping_url파라미터가 없을 경우 posts count를 받아온다
	  sendPagePing: function( url, result ) {
			var totalpages = parseInt(JSON.parse(result).pages);
			if ( totalpages < 1 ) {
				this.dispMessage('연동문서목록이 없습니다.', false);
				this.setLeaveFlag(false);
				return;
			}
			this.wlResult.html('');
			this.sendPostPing( totalpages, url );
			this.nCurrentPage = 1; //reset ping을 위해서 초기화
	  },
	
	//badrSyndicationAdmin::sendPagePing 에 전달되는 url에 ping_url파라미터를 전달하여 핑을 발송하고 리턴메세지를 출력한다.
	  sendPostPing: function( total, url ) {
		  
			if (this.nCurrentPage > total) {
				//this.wlButton.prop("disabled", false);
				this.setLeaveFlag(false);
				//document.location.reload();
				return;
			}
			
			var self = this, page = 'page-' + this.nCurrentPage + '.xml';
			//this.setAjaxContent('sending ping for ' + ping_url);
			$.ajax( { 
				url: url + '&ping_id=' + page, 
				beforeSend: function() {
					self.wlResult.append('<p class="' + self.nCurrentPage + '">' + page + ' : <span>' + self.elLoadingImg + '</span></p>');
				}
			})
			.done( function(result) {
				result = JSON.parse(result);
				var wlMessage = self.wlResult.find('p.' + self.nCurrentPage).html('<a href="' + result.ping_url + '" target="blank">'+ page + '</a> : <span />');
				wlMessage.find('span').html(self.htError[result.code]).append(result.message); 
				self.nCurrentPage += 1;
				self.sendPostPing(total, url);

			});
			
	  },
	 
	  checkInput: function() {
		  var b = true;
		  $('input[name*=syndi]').each(function(i,v) {
			  if(v.value != '') return true;
			  alert('입력항목 확인후 저장해 주세요.');
			  v.focus();
			  b= false;
		  });
		  return b;
	  }
	};

	badrSyndication.admin.init('p.syndication_ajax input[type="button"]');
	
  $('input[name="post_category[]"]').change(function(){
  	var checked_categories = $('input[name="post_category[]"]:checked').map(function(){ return this.value; }).get();
  	$('#except_category').val(checked_categories.join(','));
  });
 
  var wlUpdateMessage = $('#updatemessage');
  if( !wlUpdateMessage.prop('hidden') ) setTimeout(function(){wlUpdateMessage.hide('slow');}, 3000);

  window.onbeforeunload = function(){
	    if(badrSyndication.admin.ajax_doing){
	        return 'AJAX가 실행중입니다.';
	   }
	};
});
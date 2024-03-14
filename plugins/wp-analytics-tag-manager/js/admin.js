jQuery( function( $ ) {
	/**
	 * 除外ホスト名一覧追加
	 */
	$('#hostBtn').click(function() {
		console.log("host");
		var hostname = $('#currentHost').text();
		if (hostname == "") {
			return;
		}
		var hostnameList = $('#hostTextArea').val();
		var checkList = hostnameList.split("\n");
		for (var i=0;i<checkList.length;i++) {
			if (checkList[i] == hostname) {
				return;
			}
		}
		if (hostnameList != "") {
			hostnameList += '\n';
		}
		hostnameList += hostname;
		$('#hostTextArea').val(hostnameList);
	});
	/**
	 * 除外IPアドレス一覧追加
	 */
	$('#ipBtn').click(function() {
		console.log("ip");
		var ipaddr = $('#currentIP').text();
		if (ipaddr == "") {
			return;
		}
		var ipList = $('#ipTextArea').val();
		var checkList = ipList.split("\n");
		for (var i=0;i<checkList.length;i++) {
			if (checkList[i] == ipaddr) {
				return;
			}
		}
		if (ipList != "") {
			ipList += '\n';
		}				
		ipList += ipaddr;
		$('#ipTextArea').val(ipList);
	});
	/**
	 * 固定ページセレクト
	 */
	$('#wp_ana_tag_page_post_id').mousedown(function() {
		console.log('sel sel');
		$('#output_page2').prop('checked', true);
	});	
	
	
} );
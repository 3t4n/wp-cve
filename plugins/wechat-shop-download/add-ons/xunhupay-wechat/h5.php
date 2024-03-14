	<html>
	<head>
	<meta charset="UTF-8">
	<title>收银台付款</title>
	<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="https://www.mac169.com/wp-content/plugins/wechat-shop/add-ons/xunhupay-wechat/assets/style.css">
	</head>
	<body ontouchstart="" class="bggrey">
		<div class="xh-title"><img src="https://api.xunhupay.com/content/images/wechat-s.png" alt="" style="vertical-align: middle"> 微信支付收银台</div>

			<div class="xhpay ">
			   <img class="logo" alt="" src="https://www.mac169.com/wp-content/plugins/wechat-shop/assets/image/wechat.png">

				<span class="price"></span>
			</div>
			<div class="xhpaybt">
				<div id="weixin-notice">支付中。。。</div>
			</div>
			<div class="xhtext" align="center">支付完成后，如需售后服务请联系客服</div>
			<div class="xhfooter" align="center">迅虎网络提供技术支持</div>
			<script type="text/javascript">
			 var url;
				url = window.location.search;
				url = url.substring(url.lastIndexOf('=') + 1, url.length);
				url=decodeURIComponent(url); /* /index.html */
				window.location.href = url;
			    </script>
	</body>
	</html>
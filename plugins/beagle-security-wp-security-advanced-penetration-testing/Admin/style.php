<?php
include sanitize_file_name('bootstrap.php');
?>


<style>

	.font-monte {
		font-family: 'Montserrat', sans-serif;
	}

	.header {
		width: 100%;
		margin: 4rem auto 1rem auto;
		justify-content: center !important;
		display: flex;
		overflow-x: hidden;
		overflow-y: auto;
	}

	.header a {
		text-decoration: none;
	}

	.header a img {
		outline: none;
	}

	.header img {
		width: 200px;
		height: auto;
	}

	.errormsg p {
		padding: 1rem;
		color: #fff;
		position: fixed;
		background-color: red;
		top: 3rem;
		right: 0px;
		font-size: 14px;
		font-family: "Georgia";
		font-weight: 500;
		width: 300px;
		z-index: 999;
	}

	.message p {
		padding: 1rem;
		color: #fff;
		background-color: green;
		position: fixed;
		top: 3rem;
		right: 0px;
		font-size: 14px;
		font-family: "Georgia";
		font-weight: 500;
		width: 300px;
		z-index: 999;
	}

	.input-form {
		width: 25rem;
		margin: 2rem auto;
		justify-content: center !important;
		display: block;
		padding: 1rem;
	}

	.help {
		padding-top: 1rem;
		font-size: 12px;
		color: #006eeb;
		text-decoration: none;
	}

	.help a {
		text-decoration: none;
		outline: none !important;
	}

	.help a:focus {
		text-decoration: none;
		outline: none !important;
	}

	.label {
		font-size: 14px;
		color: #676767;
		margin: 24px 0 10px 0px;
		font-weight: 400;
	}

	.form-control {
		display: flex;
		width: 100%;
		border-radius: 4px;
		padding: 0.5rem 1rem !important;
		margin-top: 10px;
		font-size: 14px;
		height: 50px;
		background-color: #f5f5f5;
		border: 1px solid #cfd8dc;
	}

	.access {
		margin-bottom: 24px !important;
	}

	.form-group {
		display: block;
		width: 100%;
		margin: auto;
	}

	.appdiv {
		margin-bottom: 46px;
	}

	.startBtn {
		background-color: #14bc53;
		border: 1px solid #1fca5f;
		border-radius: 4px;
		padding: 12px 16px;
		color: #fff;
		font-size: 16px;
		font-weight: 600;
		width: 100%;
		position: relative;
		display: flex;
		justify-content: center;
		align-items: center;
		transition: all .2s ease-in-out;
		cursor: pointer;
	}

	.startBtn:focus {
		outline: transparent;
	}

	.container {
		padding: 7px;
		overflow-x: hidden;
		margin-top: 52px;
		width: 50rem;
		background-color: #f2f4f8;
		border-radius: 12px;
		box-shadow: 0px 1px 4px 0px #d2d2d2;
		padding-bottom: 2rem;
	}

	.head-main {
		height: 135px;
		background-color: #ffffff;
		border-radius: 8px;
		box-shadow: 0px 2px 3px 0px #d0d0d0;
		padding: 2rem;
	}

	.head-block {
		padding: auto 24px !important;
	}

	.titledomain {
		font-size: 18px;
		color: #3d3d3d;
		line-height: 25px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsi
	}

	.urldomain {
		font-size: 14px;
		color: #006eeb;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsi
	}

	.app-image {
		max-width: 50px;
	}

	.delete-icon {
		height: 36px;
		width: 36px;
		background-color: #e9e9e9;
		border-radius: 50%;
		padding: 8px 4px;
		color: #eb0000;
		cursor: pointer;
	}

	.delete-icon:hover {
		box-shadow: 0px 1px 6px 0px;
	}

	.delete-icon-hide {
		height: 36px;
		width: 36px;
		background-color: #e9e9e9;
		border-radius: 50%;
		padding: 8px 4px;
		color: #a0a0a0;
		cursor: pointer;
	}

	.verify-icon {
		height: 16px;
		width: 16px;
		border: 1px solid #1cd160;
		border-radius: 50%;
		border-radius: 50%;
		color: #1cd160;
		font-size: 12px;
		margin-left: 10px !important;
		margin-top: 3px !important;
		padding: 1px  !important;
	}

	.close-icon {
		height: 36px;
		width: 36px;
		border-radius: 50%;
		padding: 6px 4px;
		color: #eb0000;
		border: 2px solid #eb0000;
	}

	.errorMsg {
		font-family: 'Montserrat';
		font-size: 12px;
		color: #eb0000;
		margin-left: 20px;
	}

	.infoicon {
		color: #3fa9f5;
		cursor: pointer;
		margin-left: 8px;
	}

	.btn {
		height: 52px;
		width: 168px;
		border-radius: 8px;
		color: #fff;
	}

	.start-test {
		background-color: #006eeb;
	}

	.hidden-test {
		background-color: #006eeb;
		opacity: 0.5;
	}

	.stop-test {
		background-color: #eb0000;
	}

	.btn:hover {
		color: #fff;
		font-weight: 500;
	}

	.verify-domain {
		height: 32px;
		background-color: #e6f4ff;
		border: 1px solid #d7edff;
		border-radius: 4px;
		color: #1f1f1f !important;
		margin-left: 20px;
		font-size: 12px;
		width: 135px;
	}

	.verify-domain:hover {
		box-shadow: 0px 1px 6px 0px #006eeb;
	}

	.verify-domain:focus {
		outline: transparent;
	}

	.test-box {
		padding: 1.5rem;
	}

	.status-box {
		padding-left: 14px;
	}

	.ststustest {
		text-transform: uppercase;
		font-family: 'Montserrat';
		font-size: 14px;
		color: #676767;
	}

	.test-msg {
		font-family: 'Montserrat';
		font-size: 14px;
		color: #1f1f1f;
	}

	.reload-icon {
		cursor: pointer;
		height: 30px;
		width: 30px;
		background-color: #ffffff;
		font-size: 20px;
		color: #006eeb;
		border-radius: 50%;
		padding: 5px;
	}

	.score {
		font-size: 14px;
	}

	.progressdiv {
		width: 90%;
	}

	.progress {
		background-color: #dfe4ef;
	}

	.progress-bar {
		background-color: #006eeb;
	}

	.progress,
	.progress-bar {
		font-size: 10px;
		border-radius: 10px;
	}

	.reloadicon {
		width: 10%;
	}

	.critical {
		background-color: #F24747;
		border: 1px solid #F24747;
		border-radius: 8px 0px 0px 8px;
		padding-top: 14px;
		padding-bottom: 12px;
	}

	.high {
		background-color: #EE9336;
		border: 1px solid #EE9336;
		padding-top: 14px;
		padding-bottom: 12px;
	}

	.medium {
		background-color: #FDC431;
		border: 1px solid #FDC431;
		padding-top: 14px;
		padding-bottom: 12px;
	}

	.low {
		background-color: #4CAE4C;
		border: 1px solid #4CAE4C;
		padding-top: 14px;
		padding-bottom: 12px;
	}

	.verylow {
		background-color: #357ABD;
		border: 1px solid #357ABD;
		padding-top: 14px;
		padding-bottom: 12px;
	}

	.total {
		background-color: #ffffff;
		border: 1px solid #DFE4EF;
		border-radius: 0px 8px 8px 0px;
		padding-top: 14px;
		padding-bottom: 12px;
	}

	.total .countnumber {
		color: #000;
		font-weight: 500;
		font-size: 24px;
		line-height: 0.95;
	}

	.countnumber {
		color: #fff;
		font-weight: 500;
		font-size: 24px;
		line-height: 0.95;
	}

	.total .counttext {
		font-size: 12px;
		padding-top: 8px;
		color: #006eeb;
	}

	.counttext {
		font-size: 12px;
		padding-top: 5px;
		color: #fff;
	}

	.startVerify {
		display: none;
	}

	.spinner-border {
		width: 20px;
		height: 20px;
		margin: auto;
	}

	.resultblock {
		display: none;
	}

	.loadingRe {
		display: flex;
		cursor: pointer;
		height: 30px;
		width: 30px;
		background-color: #ffffff;
		font-size: 20px;
		color: #006eeb;
		border-radius: 50%;
		padding: 5px;
	}

	.gendate {
		font-size: 12px;
		color: #1f1f1f;
	}

	.gentxt {
		font-size: 14px;
		color: #1f1f1f;
	}

	.gentxt span {
		font-size: 12px;
		color: #676767;
	}

	.goto {
		font-size: 12px;
	}

	.goto a {
		text-decoration: none;
	}

	.loader {
		border: 5px solid transparent;
		background-color: transparent;
		border-radius: 50%;
		border-top: 3px solid #006eeb;
		width: 20px;
		height: 20px;
		-webkit-animation: spin 2s linear infinite;
		/* Safari */
		animation: spin 2s linear infinite;
	}

	.loaderFirst {
		border: 5px solid transparent;
		background-color: transparent;
		border-radius: 50%;
		border-top: 5px solid #fff;
		width: 20px;
		height: 20px;
		-webkit-animation: spin 2s linear infinite;
		/* Safari */
		animation: spin 2s linear infinite;
	}

	/* Safari */
	@-webkit-keyframes spin {
		0% {
			-webkit-transform: rotate(0deg);
		}

		100% {
			-webkit-transform: rotate(360deg);
		}
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}

	/* cyrillic-ext */
	@font-face {
		font-family: 'Montserrat';
		font-style: normal;
		font-weight: 400;
		src: url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459WRhyzbi.woff2) format('woff2');
		unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
	}

	/* cyrillic */
	@font-face {
		font-family: 'Montserrat';
		font-style: normal;
		font-weight: 400;
		src: url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459W1hyzbi.woff2) format('woff2');
		unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
	}

	/* vietnamese */
	@font-face {
		font-family: 'Montserrat';
		font-style: normal;
		font-weight: 400;
		src: url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459WZhyzbi.woff2) format('woff2');
		unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
	}

	/* latin-ext */
	@font-face {
		font-family: 'Montserrat';
		font-style: normal;
		font-weight: 400;
		src: url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459Wdhyzbi.woff2) format('woff2');
		unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
	}

	/* latin */
	@font-face {
		font-family: 'Montserrat';
		font-style: normal;
		font-weight: 400;
		src: url(https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459Wlhyw.woff2) format('woff2');
		unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
	}


	.application-image {
		margin: auto;
		width: 50px;
		height: 50px;
		background-size: 50px 50px;
		background-repeat: no-repeat;
		background-image: url('data:image/png;base64,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');
	}
</style>
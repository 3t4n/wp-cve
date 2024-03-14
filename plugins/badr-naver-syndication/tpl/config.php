<div class="wrap">
	<h2>네이버 신디케이션 V2</h2>
	<div id="updatemessage" class="updated fade" style="display: <?php echo $bResult ? 'block' : 'none'?>;"><p>설정이 업데이트 되었습니다.</p></div>
	<div class="postbox-container" style="width:60%;">
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="gasettings" class="postbox">
           			<div class="handlediv" title="Click to toggle"><br/></div>
            		<h3 class="hndle"><span>설정</span></h3>
					<div class="inside">
						<form method="post">
						<table class="form-table">
							<tbody>
								<tr>
									<th scope="row"><span>연동키</span></th>
									<td>
										<input type="text" name="syndi_key" class="large-text" value="<?php echo isset($this->_aOptions['key']) ? $this->_aOptions['key'] : ''?>" title="연동키" />
										<p><a href="http://webmastertool.naver.com/index.naver" target="blank">네이버 웹마스터 도구</a>에서 발급받은 연동키를 입력하세요.</p>
									</td>
								</tr>
								<tr class="even">
									<th scope="row"><span>관리자</span></th>
									<td>
										<input type="text" name="syndi_name" value="<?php echo isset($this->_aOptions['name']) ? $this->_aOptions['name'] : ''?>"  />
										<p>사이트 관리자나 회사명, 저작권자의 이름을 입력하세요.</p>
									</td>
								</tr>
								<tr>
									<th scope="row"><span>관리자 이메일</span></th>
									<td>
										<input type="text" name="syndi_email" value="<?php echo isset($this->_aOptions['email']) ? $this->_aOptions['email'] : get_option('admin_email', false)?>" />
									</td>
								</tr>
								<tr class="even">
									<th scope="row"><span>제외할 카테고리</span></th>
									<td>
								    	<div style="border-color:#CEE1EF; border-style:solid; border-width:2px; height:10em; margin:5px 0px 5px 0px; overflow:auto; padding:0.5em 0.5em; background-color:#fff;">
								    	<ul>
								    	<?php wp_category_checklist( 0, 0, $aExCategory );?>
								    	</ul>
								   		</div>
								   		<input type="hidden" value="<?php echo isset($this->_aOptions['except_category']) ? $this->_aOptions['except_category'] : ''?>" name="except_category" id="except_category" />
								   	</td>
							   	</tr>
							   	<tr>
									<th scope="row"><span>확장 파라미터 사용</span></th>
									<td><input type="checkbox" name="useExParameter" id="useExParameter" <?php echo !empty($this->_aOptions['use_ex_parameter']) ? 'checked' : ''?>/>	<label for="useExParameter">확장 파라미터를 사용합니다.</label>
									</td>
								</tr>
								<tr>
								<td colspan="2">
										<p>확장파라미터는 포스트의 url을 "domain.com/?p={포스트아이디}&s={연동날짜}"로 나타냅니다. 향후 로그분석에 이용될 수도 있습니다만, 일부 플러그인과 충돌로 인하여 404(페이지를 찾을수 없음)에러를 낼수도 있습니다.</p>
								 </td>
							   	</tr>
								<tr>
									<td colspan="2">
								    	<div class="alignright"><input type="submit" class="button-primary" name="submit" value="설정 저장"></div>
								   	</td>
							   	</tr>
						   	</tbody>
					   	</table>
						</form>
					</div>
				</div>
			</div>

			<div class="meta-box-sortables">
				<div id="gasettings" class="postbox">
           			<div class="handlediv" title="Click to toggle"><br/></div>
            		<h3 class="hndle"><span>동작확인</span></h3>
					<div class="inside">
						<p>
							공개 발행된 문서에 대하여 목록을 생성하고 네이버신디케이션 서버로 핑을 발송한후 응답을 확인합니다. 문제가 없다면 몇시간후 네이버에 색인을 확인 할 수 있습니다.
							<a href="<?php echo 'http://web.search.naver.com/search.naver?where=webkr&query=' . urlencode('site:'.site_url())?>" target="blank">네이버검색결과</a>
						</p>
						
						<p>
							<input type="checkbox" name="configCheckValidator" id="configCheckValidator" />	<label for="configCheckValidator">신디케이션문서가 정해진 형식에 맞는지 정합성 검사를 수행합니다.</label>
						</p>
						<div class="message" id="configCheckResult"><img src="<?php echo plugins_url('../img/loadingAnimation.gif',__FILE__)?>" width="208" /></div>
			    		
			    		<p class="syndication_ajax">
			    			<input type="button" name="configCheck" class="button-primary" value="동작확인 & 문서목록 발송" />
			    		</p>
							

					</div>
				</div>
			</div>			
			
			<div class="meta-box-sortables">
				<div id="gasettings" class="postbox">
           			<div class="handlediv" title="Click to toggle"><br/></div>
            		<h3 class="hndle"><span>신디케이션 색인 확인</span></h3>
					<div class="inside">
					<p>신디케이션API로 색인된 내용을 확인합니다. API문서의 경우 url이 'domain.com/?p=1234&s=20140101000000'의 형식으로 나타납니다. 검색로봇이 직접수집해간 경우, 페이지링크는 퍼마링크가 표시됩니다.</p>
					<p>문서를 연동해제 했다가 다시 연동하는 경우 post meta(url에서 s=20140101000000 값)가 달라지고 같은 문서라도 별도의 네이버 색인이 생성됩니다.</p>
					
			    	<?php 
					  $logTable->prepare_items(); 
					  $logTable->display(); 
					?>
					</div>
				</div>
			</div>	
			
		</div>
	</div>
	
    <div class="postbox-container side" style="width:261px;">
        <div class="metabox-holder">
            <div class="meta-box-sortables">
		        <div id="usefullink" class="postbox">
		            <div class="handlediv" title="Click to toggle"><br/></div>
		            <h3 class="hndle"><span>최근 수집</span></h3>
		            <div class="inside">
						<p>네이버신디케이션API 서버가<?php echo $yeti_visited_time ? ' 문서를 최종 확인한 시각은 
						<span class="message">'.$yeti_visited_time.'</span>입니다.' : ' 아직 방문하지 않았습니다.'?><br /><br /></p>
		            </div>
		        </div>
		        <div id="usefullink" class="postbox">
		            <div class="handlediv" title="Click to toggle"><br/></div>
		            <h3 class="hndle"><span>유용한 링크</span></h3>
		            <div class="inside">
						<ul>
						<li><a href="http://webmastertool.naver.com/index.naver" target="blank">네이버 웹마스터 도구</a></li>
						<li><a href="<?php echo 'http://web.search.naver.com/search.naver?where=webkr&query=' . urlencode('site:'.site_url())?>" target="blank">네이버 사이트검색</a></li>
						<li><a href="http://badr.kr/?p=1164" target="blank">신디케이션 문서 색인 고찰</a></li>
						<li><a href="http://badr.kr/?p=1152" target="blank">피드백</a></li>
						</ul>
						<br />
		            </div>
		        </div>
            </div>
        </div>
     </div>
</div>	
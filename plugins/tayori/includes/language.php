<?php
  $tayori_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
  $tayori_languages = array_reverse($tayori_languages);
  $tayori_result = 1;
  foreach ($tayori_languages as $language) {
    if (preg_match('/^ja/i', $language)) {
      $tayori_result = 0;
    }
  }
  $tayori_text = array(array('閉じる','名前','メールアドレス','お問い合わせ内容','必須','お名前を入力してください','メールアドレスを入力してください','お問い合わせ内容を入力してください','内容を送信','編集','編集中','送信が完了しました。<br/>お問い合わせいただき、ありがとうございました。','お問い合わせありがとうございます。','必須項目ですので入力してください。','メールアドレスを正しく入力してください。','お問い合わせ','回答中','お問い合わせがありました', '名前：', 'メールアドレス：', 'お問い合わせ内容：'),array('Close','Name','Email','Inquiry','Required','Please input name','Please input Email','Please input inquiry','Send','Edit','Editing','An inquiry has been sent.<br/>Thank you very much.','Thank you for your inquiry.','Please fill in the required items.','Please insert your email address correctly.','Information','Currently responding', 'Inquiry', 'Name : ', 'Email : ', 'Content of inquiry : '));
?>
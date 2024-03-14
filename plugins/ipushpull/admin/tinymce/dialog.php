<?php
include 'common.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ipushpull</title>
    <style>
        html,body{
            height: 100%;
        }
        body {
            margin: 0;
        }
        iframe{
            position: absolute;
            z-index: 10;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>

<?php if(strstr($_SERVER['HTTP_HOST'],'.local')) { ?>
<iframe src="http://ipushpull.local/wordpress-plugin" frameborder="0"></iframe>
<?php } else { ?>
<iframe src="https://www.ipushpull.com/wpembedapp/" frameborder="0"></iframe>
<?php } ?>

<script language="javascript" type="text/javascript">

    var passed_arguments = top.tinymce.activeEditor.windowManager.getParams();

    if(window.attachEvent) {
        // Internet Explorer
        window.attachEvent('onmessage',receiveMessage);
    } else {
        // Opera/Mozilla/Webkit
        window.addEventListener("message", receiveMessage, false);
    }

    function receiveMessage(event){

        if(!passed_arguments){
            alert('Error: Please reload page');
            return;
        }

        if(event.data && event.data.event == 'addCode'){
            passed_arguments.editor.selection.setContent(event.data.shortcode);
            passed_arguments.editor.windowManager.close();
        }

    }

</script>

</body>
</html>

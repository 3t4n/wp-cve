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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        body {
            margin: 20px;
        }
    </style>
</head>
<body>

<p class="text-center">
    <?php if ($url) { ?>
        <a class="btn btn-default btn-large" href="<?php echo $url ?>/wp-admin/admin.php?page=ipushpull"
           target="_top">Please login</a>
    <?php } else { ?>
        <span class="alert alert-danger">Url not found</span>
    <?php } ?>
</p>

</body>
</html>

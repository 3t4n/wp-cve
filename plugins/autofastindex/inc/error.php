<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title></title>
    <link data-require="datatables@*" data-semver="1.10.12" rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
    <link data-require="font-awesome@*" data-semver="4.5.0" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css" />
    <link data-require="bootstrap-css@3.3.6" data-semver="3.3.6" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" />

    <script data-require="jquery" data-semver="3.0.0" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.js"></script>
    <script data-require="datatables@*" data-semver="1.10.12" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="style.css" />
    <script src="script.js"></script>
</head>

<body>

<?php

if(isset($_POST['removelogs'])){

    file_put_contents(plugin_dir_path(__FILE__) . 'error.txt', "");
}

?>
<form method="post">

    <button type="submit" name="removelogs" class="btn btn-default">Clear Logs</button>
</form>
<br/>
<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Error Code</th>
        <th>Url</th>
        <th>Message</th>
        <th>Channel</th>
        <th>Date</th>
    </tr>
    </thead>


    <tbody>

    <?php
    $file = fopen(plugin_dir_path(__FILE__) . 'error.txt',"r");
    while(! feof($file))
    {
        $data=json_decode(fgets($file));
if($data==''){
    continue;
}

    ?>
    <tr>
        <th><?php echo $data->errorcode; ?></th>
        <th><?php echo $data->url; ?></th>
        <th><?php echo $data->msg; ?></th>
        <th><?php echo $data->channel; ?></th>
        <th><?php echo $data->date; ?></th>
    </tr>

  <?php } ?>

    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
</script>

</body>

</html>

<form class="spac_columns_form" method="post" data-custom-type="">
	<div class="right_block">
            <?php require_once 'filters_block.php'; ?>
	</div>
</form>

<div class="wrapper1">
    <div class="div1"></div>
</div>
<div class="wrapper2">
    <div class="div2">
        <input type="text" id="search_spacs_frontend" placeholder="<?php echo __( "Search", "wordress-laravel-plugin" );?>...">
        <br/>
        <table id="custom_table_results"  width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>A</td>
                    <td>B</td>
                    <td>C</td>
                    <td>D</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


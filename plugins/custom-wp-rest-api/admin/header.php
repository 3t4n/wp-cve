<?php 
$tab = ( ! empty( $_GET['page'])  ) ?  esc_attr($_GET['page'])  : 'wcra_api_endpoints';
wcra_admin_page_tabs( $tab );
?>
<style type="text/css">
	.customtooltip {
  position: relative;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.customtooltip .customtooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.customtooltip:hover .customtooltiptext {
  visibility: visible;
}
#the-list input[type="checkbox"], .wp-list-table input[type="checkbox"]{
	background: none;
height: 16px;
border: 1px solid silver;
}
</style>
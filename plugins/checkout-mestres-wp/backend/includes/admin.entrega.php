<?php
function cwmp_admin_entrega(){
	$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        // Nonce válido, execute as ações necessárias
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            // Nonce inválido, redirecionar ou lidar com a situação de não autorizado
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
$mwp_pages_admin = array(
	/*
	'entrega.correios'=>array('name'=>__( 'Correios', 'checkout-mestres-wp')),
	'entrega.melhor-envio'=>array('name'=>__( 'Melhor Envio', 'checkout-mestres-wp')),
	'entrega.kangu'=>array('name'=>__( 'Kangu', 'checkout-mestres-wp')),
	'entrega.frenet'=>array('name'=>__( 'Frenet', 'checkout-mestres-wp')),
	'entrega.mandabem'=>array('name'=>__( 'Manda Bem', 'checkout-mestres-wp')),
	'entrega.simulador-frete'=>array('name'=>__( 'Freight Simulator', 'checkout-mestres-wp')),
	*/
	'entrega.transportadoras'=>array('name'=>__( 'Carriers', 'checkout-mestres-wp'))
);

?>

<div class="wrap">
<h2></h2>
<div class="mwpbody">
	<div class="mwpbrcolone">
		<div class="mwp-title">
			<svg width="150" height="169" viewBox="0 0 150 169" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 129.201V43.3397L16.3934 32.7092V119.389L27.8689 125.113V27.8028L43.4426 17.9901V134.108L54.0984 139.832V11.4482L75.4098 0L150 43.3397V129.201L108.197 152.098V134.108L133.607 119.389V51.517L75.4098 17.9901L68.8525 22.0787V168.452L0 129.201Z" fill="#EE451A"/><path d="M81.1475 168.452V65.4184L97.541 73.5957V108.758L105.738 104.669V65.4184L81.1475 51.517V34.3447L122.951 58.0588V114.482L97.541 129.201V157.822L81.1475 168.452Z" fill="#EE451A"/></svg>
		</div>
		<div class="mwp-sections">
			<ul>
				<?php $i=0; foreach($mwp_pages_admin as $mwp_pages_key => $mwp_pages_value){
					if(isset($_GET['type'])){
						$page=$_GET['type'];
					}else{
						$page="entrega.transportadoras";
					}
					if(strpos($_SERVER['REQUEST_URI'],$mwp_pages_key)){ $active = "mpcw-section-active"; } else { if(isset($_GET['type'])){ $active = ""; }else{ if($mwp_pages_key==$page){ $active = "mpcw-section-active"; }else{ $active = ""; } } }
				?>
				<li class="<?php echo $active; ?> <?php echo $mwp_pages_key; ?> box_menu">
				<a href="admin.php?page=cwmp_admin_entrega&type=<?php echo $mwp_pages_key; ?>">
					<h4><?php echo $mwp_pages_value['name']; ?></h4>
					<svg width="15" height="24" viewBox="0 0 15 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M14.1744 11.3693L1.19313 0.174345C0.709062 -0.241547 0 0.133438 0 0.805001V23.195C0 23.8666 0.709062 24.2415 1.19313 23.8257L14.1744 12.6307C14.546 12.3102 14.546 11.6898 14.1744 11.3693Z" fill="#EE451A"/>
					</svg>
				</a>
				</li>
				<?php $i++; } ?>
				<li><a href="https://docs.mestresdowp.com.br" target="blank"><h4>Documentação</h4></a></li>
			</ul>
		</div>
	</div>
	<div class="mwpbrcoltwo"><div class="mwpsectioncontent">
	<div class=""><?php if(isset($_GET['type'])){ include "html/".$_GET['type'].".php"; }else{  include "html/entrega.transportadoras.php"; } ?></div>
	</div></div>
	<?php do_action("cwmp_admin_sidebar"); ?>
</div>
</div>
<?php
}
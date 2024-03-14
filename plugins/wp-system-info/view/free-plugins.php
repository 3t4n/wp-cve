<?php
global $wpdb;

$common = new BSI_Common();
?>
<div class="php_info bsi">
	<h1 class=""> Innovative tools to empower your businesses </h1>
	<div>

		<div class="plugin-box">

			<?php foreach($freeItems as $item ) {?>

			<div class="item">
				<img src="<?php echo $item['image'] ?>" />
				<h2> <?php echo $item['title'] ?> </h2>
				<p><?php echo $item['text'] ?></p>
				<a class="button" href="<?php echo $item['url'] ?>" target="_new"> Get it free!</a>
			</div>
			<?php } ?>

			<br clear="all"/> 
			<div style="clear:both"></div>
		</div>

		<p class="get-more-about"> <a  href="https://springdevs.com" target="_new"> <button class="bn632-hover bn18"> Get more about us </button> </a> </p>

		

	</div>
</div>

<style>
	.plugin-box .item{
		position:relative;
		float: left ; 
		width: 350px; 
		height: 270px;
		margin-right: 40px;
		margin-bottom: 40px;
		border: solid 1px #ccc; 
		padding: 10px;
		text-align: center;
	}
	.plugin-box .item img{
		max-height: 100px;
		margin-bottom: 5px;
	}

	.plugin-box .item .button{
		
}
	.get-more-about{
		text-align: center;
		font-size : 40px; 
	}

	
	
	.get-more-about	.button {
  border: 0;
  text-align: center;
  display: inline-block;
  padding: 14px;
  width: 150px;
  margin: 7px;
  color: #ffffff;
  background-color: #36a2eb;
  border-radius: 8px;
  font-family: "proxima-nova-soft", sans-serif;
  font-weight: 600;
  text-decoration: none;
  transition: box-shadow 200ms ease-out;
}

.bn632-hover {
  width:200px;
  font-size: 16px;
  font-weight: 600;
  color: #000;
  cursor: pointer;
  margin: 20px;
  height: 60px;
  text-align:center;
  border: none;
  background-size: 300% 100%;
  border-radius: 50px;
  moz-transition: all .4s ease-in-out;
  -o-transition: all .4s ease-in-out;
  -webkit-transition: all .4s ease-in-out;
  transition: all .4s ease-in-out;
}

.bn632-hover:hover {
  background-position: 100% 0;
  moz-transition: all .4s ease-in-out;
  -o-transition: all .4s ease-in-out;
  -webkit-transition: all .4s ease-in-out;
  transition: all .4s ease-in-out;
}

.bn632-hover:focus {
  outline: none;
}

.bn632-hover.bn18 {
  background-image: linear-gradient(
    to right,
    #25aae1,
    #40e495,
    #30dd8a,
    #2bb673
  );
  box-shadow: 0 4px 15px 0 rgba(49, 196, 190, 0.75);
}

</style>
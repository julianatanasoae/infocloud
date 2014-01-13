<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_owner();
?>
	<div id="continutul_nostru">
		<header>
			<h1>Administrare useri</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
			<a href="manage_profs.php">Administrare profesori</a><br/>
			<a href="manage_users.php">Administrare elevi</a><br/>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>
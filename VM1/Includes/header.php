<?php require_once ("Includes/session.php"); $todays_date = date("d/m/Y"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8" />
    <title>InfoCloud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap -->
    <link href="css/bootstrap-theme.css" rel="stylesheet" media="screen" />
	<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" />
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/modernizr.js"></script>
	<link href="css/Site.css" rel="stylesheet" />
  </head>
  <body>
	<header>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
					
					<a class="brand" href="index.php"><img src="Images/IClogo2.png" style="width: 100px;" /></a>
					
					<div class="nav-collapse collapse">
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="probleme.php">Probleme</a></li>
                                <li><a href="clasament.php">Clasament</a></li>
								<li><a href="concursuri.php">Concursuri</a></li>
								<li><a href="monitor.php">Monitor</a></li>
								<li><a href="documentatie.pdf">Ajutor</a></li>
                            </ul>
							<ul class="nav nav-pills nav-stacked pull-right">
								<?php
									if (logged_on())
									{
									$header_statement = $databaseConnection->prepare("SELECT puncte, clasa FROM users WHERE username = ?");
			    $header_statement->bind_param('s', $_SESSION['username']);
			    $header_statement->execute();
			    $header_statement->store_result();
			    if ($header_statement->error)
			    {
				die("Database query failed: " . $header_statement->error);
			    }
			    $header_statement->bind_result($puncte, $clasa);
			    $header_statement->fetch();
										echo "<li class=\"navbar-text\"><a href=\"logoff.php\">Logout</a></li>";
										
										if (is_admin())
										{
										echo "<li class=\"navbar-text\"><a href=\"admin.php\">Administrare</a></li>";
										echo "<li class=\"navbar-text\">Logat ca: " . $_SESSION['username'] . "<br />" . $todays_date . "</li>";
										}
										else if (is_owner())
										{
											echo "<li class=\"navbar-text\"><a href=\"admin_users.php\">Administrare</a></li>";
											echo "<li class=\"navbar-text\">Logat ca: " . $_SESSION['username'] . "<br />" . $todays_date . "</li>";
										}
										else
										{
										echo "<li class=\"navbar-text\">Logat ca: " . $_SESSION['username'] . "<br/>" . $todays_date . "<br/>Clasa: " . $clasa . " &nbsp;&nbsp;&nbsp; Puncte: " . $puncte . "</li>";
										}
									}
									else
									{
										echo "<li class=\"navbar-text\"><a href=\"register.php\">Înregistrare</a></li>";
										echo "<li class=\"navbar-text\"><a href=\"logon.php\">Login</a></li>";
									}
								?>
							</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	
	<div class="container">
<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");

		// replace connection string with data provided by the Windows Azure portal
		$connectionString = "DefaultEndpointsProtocol=http;AccountName=;AccountKey=";
		require_once('vendor/autoload.php');

		use WindowsAzure\Common\ServicesBuilder;
		use WindowsAzure\Common\ServiceException;
		use WindowsAzure\Queue\Models\PeekMessagesOptions;
?>
	<div id="continutul_nostru">
		<header>
			<h1>Monitor de evaluare</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
			<?php
				
				// Create queue REST proxy.
				$queueRestProxy = ServicesBuilder::getInstance()->createQueueService($connectionString);
				
				// OPTIONAL: Set peek message options.
				$message_options = new PeekMessagesOptions();
				$message_options->setNumberOfMessages(32); // Default value is 1.


				try {
					$peekMessagesResult = $queueRestProxy->peekMessages("evalqueue", $message_options);
				}
				catch(ServiceException $e){
					$code = $e->getCode();
					$error_message = $e->getMessage();
					echo $code.": ".$error_message."<br />";
				}
				
				$messages = $peekMessagesResult->getQueueMessages();

				// View messages.
				$messageCount = count($messages);
				if($messageCount <= 0){
					echo "<p>Nu există surse în coadă.</p>";
				}
				else {
						echo "<p style=\"font-family: 'Lobster', cursive; font-size: 20px;\">Coadă evaluare</p>";
						echo "<table border=\"1\">";
						echo "<tr><th>ID evaluare</th><th>User</th><th>Clasa</th><th>Problema</th><th>Concurs</th><th>Timp de execuție</th><th>Status</th></tr>";
						foreach($messages as $message)  {
							$json = $message->getMessageText();
							$decoded_json = json_decode($json,true);
							echo "<tr>";
								$query = "SELECT username FROM users WHERE id = ? LIMIT 1";
								$statement = $databaseConnection->prepare($query);
								$statement->bind_param('d', $decoded_json['id_user']);

								$statement->execute();
								$statement->store_result();

								if ($statement->num_rows == 1)
								{
									$statement->bind_result($user);
									$statement->fetch();
								}
								
								$query = "SELECT nume FROM probleme WHERE id = ? LIMIT 1";
								$statement = $databaseConnection->prepare($query);
								$statement->bind_param('d', $decoded_json['id_problema']);

								$statement->execute();
								$statement->store_result();

								if ($statement->num_rows == 1)
								{
									$statement->bind_result($problema);
									$statement->fetch();
								}
								echo "<td>" . $decoded_json['id_eval'] . "</td>";
								echo "<td>" . $user . "</td>";
								echo "<td>" . $decoded_json['clasa'] . "</td>";
								echo "<td>" . $problema . "</td>";
								
								if (intval($decoded_json['id_concurs']) != 0)
								{
									$query = "SELECT nume FROM concursuri WHERE id = ? LIMIT 1";
									$statement = $databaseConnection->prepare($query);
									$statement->bind_param('d', $decoded_json['id_concurs']);

									$statement->execute();
									$statement->store_result();

									if ($statement->num_rows == 1)
									{
										$statement->bind_result($concurs);
										$statement->fetch();
									}
								}
								else $concurs = "";
								echo "<td>" . $concurs . "</td>";
								if (intval($decoded_json['timp']) == 1)
									echo "<td>" . $decoded_json['timp'] . " secundă" . "</td>";
								else echo "<td>" . $decoded_json['timp'] . " secunde" . "</td>";
								if (intval($decoded_json['status']) != 0)
									echo "<td>" . "în evaluare pe " . " " . "VM" . $decoded_json['vm'] . "</td>";
								else echo "<td>" . "pending" . "</td>";
							echo "</tr>";
						}
						echo "</table>";
					}
			?>
			<br/><br/>
			<p style="font-family: 'Lobster', cursive; font-size: 20px;">Evaluări terminate</p>
			<?php
				echo "<table border=\"1\">";
				echo "<tr><th>ID evaluare</th><th>User</th><th>Clasa</th><th>Problema</th><th>Concurs</th><th>Timp de execuție</th><th>Evaluat de</th><th>Punctaj</th><th></th></tr>";
					$query = "SELECT id, id_eval, id_user, id_problema, clasa, id_concurs, timp, vm, punctaj FROM evaluari ORDER BY id DESC";
					$statement = $databaseConnection->prepare($query);
					$statement->execute();
					if($statement->error)
            {
                die("Database query failed: " . $statement->error);
            }

            $statement->bind_result($eval_id, $eval_id_eval, $eval_id_user, $eval_id_problema, $eval_clasa, $eval_id_concurs, $eval_timp, $eval_vm, $eval_punctaj);
            while($statement->fetch())
            {
				$databaseConnection2 = new mysqli("localhost", "sampleuser", "samplepassword", "infocloud");
				if ($databaseConnection2->connect_error)
					{
						die("Database selection failed: " . $databaseConnection2->connect_error);
					}
					$query2 = "SELECT username FROM users WHERE id = ? LIMIT 1";
					$statement2 = $databaseConnection2->prepare($query2);
					$statement2->bind_param('d', $eval_id_user);
					$statement2->execute();
					$statement2->store_result();
					
					$statement2->bind_result($eval_user);
					$statement2->fetch();
					
					$query3 = "SELECT nume FROM probleme WHERE id = ? LIMIT 1";
					$statement3 = $databaseConnection2->prepare($query3);
					$statement3->bind_param('d', $eval_id_problema);
					$statement3->execute();
					$statement3->store_result();
					$statement3->bind_result($eval_problema);
					$statement3->fetch();
				
					if ($eval_id_concurs != 0)
					{
						$query4 = "SELECT nume FROM concursuri WHERE id = ? LIMIT 1";
						$statement4 = $databaseConnection2->prepare($query4);
						$statement4->bind_param('d', $eval_id_concurs);
						$statement4->execute();
						$statement4->store_result();
						$statement4->bind_result($eval_concurs);
						$statement4->fetch();
					}
					else $eval_concurs = "";
					
                echo "<tr>
			<td>" . $eval_id_eval . "</td>
			<td>" . $eval_user . "</td>
			<td>" . $eval_clasa . "</td>
			<td>" . $eval_problema . "</td>
			<td>" . $eval_concurs . "</td>";
			if ($eval_timp == 1)
			echo "<td>" . $eval_timp . " secundă" . "</td>";
			else 
			{ echo "<td>" . number_format($eval_timp, 2, '.', '') . " secunde" . "</td>"; }
			echo "<td>" . "VM" . $eval_vm . "</td>
			<td>" . $eval_punctaj . "</td>
			<td>" . "<a href=\"view_eval_result.php?id_eval=" . $eval_id_eval . "\">Vizualizare</a>" . "</td>";
            }
				echo "</table>";
			?>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>
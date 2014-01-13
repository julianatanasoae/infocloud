#!/usr/bin/php
 
<?php
	use WindowsAzure\Common\ServicesBuilder;
	use WindowsAzure\Common\ServiceExcepion;
	use WindowsAzure\Queue\Models\PeekMessagesOptions;
	// replace connection string with data provided by the Windows Azure portal
	$connectionString = "DefaultEndpointsProtocol=http;AccountName=;AccountKey=";
	require_once('vendor/autoload.php');
	
	function checkStatus($message)
	{
		if (isset($message))
		{
			$messageId = $message->getMessageId();
			$popReceipt = $message->getPopReceipt();

			$json = $message->getMessageText();
			$decoded_json = json_decode($json,true);

			$status = intval($decoded_json['status']);
			return $status;
		}
		else return -1;
	}

	//fork the process to work in a daemonized environment
	$pid = pcntl_fork();
	if($pid == -1){
		return 1; //error
	}
	else if($pid){
		return 0; //success
	}
	else{
		//the main process
		$this_vm = 3;
		while(true){
			// pune codu aici

			// Create queue REST proxy.
			$queueRestProxy = ServicesBuilder::getInstance()->createQueueService($connectionString);

			// Get message.
			$listMessagesResult = $queueRestProxy->listMessages("evalqueue");
			$messages = $listMessagesResult->getQueueMessages();
			if ($messages != null)
			{
				$x = 0;
				while(isset($messages[$x]))
				{
					if (checkStatus($messages[$x]) > 0)
						$x++;
					else break;
				}
				if(isset($messages[$x]))
					if(checkStatus($messages[$x]) == 0)
				{
					$message = $messages[$x];
					/* ---------------------
					Process message.
				   --------------------- */

					// Get message Id and pop receipt.
					$messageId = $message->getMessageId();
					$popReceipt = $message->getPopReceipt();

					$json = $message->getMessageText();
					$decoded_json = json_decode($json,true);

					$status = $decoded_json['status'];
					$id_eval = $decoded_json['id_eval'];
					$id_user = $decoded_json['id_user'];
					$id_concurs = $decoded_json['id_concurs'];
					$vm = $decoded_json['vm'];
					$nume_fisier = $id_eval . ".cpp";
					$id_problema = intval($decoded_json['id_problema']);
					$fisier_io = $decoded_json['fisier_io'];
					$clasa = intval($decoded_json['clasa']);
					$timp = floatval($decoded_json['timp']);
					
					$new_message = "{ \"id_problema\": \"" . $id_problema . "\", \"id_eval\": \"" . $id_eval . "\", \"clasa\": \"" . $clasa . "\", \"id_user\": \"" . $id_user . "\", \"timp\": \"" . $timp . "\", \"fisier_io\": \"" . $fisier_io . "\", \"id_concurs\": \"" . $id_concurs . "\", \"status\": \"1\", \"vm\": \"" . $this_vm . "\" }";
					
					try {
						// Update message.
						$raspuns = $queueRestProxy->updateMessage("evalqueue", 
													$messageId, 
													$popReceipt, 
													$new_message,
													1);
						$popReceipt = $raspuns->getPopReceipt();
						
					}
					catch(ServiceException $e){
						// Handle exception based on error codes and messages.
						// Error codes and messages are here: 
						// http://msdn.microsoft.com/en-us/library/windowsazure/dd179446.aspx
						$code = $e->getCode();
						$error_message = $e->getMessage();
						echo $code.": ".$error_message."<br />";
					}

					// aici incepe compilarea
					$punctaj = 0;
					$eval_con = new mysqli("localhost", "sampleuser", "samplepassword", "eval");
					if ($eval_con->connect_error)
					{
						die("Database selection failed: " . $eval_con->connect_error);
					}
					
					$create_table_query = "CREATE TABLE " . $id_eval . "(test VARCHAR(50), status VARCHAR(3000), timp VARCHAR(50))";
					$create_table_statement = $eval_con->prepare($create_table_query);
					$create_table_statement->execute();
					$create_table_statement->store_result();
					
					mysqli_close($eval_con);
					
					$output = shell_exec('g++ -Wall -O2 -static -std=c++0x ' . "/var/www/surse/" . (string)$clasa . '/' . $id_problema . '/work/' . $id_eval . '/' . $nume_fisier . ' -o ' . "/var/www/surse/" . (string)$clasa . '/' . $id_problema . '/work/' . $id_eval . '/' . $nume_fisier . '.exec' . ' -lm ' . ' 2>&1');
					
					for ($i = 1; $i <= 10; $i++)
					{
						$overtimelimit = 0;
						$oldpath = getcwd();
							copy('/var/www/surse/' . (string)$clasa . '/' . (string)$id_problema . '/in/' . $i . '-' . $fisier_io . '.in', '/var/www/surse/' . (string)$clasa . '/' . (string)$id_problema . '/work/' . $id_eval . '/' . $fisier_io . '.in');
						$work_dir = '/var/www/surse/' . (string)$clasa . '/' . (string)$id_problema . '/work/' . $id_eval;
						$problem_dir = '/var/www/surse/' . (string)$clasa . '/' . (string)$id_problema;
						chdir($work_dir);
						$programu_meu = '/usr/bin/time -o time.txt --format %Eelapsed timelimit -t ' . $timp . ' ' . $work_dir . '/' . $id_eval . '.cpp.exec' . ' 2>&1';
						$output2 = shell_exec($programu_meu);
						if ($output2 == "timelimit: sending warning signal 15\n")
						{
							$overtimelimit = 1;
							
							$eval_con2 = mysqli_connect("localhost", "sampleuser", "samplepassword", "eval");
							if (mysqli_connect_errno())
							{
								echo "Failed to connect to MySQL: " . mysqli_connect_error();
							}
							$insert_query2 = "INSERT INTO `" . $id_eval . "` (test, status, timp) VALUES (" . "'" . (string)$i . "'" . "," . "'depasit timpul de executie'" . "," . "'0'" . ")";
							mysqli_query($eval_con2, $insert_query2);
							mysqli_close($eval_con2);
						}
						else
						{
						$actual_time = shell_exec('cat ' . $work_dir . '/time.txt 2>&1');
						copy($fisier_io . '.out', 'out/'. $i . '-' . $fisier_io . '.out');
						$out_file = $fisier_io . '.out';
						$out_handle = fopen($out_file, "r");
						$out_contents = fread($out_handle, filesize($out_file));
						$out_contents = str_replace("\r\n","\n", $out_contents);
						if (substr($out_contents, -1) != "\n")
							$out_contents = $out_contents . "\n";
						fclose($out_handle);
						$ok_file = $problem_dir . '/ok/' . $i .'-' . $fisier_io . '.ok';
						$ok_handle = fopen($ok_file, "r");
						$ok_contents = fread($ok_handle, filesize($ok_file));
						$ok_contents = str_replace("\r\n","\n", $ok_contents);
						if (substr($ok_contents, -1) != "\n")
							$ok_contents = $ok_contents . "\n";
						fclose($ok_handle);
						}
						if ($overtimelimit != 1)
						if ($out_contents == $ok_contents)
						{
							$eval_con3 = mysqli_connect("localhost", "sampleuser", "samplepassword", "eval");
							if (mysqli_connect_errno())
							{
								echo "Failed to connect to MySQL: " . mysqli_connect_error();
							}
							$insert_query3 = "INSERT INTO `" . $id_eval . "` (test, status, timp) VALUES (" . "'" . (string)$i . "'" . "," . "'OK'" . "," . "'" . $actual_time . "'" . ")";
							mysqli_query($eval_con3, $insert_query3);
							$punctaj = $punctaj + 10;
							mysqli_close($eval_con3);
						}
						else
						{
							$eval_con4 = mysqli_connect("localhost", "sampleuser", "samplepassword", "eval");
							if (mysqli_connect_errno())
							{
								echo "Failed to connect to MySQL: " . mysqli_connect_error();
							}
							$insert_query4 = "INSERT INTO `" . $id_eval . "` (test, status, timp) VALUES (" . "'" . (string)$i . "'" . "," . "'gresit'" . "," . "'" . $actual_time . "'" . ")";
							mysqli_query($eval_con4, $insert_query4);
							mysqli_close($eval_con4);
						}
						unlink($fisier_io . '.in');
						unlink($fisier_io . '.out');
						chdir($oldpath);
					}
					
					$eval_con5 = mysqli_connect("localhost", "sampleuser", "samplepassword", "eval");
					if (mysqli_connect_errno())
					{
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
					$insert_query5 = "INSERT INTO `" . $id_eval . "` (test, status, timp) VALUES (" . "'" . "punctaj" . "'" . "," . "'" . $punctaj . "'" . "," . "'" . "0" . "'" . ")";
					mysqli_query($eval_con5, $insert_query5);
					mysqli_close($eval_con5);
					
					$eval_con00 = mysqli_connect("localhost", "sampleuser", "samplepassword", "eval");
					if (mysqli_connect_errno())
					{
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
					$insert_query00 = "INSERT INTO `" . $id_eval . "` (test, status, timp) VALUES (" . "'" . "output" . "'" . "," . "'" . $output . "'" . "," . "'" . "0" . "'" . ")";
					mysqli_query($eval_con00, $insert_query00);
					mysqli_close($eval_con00);
					
					$eval_con7 = mysqli_connect("localhost", "sampleuser", "samplepassword", "eval");
					if (mysqli_connect_errno())
					{
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
					$insert_query7 = "INSERT INTO `" . $id_eval . "` (test, status, timp) VALUES (" . "'" . "vm" . "'" . "," . "'" . $this_vm . "'" . "," . "'" . "0" . "'" . ")";
					mysqli_query($eval_con7, $insert_query7);
					mysqli_close($eval_con7);

					$eval_con10 = mysqli_connect("localhost", "sampleuser", "samplepassword", "infocloud");
					if (mysqli_connect_errno())
					{
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
					$insert_query10 = "INSERT INTO `evaluari` (id_eval, id_user, id_problema, clasa, id_concurs, timp, vm, punctaj) VALUES (" . "'" . $id_eval . "'" . "," . intval($id_user) . "," . $id_problema . "," . $clasa . "," . intval($id_concurs) . "," . $timp . "," . "'" . $this_vm . "'" . "," . intval($punctaj) . ")";
					mysqli_query($eval_con10, $insert_query10);
					mysqli_close($eval_con10);
	
					sleep(2);
					try {
						// Delete message.
						$queueRestProxy->deleteMessage("evalqueue", $messageId, $popReceipt);
					}
					catch(ServiceException $e){
						// Handle exception based on error codes and messages.
						// Error codes and messages are here: 
						// http://msdn.microsoft.com/en-us/library/windowsazure/dd179446.aspx
						$code = $e->getCode();
						$error_message = $e->getMessage();
						echo $code.": ".$error_message."<br />";
					}
				}
			}
				sleep(10);
		}//end while
	}//end if
?>

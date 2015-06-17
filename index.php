<?php
/*
Chatroom Webapp
16.06.2015	Christoph Wenk
*/
header('Content-Type: text/html; charset=utf-8');
SESSION_START();

//Initialize variables
$redirect = false;

if (isset($_POST["betreten"]) || isset($_POST["erstellen"])) {
	
	// Connect to Database
	$connection = mysqli_connect("localhost","root");
		
	// Select DB
	mysqli_select_db($connection,"chatdb");

	// Strip input
	$name = strip_tags($_POST['nickname']);
	
	// SQL INSERT statement
	$eintrag = "INSERT INTO user
	(user_name)
	VALUES
	('$name');";

	$eintragen = mysqli_query($connection, $eintrag);
	
	// Declare session-variables
	$_SESSION["nickname"] = $_POST['nickname'];
	
	if (isset($_POST["betreten"])) {
		$_SESSION["switch"] = $_POST["betreten"];
	}
	if (isset($_POST["erstellen"])) {
		$_SESSION["switch"] = $_POST["erstellen"];
	}
	
	// SQL Query get user ID
	$nick = $_POST['nickname'];	
	$result = mysqli_query($connection, "select max(user_id) as result from user where user_name = '$nick'");
	$cache = mysqli_fetch_assoc($result);
	$_SESSION["userid"] = $cache["result"];
	
	$redirect = true;	
}

// Wait a bit and redirect to adequate site	
if ($redirect == true) {
		echo '<script language="JavaScript" type="text/javascript">
		setTimeout("location.href=\'join_chatroom.php\'", 0); //0 Millisekunden
		</script>';	
}

?>

<!DOCTYPE html>
<html lang="de-CH">
	<head>
		<meta charset="utf-8">
		<meta name="author" content="Christoph Wenk">
		<link rel="stylesheet" href="formular.css" type="text/css">
		<title>Chatapp for mobile devices</title>
	</head>

	<body>

		<div class="wrapper">
			<header>
				<h1 class="title">Welcome to ASDF-Chat</h1>
			</header>
			<div class="main">
			<?php	
				echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'" accept-charset="utf-8">';
			?>
			<fieldset>
			<!-- Inputfield for nickname -->
				<label>Nickname eingeben: 
				<input type="text" name="nickname" id="nickname" maxlength="10000" autofocus><br>
				</label>
			</fieldset>
			<!-- Buttons for joining or creating a chatroom -->
			<input type="submit" value="Chatroom betreten" name="betreten">
			<input type="submit" value="Chatroom erstellen" name="erstellen">
			
			</form>
			</div>
			
		</div>
	</body>
</html>

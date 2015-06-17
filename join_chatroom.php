<?php
/*
Chatroom Webapp
16.06.2015	Christoph Wenk
*/
header('Content-Type: text/html; charset=utf-8');
SESSION_START();
//Initialize variables
$redirect = false;

// Connect to Database
$connection = mysqli_connect("localhost","root");
		
// Select DB
mysqli_select_db($connection,"chatdb");

if (isset($_POST["create"])) {

	// Strip input
	$chatroom = strip_tags($_POST['chatroom']);
	
	// SQL INSERT statement
	$eintrag = "INSERT INTO chatrooms
	(chatroom_name)
	VALUES
	('$chatroom');";

	$eintragen = mysqli_query($connection, $eintrag);
	
	// Declare session-variables
	$_SESSION["chatroom"] = $_POST['chatroom'];
}
	
if (isset($_POST["join"]) || isset($_POST["create"])) {
		
	// SQL Query get chatroom ID
	$room = $_POST['chatroom'];		
	$result = mysqli_query($connection, "select max(chatroom_id) as result from chatrooms where chatroom_name = '$room'");
	$cache = mysqli_fetch_assoc($result);
	$_SESSION["chatroomid"] = $cache["result"];	
	
	// Close DB-Connection
	mysqli_close($connection);	
		
	// Wait a bit and redirect to adequate site
	echo '<script language="JavaScript" type="text/javascript">
	setTimeout("location.href=\'chatroom.php\'", 0); //0 Millisekunden
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

	<?php
	
	// CASE: Join chatroom
	if ($_SESSION["switch"] == "Chatroom betreten") {

		echo 
		'<body>

		<div class="wrapper">
			<header>
				<h1 class="title">Join an existing chatroom</h1>
			</header>
			<div class="main">';
	
			echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'" accept-charset="utf-8">';
	
			// SQL Query
			$result = mysqli_query($connection, "select * from chatrooms");
		
			echo '<!-- list available chatrooms -->
			<fieldset>
				<select name="chatroom">';
			while ($dataset = mysqli_fetch_assoc($result)) {	
				echo '<option value="'.$dataset["chatroom_name"].'">'.$dataset["chatroom_name"];
			}
			echo' </select>
			</fieldset>

			<!-- Buttons for joining a chatroom -->
			<input type="submit" value="Chatroom betreten" name="join">';
			
		
	}
	
	// CASE: Create chatroom
	if ($_SESSION["switch"] == "Chatroom erstellen") {

		echo
		'<body>

		<div class="wrapper">
			<header>
				<h1 class="title">Create new chatroom</h1>
			</header>
			<div class="main">	
				<form method="post" action="'.$_SERVER['PHP_SELF'].'" accept-charset="utf-8">
				
			<fieldset>
			<!-- Inputfield for chatroom name -->
				<label>Name für neuen Chatroom eingeben: 
				<input type="text" name="chatroom" id="chatroom" autofocus><br>
				</label>
			</fieldset>
			<!-- Buttons for creating a chatroom -->
			<input type="submit" value="Chatroom erstellen" name="create">
					</form>
			</div>		
		</div>
		</body>';
		
	}
	?>			
			</form>
			</div>			
		</div>
	</body>
</html>

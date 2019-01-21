<?php # Script 12.11 - logout.php #2
// This page lets the user logout.
// This version uses sessions.

session_start(); // Access the existing session.

// If no session variable exists, redirect the user:
if (!isset($_SESSION['user'])) {
	header("Location:login.php");
	// Quit the script.
	exit();	
	
} else { // Cancel the session:

	$_SESSION = array(); // Clear the variables.
	session_destroy(); // Destroy the session itself.
	//setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0); // Destroy the cookie.

}

// Set the page title and include the HTML header:
$page_title = 'Logged Out!';
include ('includes/header.html');
?>
<section>
		<div id="sectionContainer">
			<?php
				// Print a customized message:
				echo '</p><p class="ad">You have left your session, please to use the application again, go back to login..</p><p><br /></p>';
			?>
			<div id="formContainer">
				<form action="login.php" id="form" method="post">
					<div class="line">
					    <span class="line__label">User:</span>
					    <input type="text" name="user" id="user" class="line__input">
					</div>
					<div class="line">
					    <span class="line__label">Password:</span>
					    <input type="password" name="password" id="password" class="line__input">
					</div>
					  <div id="containerButton">
				        <input id="button" type="submit"  value="Accept"/>
			        </div>
			        <div id="register">
			        	<p><a href="register_admin.php">Register a new administrator for the database.</a></p>
			        </div>
				</form>
			</div>
		</div>
	</section>
<?php
include ('includes/footer.html');
?>
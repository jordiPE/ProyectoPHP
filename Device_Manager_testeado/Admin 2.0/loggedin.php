<?php # Script 12.9 - loggedin.php #2
// Start the session.
session_start();
// If no session variable exists, redirect the user:
if (!isset($_SESSION['user'])) {
	header("Location:login.php");
	// Quit the script.
	exit();	
	
}
// The user is redirected here from login.php.
 // Set the page title and include the HTML header:
$page_title = 'Logged In!';
require ('includes/headerLoggedIn.html');
?>
<section>
	<div id="sectionContainer">
		<div id="formContainer">
			<div class="link">
				<a href="index/personal/index.php" >Personal</a>
			</div>
			<div class="link">
				<a href="index/deliveries/index.php" >Deliveries</a>
			</div>
			<div class="link">
				<a href="index/hardware/index.php">Hardware</a>
			</div>
			<div class="link">
				<a href="index/types/index.php">Types</a>
			</div>		
		</div>
	</div>
</section>
<?php
include ('includes/footer.html');
?>
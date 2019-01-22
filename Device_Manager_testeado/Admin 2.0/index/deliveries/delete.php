<?php
// Start the session.
session_start();
// If no session variable exists, redirect the user:
if (!isset($_SESSION['user'])) {
	header("Location:../../login.php");
	// Quit the script.
	exit();	
	
}
$page_title = 'Deliveries Page';
include ('../includes/header.html');
echo '<h1>Delete Delivery</h1>';
// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['DeliveriesId'])) && (is_numeric($_GET['DeliveriesId'])) ) 
{ 
	// From view_users.php
	$deliveriesId = $_GET['DeliveriesId'];
} 
	elseif ( (isset($_POST['DeliveriesId'])) && (is_numeric($_POST['DeliveriesId'])) ) 
	{ 
		// Form submission.
		$deliveriesId = $_POST['DeliveriesId'];
	} 
else 
{	
	 // No valid ID, kill the script.
	echo '<p class="error">The staff member does not exist in the database.</p>';
	include ('../includes/footer.html'); 
	exit();
}
require ('../mysqli_connect.php'); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	if ($_POST['sure'] == 'Yes') 
	{ 
		// Delete the record.
		// Make the query:
		$q = "DELETE FROM deliveries WHERE DeliveriesId='$deliveriesId' LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) 
		{ 
			// If it ran OK.
			// Print a message:
			echo '<p class="ok">The Delivery has been deleted.</p>';
			echo "<p class='ok'><a href='index.php'>Back to Deliveries page registered</a></p>";	

		} 
		else 
		{ 
			// If the query did not run OK.
			echo '<p class="error">The Delivery could not be deleted due to a system error.</p>'; // Public message.
			//echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} 
	else 
	{ 
		// No confirmation of deletion.
		echo '<p class="ok">The Delivery could not be deleted.</p>';
		echo "<p class='ok' ><a href='index.php'>Back to Deliveries page registered</a></p>";
	}
}
else
{
	// Show the form.
	// Retrieve the user's information:
	$q = "SELECT DeliveriesId FROM deliveries WHERE DeliveriesId='$deliveriesId'";
	$r = @mysqli_query ($dbc, $q);
	if (mysqli_num_rows($r) == 1) 
	{ 
		// Valid user ID, show the form.
		// Get the user's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		// Display the record being deleted:
		echo "<h3>Name: $row[0]</h3>
		<p class='ok' >Are you sure you want to delete this Delivery?</p>";
		// Create the form:
		echo '<form action="delete.php" method="post">
		<input type="radio" name="sure" class="radio" value="Yes" /> Yes 
		<input type="radio" name="sure" class="radio" value="No" checked="checked" /> No
		<input type="submit" name="submit" class="del" value="Delete" />
		<input type="hidden" name="DeliveriesId" value="' . $deliveriesId . '" />
		</form>';
	
	} 
	else 
	{ // Not a valid user ID.
		echo '<p class="error">Could not connect to the database..</p>';
	}

} // End of the main submission conditional.

mysqli_close($dbc);
		
include ('../includes/footer.html');
?>
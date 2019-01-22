<?php
// Start the session.
session_start();
// If no session variable exists, redirect the user:
if (!isset($_SESSION['user'])) {
	header("Location:../../login.php");
	// Quit the script.
	exit();	
	
}
$page_title = 'Types Page';
include ('../includes/header.html');
echo '<h1>Edit Type</h1>';
// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['TypesId'])) && (is_numeric($_GET['TypesId'])) ) 
{ 
	// From view_users.php
	$typesId = $_GET['TypesId'];
} 
	elseif ( (isset($_POST['TypesId'])) && (is_numeric($_POST['TypesId'])) ) 
	{ 
		// Form submission.
		$typesId = $_POST['TypesId'];
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
		// Initialize an error array.
		$errors = array();  
		// Check for Description
		if (!empty($_POST['description'])) 
		{	
			if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ0-9\/\.\,\(\)\?\¿\!\¡\-\º\ª]+$/', $_POST['description']))
			{
				$d = mysqli_real_escape_string($dbc, trim($_POST['description']));
			}
			else
			{
				$errors[] = 'The input format of the description field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a description to continue.';
		}
		if (!empty($_POST['acronym'])) 
		{	
			if(preg_match('/^[A-Z]{3}$/', $_POST['acronym']))
			{
				$a = mysqli_real_escape_string($dbc, trim($_POST['acronym']));
			}
			else
			{
				$errors[] = 'The input format of the acronym field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a acronym to continue.';
		}

		if (empty($errors)) 
		{ 
			// If everything's OK.
			//  Test for unique email address:
			$q = "SELECT TypesId FROM types WHERE Acronym='$a'AND Description='$d' AND TypesId != '$typesId'";
			$r = @mysqli_query($dbc, $q);
			if (mysqli_num_rows($r) == 0) 
			{
				// Make the query:
				$q = "UPDATE types SET Acronym='$a', Description='$d' WHERE TypesId=$typesId LIMIT 1";
				$r = @mysqli_query ($dbc, $q);
				if (mysqli_affected_rows($dbc) == 1) 
				{ 
					// If it ran OK.
					// Print a message:
					echo '<p class="ok">The Type has been edited.</p>';	
				
				} 	
				else 
				{ 
					// If it did not run OK.
					echo '<p class="error">The Type could not be edited due to a system error.</p>'; // Public message.
					//echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
				}
			} 
			else 
			{ 
				// Already registered.
				echo '<p class="error">You have not made any changes to the registry..</p>';
			}
		} 
		else 
		{ 
			// Report the errors.
			echo '<p class="error">The following error(s) occurred:<br />';
			foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p class="ok">Please try again.</p>';
		} // End of if (empty($errors)) IF.	
	}
	// Always show the form...
	// Retrieve the user's information:
	$q = "SELECT Acronym, Description FROM types WHERE TypesId='$typesId'";		
	$r = @mysqli_query ($dbc, $q);
	if (mysqli_num_rows($r) == 1) 
	{ 
		// Valid user ID, show the form.
		// Get the user's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		// Create the form:
		echo '<section>
		<div id="sectionContainer">
			<div id="formContainer">
				<div class="link">
					<a href="../../loggedin.php" >Home Page</a>
				</div>
				<div class="link">
					<a href="index.php" >Registered Types</a>
				</div>
		<form action="edit.php" id="form" method="post" >	
			<div class="line">
				<span class="line__label">Acronym:</span>
				<input type="text" name="acronym" id="acronym" class="line__input" value="' . $row[0] . '" >
			</div>
			<div class="line">
				<span class="line__label">Description:</span>
				<<textarea name="description" id="description" class="line__input">' . $row[1] . '</textarea>
			</div>
			<div id="containerButton">
				<input id="button" type="submit"  value="Edit"/>
				<input type="hidden" name="TypesId" value="' . $typesId . '" />
			</div>
		</form>
	</div>
	</div>
	</section>';
	}
	else 
	{ 
		// Not a valid user ID.
		echo '<p class="error">Could not connect to the database..</p>';
	}
	mysqli_close($dbc);
	include ('../includes/footer.html');
?>
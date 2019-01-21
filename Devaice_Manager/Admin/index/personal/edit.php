<?php
// Start the session.
session_start();
// If no session variable exists, redirect the user:
if (!isset($_SESSION['user'])) {
	header("Location:../../login.php");
	// Quit the script.
	exit();	
	
}
$page_title = 'Staff Page';
include ('../includes/header.html');
echo '<h1>Edit Staff</h1>';
// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['PersonalId'])) && (is_numeric($_GET['PersonalId'])) ) 
{ 
	// From view_users.php
	$personalId = $_GET['PersonalId'];
} 
	elseif ( (isset($_POST['PersonalId'])) && (is_numeric($_POST['PersonalId'])) ) 
	{ 
		// Form submission.
		$personalId = $_POST['PersonalId'];
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
		// Check for a full name
		if (!empty($_POST['fullName'])) 
		{	
			if(preg_match('/^[A-ZÑÁÉÍÓÚÀÈÌÒÙ][a-zñáéíóúàèìòù]+(\s[A-ZÑÁÉÍÓÚÀÈÌÒÙ][a-zññáéíóúàèìòù]+){1,39}$/', $_POST['fullName']))
			{
				$fl = mysqli_real_escape_string($dbc, trim($_POST['fullName']));
			}
			else
			{
				$errors[] = 'The input format of the full name field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a Full Name to continue.';
		}
		// Check for a phone 
		if (!empty($_POST['phone'])) 
		{
			if(preg_match('/^[0-9]{9}$/', $_POST['phone']))
			{
				$ph = mysqli_real_escape_string($dbc, trim($_POST['phone']));
			}
			else
			{
					$errors[] = 'The input format of the phone field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a phone to continue.';
		}
		// Check for a departament 
		if (!empty($_POST['departament'])) 
		{
			if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ]{1,20}$/', $_POST['departament']))
			{
				$dp = mysqli_real_escape_string($dbc, trim($_POST['departament']));
			}
			else
			{
					$errors[] = 'The input format of the departament field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a departament to continue.';
		}
		// Check for a observations, This option can be null 
		if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ0-9\/\.\,\(\)\?\¿\!\¡]{0,}+$/', $_POST['observations']))
		{
			$ob = mysqli_real_escape_string($dbc, trim($_POST['observations']));
		}
		else
		{
			$errors[] = 'The input format of the observations field is not correct.';
		}

		if (empty($errors)) 
		{ 
			// If everything's OK.
			//  Test for unique email address:
			$q = "SELECT PersonalId FROM personal WHERE FullName='$fl'AND Phone='$ph'AND Department='$dp'AND Observations='$ob'AND PersonalId != '$personalId'";
			$r = @mysqli_query($dbc, $q);
			if (mysqli_num_rows($r) == 0) 
			{
				// Make the query:
				$q = "UPDATE personal SET FullName='$fl', Phone='$ph', Department='$dp',Observations='$ob' WHERE PersonalId=$personalId LIMIT 1";
				$r = @mysqli_query ($dbc, $q);
				if (mysqli_affected_rows($dbc) == 1) 
				{ 
					// If it ran OK.
					// Print a message:
					echo '<p class="ok">The staff has been edited.</p>';	
				
				} 	
				else 
				{ 
					// If it did not run OK.
					echo '<p class="error">The staff could not be edited due to a system error.</p>'; // Public message.
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
	$q = "SELECT FullName, Phone, Department,Observations FROM personal WHERE PersonalId='$personalId'";		
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
					<a href="index.php" >Registered Staff</a>
				</div>
		<form action="edit.php" id="form" method="post" >	
			<div class="line">
				<span class="line__label">Full Name:</span>
				<input type="text" name="fullName" id="fullName" class="line__input" value="' . $row[0] . '" >
			</div>
			<div class="line">
				<span class="line__label">Phone:</span>
				<input type="text" name="phone" id="phone" class="line__input" value="' . $row[1] . '" >
			</div>
			<div class="line">
				<span class="line__label">Departament:</span>
				<input type="text" name="departament" id="departament" class="line__input" value="' . $row[2] . '" >
			</div>
			<div class="line">
				<span class="line__label">Observations:</span>
				<textarea name="observations" id="observations" class="line__input">'.$row[3].'</textarea>
			</div>
			<div id="containerButton">
				<input id="button" type="submit"  value="Edit"/>
				<input type="hidden" name="PersonalId" value="' . $personalId . '" />
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
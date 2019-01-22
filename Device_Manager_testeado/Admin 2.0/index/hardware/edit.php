<?php
// Start the session.
session_start();
// If no session variable exists, redirect the user:
if (!isset($_SESSION['user'])) 
{
	header("Location:../../login.php");
	// Quit the script.
	exit();	
	
}
$page_title = 'Hardware Page';
include ('../includes/headerDeliveries.html');
echo '<h1>Edit Hardware</h1>';
// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['HardwareId'])) && (is_numeric($_GET['HardwareId'])) ) 
{ 
	// From index.php
	$hardwareId = $_GET['HardwareId'];
} 
	elseif ( (isset($_POST['HardwareId'])) && (is_numeric($_POST['HardwareId'])) ) 
	{ 
		// Form submission.
		$hardwareId = $_POST['HardwareId'];
	} 
else 
{	
	 // No valid ID, kill the script.
	echo '<p class="error">The Delivery does not exist in the database.</p>';
	include ('../includes/footer.html'); 
	exit();
}
require ('../mysqli_connect.php'); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		// Connect to the db.
		//require('../mysqli_connect.php');
		// Initialize an error array.
		$errors = array();
		// Check for a Personal Id
		if (!empty($_POST['typeId'])) 
		{	
			if(preg_match('/^[0-9]+$/', $_POST['typeId']))
			{
				$ti = mysqli_real_escape_string($dbc, trim($_POST['typeId']));
			}
			else
			{
				$errors[] = 'The input format of the Type ID field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a Type ID to continue.';
		}
		 // Check for a Number of Label
		if (!empty($_POST['numberOfLabel'])) 
		{	
			if(preg_match('/^([A-Z]{3}\-[0-9]{3})$/', $_POST['numberOfLabel']))
			{
				$nl = mysqli_real_escape_string($dbc, trim($_POST['numberOfLabel']));
			}
			else
			{
				$errors[] = 'The input format of the number of label field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a number of label to continue.';
		} 
		// Check for Description
		if (!empty($_POST['description'])) 
		{	
			if(preg_match('/^[A-ZÁÉÍÓÚÀÈÌÒÙa-záéíóúàèìòù\s\,\.\/\\0-9\?\¿\¡\!]+$/', $_POST['description']))
			{
				$dc = mysqli_real_escape_string($dbc, trim($_POST['description']));
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
		// Check for a Registration Date 
		if (!empty($_POST['registrationDate'])) 
		{
			if(preg_match('/^([0-9]{4})(\-)(0[1-9]|1[0-2])(\-)([012][1-9]|3[0-1])$/', $_POST['registrationDate']))
			{
				$rd = mysqli_real_escape_string($dbc, trim($_POST['registrationDate']));
			}
			else
			{
					$errors[] = 'The input format of the Registration Date field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a Registration Date to continue.';
		}
		// Check for a Serial Date 
		if (!empty($_POST['serialNumber'])) 
		{
			if(preg_match('/^([0-9A-Z]{10})$/', $_POST['serialNumber']))
			{
				$sn = mysqli_real_escape_string($dbc, trim($_POST['serialNumber']));
			}
			else
			{
					$errors[] = 'The input format of the serial number field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a serial number to continue.';
		}
		// Check for a Model 
		if (!empty($_POST['model'])) 
		{
			if(preg_match('/^[A-ZÁÉÍÓÚÀÈÌÒÙa-záéíóúàèìòù0-9\s]{1,50}$/', $_POST['model']))
			{
				$ml = mysqli_real_escape_string($dbc, trim($_POST['model']));
			}
			else
			{
					$errors[] = 'The input format of the model field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a model to continue.';
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
		if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ0-9\/\.\,\(\)\?\¿\!\¡\-]{0,}+$/', $_POST['observations']))
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
			$q = "SELECT HardwareId FROM hardware WHERE TypeId='$ti'AND NumberOnLabel='$nl'AND Description='$dc'AND RegistrationDate='$rd'AND SerialNumber='$sn'AND Model='$ml'AND Departament='$dp'AND Observations='$ob'AND HardwareId != '$hardwareId'";
			$r = @mysqli_query($dbc, $q);
			if (mysqli_num_rows($r) == 0) 
			{
				// Make the query:
				$q = "UPDATE hardware SET TypeId='$ti',NumberOnLabel='$nl',Description='$dc',RegistrationDate='$rd',SerialNumber='$sn',Model='$ml',Departament='$dp',Observations='$ob' WHERE HardwareId='$hardwareId' LIMIT 1";
				$r = @mysqli_query ($dbc, $q);
				if (mysqli_affected_rows($dbc) == 1) 
				{ 
					// If it ran OK.
					// Print a message:
					echo '<p class="ok">The Hardware has been edited.</p>';	
				
				} 	
				else 
				{ 
					// If it did not run OK.
					echo '<p class="error">The Hardware could not be edited due to a system error.</p>'; // Public message.
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
	$q = "SELECT TypeId, NumberOnLabel,Description,RegistrationDate,SerialNumber,Model,Departament,Observations  FROM hardware WHERE HardwareId='$hardwareId'";		
	$r = @mysqli_query ($dbc, $q);
	if (mysqli_num_rows($r) == 1) 
	{ 
		// Valid deliveries ID, show the form.
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
				<a href="index.php" >Registered Deliveries</a>
			</div>
	<form action="edit.php" id="form" method="post" >
	<div class="line__uno">	
			<div class="line">
				<span class="line__label">Type ID:</span>
				<select name="typeId" class="line__select">
					<option value="' . $row[0] . '">' . $row[0] . '</option>';
						$q = "SELECT TypesId FROM types";
						$r = @mysqli_query ($dbc, $q);
						while ($rows = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
						{
	            			echo '<option value="'.$rows['TypesId'].'">'.$rows['TypesId'].'</option>';
	          			}
				echo '</select>
			</div>
			<div class="line">
				<span class="line__label">Number Of Label:</span>
				<input type="text" name="numberOfLabel" id="numberOfLabel" class="line__input" value="' . $row[1] . '" >
			</div>
			<div class="line">
				<span class="line__label">Serial Number:</span>
				<input type="text"name="serialNumber" id="serialNumber" class="line__input" value="' . $row[4] . '" >
			</div>
			<div class="line">
				<span class="line__label">Description:</span>
				<textarea name="description" id="description" class="line__input" >' . $row[2] . '</textarea>
			</div>
		</div>
		<div class="line__dos">
			<div class="line">
				<span class="line__label">Registration Date:</span>
				<input type="date" name="registrationDate" id="registrationDate" class="line__input" value="' . $row[3] . '" >
			</div>
			<div class="line">
				<span class="line__label">Model:</span>
				<input type="text" name="model" id="model" class="line__input" value="' . $row[5] . '" >
			</div>
			<div class="line">
				<span class="line__label">Departament:</span>
				<input type="text" name="departament" id="departament" class="line__input" value="' . $row[6] . '" >
			</div>
			<div class="line">
				<span class="line__label">Obervations:</span>
				<textarea name="observations" id="observations" class="line__input" >' . $row[7] . '</textarea>
			</div>
		</div>
		<div id="containerButton">
			<input id="button" type="submit"  value="Edit"/>
			<input type="hidden" name="HardwareId" value="' . $hardwareId . '" />
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
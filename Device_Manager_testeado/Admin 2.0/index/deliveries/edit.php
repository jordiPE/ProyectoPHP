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
$page_title = 'Deliveries Page';
include ('../includes/headerDeliveries.html');
echo '<h1>Edit Delivery</h1>';
// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['DeliveriesId'])) && (is_numeric($_GET['DeliveriesId'])) ) 
{ 
	// From index.php
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
		if (!empty($_POST['personalId'])) 
		{	
			if(preg_match('/^[0-9]+$/', $_POST['personalId']))
			{
				$pi = mysqli_real_escape_string($dbc, trim($_POST['personalId']));
			}
			else
			{
				$errors[] = 'The input format of the Personal ID field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a Personal ID to continue.';
		}
		 // Check for a Hardware ID
		if (!empty($_POST['hardwareId'])) 
		{	
			if(preg_match('/^[0-9]+$/', $_POST['hardwareId']))
			{
				$hi = mysqli_real_escape_string($dbc, trim($_POST['hardwareId']));
			}
			else
			{
				$errors[] = 'The input format of the Hardware ID field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a Hardware ID to continue.';
		} 
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
		// Check for a observations, This option can be null 
		if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ0-9\/\.\,\(\)\?\¿\!\¡\-]{0,}+$/', $_POST['observations']))
		{
			$ob = mysqli_real_escape_string($dbc, trim($_POST['observations']));
		}
		else
		{
			$errors[] = 'The input format of the observations field is not correct.';
		}
		// Check for a Date Of Delivery 
		if (!empty($_POST['dateOfDelivery'])) 
		{
			if(preg_match('/^([0-9]{4})(\-)(0[1-9]|1[0-2])(\-)([012][1-9]|3[0-1])$/', $_POST['dateOfDelivery']))
			{
				$dd = mysqli_real_escape_string($dbc, trim($_POST['dateOfDelivery']));
			}
			else
			{
					$errors[] = 'The input format of the Date Of Delivery field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a Date Of Delivery to continue.';
		}
		// Check for a Delivery Status 
		if (!empty($_POST['deliveryStatus'])) 
		{
			if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ0-9\/\.\,\(\)\?\¿\!\¡]{0,}+$/', $_POST['deliveryStatus']))
			{
				$ds = mysqli_real_escape_string($dbc, trim($_POST['deliveryStatus']));
			}
			else
			{
					$errors[] = 'The input format of the Delivery Status field is not correct.';
			}
		} 
		else 
		{
			$errors[] = 'Please enter a Delivery Status to continue.';
		}
		// Check for a Return Date ,This option can be null 
		if(preg_match('/^([0-9]{4})(\-)(0[1-9]|1[0-2])(\-)([012][1-9]|3[0-1])$/', $_POST['returnDate']) || empty($_POST['returnDate']))
			{
				$rd = mysqli_real_escape_string($dbc, trim($_POST['returnDate']));
			}
			else
			{
				echo $_POST['returnDate'];
					$errors[] = 'The input format of the Return Date field is not correct.';
			}
		// Check for a Return Status ,This option can be null 
		if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ0-9\/\.\,\(\)\?\¿\!\¡]{0,}+$/', $_POST['returnStatus']))
			{
				$rs = mysqli_real_escape_string($dbc, trim($_POST['returnStatus']));
			}
			else
			{
					$errors[] = 'The input format of the Return Status field is not correct.';
			}
		// Check for a departament 
		if (!empty($_POST['dep'])) 
		{
			if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ]{1,40}$/', $_POST['dep']))
			{
				$dp = mysqli_real_escape_string($dbc, trim($_POST['dep']));
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

		if (empty($errors)) 
		{ 
			// If everything's OK.
			//  Test for unique email address:
			$q = "SELECT DeliveriesId FROM deliveries WHERE PersonalId='$pi'AND HardwareId='$hi'AND FullName='$fl'AND Observations='$ob'AND DateOfDelivery='$dd'AND DeliveryStatus='$ds'AND ReturnDate='$rd'AND ReturnStatus='$rs'AND Departament='$dp'AND  DeliveriesId != '$deliveriesId'";
			$r = @mysqli_query($dbc, $q);
			if (mysqli_num_rows($r) == 0) 
			{
				// Make the query:
				$q = "UPDATE deliveries SET PersonalId='$pi',HardwareId='$hi', FullName='$fl',Observations='$ob',dateOfDelivery='$dd',DeliveryStatus='$ds',ReturnDate='$rd',ReturnStatus='$rs',Departament='$dp' WHERE DeliveriesId='$deliveriesId' LIMIT 1";
				$r = @mysqli_query ($dbc, $q);
				if (mysqli_affected_rows($dbc) == 1) 
				{ 
					// If it ran OK.
					// Print a message:
					echo '<p class="ok">The Delivery has been edited.</p>';	
				
				} 	
				else 
				{ 
					// If it did not run OK.
					echo '<p class="error">The Delivery could not be edited due to a system error.</p>'; // Public message.
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
	$q = "SELECT PersonalId, HardwareId, FullName,Observations,DateOfDelivery,DeliveryStatus,ReturnDate,ReturnStatus,Departament  FROM deliveries WHERE DeliveriesId='$deliveriesId'";		
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
				<span class="line__label">Staff ID:</span>
				<select name="personalId" class="line__select">
					<option value="' . $row[0] . '">'.$row[0].'</option>';
						$qu = "SELECT PersonalId FROM personal";
						$ru = @mysqli_query ($dbc, $qu);
						while ($rows = mysqli_fetch_array($ru, MYSQLI_ASSOC)) 
						{
	            			echo '<option value="'.$rows['PersonalId'].'">'.$rows['PersonalId'].'</option>';
	          			}
				echo '</select>
			</div>
			<div class="line">
				<span class="line__label">Hardware ID:</span>
				<select name="hardwareId" class="line__select">
					<option value="' . $row[1] . '">'.$row[1].'</option>';
						$qd = "SELECT HardwareId FROM hardware";
						$rd = @mysqli_query ($dbc, $qd);
						while ($rows = mysqli_fetch_array($rd, MYSQLI_ASSOC)) 
						{
	            			echo '<option value="'.$rows['HardwareId'].'">'.$rows['HardwareId'].'</option>';
	          			}
							echo '</select>
						</div>
						<div class="line">
							<span class="line__label">Full Name:</span>
							<input type="text" name="fullName" id="fullName" class="line__input" value="' . $row[2] . '" >
						</div>
						<div class="line">
							<span class="line__label">Observations:</span>
							<textarea name="observations" id="observations" class="line__input" >' . $row[3] . '</textarea>
						</div>
					</div>
					<div class="line__dos">
						<div class="line">
							<span class="line__label">Date Of Delivery:</span>
							<input type="date" name="dateOfDelivery" id="dateOfDelivery" class="line__input" value="' . $row[4] . '" >
						</div>
						<div class="line">
							<span class="line__label">Delivery Status:</span>
							<textarea name="deliveryStatus" id="deliveryStatus" class="line__area">' . $row[5] . '</textarea>
						</div>
						<div class="line">
							<span class="line__label">Return Date:</span>
							<input type="date" name="returnDate" id="retrunDate" class="line__input" value="' . $row[6] . '" >
						</div>
						<div class="line">
							<span class="line__label">Return Status:</span>
							<textarea  name="returnStatus" id="returnStatus" class="line__area">' . $row[7] . '</textarea>
						</div>
						<div class="line">
							<span class="line__label">Departament:</span>
							<input type="text" name="dep" id="departament" class="line__input" value="' . $row[8] . '" >
						</div>
					</div>
					<div id="containerButton">
						<input id="button" type="submit"  value="Edit"/>
						<input type="hidden" name="DeliveriesId" value="' . $deliveriesId . '" />
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
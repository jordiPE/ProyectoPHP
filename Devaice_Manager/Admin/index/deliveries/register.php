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
echo '<h1>Register Deliveries</h1>';
?>
	
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
		// Connect to the db.
		require('../mysqli_connect.php');
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
		if(preg_match('/^([0-9]{4})(\-)(0[1-9]|1[0-2])(\-)([012][1-9]|3[0-1])$/', $_POST['returnDate']))
			{
				$rd = mysqli_real_escape_string($dbc, trim($_POST['returnDate']));
			}
				elseif(empty($_POST['returnDate']))
				{

					$rd = mysqli_real_escape_string($dbc, trim(" "));
				}
			else
			{
					$errors[] = 'The input format of the Return Date field is not correct.';
			}
		// Check for a Return Status 
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
			if(preg_match('/^[\sA-ZÁÉÍÓÚÀÈÌÒÙÑa-záéíóúàèìòùñ]{1,20}$/', $_POST['dep']))
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
		// If everything's OK.
		if (empty($errors)) 
		{ 
			// Register the Delivery in the database...
			// Make the query:
			$q = "INSERT INTO deliveries (DeliveriesId,PersonalId,HardwareId,FullName,Observations,dateOfDelivery,DeliveryStatus,ReturnDate,ReturnStatus,Departament) VALUES ('0','$pi','$hi','$fl','$ob','$dd','$ds','$rd','$rs','$dp')";
			// Run the query.		
			$r = @mysqli_query ($dbc, $q);
			// If it ran OK.
			if ($r) 
			{ 
				// Print a message:
				echo '<h1 class="ok">:)! Registered Delivery</h1>';
			} 
			else 
			{ 
				// If it did not run OK.
				// Public message:
				echo '<h1 class="error">:(! A problem has occurred.</h1><p class="error">The new Delivery is not registered in the database due to an internal error.</p>'; 
				// Debugging message:
				//echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
			} 
			// End of if ($cr) IF.	
		}
		else
		{
			$errors[] = 'There are errors in the data entered in the form.';
		}
		// Close the database connection.
		mysqli_close($dbc); 
		// Quit the script.
		//exit();
		if (!empty($errors)) 
				{ 
					// Report the errors.
					echo '<h1 class="error">Error!</h1><p class="error">The following error(s) occurred:<br />';
					foreach ($errors as $msg) 
					{	 
						// Print each error.
						echo " - $msg<br />\n";
					}
					echo '</p><p class="ad">Please try again.</p><p><br /></p>';
				} 
}
	?>
	<section>
	<div id="sectionContainer">
		<div id="formContainer">
			<div class="link">
				<a href="../../loggedin.php" >Home Page</a>
			</div>
			<div class="link">
				<a href="index.php" >Registered Deliveries</a>
			</div>
	<form action="register.php" id="form" method="post" >
	<div class="line__uno">	
			<div class="line">
				<span class="line__label">Staff ID:</span>
				<select name="personalId" class="line__select">
					<option value="0">Select a Staff ID</option>
					<?php
						// Connect to the db.
						require('../mysqli_connect.php');
						$q = "SELECT PersonalId FROM personal";
						$r = @mysqli_query ($dbc, $q);
						while ($rows = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
						{
	            			echo '<option value="'.$rows['PersonalId'].'">'.$rows['PersonalId'].'</option>';
	          			}
	          			// Close the database connection.
						mysqli_close($dbc); 
					 ?>
				</select>
			</div>
			<div class="line">
				<span class="line__label">Hardware ID:</span>
				<select name="hardwareId" class="line__select">
					<option value="0">Select a Staff ID</option>
					<?php
						// Connect to the db.
						require('../mysqli_connect.php');
						$q = "SELECT HardwareId FROM hardware";
						$r = @mysqli_query ($dbc, $q);
						while ($rows = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
						{
	            			echo '<option value="'.$rows['HardwareId'].'">'.$rows['HardwareId'].'</option>';
	          			}
	          			// Close the database connection.
						mysqli_close($dbc); 
					 ?>
				</select>
			</div>
			<div class="line">
				<span class="line__label">Full Name:</span>
				<input type="text" name="fullName" id="fullName" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Observations:</span>
				<textarea name="observations" id="observations" class="line__input" ></textarea>
			</div>
		</div>
		<div class="line__dos">
			<div class="line">
				<span class="line__label">Date Of Delivery:</span>
				<input type="date" name="dateOfDelivery" id="dateOfDelivery" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Delivery Status:</span>
				<textarea name="deliveryStatus" id="deliveryStatus" class="line__area"></textarea>
			</div>
			<div class="line">
				<span class="line__label">Return Date:</span>
				<input type="date" name="returnDate" id="retrunDate" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Return Status:</span>
				<textarea  name="returnStatus" id="returnStatus" class="line__area"></textarea>
			</div>
			<div class="line">
				<span class="line__label">Departament:</span>
				<input type="text" name="dep" id="departament" class="line__input">
			</div>
		</div>
		<div id="containerButton">
			<input id="button" type="submit"  value="Register"/>
		</div>
	</form>
</div>
</div>
</section>
<?php
include ('../includes/footer.html');
?>




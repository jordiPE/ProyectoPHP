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
echo '<h1>Register Hardware</h1>';
?>
	
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
		// Connect to the db.
		require('../mysqli_connect.php');
		// Initialize an error array.
		$errors = array();
		// Check for a Type Id
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
			if(preg_match('/^[0-9]{3}$/', $_POST['numberOfLabel']))
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
			if(preg_match('/^[A-ZÑÁÉÍÓÚÀÈÌÒÙ][a-zñáéíóúàèìòù]+(\s[A-ZÑÁÉÍÓÚÀÈÌÒÙ][a-zññáéíóúàèìòù]+)$/', $_POST['description']))
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
			if(preg_match('/^([A-Z]{2}[0-9]{8})$/', $_POST['serialNumber']))
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
			if(preg_match('/^[A-Za-z0-9]{1,50}$/', $_POST['model']))
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
		// If everything's OK.
		if (empty($errors)) 
		{ 
			// Register the Delivery in the database...
			// Make the query:
			$q = "INSERT INTO hardware (HardwareId,TypeId,NumberOnLabel,Description,RegistrationDate,SerialNumber,Model,Departament,Observations) VALUES ('0','$ti','$nl','$dc','$rd','$sn','$ml','$dp','$ob')";
			// Run the query.		
			$r = @mysqli_query ($dbc, $q);
			// If it ran OK.
			if ($r) 
			{ 
				// Print a message:
				echo '<h1 class="ok">:)! Registered Hardware</h1>';
			} 
			else 
			{ 
				// If it did not run OK.
				// Public message:
				echo '<h1 class="error">:(! A problem has occurred.</h1><p class="error">The new Hardware is not registered in the database due to an internal error.</p>'; 
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
				<span class="line__label">Type ID:</span>
				<select name="typeId" class="line__select">
					<option value="0">Select a Type ID</option>
					<?php
						// Connect to the db.
						require('../mysqli_connect.php');
						$q = "SELECT TypesId FROM types";
						$r = @mysqli_query ($dbc, $q);
						while ($rows = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
						{
	            			echo '<option value="'.$rows['TypesId'].'">'.$rows['TypesId'].'</option>';
	          			}
	          			// Close the database connection.
						mysqli_close($dbc); 
					 ?>
				</select>
			</div>
			<div class="line">
				<span class="line__label">Number Of Label:</span>
				<input type="text" name="numberOfLabel" id="numberOfLabel" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Serial Number:</span>
				<input type="text"name="serialNumber" id="serialNumber" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Description:</span>
				<textarea name="description" id="description" class="line__input" ></textarea>
			</div>
		</div>
		<div class="line__dos">
			<div class="line">
				<span class="line__label">Registration Date:</span>
				<input type="date" name="registrationDate" id="registrationDate" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Model:</span>
				<input type="text" name="model" id="model" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Departament:</span>
				<input type="text" name="departament" id="departament" class="line__input">
			</div>
			<div class="line">
				<span class="line__label">Obervations:</span>
				<textarea name="observations" id="observations" class="line__input" ></textarea>
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




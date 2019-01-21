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
echo '<h1>Register Staff</h1>';
?>
	
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		// Connect to the db.
		require('../mysqli_connect.php');
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

		// If everything's OK.
		if (empty($errors)) 
		{ 
			//We check if the record we are trying to insert already exists in the database.
			$cq = "SELECT FullName FROM personal WHERE FullName ='$fl'";
			// Run the query.
			$cr = @mysqli_query ($dbc, $cq);
			$crow = mysqli_fetch_array ($cr, MYSQLI_ASSOC);
			if (mysqli_num_rows($cr) == 0)
			{
				echo $crow['FullName'];
				// Register the user in the database...
				// Make the query:
				$q = "INSERT INTO personal (PersonalId,FullName,Phone, Department,Observations) VALUES ('0','$fl','$ph','$dp','$ob')";
				// Run the query.		
				$r = @mysqli_query ($dbc, $q);
				// If it ran OK.
				if ($r) 
				{ 
					// Print a message:
					echo '<h1 class="ok">:)! Registered Staff</h1>';
					
					
				} 
				else 
				{ 
					// If it did not run OK.
					// Public message:
					echo '<h1 class="error">:(! A problem has occurred.</h1><p class="error">The new Staff is not registered in the database due to an internal error.</p>'; 
					// Debugging message:
					//echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
				} 
				// End of if ($cr) IF.	
			}
			else
			{
				$errors[] = 'The full name of the staff you are trying to register already exists in the database.';
			}
			// Close the database connection.
			mysqli_close($dbc); 
			// Quit the script.
			//exit();
		}
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
				<a href="index.php" >Registered Staff</a>
			</div>
	<form action="register.php" id="form" method="post" >	
		<div class="line">
			<span class="line__label">Full Name:</span>
			<input type="text" name="fullName" id="fullName" class="line__input">
		</div>
		<div class="line">
			<span class="line__label">Phone:</span>
			<input type="text" name="phone" id="phone" class="line__input">
		</div>
		<div class="line">
			<span class="line__label">Departament:</span>
			<input type="text" name="departament" id="departament" class="line__input">
		</div>
		<div class="line">
			<span class="line__label">Observations:</span>
			<textarea name="observations" id="observations" class="line__input" ></textarea>
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




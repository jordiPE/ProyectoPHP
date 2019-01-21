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
echo '<h1>Register Types</h1>';
?>
	
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		// Connect to the db.
		require('../mysqli_connect.php');
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
		// If everything's OK.
		if (empty($errors)) 
		{ 
			//We check if the record we are trying to insert already exists in the database.
			$cq = "SELECT Acronym FROM types WHERE Acronym ='$a'";
			// Run the query.
			$cr = @mysqli_query ($dbc, $cq);
			$crow = mysqli_fetch_array ($cr, MYSQLI_ASSOC);
			if (mysqli_num_rows($cr) == 0)
			{
				// Register the user in the database...
				// Make the query:
				$q = "INSERT INTO types (TypesId,Description,Acronym) VALUES ('0','$d','$a')";
				// Run the query.		
				$r = @mysqli_query ($dbc, $q);
				// If it ran OK.
				if ($r) 
				{ 
					// Print a message:
					echo '<h1 class="ok">:)! Registered Types</h1>';
					
					
				} 
				else 
				{ 
					// If it did not run OK.
					// Public message:
					echo '<h1 class="error">:(! A problem has occurred.</h1><p class="error">The new Type is not registered in the database due to an internal error.</p>'; 
					// Debugging message:
					//echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
				} 
				// End of if ($cr) IF.	
			}
			else
			{
				$errors[] = 'The Acronym of the Type you are trying to register already exists in the database.';
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
				<a href="index.php" >Registered Types</a>
			</div>
	<form action="register.php" id="form" method="post" >	
		<div class="line">
			<span class="line__label">Acronym:</span>
			<input type="text" name="acronym" id="acronym" class="line__input">
		</div>
		<div class="line">
			<span class="line__label">Description:</span>
			<textarea name="description" id="description" class="line__input"></textarea>
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




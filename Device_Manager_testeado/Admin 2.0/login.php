<?php # Script 3.4 - index.php
// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	// Need two helper files:
	require ('mysqli_connect.php');
	// Check the login:
	// Initialize error array.
	$errors = array(); 
	// Validate the user:
	if (empty($_POST['user']))  
	{
		$errors[] = 'You forgot to enter your correct user.';
	} 
	else 
	{
		$e = mysqli_real_escape_string($dbc, trim($_POST['user']));
	}

	// Validate the password:
	if (empty($_POST['password'])) 
	{
		
		$errors[] = 'You forgot to enter your password.';
	} 
	else 
	{
		$p = mysqli_real_escape_string($dbc, trim($_POST['password']));
	}

	if (empty($errors)) 
	{	
		// Retrieve the user_id and first_name for that email/password combination:
		$q = "SELECT Name, Password FROM admin WHERE Name ='$e' AND Password = sha1('$p')";
		// Run the query.			
		$r = @mysqli_query ($dbc, $q); 
		// Check the result:
		if (mysqli_num_rows($r) == 1) 
		{
			// Fetch the record:
			$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
			// Set the session data:
			if($row['Password'] == sha1($_POST['password']) && $row['Name'] == $_POST['user'])
			{
				session_start();
				$_SESSION['user'] = $row['Name'];
				$_SESSION['password'] = $row['Password'];
				 // Redirect the user:
				header("Location:loggedin.php");
				// Quit the script.
				exit();
			}
		} 
		else 
		{	 
			// Not a match!
			$errors[] = 'No record with the entered data has been found..';
		}
		// End of if (empty($errors)) IF.
		// // Close the database connection.
		mysqli_close($dbc); 
	}
}	
	$page_title = 'Login';
	include ('includes/header.html');
	
?>
	<section>
		<div id="sectionContainer">
		<?php
			if (!empty($errors)) 
			{ 
				// Report the errors.
				echo '<h1>Error!</h1><p class="error">The following error(s) occurred:<br />';
				foreach ($errors as $msg) 
				{ 
					// Print each error.
					echo " - $msg<br/>\n";
				}
				echo '</p><p class="ad">Please try again.</p><p><br /></p>';
			}
		?>	
			<div id="formContainer">
				<form action="login.php" id="form" method="post">
					<div class="line">
					    <span class="line__label">User:</span>
					    <input type="text" name="user" id="user" class="line__input">
					</div>
					<div class="line">
					    <span class="line__label">Password:</span>
					    <input type="password" name="password" id="password" class="line__input">
					</div>
					  <div id="containerButton">
				        <input id="button" type="submit"  value="Accept"/>
			        </div>
			        <div id="register">
			        	<p><a href="register_admin.php">Register a new administrator for the database.</a></p>
			        </div>
				</form>
			</div>
		</div>
	</section>
	<?php
	include ('includes/footer.html');
	?>
</body>
</html>
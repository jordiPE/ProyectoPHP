<?php # Script 3.4 - index.php
$page_title = 'register Admin';
include ('includes/header.html');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		// Connect to the db.
		require ('mysqli_connect.php');
		// Initialize an error array.
		$errors = array();  
		// Check for a user
		if (empty($_POST['user'])) 
		{	
				$errors[] = 'Please enter a user to continue.';
		} 
		else 
		{
			if(preg_match('/^[a-zA-z]{1,10}$/', $_POST['user']))
			{
				$u = mysqli_real_escape_string($dbc, trim($_POST['user']));
			}
			else
			{
				$errors[] = 'Please enter a format correct user to continue.';
			}
		}
		// Check for a password and match against the confirmed password:
		if (!empty($_POST['password'])) 
		{
			if ($_POST['password'] != $_POST['RepeatPassword']) 
			{
				$errors[] = 'There is no password match, please enter your password again.';
			} 
			else 
			{
				if(preg_match('/^[A-Za-z0-9]{6}$/', $_POST['password']))
				{
					$p = mysqli_real_escape_string($dbc, trim($_POST['password']));
				}
				else
				{
					$errors[] = 'Please enter a format correct password to continue.';
				}
			}
		} 
		else 
		{
			$errors[] = 'Please enter a password to continue.';
		}
		// If everything's OK.
		if (empty($errors)) 
		{ 
			// Register the user in the database...
			// Make the query:
			$q = "INSERT INTO admin (AdminId,Name, Password) VALUES ('0','$u',sha1('$p'))";
			// Run the query.		
			$r = @mysqli_query ($dbc, $q);
				// If it ran OK. 
				if ($r) 
				{ 
					// Print a message:
					echo '<h1>:)! Registered user</h1><p class="ad">The new administrator user has registered in the database.</p><p><br /></p>';	
					echo '<p><a class="ad" href="login.php">Back to principal page.</a></p>';
				} 
				else 
				{ 
					// If it did not run OK.
					// Public message:
					echo '<h1>:(! A problem has occurred.</h1><p class="error">The new user is not registered in the database due to an internal error.</p>'; 
					// Debugging message:
					echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
				} 
			// End of if ($r) IF.
			// Close the database connection.
			mysqli_close($dbc); 
			// Include the footer and quit the script:
			include ('includes/footer.html'); 
			//exit();
		}
		
	}
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
						echo " - $msg<br />\n";
					}
					echo '</p><p class="ad">Please try again.</p><p><br /></p>';
				} 
			?>
			<div id="formContainer">
				<form action="register_admin.php" id="form" method="post">
					<div class="line">
					    <span class="line__label">User:</span>
					    <input type="text" name="user" id="user" class="line__input">
					</div>
					<div class="line">
					    <span class="line__label">Password:</span>
					    <input type="password" name="password" id="password" class="line__input">
					</div>
					<div class="line">
					    <span class="line__label">Repeat password:</span>
					    <input type="password" name="RepeatPassword" id="password" class="line__input">
					</div>
					  <div id="containerButton">
				        <input id="button" type="submit"  value="Register"/>
			        </div>
			        <div id="register">
			        	<p><a href="login.php">Back to principal page.</a></p>
			        </div>
				</form>
			</div>
		</div>
	</section>
	<?php
	include ('includes/footer.html');
	?>
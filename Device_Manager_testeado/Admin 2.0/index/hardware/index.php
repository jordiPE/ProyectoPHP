<?php 
// Start the session.
session_start();
// If no session variable exists, redirect the user:
if (!isset($_SESSION['user'])) {
	header("Location:../../login.php");
	// Quit the script.
	exit();	
	
}
$page_title = 'Hardware Page';
include ('../includes/headerDeliveries.html');
echo '<h1>Registered Hardware</h1>';
?>
	<section>
	<div id="sectionContainer">
		<div id="formContainer">
			<div class="link">
				<a href="../../loggedin.php" >Home Page</a>
			</div>
			<div class="link">
				<a href="register.php" >Register Hardware</a>
			</div>
<?php
require('../mysqli_connect.php');
// Number of records to show per page:
$display = 10;
	// Determine how many pages there are,Where $ _Get ['p'] are the pages where the database records will be loaded.
	if (isset($_GET['p']) && is_numeric($_GET['p'])) 
	{ 
		// Already been determined.
		$pages = $_GET['p'];

	} 
	else 
	{ 
		// Need to determine, Count the number of records:
		$q = "SELECT COUNT(HardwareId) FROM hardware";
		$r = @mysqli_query ($dbc, $q);
		$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
		$records = $row[0];

		// Calculate the number of pages...
		if ($records > $display) 
		{ 
			// More than 1 page.
			$pages = ceil ($records/$display);
		} 
		else 
		{ 
			$pages = 1;
		}
		
	} // End of p IF.
	// Determine where in the database to start returning results, Where $ _Get ['s'] are the records of the database that we will show on each page.
	if (isset($_GET['s']) && is_numeric($_GET['s'])) 
	{
		$start = $_GET['s'];
	} 
	else 
	{
		$start = 0;
	}
	// Define the query:
	$q = "SELECT HardwareId,TypeId,NumberOnLabel,Description,RegistrationDate,SerialNumber,Model,Departament,Observations FROM hardware ORDER BY HardwareId ASC LIMIT $start, $display";		
	$r = @mysqli_query ($dbc, $q);
	// Fetch and print all the records....
	echo '<table class="table" cellspacing="0" cellpadding="3" width="95%">
	<tr>
		<td align="left"><b>Edit</b></td>
		<td align="left"><b>Delete</b></td>
		<td align="left"><b>Type ID</b></td>
		<td align="left"><b>Number On Label</b></td>
		<td align="left"><b>Description</b></td>
		<td align="left"><b>Registration Date</b></td>
		<td align="left"><b>Serial Number</b></td>
		<td align="left"><b>Model</b></td>
		<td align="left"><b>Departament</b></td>
		<td align="left"><b>Observations</b></td>
	</tr>';
	// Set the initial background color.
	$bg = '#F0F9F5';

	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	// Switch the background color.	
	$bg = ($bg=='#F0F9F5' ? '#D1D6D6' : '#F0F9F5'); 
	
	echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit.php?HardwareId=' . $row['HardwareId'] . '"><i class="fas fa-check-circle"></i></a></td>
		<td align="left"><a href="delete.php?HardwareId=' . $row['HardwareId'] . '"><i class="fas fa-check-circle"></i></a></td>
		<td align="left">' . $row['TypeId'] . '</td>
		<td align="left">' . $row['NumberOnLabel'] . '</td>
		<td align="left">' . $row['Description'] . '</td>
		<td align="left">' . $row['RegistrationDate'] . '</td>
		<td align="left">' . $row['SerialNumber'] . '</td>
		<td align="left">' . $row['Model'] . '</td>
		<td align="left">' . $row['Departament'] . '</td>
		<td align="left">' . $row['Observations'] . '</td>
	</tr>
	';
	
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r);
mysqli_close($dbc);
// Make the links to other pages, if necessary.
if ($pages > 1) {
	
	// Add some spacing and start a paragraph:
	echo '<br /><p class="next">';
	
	// Determine what page the script is on:	
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous link:
	if ($current_page != 1) {
		echo '<a href="index.php?s=' . ($start - $display) . '&p=' . $pages . '"><i class="fas fa-chevron-left"></i></a> ';
	}
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="index.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="index.php?s=' . ($start + $display) . '&p=' . $pages . '"><i class="fas fa-chevron-right"></i></a>';
	}
	
	echo '</p>'; // Close the paragraph.
	
} // End of links section.
?>
		</div>
	</section>
<?php
include ('../includes/footer.html');
?>
<?php
	require_once('application_top.php');
	require_once('header.php');
	require_once('customer_toolbar.php');
?>

<div id="content">
		<div class="post">
			<h1 class="title"><strong>New</strong> Customer
				 </h1>
			<div class="entry">
				<p>
				<?php
					echo "<form action='customer_save.php' method='post'>";
					echo "Customer Name:<br><input type='text' name='customername' value=''><br>";
					echo "Address:<br><textarea rows='5' cols='30' Name='address'></textarea><br>";
					echo "City:<br><input type='text' name='city' value=''><br>";
					echo "State:<br><input type='text' name='state' value=''><br>";
					echo "Zip:<br><input type='text' name='zip' value=''><br>";
					echo "Type:<br>";
					echo "<select name='type'>";
					echo "<option value='Main Office'>Main Office</option>";
					echo "<option value='Bill To'>Bill To</option>";
					echo "<option value='Ship To'>Ship To</option>";
					echo "<option value='Remit To'>Remit To</option>";
					echo "<option value='Home'>Home</option>";
					echo "</select><br>";
					echo "Phone:<br><input type='text' name='phone' value=''><br>";
					echo "Email:<br><input type='text' name='email' value=''><br>";
					echo "<input type='submit' name='Save' value='Save' />";
					echo "</form>";

					if (isset($_POST['Save'])) {
						$customername = $_POST['customername'];
						$address = $_POST['address'];
						$city = $_POST['city'];
						$state = $_POST['state'];
						$zip = $_POST['zip'];
						$type = $_POST['type'];
						$phone = $_POST['phone'];
						$email = $_POST['email'];
						
						$fbsdk->saveCustomer($customername, null, "true", $address, $city, $zip, $type, $state, $phone, $email);
					}
				?>
				</p>
<?php
	require_once('footer.php');
?>
<?php
	require_once('application_top.php');
	require_once('header.php');
	require_once('customer_toolbar.php');
?>

<div id="content">
		<div class="post">
			<h1 class="title"><strong>customer </strong>information
				 </h1>
			<div class="entry">
				<p>
					<?php
						require_once('customer_box.php');
					   											
						customerBox("customer_data.php");
						//search2 is the name of the form in customer box	
						if (isset($_POST['search2'])) { 
							$cust = $_POST['search2'];
													
							// Get Specific Customer Data
							$fbsdk->GetCustomer('Get', $cust);
	
							// customer address information
							$address = $fbsdk->result['FbiMsgsRs']['CustomerGetRs']['Customer']['Addresses']['Address'];
							echo "{$address['Name']}<br/>
									{$address['Type']}<br/>
									{$address['Street']}<br/>
									{$address['City']} {$address['State']} {$address['Zip']}<br/><br/>";
							echo "<strong>Contact Information:</strong><br>";
							echo "<table border=0>";
							$contacts = $fbsdk->result['FbiMsgsRs']['CustomerGetRs']['Customer']['Addresses']['Address']['AddressInformationList']['AddressInformation'];

							if (count($contacts)) {
								foreach ($contacts AS $key=>$value) {
									$data = (array) $value;
									echo "<tr>  
											<td>{$data['Name']}</td>
											<td>{$data['Data']}</td>
											<td>{$data['Type']}</td>
										</tr>";
								}
							} else {
								echo "<b>No Contacts</b>";
							}
							echo "</table>";
						}
				?>
			</p>
<?php
	require_once('footer.php');
?>
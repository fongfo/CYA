<?php
	require_once('application_top.php');
	require_once('header.php');
	require_once('sales_toolbar.php');
?>
<div id="content">
		<div class="post">
			<h1 class="title"><strong>Sales </strong>Orders
				 </h1>
			<div class="entry">
				<p>
					<?php
						if (isset($_POST['c'])) {
							$name = $_POST['c'];
						} else {
							$name = '50032';
						}
					?>
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
							SO: <input type="text" name="c" value="<?php echo $name; ?>" />
							<input type="submit" value="Find" />
						</form>
					<?php
						if (isset($_POST['c'])) {
							// Get Sales Order
	    					$fbsdk->GetSO($_POST['c']);
							$so = $fbsdk->result['FbiMsgsRs']['LoadSORs']['SalesOrder'];
							if (!is_null($so)) {
								// the customers name
								echo "<br><strong>Customer:</strong><br>{$so['CustomerName']}<br>";
								// the customers bill to address
								echo "<br><strong>Bill To:</strong><br>{$so['BillTo']['AddressField']}<br>";
								// the cusotmers bill to city state and zip
								
								echo "{$so['BillTo']['City']}, {$so['BillTo']['State']} {$so['BillTo']['Zip']}";
								
								// the so items
								echo "<table border=0>";
								echo "<br><br><strong>Sales Order Items:</strong>";
	
								$soitems = $so ['Items']['SalesOrderItem'];
	
								$sumprice = array();
								if (count($soitems)) {
									foreach($soitems as $key=>$value){
										$value = (array) $value;
										echo "<tr>
												<td>{$value['ProductNumber']}</td>
												<td>{$value['Quantity']}</td>
												<td>{$value['UOMCode']}</td>
												<td>{$value['Description']}</td>
												<td align=\"right\">\${$value['TotalPrice']}</td>
											</tr>";
										// the array items for each line price
										$sumprice[] = $value['TotalPrice'];
									}
								}
								$sototalprice = array_sum($sumprice);
								$soGrandTotal = $so['TotalTax'] + $sototalprice;	
								echo "<tr>
										<td colspan=\"5\" align=\"right\">
											<br/><strong>Total:&nbsp;</strong> \${$sototalprice}<br/>
											<strong>Tax:&nbsp;</strong> \${$so['TotalTax']}<br/>
											<strong>Grand Total:&nbsp;</strong> \${$soGrandTotal}
										</td>
									  </tr>
								  </table>";
							} else {
								echo "<b>No order found</b>";
							}
						}
					?>
				</p>
<?php
	require_once('footer.php');
?>
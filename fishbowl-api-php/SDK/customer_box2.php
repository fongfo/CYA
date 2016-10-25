<?php
	require_once('application_top.php');
	
function customerBox2() {
	global $fbsdk;
    $fbsdk->getCustomer();

    //count how many customers
    $list = $fbsdk->result['FbiMsgsRs']['CustomerNameListRs']['Customers']['Name'];
    //add it to the drop down
		echo "Customer:<br>
					<select name=\"customername\">
						<option></option>";
		foreach ($list AS $key=>$value) {
			echo "<option>{$value}</option>";
		}
		echo "  </select><br>";
}
  
?>
<?php
	require_once('application_top.php');
	
function customerBox($pagename) {
	global $fbsdk;
    $fbsdk->getCustomer();

    //count how many customers
    $list = $fbsdk->result['FbiMsgsRs']['CustomerNameListRs']['Customers']['Name'];
    $total = count($list);
	//add it to the drop down
		echo "<form action=\"{$pagename}\" method=\"post\">
				Customer:<br>
					<select name=\"search2\">
						<option> </option>";
		foreach ($list AS $key=>$value) {
			echo "<option>{$value}</option>";
		}
		echo "  </select>
				<input name=\"sea\" type=\"submit\" value=\"go\"/>
			</form>";
		echo "Total Customers: {$total}<br><br><strong>Address Information: <br></strong>";
}

    
?>
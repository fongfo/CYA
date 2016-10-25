<?php
	require_once('application_top.php');
	require_once('header.php');
	require_once('sales_toolbar.php');
	require_once('customer_box2.php');
?>

<div id="content">
		<div class="post">
			<h1 class="title"><strong>New</strong> Sales Order
				 </h1>
			<div class="entry">
				<p>
				<?php
					//echo $fbsdk->errorCode . "<br/>" . $fbsdk->errorMsg . "<br/><br/>";
					echo "<form action='so_save.php' method='post'>";
					echo "Sales Order Number:<br><input type='text' name='sonumber' value=''><br>";
					customerBox2();
					//echo "Customer Name:<br><input type='text' name='customername' value=''><br>";
					echo "Tax Rate:<br><input type='text' name='taxname' value=''><br>";
					echo "Payment Terms:<br><input type='text' name='paymentterms' value=''><br>";
					
					echo "Class:<br><input type='text' name='class' value=''><br>";
					echo "location Group:<br><input type='text' name='locationgroup' value=''><br>";
					// put all of the products in to an array using 'product[]', later the variable product pulled using post will find this array
					echo "Product:<br><input type='text' name='product[]' value=''><br>";
					echo "Product2:<br><input type='text' name='product[]' value=''><br>";
					echo "Product3:<br><input type='text' name='product[]' value=''><br>";
					echo "Product4:<br><input type='text' name='product[]' value=''><br>";
					
					
					echo "<input type='submit' name='Save' value='Save' />";
					echo "</form>";

					if (isset($_POST['Save'])) {
						
						$data['number'] = $_POST['sonumber'];
						$data['taxName'] = $_POST['taxname'];
						$data['paymentTerms'] = $_POST['paymentterms'];
						$data['customerName'] = $_POST['customername'];
						$data['class'] = $_POST['class'];
						$data['locationGroup'] = $_POST['locationgroup'];
						$data['soitems'] = $_POST['product'];
						print_r($data);
						die();  
						$fbsdk->saveSO($data);
                                                //print_r($fbsdk->statusMsg);
						
					}
				?>
				</p>
<?php
	require_once('footer.php');
?>
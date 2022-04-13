<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
/* if (isset($_SESSION["user"])) {
	$username = $_SESSION["user"];
	echo "<br>";
	echo "Welcome, ".$username."!";
}
else {
    echo "<br>";
    echo "You must be logged in to access this page.";
}
*/
?>
</div>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$("form").hide();

			$("#method").change(function(){
				stateChange($(this).val());
			});

			function stateChange(stateValue){
				$("form").hide();

				switch(stateValue){
				case 'deposit':
					$("#depositForm").show();
					break;
				case 'withdraw':
					$("#withdrawForm").show();
					break;
				}
			}
		})
	</script>
</head>


<div>
	<p>Please Pick a Transaction Type:</p>
	<select id="method" name="method">
		<option value="">Choose an Option</option>
		<option value="deposit">Deposit</option>
		<option value="withdraw">Withdraw</option>
	</select><br><br><br>

	<form id="depositForm" method="POST">
		<input style="width: 25%; float: left;" type="number" id="depositAmount" name="depositAmount" placeholder="Amount to Deposit" required/><br><br><br>
		
		<select id="depositType" name="depositType" required>
			<option value="">Choose an Deposit Method</option>
			<option value="bankTransfer">Bank Transfer (2-3 days, No Fee)</option>
			<option value="cardDeposit">Credit/Debit (Instant, 2% Fee)</option>
		</select><br><br><br>

		<div>
        	<input style="float: left;" class="submitButton" type="submit" id="submitDeposit" name="submitDeposit" value="Deposit"/><br><br>
		</div>
    </form>

	<form id="withdrawForm" method="POST">
		<input style="width: 25%; float: left;" type="number" id="withdrawAmount" name="withdrawAmount" placeholder="Amount to Withdraw" required/><br><br><br>
		
		<select id="withdrawType" name="withdrawType" required>
			<option value="">Choose an Withdrawal Method</option>
			<option value="bankTransfer">Wire Transfer (2-3 days, No Fee)</option>
			<option value="cardDeposit">ACH Transfer (Instant, 2% Fee)</option>
		</select><br><br><br>

		<div>
        	<input style="float: left;" class="submitButton" type="submit" id="submitWithdraw" name="submitWithdraw" value="Withdraw"/><br><br>
		</div>
    </form>
</div>

<?php
	if(array_key_exists('submitDeposit', $_POST))
	{
		$depositAmount = "";
		if (isset($_POST["depositAmount"])) 
		 {
			$depositAmount = $_POST["depositAmount"];
			$flashMsg = "You have successfully deposited: $" . $depositAmount; 
			print($flashMsg);
			//flash($flashMsg); ARRAY TO STRING CONVERSION ERROR in flash.php line 10
		 }
	} 

	if(array_key_exists('submitWithdraw', $_POST))
	{
		$withdrawAmount = "";
		if (isset($_POST["withdrawAmount"])) 
		 {
			$withdrawAmount = $_POST["withdrawAmount"];
			$flashMsg = "You have successfully withdrawn: $" . $withdrawAmount; 
			print($flashMsg);
			//flash($flashMsg); ARRAY TO STRING CONVERSION ERROR 
		 }
	}
?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
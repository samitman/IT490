<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
if (isset($_SESSION["user"])) {
	$fname = $_SESSION["fname"];
	$balance = $_SESSION["balance"];
	$username = $_SESSION["username"];
	echo "<br>";
	echo "Your available balance is: $" .$balance;
}
else {
    echo "<br>";
    echo "You must be logged in to access this page.";
	die(header("Location: index.php"));
}
?>
</div>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="./js/transactScript.js"></script>
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
			<option value="">Choose a Deposit Method</option>
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
			<option value="">Choose a Withdrawal Method</option>
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

			//RMQ deposit process
			//username should be stored in session, see top of page
			$result = exec("python3 ./rmq/deposit.py $username $depositAmount");

			if ($result == 1){
				//display updated balance
				$_SESSION["balance"] += $depositAmount;
				$balance = $_SESSION["balance"];
				die(header("Location: home.php"));
				
			}

		 }
	} 

	if(array_key_exists('submitWithdraw', $_POST))
	{
		$withdrawAmount = "";

		if (isset($_POST["withdrawAmount"]) && ($_POST["withdrawAmount"] > $balance)) {
			flash("Error: Insufficient balance!");
		}

		if (isset($_POST["withdrawAmount"]) && $_POST["withdrawAmount"] <= $balance) 
		 {
			$withdrawAmount = $_POST["withdrawAmount"];
			$withdrawAmount*=-1;

			$withdrawMsg = $_POST["withdrawAmount"]; 

			//RMQ withdraw process
			//username should be stored in session, see top of page
			$result = exec("python3 ./rmq/deposit.py $username $withdrawAmount");

			if ($result == 1){
				//display updated balance
				$_SESSION["balance"] += ($withdrawAmount);
				$balance = $_SESSION["balance"];
				die(header("Location: home.php"));
			}

		 }
	}
?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
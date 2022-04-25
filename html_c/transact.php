<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
if (isset($_SESSION["user"])) {
	$username = $_SESSION["username"];
	$balance = $_SESSION["balance"];
	echo "<br>";
	echo "Welcome, ".$username."!";
	echo "<br>";
	#echo "Your available balance is: $" .$balance;
}
else {
    echo "<br>";
    echo "You must be logged in to access this page.";
	//die(header("Location: index.php"));
}
?>
</div>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="transactScript.js"></script>
	<script>

		const targetDiv = document.getElementById("balMsg");
		const btn = document.getElementById("submitDeposit");
		const btn1 = document.getElementById("submitWithdraw");

		btn.onclick = function () {
		if (targetDiv.style.display !== "none") {
			targetDiv.style.display = "none";
			}
		};

		btn1.onclick = function () {
		if (targetDiv.style.display !== "none") {
			targetDiv.style.display = "none";
			}
		};
		
	</script>
</head>

<div id="balMsg">
	<?php echo "Your available balance is: $" .$balance;?>
</div>

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
			$flashMsg = "You have successfully deposited: $" . $depositAmount; 
			print($flashMsg);
			//flash($flashMsg); ARRAY TO STRING CONVERSION ERROR in flash.php line 10


			//RMQ deposit process
			//username should be stored in session, see top of page
			$result = exec("python3 deposit.py $username $depositAmount");

			if ($result == 1){
				//display updated balance
				$_SESSION["balance"] += $depositAmount;
				$balance = $_SESSION["balance"];
				flash("Your available balance is now: $".$balance);
			}

			//echo $result;
		 }
	} 

	if(array_key_exists('submitWithdraw', $_POST))
	{
		$withdrawAmount = "";
		if (isset($_POST["withdrawAmount"])) 
		 {
			$withdrawAmount = $_POST["withdrawAmount"];
			$withdrawAmount*=-1;

			$withdrawMsg = $_POST["withdrawAmount"];
			$flashMsg = "You have successfully withdrawn: $" . $withdrawMsg; 
			print($flashMsg);
			//flash($flashMsg); ARRAY TO STRING CONVERSION ERROR 

			//RMQ withdraw process
			//username should be stored in session, see top of page
			$result = exec("python3 deposit.py $username $withdrawAmount");

			if ($result == 1){
				//display updated balance
				$_SESSION["balance"] += ($withdrawAmount);
				$balance = $_SESSION["balance"];
				flash("Your available balance is now: $".$balance);
			}

			//echo $result;
		 }
	}
?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
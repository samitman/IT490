<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<t>Welcome to the Transaction Center!</t><br>
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

<div style="text-align: center;">
	<t1>Please Pick a Transaction Type:</t1><br>
	<select id="method" name="method">
		<option value="" disabled selected>Choose an Option</option>
		<option value="deposit">Deposit</option>
		<option value="withdraw">Withdraw</option>
	</select><br>

	<form id="despositForm" method="POST">
		<input style="width: 100%" type="number" id="depositAmount" name="depositAmount" placeholder="Amount to Deposit" /><br><br><br>
	<div style="display: flex;">
        	<input style="float: right;" class="submitButton" type="submit" id="submitDeposit" name="submitDeposit" value="Deposit"/><br><br>
	</div>
    </form>

	<form id="withdrawForm" method="POST">
		<input style="width: 100%" type="number" id="withdrawAmount" name="withdrawAmount" placeholder="Amount to Withdraw" /><br><br><br>
	<div style="display: flex;">
        	<input style="float: right;" class="submitButton" type="submit" id="submitWithdraw" name="submitWithdraw" value="Withdraw"/><br><br>
	</div>
    </form>
</div>

<?php require_once(__DIR__ . "/partials/nav.php");?>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript">

		$(document).ready(function(){

			$("#method").change(function(){
				stateChange($(this).val());
			});

			function stateChange(stateValue){
				$("form").hide();

				switch(stateValue){
				case 'deposit':
					$("#depositForm").show();
				;
				case 'withdraw':
					$("#withdrawForm").show();
				;
				}
			}
		})
	</script>
</head>

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

<div>
	<p>Please Pick a Transaction Type:</p>
	<select id="method" name="method">
		<option value="" disabled selected>Choose an Option</option>
		<option value="deposit">Deposit</option>
		<option value="withdraw">Withdraw</option>
	</select><br><br><br>

	<form id="despositForm" method="POST">
		<input style="width: 25%; float: left;" type="number" id="depositAmount" name="depositAmount" placeholder="Amount to Deposit" /><br><br><br>
		<div>
        	<input style="float: left;" class="submitButton" type="submit" id="submitDeposit" name="submitDeposit" value="Deposit"/><br><br>
		</div>
    </form>

	<form id="withdrawForm" method="POST">
		<input style="width: 25%; float: left;" type="number" id="withdrawAmount" name="withdrawAmount" placeholder="Amount to Withdraw" /><br><br><br>
		<div>
        	<input style="float: left;" class="submitButton" type="submit" id="submitWithdraw" name="submitWithdraw" value="Withdraw"/><br><br>
		</div>
    </form>
</div>

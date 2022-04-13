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

<div>
	<t1>Please Pick a Transaction Type:</t1><br>
	<select id="method" name="method">
		<option value="" disabled selected>Choose an Option</option>
		<option value="deposit">Deposit</option>
		<option value="withdraw">Withdraw</option>
	</select>
</div>

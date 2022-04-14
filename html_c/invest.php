<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
/* if (isset($_SESSION["user"])) {
	$username = $_SESSION["user"];

    if (isset($_SESSION["balance"])) {
        $balance = $_SESSION["balance"];
    }
	echo "<br>";

	echo "Welcome, ".$username."!";
}
else {
    echo "<br>";
    echo "You must be logged in to access this page.";
}
*/
$balance = 20000;
?>
</div>

<div>
    <t>Welcome to the Investment Center!</t> <br>
    <p>Your available balance is: $<?php print($balance); ?></p>


	<form id="investForm" method="POST">

        <p>Please Choose Your Desired Portfolio:</p>
        <select id="portfolio" name="portfolio" required>
            <option value="">Choose a Portfolio</option>
            <option value="Aggressive">Aggressive</option>
            <option value="Boomer">Boomer</option>
            <option value="Crypto">Crypto</option>
            <option value="Growth">Growth</option>
            <option value="Meme">Meme</option>
            <option value="Moderate">Moderate</option>
            <option value="Tech">Tech</option>
        </select><br><br><br>

		<input style="width: 25%; float: left;" type="number" id="investAmount" name="investAmount" placeholder="Amount to Invest" required/><br><br><br>

		<div>
        	<input style="float: left;" class="submitButton" type="submit" id="submitInvest" name="submitInvest" value="Invest"/><br><br>
		</div>
    </form>
</div>

<?php
	if(array_key_exists('submitInvest', $_POST))
	{
		$investAmount = "";
        //$balance = $_SESSION["balance"];
        //balance should be stored in session at the top of page

		if (isset($_POST["investAmount"]) && isset($_POST["portfolio"]))
		 {
			$portfolio = $_POST["portfolio"];
            $investAmount = $_POST["investAmount"];

            if ($investAmount <= $balance)
            {
                $flashMsg = "You have successfully invested: $" . $investAmount . " into the Walnuts™ " . $portfolio . " portfolio!"; 
                print($flashMsg);
                //flash($flashMsg); ARRAY TO STRING CONVERSION ERROR in flash.php line 10

                //RMQ investing process
                $username = "test"; //username should be stored in session, see top of page
                $result = exec("python3 invest.py $username $investAmount $portfolio");
                echo $result;
            } else {
                print("Insufficient Balance... get your broke ass outta here.");
            }
		 }
	} 

?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
    if (isset($_SESSION["username"])) {
        $fname = $_SESSION["fname"];
        $username = $_SESSION["username"];

        if (isset($_SESSION["balance"])) {
            $balance = $_SESSION["balance"];
            $etfMeme = $_SESSION["etfMeme"];
			$etfBoomer = $_SESSION["etfBoomer"];
			$etfTech = $_SESSION["etfTech"];
			$etfCrypto = $_SESSION["etfCrypto"];
			$etfModerate = $_SESSION["etfModerate"];
			$etfAggressive = $_SESSION["etfAggressive"];
			$etfAggressive = $_SESSION["etfGrowth"];
        }
        echo "<br>";

        echo "Welcome, ".$fname."!";
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
	<script src="investScript.js"></script>
</head>

<div>
    <t>Welcome to the Investment Center!</t> <br>
    <p>Your available balance is: $<?php print($balance); ?></p>

    <p>Please Choose an Action:</p>
        <select id="action" name="action" required>
            <option value="">Choose an Option</option>
            <option value="buy">Buy</option>
            <option value="sell">Sell</option>
        </select><br><br>


	<form id="investForm" method="POST">

        <p>Please Choose Your Desired Portfolio:</p>
        <select id="portfolio" name="portfolio" required>
            <option value="">Choose a Portfolio</option>
            <option value="etfAggressive">Aggressive</option>
            <option value="etfBoomer">Boomer</option>
            <option value="etfCrypto">Crypto</option>
            <option value="etfGrowth">Growth</option>
            <option value="etfMeme">Meme</option>
            <option value="etfModerate">Moderate</option>
            <option value="etfTech">Tech</option>
        </select><br><br>

		<input style="width: 25%; float: left;" type="number" id="investAmount" name="investAmount" placeholder="Amount to Invest" required/><br><br><br>

		<div>
        	<input style="float: left;" class="submitButton" type="submit" id="submitInvest" name="submitInvest" value="Invest"/><br><br>
		</div>
    </form>

    <form id="sellForm" method="POST">

        <p>Please Choose Your Desired Portfolio:</p>
        <select id="portfolio" name="portfolio" required>
            <option value="">Choose a Portfolio</option>
            <option value="etfAggressive">Aggressive</option>
            <option value="etfBoomer">Boomer</option>
            <option value="etfCrypto">Crypto</option>
            <option value="etfGrowth">Growth</option>
            <option value="etfMeme">Meme</option>
            <option value="etfModerate">Moderate</option>
            <option value="etfTech">Tech</option>
        </select><br><br>

		<input style="width: 25%; float: left;" type="number" id="sellAmount" name="sellAmount" placeholder="Amount to Sell" required/><br><br><br>

		<div>
        	<input style="float: left;" class="submitButton" type="submit" id="submitSell" name="submitSell" value="Sell"/><br><br>
		</div>
    </form>
</div>

<?php
	if(array_key_exists('submitInvest', $_POST) || array_key_exists('submitSell', $_POST))
	{

		$investAmount = "";
        $action = "";
        $sellAmount = "";
        //$balance = $_SESSION["balance"];
        //balance should be stored in session at the top of page

		if (isset($_POST["investAmount"]) && isset($_POST["portfolio"]))
		 {
			$portfolio = $_POST["portfolio"];

            //determine if buying or selling
            if(array_key_exists('submitInvest', $_POST) &&  !array_key_exists('submitSell', $_POST)) {
                $action = "buy";
                $investAmount = $_POST["investAmount"];
            }
            if(array_key_exists('submitSell', $_POST) &&  !array_key_exists('submitInvest', $_POST)) {
                $action = "sell";
                $sellAmount = $_POST["sellAmount"];
            }
            
        
            if ($investAmount <= $balance) //or sellAmount <= holdings
            {
                if($action == "buy") {
                    //RMQ investing process
                    $result = exec("python3 invest.py $username $portfolio $investAmount");
                    //echo $result;
                    //result = (username, num shares, etf price, avail balance)
                    $numshares = $result[1];
                    $etfPrice = $result[2];
                    $balance = $result[3];
                    
                    $holdings = $numShares * $etfPrice;
                    //you now have <holdings> of $portfolio
                    //your new balance is <balance>
                    

                    $flashMsg = "You have successfully purchased: $" . $investAmount . " of the Walnuts™ " . $portfolio . " portfolio!"; 
                    
                    print($flashMsg);

                    echo"<br>";

                    $holdingsMsg = "Total " .$portfolio . " holdings: $" .$holdings;
                    print($holdingsMsg);

                    echo "<br>";

                    $balanceMsg = "Your available balance is: $".$balance;
                    print($balanceMsg);

                    echo "<br>";
                    //flash($flashMsg); ARRAY TO STRING CONVERSION ERROR in flash.php line 10

                    //update balance
                    //$_SESSION["balance"] -= $balance;
                    $balance -= $investAmount;
                    print("Your available balance is now: $" . $balance);
                    
                }

                if($action == "sell") {
                    //RMQ investing process
                    $result = exec("python3 sell.py $username $portfolio $sellAmount");
                    echo $result;
                    //response should be the share amount of the portfolio and the etf price

                    $flashMsg = "You have successfully sold: $" . $sellAmount . " of the Walnuts™ " . $portfolio . " portfolio!"; 
                    print($flashMsg);
                    echo "<br>";
                    //flash($flashMsg); ARRAY TO STRING CONVERSION ERROR in flash.php line 10

                    //update balance
                    //$_SESSION["balance"] -= $balance;
                    $balance -= $sellAmount;
                    print("Your available balance is now: $" . $balance);
                }
            
            } else {
                print("Insufficient Balance.");
            }
		 }
	} 

?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
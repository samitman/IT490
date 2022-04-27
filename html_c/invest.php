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
                    //result = (username, num shares, etf price, avail balance)
                    $numShares = floatval($result[1]);
                    $etfPrice = floatval($result[2]);
                    $balance = floatval($result[3]);
                    
                    $priceString = $portfolio . "Price"; //etfMemePrice

                    $_SESSION["balance"] = $balance;
                    $_SESSION[$portfolio] = $numShares; //session[etfMeme] = numShares
                    $_SESSION[$priceString] = $etfPrice; //session[etfMemePrice] = etfPrice
                    die(header("Location: home.php"));
                    
                }

                if($action == "sell" && ($sellAmount <= ($_SESSION[$portfolio] * $_SESSION[$portfolio."Price"]))) {
                    //RMQ investing process
                    $result = exec("python3 sell.py $username $portfolio $sellAmount");
                    
                    $numShares = floatval($result[1]);
                    $etfPrice = floatval($result[2]);
                    $balance = floatval($result[3]);
                    
                    $priceString = $portfolio . "Price"; //etfMemePrice

                    $_SESSION["balance"] = $balance;
                    $_SESSION[$portfolio] = $numShares; //session[etfMeme] = numShares
                    $_SESSION[$priceString] = $etfPrice; //session[etfMemePrice] = etfPrice
                    die(header("Location: home.php"));

                }
            
            } else {
                print("Insufficient Balance.");
            }
		 }
	} 

?>
<?php require(__DIR__ . "/partials/flash.php"); ?>
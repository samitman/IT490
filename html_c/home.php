<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
if (isset($_SESSION["user"])) {
	$fname = $_SESSION["fname"];
  $balance = $_SESSION["balance"];
	echo "<br>";
	echo "Welcome, ".$fname."!";
  echo "<br>";
  echo "Your available balance is: $" .$balance;
  echo "<br>";

  //holdings = numShares + etfPrice
  //memeHoldings = etfMeme * memePrice
  if(isset($_SESSION["etfMeme"]) && $_SESSION["etfMeme"] > 0) {
    echo "Total etfMeme Holdings: $" . ($_SESSION["etfMeme"] * $_SESSION["etfMemePrice"]);
  }
  if(isset($_SESSION["etfBoomer"]) && $_SESSION["etfBoomer"] > 0) {
    echo "Total etfBoomer Holdings: $" . ($_SESSION["etfBoomer"] * $_SESSION["etfBoomerPrice"]);
  }
  if(isset($_SESSION["etfTech"]) && $_SESSION["etfTech"] > 0) {
    echo "Total etfTech Holdings: $" . ($_SESSION["etfTech"] * $_SESSION["etfTechPrice"]);
  }
  if(isset($_SESSION["etfCrypto"]) && $_SESSION["etfCrypto"] > 0) {
    echo "Total etfCrypto Holdings: $" . ($_SESSION["etfCrypto"] * $_SESSION["etfCryptoPrice"]);
  }
  if(isset($_SESSION["etfModerate"]) && $_SESSION["etfModerate"] > 0) {
    echo "Total etfModerate Holdings: $" . ($_SESSION["etfModerate"] * $_SESSION["etfModeratePrice"]);
  }
  if(isset($_SESSION["etfAggressive"]) && $_SESSION["etfAggressive"] > 0) {
    echo "Total etfAggressive Holdings: $" . ($_SESSION["etfAggressive"] * $_SESSION["etfAggressivePrice"]);
  }
  if(isset($_SESSION["etfGrowth"]) && $_SESSION["etfGrowth"] > 0) {
    echo "Total etfGrowth Holdings: $" . ($_SESSION["etfGrowth"] * $_SESSION["etfGrowthPrice"]);
  }
  
}
else {
    echo "<br>";
    echo "Welcome, please log in!";
	die(header("Location: index.php"));
}
?>
</div>

<head>
	<link rel="stylesheet" href="./static/css/calculatorStyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="calculatorScript.js"></script>
</head>

<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
</div>

<div id="calculator" class="calculator">
  <label class="title">Growth Calculator</label>
  <hr/>
  <label>Initial Investment ($)</label>
  <input type="number" id="principle" value="1000" min="0">
    <br/>
  <label>Contributions ($)</label>
  <input type="number" id="contribution" value="100" min="0">
    <br/>
  <label>Frequency</label>
  <select id="frequency">
    <option value="1">Daily</option>
    <option value="7">Weekly</option>
    <option value="14">Bi-weekly</option>
    <option value="30" selected>Monthly</option>
    <option value="91">Quarterly</option>
    <option value="364">Annually</option>
  </select>
    <br/>
  <label>Expected Annual Return (%)</label>
  <input type="number" id="interest-rate" value="10" min="0">
    <br/>
  <label>Time Period (yrs)</label>
  <input type="number" id="period" value="10" min="1">
    <br/>
  <hr/>
  <div id="calc-result" class="result">
	  $0
  </div>
</div>



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
}
else {
    echo "<br>";
    echo "Welcome, please log in!";
	//die(header("Location: index.php"));
}
?>
</div>

<head>
	<link rel="stylesheet" href="./static/css/calculatorStyle.css">
  <link rel="stylesheet" href="./static/css/squares.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="./js/calculatorScript.js"></script>
</head>

<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
</div>

<div>
  <?php
  //displays investment holdings in each portfolio if they exist
    $portfolioList = ["etfMeme","etfBoomer","etfTech", "etfCrypto", "etfModerate", "etfAggressive", "etfGrowth"];
    foreach ($portfolioList as $portfolio) {
      if(isset($_SESSION[$portfolio]) && ($_SESSION[$portfolio] > 0) && isset($_SESSION[$portfolio."Price"])) {
        echo "Total ".substr($portfolio,3). " Portfolio Holdings: $" . ($_SESSION[$portfolio] * $_SESSION[$portfolio."Price"]);
        echo "<br>";
    }
  }
  ?>
</div>

<div><h3>Explore our curated portfolios:</h3></div><br>
<div class="container">
    <div class="square">
      <h4>Boomer</h4><br>
      <p>WMT</p><br>
      <p>KO</p><br>
      <p>JNJ</p><br>
      <p>T</p><br>
      <p>UNH</p><br>
    </div>
    <div class="square">
      <h4>Moderate</h4>
      <p>AAPL</p>
      <p>AMZN</p>
      <p>JNJ</p>
      <p>SPY</p>
      <p>BRK-A</p>
    </div>
    <div class="square">
      <h4>Aggressive</h4>
      <p>GOOGL</p>
      <p>TSLA</p>
      <p>NVDA</p>
      <p>FB</p>
      <p>BABA</p>
    </div>
    <div class="square">
      <h4>Growth</h4>
      <p>PLTR</p>
      <p>AMD</p>
      <p>TSLA</p>
      <p>UPST</p>
      <p>CRM</p>
    </div>
    <div class="square">
      <h4>Tech</h4>
      <p>AMD</p>
      <p>TSLA</p>
      <p>MSFT</p>
      <p>GOOGL</p>
      <p>AAPL</p>
    </div>
    <div class="square">
      <h4>Crypto</h4>
      <p>BTC</p>
      <p>ETH</p>
      <p>BNB</p>
      <p>XRP</p>
      <p>ADA</p>
    </div>
    <div class="square">
      <h4>Meme</h4>
      <p>GME</p>
      <p>AMC</p>
      <p>DOGE</p>
      <p>DWAC</p>
      <p>CLOV</p>
    </div>
</div><br><br>




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



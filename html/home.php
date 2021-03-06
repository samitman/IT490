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
    die(header("Location: index.php"));
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
    $totalInvestments = 0;
    foreach ($portfolioList as $portfolio) {
      if(isset($_SESSION[$portfolio]) && ($_SESSION[$portfolio] > 0) && isset($_SESSION[$portfolio."Price"])) {
        $holdings = $_SESSION[$portfolio] * $_SESSION[$portfolio."Price"];
        $roundedHoldings = number_format($holdings,2);
        echo "Total ".substr($portfolio,3). " Portfolio Holdings: $" . $roundedHoldings;
        $totalInvestments += $holdings;
        echo "<br>";
    }
  }

  if($totalInvestments > 0){
    echo "<br>";
    echo "<br>";
    echo "Total Investments: $" . number_format($totalInvestments,2);
  }
  ?>
</div>

<div><h3>Explore our curated portfolios:</h3></div>
<div class="container">
    <div 
    onclick="window.location.href = 'invest.php';" 
    style="background-image: url('/images/boomer.jpg'); background-position: 50%; background-size: cover;" 
    class="square">
      <div><b>Boomer</b></div>
      <div>WMT</div>
      <div>KO</div>
      <div>JNJ</div>
      <div>T</div>
      <div>UNH</div>
    </div>
    <div 
    onclick="window.location.href = 'invest.php';" 
    style="background-image: url('/images/moderate.jpg'); background-position: 50%; background-size: cover;" 
    class="square">
    <div><b>Moderate</b></div>
      <div>AAPL</div>
      <div>AMZN</div>
      <div>JNJ</div>
      <div>SPY</div>
      <div>BRK-A</div>
    </div>
    <div 
    onclick="window.location.href = 'invest.php';" 
    style="background-image: url('/images/aggressive.jpg'); background-position: 50%; background-size: cover;" 
    class="square">
    <div><b>Aggressive</b></div>
      <div>GOOGL</div>
      <div>TSLA</div>
      <div>NVDA</div>
      <div>FB</div>
      <div>BABA</div>
    </div>
    <div 
    onclick="window.location.href = 'invest.php';" 
    style="background-image: url('/images/growth.jpg'); background-position: 50%; background-size: cover;" 
    class="square">
    <div><b>Growth</b></div>
      <div>PLTR</div>
      <div>AMD</div>
      <div>TSLA</div>
      <div>UPST</div>
      <div>CRM</div>
    </div>
    <div 
    onclick="window.location.href = 'invest.php';" 
    style="background-image: url('/images/tech.jpg'); background-position: 50%; background-size: cover;" 
    class="square">
    <div><b>Tech</b></div>
      <div>AMD</div>
      <div>TSLA</div>
      <div>MSFT</div>
      <div>GOOGL</div>
      <div>AAPL</div>
    </div>
    <div 
    onclick="window.location.href = 'invest.php';" 
    style="background-image: url('/images/crypto.jpg'); background-position: 50%; background-size: cover;" 
    class="square">
    <div><b>Crypto</b></div>
      <div>BTC</div>
      <div>ETH</div>
      <div>BNB</div>
      <div>XRP</div>
      <div>ADA</div>
    </div>
    <div 
    onclick="window.location.href = 'invest.php';" 
    style="background-image: url('/images/meme.jpg'); background-position: 50%; background-size: cover;" 
    class="square">
    <div><b>Meme</b></div>
      <div>GME</div>
      <div>AMC</div>
      <div>DOGE</div>
      <div>DWAC</div>
      <div>CLOV</div>
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



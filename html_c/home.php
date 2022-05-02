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

<div class="container">
  <div> some content here </div>
  <div>test</div>
  <div>
    <h1>a title</h1>
  </div>
  <div>more and more content <br>here</div>
  <div>
    <h2>another title</h2>
  </div>
  <div>test</div>
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



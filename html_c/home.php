<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
if (isset($_SESSION["user"])) {
	$fname = $_SESSION["fname"];
	echo "<br>";
	echo "Welcome, ".$fname."!";
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="calculatorScript.js"></script>
</head>

<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
</div>

<div id="calculator" class="calculator">
  <label class="title">Future Returns Calculator</label>
  <hr/>
  <br/>
  <label>Principle ($)</label>
  <input type="number" id="principle" value="1000" min="0">
    <br/>
  <label>Contribution ($)</label>
  <input type="number" id="contribution" value="100" min="0">
    <br/>
  <label>Frequency</label>
  <select id="frequency">
    <option value="7">Weekly</option>
    <option value="14">Bi-weekly</option>
    <option value="30">Monthly</option>
    <option value="91">Quarterly</option>
    <option value="364">Annually</option>
  </select>
    <br/>  <br/>
  <label>Interest Rate (%)</label>
  <input type="number" id="interest-rate" value="10" min="0">
    <br/>
  <label>Time Period (yrs)</label>
  <input type="number" id="period" value="10" min="1">
    <br/><br/>
  <hr/>
  <div id="calc-result" class="result">
	  $0
  </div>
</div>



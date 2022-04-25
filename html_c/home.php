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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<script>
		!function($){
		var principle = $('#principle'),
		contribution = $('#contribution'),
		frequency = $('#frequency'),
		interest = $('#interest-rate'),
		period = $('#period'),
		result = $('#calc-result'),
		inputs = $('input, select'),

		fv = function () {
			var r = parseFloat(interest.val()) / 100 / 365,
			C = parseFloat(contribution.val()),
			P = parseFloat(principle.val()),
			y = parseFloat(period.val()),
			d = 365 * y,
			n = parseFloat(frequency.val()),
			nn = Math.floor(365 / n),
			total = P + C,//add initial contribution to account for loss of interest
			ri = 0;

			var yr = new Date().getFullYear(),
			count = 0,
			initialDeposit = true,
			z,zz;

			while (count++ < d) {
			z = new Date(yr, 0 , count);
			zz = new Date(yr, 0 , count + 1);

			if (count % n === 0) {
				if (!initialDeposit) {
				total += C;
				} else {
				initialDeposit = false;          
				}
			}

			if (zz.getDate() < z.getDate()) {
				total += ri;
				ri = 0;
			}

			ri += total * r;
			}
			return total;
		},

		update = function () {
			var val = fv();
			result.html( val.toFixed(2).replace(/(\d)(?=(\d{3})+\b)/g, '$1,'));
		};

		update();
		inputs.on('change keyup', update);
		}(jQuery);
	</script>

	<style>
		body{
		font-family:'Century Gothic';
		background-color:#efefef;
		}

		.calculator {
		background-color:#dedede;
		padding: 10px;
		margin: 20px auto;
		width: 320px;

		}
		
		label,input,select { 
			width:300px;
			display: inline-block;
			margin: 5px 0;
			font-size:20px;
			
		}
		
		input,select { 
			display:inline-block;
			float:none;
			font-size:25px;
		}
		
		.result{
			width:290px;
			font-size:35px;
			margin-top: 10px;
		}
		.result:before{
			content:"Result: $";
			}
		
		.title{
		font-size:30px;
		margin-bottom:5px;
		}

	</style>
</head>

<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
</div>

<div id="calculator" class="calculator">
  <label class="title">Savings Calulator : Basic Compound Interest</label>
  <hr/>
  <br/>
  <label>Principle ($)</label>
  <input type="number" id="principle" value="" min="0">
    <br/>
  <label>Contribution ($)</label>
  <input type="number" id="contribution" value="" min="0">
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
  <input type="number" id="interest-rate" value="" min="0">
    <br/>
  <label>Time Period (yrs)</label>
  <input type="number" id="period" value="" min="1">
    <br/><br/>
  <hr/>
  <div id="calc-result" class="result">
  </div>
</div>



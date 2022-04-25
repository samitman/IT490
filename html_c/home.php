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
	
	<script>
		function interest(){

			var principal=document.getElementById('principal').value;
			var rate=document.getElementById('rate').value;
			var time=document.getElementById('time').value;
			var itype=document.getElementById('itype').value;
			var crate=document.getElementById('crate').value;
			var time=document.getElementById('time').value;
			var irate=rate/crate;
			var inttamt=document.getElementById('inttamt').value;
			var decimal=2;
			
			if( itype=='c' ){
				document.getElementById('inttamt').value=Math.round((document.getElementById('principal').value * Math.pow((1+irate/100),(time/12*crate))*1-principal*1 ) *100)/100
				}
			if(itype=='s' ){
				document.getElementById('inttamt').value=Math.round(((document.getElementById('principal').value * rate * time/12)/100)*100)/100
				}
		}
	</script>

	<style>
		#outer{width:90%;max-width:600px;background:#fff;margin:0 auto}
		#cover{border:2px solid #111;padding:15px 0}
		.main{table-layout:fixed;width:94%;border:0;border-collapse:collapse;margin:0 auto}
		.main td{padding:0 8px;vertical-align:middle;border:0}
		.main input{width:100%;border:1px solid #ccc;margin:2px 0;padding:0 2%;height:22px;text-align:right}
		.ac{text-align:center}
		.b{font-weight:bold}
		.main select{width:100%;border:1px solid #ccc;margin:2px 0;background:#fff;height:22px}
		.w50{width:50%}
		.main button{width:100%;font-weight:bold;margin:3px 0}
	</style>

</head>

<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
</div>


<div id=outer>
	<div class='ac b'>Interest Calculator</div>
	<div id=cover>
		<form name=intt>
			<table class=main>
				<col class=w50>
				<col class=w40>
					<tr><td>Principal Amount<td>
					<input id=principal>
					<tr><td>Interest Rate<td>
					<input id=rate>

					<tr><td>Interest Type<td>
					<select id=itype>
						<option value=s>Simple
						<option value=c selected>Compound
					</select>
					
					<tr><td>Compounding Frequency<td>
					<select id=crate>
						<option value=12>Monthly
						<option value=4 selected>Quarterly
						<option value=2>Half-yearly
						<option value=1>Yearly
					</select>
					
					<tr><td>Period (months)<td>
					<input id=time><tr><td>

					<button type=reset>Reset</button><td>

					<button type=button onclick=interest()>Submit</button>
					
					<tr><td>Interest Amount<td>
					<input id=inttamt></table>
		</form>
	</div>
</div>



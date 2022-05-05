<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php
if (isset($_SESSION["user"])) {
	$fname = $_SESSION["fname"];
    $balance = $_SESSION["balance"];
	echo "<br>";
	echo "My Profile";
}
else {
    echo "<br>";
    echo "Welcome, please log in!";
	//die(header("Location: index.php"));
}
?>
</div>

<?php
    $name = $_SESSION["fname"] ." " . $_SESSION["lname"];
    $username = $_SESSION["username"];
    $email = $_SESSION["email"];
    $balance = $_SESSION["balance"];
?>

<div>
    <h3>Account Information</h3>
    <div>Name: <?php echo $name;?></div>
    <div>Username: <?php echo $username;?></div>
    <div>Email: <?php echo $email;?></div>
</div><br>


<div>
    <h3>Financial Information</h3>
    <div>Available Balance: $<?php echo $balance;?></div><br>
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
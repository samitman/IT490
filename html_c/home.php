<?php require_once(__DIR__ . "/partials/nav.php");?>

<div style="text-align: center;">
<?php

if (isset($_SESSION["user"]["username"])) {
	$username = $_SESSION["user"]["username"];
	echo "Welcome, ".$username;
}
elseif (!is_logged_in()) {
    echo "<br>";
    echo "Welcome, please log in!";
}
?>
</div>

<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
</div>

<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<link rel="stylesheet" href="static/css/styles.css">
<div>
<div style="text-align: center;">
	<p><img src="images/walnuts_header.png" alt="Walnuts logo" width="760", height="383"></p>
	Please log in or register to continue.
</div>
<br>
<div style="width: 60%; margin:auto;">
	<form name="login" method="POST">
       	 	<input style="width: 100%" type="text" id="email" name="email" placeholder="Email address or username" required/><br><br><br>
        	<input style="width: 100%" type="password" id="p1" name="password" placeholder="Password" required/><br><br><br>
		<div style="display:flex;">
			<a style="text-decoration: none;" href="/register.php" class="submitButton" type="button">Register</a>
			<input class="submitButton" style="float: right;" type="submit" name="login" value="Log In"/>
		</div>
	</form>
</div>

<?php
$email = $_POST['email'];
$password = $_POST['p1'];
echo $email;
echo $password;
$command = "../database/rpc_fe_logintest.py $email, $password";
shell_exec($command);
?>

<?php require(__DIR__ . "/partials/flash.php"); ?>

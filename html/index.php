<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<link rel="stylesheet" href="static/css/styles.css">
<br>
<div>
<div style="text-align: center;">
	<b>Walnuts Investment Platform</b><br>
	<p><img src="images/walnuts_header.png" alt="Newark Academy logo" width="760", height="383"></p>
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

<?php require(__DIR__ . "/partials/flash.php");

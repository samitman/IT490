<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<br>
<div style="text-align: center;">
	<b>Walnuts Investment Platform</b><br>
        New User Registration<br>
</div>
<br>
<div style="width: 60%; margin: auto;">
    <form method="POST">
	<input style="width: 100%" type="text" id="firstName" name="firstName" placeholder="First Name" required/><br><br><br>
        <input style="width: 100%" type="text" id="lastName" name="lastName" placeholder="Last Name" required/><br><br><br>
        <input style="width: 100%" type="email" id="email" name="email" placeholder="Email address" required/><br><br><br>
        <input style="width: 100%" type="text" id="user" name="username" required maxlength="60" placeholder="Desired username"/><br><br><br>
        <input style="width: 100%" type="password" id="p1" name="password" placeholder="Password" required/><br><br><br>
        <input style="width: 100%" type="password" id="p2" name="confirm" placeholder="Confirm password" required/><br><br><br>
	<div style="display: flex;">
		<a style="text-decoration: none;" href="/index.php" class="submitButton" type="button">Go Back</a>
        	<input class="submitButton" style="float: right;" type="submit" name="register" value="Register"/><br><br>
	</div>
    </form>
</div>

<?php require(__DIR__ . "/partials/flash1.php");


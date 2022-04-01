<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<p style="text-align:center;"><b>User Dashboard</b></p>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page!");
    die(header("Location: index.php"));
}

//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>

<?php
	$username = get_username();
	$userID = get_user_id();
?>

<?php require_once(__DIR__ . "/lib/summary.php"); ?>
<?php require(__DIR__ . "/partials/flash.php"); ?>

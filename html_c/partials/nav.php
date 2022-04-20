<link rel="stylesheet" href="static/css/styles.css">
<?php
/*//Note: this is to resolve cookie issues with port numbers
$domain = $_SERVER["HTTP_HOST"];
if (strpos($domain, ":")) {
    $domain = explode(":", $domain)[0];
}
session_set_cookie_params([
    "lifetime" => 60 * 60,
    "path" => "/Project",
    //"domain" => $_SERVER["HTTP_HOST"] || "localhost",
    "domain" => $domain,
    "secure" => true,
    "httponly" => true,
    "samesite" => "lax"
]);
*/
session_start();
require_once(__DIR__ . "/../lib/functions.php");

?>

<style>
    nav li
    {
        display: inline;
    }
    nav ul
    {
        list-style-type: none;
    }
    nav
    {
	vertical-align: middle;
	height: 40px;
	background-image: linear-gradient(#73BC2D, #355600);
	border-radius: 9px;
	box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
    }
    nav   a
    {
	vertical-align: middle;
        color: white;
        text-align: center;
        padding: 10px 18px;
        text-decoration: none;
	border-radius: 9px;
    }
    nav a:hover 
    {
	background-image: linear-gradient(#FDC956, #f9982b);
        color: #305D00;
	box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
    }
    .dropbtn 
    {
        background-color: #4CAF50;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropdown 
    {
        position: relative;
        display: inline-block;
    }

    .dropdown-content 
    {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a 
    {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #f1f1f1}

    .dropdown:hover .dropdown-content 
    {
        display: block;
    }

    .dropdown:hover .dropbtn 
    {
        background-color: #3e8e41;
    }
</style>

<nav>
    <ul>
        <?php if (is_logged_in()) : ?>
            <li><a href="home.php">Home</a></li>
        <?php endif; ?>
        <?php if (!is_logged_in()) : ?>
            <li><a href="index.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
        <div class="dropdown">
        <?php if (has_role("Admin")) : ?>
                <li><button class="dropbtn">Admin</button>
                <ul class="dropdown-content">
                    <li><a href="admin_users.php">Search Users</a></li>
                </ul>
                 </li>
        <?php endif; ?>
        </div>
        <?php if (is_logged_in()) : ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="invest.php">Invest</a></li>
	        <li><a href="transact.php">Transact</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>

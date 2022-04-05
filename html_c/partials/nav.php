<?php
//Note: this is to resolve cookie issues with port numbers
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
session_start();
require_once(__DIR__ . "/../lib/functions.php");

?>



<!-- CSS only-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" -->
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- jQuery 3.6.0 min -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>



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
        background-color: rgb(71, 64, 64);
    }
    nav   a
    {
        color: white;
        text-align: auto;
        padding: 10px 18px;
        text-decoration: none;
    }
    nav a:hover 
    {
        background-color: rgb(42, 126, 74);
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
            <li><a href="login.php">Login</a></li>
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
            <li><a href="logout.php">Logout</a></>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
        <?php endif; ?>
    </ul>
</nav>

<?php
//session_start();

require_once(__DIR__ . "/db.php");
function is_logged_in(){
    return isset($_SESSION["user"]);
}

function has_role($role){
    if(is_logged_in() && isset($_SESSION["user"]["roles"])){
        foreach($_SESSION["user"]["roles"] as $r){
            if($r["name"] == $role){
                return true;
            }
        }
    }
    return false;
}

function get_username() {
    if (is_logged_in() && isset($_SESSION["user"]["username"])) {
        return $_SESSION["user"]["username"];
    }
    return "";
}

function get_email() {
    if (is_logged_in() && isset($_SESSION["user"]["email"])) {
        return $_SESSION["user"]["email"];
    }
    return "";
}

function get_user_id() {
    if (is_logged_in() && isset($_SESSION["user"]["id"])) {
        return $_SESSION["user"]["id"];
    }
    return -1;
}

function get_firstName() {
    if (is_logged_in() && isset($_SESSION["user"]["firstName"])) {
	return $_SESSION["user"]["firstName"];
    }
    return "";
}

function get_lastName() {
    if (is_logged_in() && isset($_SESSION["user"]["lastName"])) {
	return $_SESSION["user"]["lastName"];
    }
    return "";
}

function safer_echo($var) {
    if (!isset($var)) {
        echo "";
        return;
    }
    echo htmlspecialchars($var, ENT_QUOTES, "UTF-8");
}

function flash($msg) {
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $msg);
    }
    else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $msg);
    }

}

function getMessages() {
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}

?>

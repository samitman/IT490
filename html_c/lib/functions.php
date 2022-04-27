<?php
require_once(__DIR__ . "/db.php");

function se($v, $k = null, $default = "", $isEcho = true) {
    if (is_array($v) && isset($k) && isset($v[$k])) {
        $returnValue = $v[$k];
    } else if (is_object($v) && isset($k) && isset($v->$k)) {
        $returnValue = $v->$k;
    } else {
        $returnValue = $v;
        //added 07-05-2021 to fix case where $k of $v isn't set
        //this is to kep htmlspecialchars happy
        if (is_array($returnValue) || is_object($returnValue)) {
            $returnValue = $default;
        }
    }
    if (!isset($returnValue)) {
        $returnValue = $default;
    }
    if ($isEcho) {
        //https://www.php.net/manual/en/function.htmlspecialchars.php
        echo htmlspecialchars($returnValue, ENT_QUOTES);
    } else {
        //https://www.php.net/manual/en/function.htmlspecialchars.php
        return htmlspecialchars($returnValue, ENT_QUOTES);
    }
}
function sanitize_email($email = "") {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}
function is_valid_email($email = "") {
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}
function is_valid_username ($username, $filter = "[^a-zA-Z0-9\-\_\.]")
{
    return preg_match("~" . $filter . "~iU", $username) ? false : true;
}
function is_valid_fname ($fname, $filter = "[^a-zA-Z0-9\-\_\.]")
{
    return preg_match("~" . $filter . "~iU", $fname) ? false : true;
}
function is_valid_lname ($lname, $filter = "[^a-zA-Z0-9\-\_\.]")
{
    return preg_match("~" . $filter . "~iU", $lname) ? false : true;
}

function depositLoan($db, $loanAcccountId, $destinationAccountId, $balChange, $memo = '') {
    // world acct
    $stmt = $db->prepare("SELECT balance from Accounts WHERE id = :id"); // 500
    $stmt->execute([":id" => $loanAcccountId]);
    $srcAcct = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // Dest Account Balance
    $stmt->execute([":id" => $destinationAccountId]);
    $destAcct = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // actual Transaction
    $transactions = $db->prepare(
      "INSERT INTO Transactions (accountSrc, accountDest, balanceChange, transactionType, memo, expectedTotal)
      VALUES (:accountSrc, :accountDest, :balanceChange, :transactionType, :memo, :expectedTotal)"
    );
    $accounts = $db->prepare(
      "UPDATE Accounts SET balance = :balance WHERE id = :id"
    );
  
    // Calc
    // Force balChange positive
    $balChange = abs($balChange);
    $finalDestBalace = $destAcct['balance'] + $balChange;
  
    // Second action
    $transactions->execute([
      ":accountSrc" => $loanAcccountId,
      ":accountDest" => $destinationAccountId,
      ":balanceChange" => $balChange,
      ":transactionType" => 'deposit',
      ":memo" => $memo,
      ":expectedTotal" => $finalDestBalace
    ]);
  
    // Update balances of Accounts
    $accounts->execute([":balance" => $finalDestBalace, ":id" => $destinationAccountId]);
  
    return $transactions;
  }

function changeBal($db, $src, $dest, $type, $balChange, $memo = '') {
    // world acct
    $stmt = $db->prepare("SELECT APY, balance, account_type, id from Accounts WHERE id = :id"); 
    $stmt->execute([":id" => $src]);
    $srcAcct = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // Dest Account Balance
    $stmt->execute([":id" => $dest]);
    $destAcct = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // actual Transaction
    $transactions = $db->prepare(
      "INSERT INTO Transactions (accountSrc, accountDest, balanceChange, transactionType, memo, expectedTotal)
      VALUES (:accountSrc, :accountDest, :balanceChange, :transactionType, :memo, :expectedTotal)"
    );
    $accounts = $db->prepare(
      "UPDATE Accounts SET balance = :balance WHERE id = :id"
    );
  
    // Calc
    // Force balChange positive
    $balChange = abs($balChange);
    $finalSrcBalace = $srcAcct['balance'] - $balChange;
    $finalDestBalace = $destAcct['balance'] + $balChange;
  
    // First action
    $transactions->execute([
      ":accountSrc" => $src,
      ":accountDest" => $dest,
      ":balanceChange" => -$balChange,
      ":transactionType" => $type,
      ":memo" => $memo,
      ":expectedTotal" => $finalSrcBalace
    ]);
  
    // Second action
    $transactions->execute([
      ":accountSrc" => $dest,
      ":accountDest" => $src,
      ":balanceChange" => $balChange,
      ":transactionType" => $type,
      ":memo" => $memo,
      ":expectedTotal" => $finalDestBalace
    ]);
  
    // Update balances of Accounts
    $accounts->execute([":balance" => $finalSrcBalace, ":id" => $src]);
    $accounts->execute([":balance" => $finalDestBalace, ":id" => $dest]);
  
    $acct = null;
    $newAmount = null;
    if ($srcAcct['account_type'] === 'loan') {
        $acct = $srcAcct;
        $newAmount = abs($finalSrcBalace);
    }
    if ($destAcct['account_type'] === 'loan') {
        $acct = $destAcct;
        $newAmount = abs($finalDestBalace);
    }
    if ($acct !== null) {
        $stmt = $db->prepare('SELECT id, amount FROM Loans WHERE account_id = :account_id LIMIT 1');
        $stmt->execute([':account_id' => $acct['id']]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($loan)) {
            $loanId = $loan['id'];
            $oldAmount = $loan['amount'];
        }

        if (empty($loan)) {
            $query = "INSERT INTO Loans (account_id, APY, amount) VALUES(:account_id, :apy, :amount)"; 
            $stmt = $db->prepare($query);
            $stmt->execute([
                ":account_id" => $acct['id'], 
                ':apy' => $acct['APY'],
                ':amount' => 0,
            ]); 
            $loanId = $db->lastInsertId();
            $oldAmount = 0;
        }

        $query = "UPDATE Loans SET amount = :amount WHERE id = :id"; 
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":id" => $loanId, 
            ':amount' => $newAmount,
        ]);

        $query = "INSERT INTO LoanHistory (loan_id, old_amount, new_amount) VALUES(:loan_id, :old_amount, :new_amount)"; 
        $stmt = $db->prepare($query);
        $stmt->execute([
            ":loan_id" => $loanId, 
            ':old_amount' => $oldAmount,
            ':new_amount' => $newAmount,
        ]); 
    }

    return $transactions;
  }
//User Helpers
function is_logged_in() {
    return isset($_SESSION["user"]); //se($_SESSION, "user", false, false);
}
function has_role($role) {
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] === $role) {
                return true;
            }
        }
    }
    return false;
}
function greet() {
    if (is_logged_in()) 
    {
       if(isset($_SESSION["user"]["fname"]))
       {
            return se($_SESSION["user"], "fname", "", false);
       }
       else if(isset($_SESSION["user"]["username"]))
       {
            flash("ALERT: Transfer 'User to User' function will not be active until First and Last name are set.","Warning");
            return se($_SESSION["user"], "username", "", false); 
       }
    }

    return "";
}
function get_username() {
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "username", "", false);
    }
    return "";
}
function get_fname() {
    if (is_logged_in())
    { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "fname", "", false);
    }
    return die(header("Location: home.php"));
}

function get_user_active() {
    if (is_logged_in())
    { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "is_active", "", false);
    }
    return die(header("Location: home.php"));
}
function get_lname() {
    if (is_logged_in())
    { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "lname", "", false);
    }
    return "";

}
function get_user_account_id() {
    if (is_logged_in() && isset($_SESSION["user"]["account"])) {
        return (int)se($_SESSION["user"]["account"], "id", 0, false);
    }
    return 0;
}
function get_user_account_APY() {
    if (is_logged_in() && isset($_SESSION["user"]["account"])) {
        return (int)se($_SESSION["user"]["account"], "APY", 0, false);
    }
    return 0;
}
function get_user_account_num() {
    if (is_logged_in() && isset($_SESSION["user"]["account"])) {
        return (int)se($_SESSION["user"]["account"], "acount", 0, false);
    }
    return 0;
}
function get_user_email() {
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "email", "", false);
    }
    return "";
}
function get_user_id() {
    if (is_logged_in()) { //we need to check for login first because "user" key may not exist
        return se($_SESSION["user"], "id", false, false);
    }
    return false;

}

function get_privacy()
{
  if (is_logged_in() && isset($_SESSION["user"]["id"])) {
    return se($_SESSION["user"],"privacy", false, false);
  }
  return -1;
}
function pagination_filter($newPage) {
    $_GET["page"] = $newPage;
    //php.net/manual/en/function.http-build-query.php
    return se(http_build_query($_GET));
}

/** Runs two queries, one to get the total_records for the potentially filtered data, and the other to return the paginated data */
function new_paginate($query, $per_page = 5,$bindParams= []) {
    global $page, $total_records; //used for pagination display after this function
    //what page is the user on?
    //ensure we're not less than page 1 (page 1 is so it makes sense to the user, we'll convert it to 0)
    $page = se($_GET, "page", 1, false);
    if ($page < 1) {
        $page = 1;
    }

    $db = getDB();

    //get the total records for the current filtered (if applicable) data
    //this will get the get the part of the query after FROM
    $t_query = "SELECT count(1) as `total` FROM " . explode(" FROM ", $query)[1];
    //var_dump($t_query);
    $stmt = $db->prepare($t_query);
    try {
        $stmt->execute($bindParams);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $total_records = (int)se($result, "total", 0, false);
        }
    } catch (PDOException $e) {
        error_log("Error getting total records: " . var_export($e->errorInfo, true));
    }
    $offset = ($page - 1) * $per_page;
    //get the data 
    $query .= " LIMIT :offset, :limit";
    //IMPORTANT: this is required for the execute to set the limit variables properly
    //otherwise it'll convert the values to a string and the query will fail since LIMIT expects only numerical values and doesn't cast
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $results = [];
    
    //END IMPORTANT
    $stmt = $db->prepare($query);

    try {
        $params = array();
        $params[":offset"] = $offset;
        $params[":limit"] = $per_page;
        
        $params = array_merge($params, $bindParams);

        //print_r($params);
        //var_dump($params);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<pre>";
        var_dump($e);
        echo "</pre>";
        error_log("Error getting records: " . var_export($e->getMessage(), true));
        flash("There was a problem with your request, please try again", "warning");
    }
    return $results;
}

/** Runs two queries, one to get the total_records for the potentially filtered data, and the other to return the paginated data */
function paginate($query, $params = [], $per_page = 5,$user_id=0) {

    global $total_records; //used for pagination display after this function
    global $page; //used for pagination display after this function
    //what page is the user on?
    //ensure we're not less than page 1 (page 1 is so it makes sense to the user, we'll convert it to 0)
    $page = se($_GET, "page", 1, false);
    if ($page < 1) {
        $page = 1;
    }

    $db = getDB();

    //get the total records for the current filtered (if applicable) data
    //this will get the get the part of the query after FROM
    $t_query = "SELECT count(1) as `total` FROM " . explode(" FROM ", $query)[1];
    //var_dump($t_query);
    $stmt = $db->prepare($t_query);
    try {
        $stmt->execute(array(':uid'=>$user_id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $total_records = (int)se($result, "total", 0, false);
        }
    } catch (PDOException $e) {
        error_log("Error getting total records: " . var_export($e->errorInfo, true));
    }
    $offset = ($page - 1) * $per_page;
    //get the data 
    $query .= " LIMIT :offset, :limit";
    //IMPORTANT: this is required for the execute to set the limit variables properly
    //otherwise it'll convert the values to a string and the query will fail since LIMIT expects only numerical values and doesn't cast
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $results = [];
    
    //END IMPORTANT
    
    $stmt = $db->prepare($query);
   
    try {
        $params = array();
        $params[":offset"] = $offset;
        $params[":limit"] = $per_page;
        $params[":uid"] = $user_id;
        //print_r($params);
        //var_dump($params);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<pre>";
        var_dump($e);
        echo "</pre>";
        error_log("Error getting records: " . var_export($e->getMessage(), true));
        flash("There was a problem with your request, please try again", "warning");
    }
    return $results;
}
//flash message system
function flash($msg = "", $color ="green") {
    $message = ["text" => $msg];
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $message);
    } else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $message);
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
//end flash message system

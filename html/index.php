<?php require_once(__DIR__ . "/partials/nav.php");?>

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
			<a style="text-decoration: none;" href="/registerc.php" class="submitButton" type="button">Register</a>
			<input class="submitButton" style="float: right;" type="submit" name="login" value="Log In"/>
		</div>
	</form>
</div>

<?php
require_once __DIR__ . '/lib/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RPCtest
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            '192.168.192.60',
            5672,
            'test',
            'test'
        );
        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            'login',
            false,
            false,
            false,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            'login',
            false,
            false,
            false,
            false,
            array(
                $this,
                'onResponse'
            )
        );
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function call($n)
    {
        $this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            (string) $n,
            array(
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            )
        );
        $this->channel->basic_publish($msg, '', 'login');
        while (!$this->response) {
            $this->channel->wait();
        }
        return intval($this->response);
    }
}

$rpc_test = new RPCtest;
$response = "";;
$response = $rpc_test->call("Hello");?>
<p> Response: <?php echo $response; ?></p>

<<<<<<< HEAD
require(__DIR__ . "/partials/flash.php"); 
?>
=======
<?php require(__DIR__ . "/partials/flash.php");?>
>>>>>>> adad94e95ca094d41132b4870a739ed5de58f9a2

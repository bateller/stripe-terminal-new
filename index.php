<?

// comment this out to allow http:
if($_SERVER['SERVER_PORT'] == 80){
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    die();
}

$key_publishable    = '';
$key_secret         = '';
$note_prefix        = 'Terminal'; //this will be used in the charge's description
$title              = 'Terminal';
$currency           = 'usd';
$currency_symbol    = '$';
$demo_mode          = true;

if(!$key_publishable || !$key_secret) die('Please set stripe API keys');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'stripe-php/init.php';

    $note_parts = array();
    if($note_prefix) $note_parts[] = $note_prefix;
    if($_POST['note']) $note_parts[] = $_POST['note'];

    $amount = str_replace('$','', $_POST['amount']);

    $params = array(
        'amount'        	=> $amount,
        'currency'      	=> $currency,
        'source'          	=> $_POST['token'],
        'description'   	=> implode(' - ', $note_parts)
    );

    $response = array(
        'success' => false
    );

    try{
	if (!isset($_POST['token'])) {
		$response['error'] = "The Stripe Token was not generated correctly";
		$response['success'] = false;
	} else {
		\Stripe\Stripe::setApiKey($key_secret);
		$charge = \Stripe\Charge::create($params);

		if ($charge->paid == true) {	
        	$response['success']    = true;
        	$response['id']         = $charge->id;
        	$response['amount']     = number_format($charge->amount / 100, 2);
        	$response['fee']        = number_format($charge->fee / 100, 2);
        	$response['card_type']  = $charge->payment_method_details->card->brand;
        	$response['card_last4'] = $charge->payment_method_details->card->last4;
		$response['cvc_check']	= $charge->payment_method_details->card->checks->cvc_check;
		$response['zip_check'] 	= $charge->payment_method_details->card->checks->address_postal_code_check;
		}
	}
    }catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response);
    die();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?></title>
        <link rel="stylesheet" type="text/css" media="screen" href="layout.css?<?= filemtime('layout.css') ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="demo_bar.css?<?= filemtime('demo_bar.css') ?>" />
        <meta name="viewport" content="width=480px" />
	<meta name="viewport" content="user-scalable=no" />
    </head>
<body>
<?php if ($demo_mode == true) {?>
<div class="header">This is a <b>DEMO</b>. Please don't enter real payment info. You can use <b>4242424242424242</b> as a valid card number. <a href="https://stripe.com/docs/testing">Stripe Testing FAQ</a></div>
<?php } ?>
<div id="main">
 <form action="" method="POST" id="payment_form">
    <input type="hidden" name="token" />
    <label>Stripe Terminal</label>
    <div class="group">
            <label>
        <span>Amount</span>
        <input id="amount" name="amount" class="field" placeholder="Ex: $19.99" />
      </label>
    </div>
    <div class="group">
      <label>
        <span>Card Number</span>
        <div id="card-element" class="field"></div>
      </label>
            <label>
        <span>Expiration</span>
        <div id="card-expiry-element" class="field"></div>
      </label>
      <label>
        <span>CVC</span>
        <div id="card-cvc-element" class="field"></div>
      </label>
              <label>
        <span>Card Zip</span>
        <input id="postal_code" name="postal_code" class="field" placeholder="17225 (Optional)" />
      </label>
    </div>
    <div class="group">
      <label>
        <span>Note</span>
        <input id="note" name="note" class="field" placeholder="A short note" />
      </label>
    </div>
    <button type="submit" id="submit_button">Submit</button>
    <div class="outcome">
      <div class="error"></div>
      <div class="success">
        Successfully got token, processing charge...
      </div>
    </div>
  </form>
</div>
	<script src="https://js.stripe.com/v3/"></script>
        <script type="text/javascript" src="form.js?<?= filemtime('form.js') ?>"></script>
        <script type="text/javascript">
	   var stripe = Stripe('<?= $key_publishable ?>');
        </script>
    </body>
</html>

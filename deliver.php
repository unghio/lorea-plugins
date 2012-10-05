#! /usr/bin/php
<?php
// only allow running from cli
if (PHP_SAPI != 'cli') {
	exit();
}

// get command parameters
$size = $argv[1];
$user = $argv[2];
$sender = $argv[3];
$network = $argv[4];
$secret = $argv[5];

// get stdinput
$data = "";
$stdin = fopen("php://stdin", "rb");
$last = 0;
$counter = 0;
while(strlen($data) < $size) {
	$data .= stream_get_contents($stdin);
	$total = strlen($data);
	$total2 = count($data);
	if ($last == $total) {
		$counter += 1;
		if ($counter  == 1000) {
			#file_put_contents("/tmp/foo", "stalled at $total $total2");
			break;
		}
	}
	else {
		$counter = 0;
		$last = $total;
	}

	//$data .= fread($stdin, $size - strlen($data));
}
$total = strlen($data);
$total2 = count($data);
fclose($stdin);

// send post to network
$post_fields = array(
		'sender' => $sender,
		'user' => $user,
		'secret' => $secret,
		'data' => $data,
);
$request = curl_init($network . 'elggman/receive');

curl_setopt($request, CURLOPT_POST, TRUE);
curl_setopt($request, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt ($request, CURLOPT_FOLLOWLOCATION, 1);

$data = curl_exec($request);
curl_close($request);


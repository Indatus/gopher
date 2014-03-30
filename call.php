<?php
require "vendor/autoload.php";
require "creds.php";
require "users.php";
require "script.php";

//default pause seconds
$pause = 13;

//find the alias for the user to use, setup pause duration
if (count($argv) > 1) {
    $alias = $argv[1];

    if (array_key_exists($alias, $users)) {
        $callNumber = $users[$alias];
    } else {
        die("Couldn't find number for alias: {$alias}");
    }

    if (count($argv) > 2) {
        $pause = $argv[2];
    }

} else {
    die("You must give an alias for a phone number to call");
}




//start out making our Twiml code
//and put it up on S3
$time = \Carbon\Carbon::now('America/New_York')->format('g:ia \o\n l, F jS, Y');
$words = ["Unicorn", "Monkey", "Wizard", "Gnome", "Thunder cat", "Finger stash"];
$random = $words[array_rand($words)];

//do script replacements
$script = str_replace('{name}', $alias, $script);
$script = str_replace('{time}', $time, $script);
$script = str_replace('{random}', $random, $script);

$response = new Services_Twilio_Twiml;
$response->pause(array('length' => $pause));
$response->say($script, array('voice' => 'woman'));

$xmlString = sprintf('%s', $response);
$uploadName = 'test-'.date('Y-m-d-H-i-s').'.xml';

$s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);

$s3->putObject(
    $xmlString,
    AWS_BUCKET,
    $uploadName,
    S3::ACL_PUBLIC_READ,
    array(),
    array('Content-Type' => 'text/xml')
);

$callbackUrl = AWS_ENDPOINT."/".AWS_BUCKET."/{$uploadName}";

//now create a twilio call and give it the S3 object URL
//we just created
$twilio = new Services_Twilio(TW_ACCOUNT_SID, TW_AUTH_TOKEN);

$call = $twilio->account->calls->create(
    TW_SOURCE_PHONE,
    $callNumber,
    $callbackUrl,
    array(
        'Method' => 'GET'
    )
);

echo "DONE, just dropped off a message for ${alias}\n";

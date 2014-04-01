# Callbot

A stand-alone PHP package for testing telecom dial-in apps. Callbot provides a simple CLI interface for making batches of test calls. It is configured to use Twilio out of the box, but can be configured to use any similar service.

## Installation

1. `$ git clone git@gitlab.indatus.com:jarstingstall/callbot.git`
2. `$ cd callbot && composer install`

## Make A Call Using Twilio and Amazon S3

### Twilio Setup

1. Signup for a free [Twilio](https://www.twilio.com/try-twilio) account.
2. Rename `config.example.php` to `config.php` and enter your Account SID, Auth Token, and Twilio Phone Number

### Amazon S3 Setup

Twilio requires an XML script located at a public URL for each call it makes. The script at this URL tells Twilio what to do once the call is answered. Callbot is configured to compile your XML scripts and push them up to an Amazon S3 bucket out of the box. Signing up for Amazon S3 is free and easy:

1. Signup for a free [Amazon S3](https://console.aws.amazon.com/s3/) account.
2. Create a bucket and give Everyone "View" permissions in the S3 console.
3. Open `config.php` and enter your Access Key, Secret Key, and Bucket Name.

### Config.php Setup

Callbot has the ability to run multiple batches of calls with multiple calls in each batch, but to get started we'll walk through a simple example with one batch containing one call.

* Open `config.php` and locate the `'batches'` element. The default example contains two batches so we'll remove the second batch for this example and we're left with the first:

```
'batches' => [
    [
        'from' => '+15551234567',
        'to' => ['+15551234567', '+15551234567', '+15551234567'],
        'callbackUrl' => 'http://www.example.com/call.xml',
        'srcFile' => 'call-scripts/test-script.xml'
    ]
]
```

* Replace the phone number in the `'from'` element with your Twilio phone number.
* The `'to'` element is an array of phone numbers that will be called when you run the script. For our example, place your phone number as the single element of this array. If you're using the free trial version of Twilio, make sure you've verified your number.

```
'to' => ['+15551234567'],
```

* The `'callbackUrl'` is the publicly accessible URL that Twilio will request before the call is placed. Twilio expects to receive an XML file that utilizes the Twilio Markup Language (TwiML). Callbot takes care of uploading your file for you, just provide the URL.

* The `'srcFile'` is the location of the TwiML file on your machine. For this example, we'll use the script provided so leave this as `call-scripts/test-script.xml`.

* We're now ready for Callbot to place the call for us.

```
$ ./callbot call
```

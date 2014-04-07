# Callbot

A stand-alone PHP package for testing telecom dial-in apps. Callbot provides a simple CLI interface for making batches of test calls. It is configured to use Twilio out of the box, but can be configured to use any similar service.

## Installation

1. `$ git clone git@gitlab.indatus.com:jarstingstall/callbot.git`
2. `$ cd callbot && composer install`

## Make An Outgoing Call Using Twilio and Amazon S3

### Twilio Setup

1. Signup for a free [Twilio](https://www.twilio.com/try-twilio) account.
2. Rename `config.example.php` to `config.php` and enter your Account SID and Auth Token credentials.
3. Replace the `'defaultFrom'` phone number with your Twilio phone number.

### Amazon S3 Setup

Twilio requires an XML script located at a public URL for each call it makes. The script at this URL tells Twilio what to do once the call is answered. Callbot is configured to compile your XML scripts and push them up to an Amazon S3 bucket out of the box. Signing up for Amazon S3 is free and easy:

1. Signup for a free [Amazon S3](https://console.aws.amazon.com/s3/) account.
2. Create a bucket and give Everyone "View" permissions in the S3 console.
3. Open `config.php` and enter your Access Key, Secret Key, and Bucket Name.

### Make The Call

We're now ready for Callbot to place the call for us. Run the `callbot` executable, passing in the `call:single` command. To place a single call, the `call:single` command requires two arguments:

1. The phone number to call
2. The path to the call script

```
$ ./callbot call:single 5551234567 call-scripts/test-script.xml
```

The default call script is located in the `call-scripts` directory and contains TwiML (Twilio Markup Language) that tells Twilio how to handle the outgoing call. Check out the Twilio docs for more info on [TwiML](https://www.twilio.com/docs/api/twiml).

`call:single` uses the phone number you provided in `congig.php` in `callServices.defaultFrom` for the default from number. You can override the default from number by passing the `from` option:

```
$ ./callbot call:single 5551234567 call-scripts/test-script.xml --from="5551234567"
```

* You can also pass a comma separated list of phone numbers as the first argument to the `call:single` command to make multiple calls using the same call-script:

```
$ ./callbot call:single 5551234567,5551234561,5551234562 call-scripts/test-script.xml
```

## Run Multiple Batches of Calls

The `call:multi` command can be used to run multiple batches of calls, each batch having it's own call script.

#### Setup

* Open `config.php` and located the `batches` array. You'll see an example batch:

```
'batches' => [
    [
        'to' => ['5551234567', '5551234567', '5551234567'],
        'script' => 'call-scripts/test-script.xml'
    ]
]
```

A batch has two required elements: `to` and `script`. `to` is an array of phone numbers to call and `script` is the local path to the call script to use for the batch.

`call:single` uses the phone number you provided in `congig.php` in `callServices.defaultFrom` for the default from number. You can override the default from number by including a `from` element with the batch:

```
'batches' => [
    [
        'to' => ['5551234567', '5551234567', '5551234567'],
        'from' => '5557654321',
        'script' => 'call-scripts/test-script.xml'
    ]
]
```

* Add as many batches as you'd like to the `batches` array and then run:

```
$ ./callbot call:multi
```

## Display Results of Outgoing Calls

The `call:results` command can be used to display the results of outgoing calls.

* Display the results of a specific call with the `id` option:

```
$ ./callbot call:results --id="UNIQUE_ID"
```

* You can specify multiple unique ids with the `id` option.

```
$ ./callbot call:results --id="UNIQUE_ID_1, UNIQUE_ID_2, UNIQUE_ID_3"
```

Use the various filter options to filter out the list of results:

| Option | Description                               |
| ------ | ----------------------------------------- |
| after  | Only show calls placed after this date. (`Y-m-d H:i:s` format)   |
| before | Only show calls placed before this date. (`Y-m-d H:i:s` format)  |
| on     | Only show calls calls placed on this date. (`Y-m-d` format)|
| to     | Only show calls to this phone number.      |
| from   | Only show calls from this phone number.    |
| status | Only show calls currently in this status. May be `queued`, `ringing`, `in-progress`, `canceled`, `completed`, `failed`, `busy`, or `no-answer`. |


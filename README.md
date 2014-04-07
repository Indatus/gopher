# Callbot

A stand-alone PHP package for testing telecom dial-in apps. Callbot provides a simple CLI interface for making batches of test calls. It is configured to use Twilio out of the box, but can be configured to use any similar service.

## Installation

1. `$ git clone git@gitlab.indatus.com:jarstingstall/callbot.git`
2. `$ cd callbot && composer install`

## Available Commands

| Command Name | Description                                                           |
| ------------ | --------------------------------------------------------------------- |
| call:single  | Run a single batch of calls that share the same call script           |
| call:multi   | Run multiple batches of calls, each batch having it's own call script |
| call:details | Fetch and display details of outgoing calls                           |

See below for further description and examples of these commands.


---

## Twilio and Amazon S3 Configuration

### Twilio Setup

1. Signup for a free [Twilio](https://www.twilio.com/try-twilio) account.
2. Rename `config.example.php` to `config.php` and enter your Account SID and Auth Token credentials.
3. Replace the `'defaultFrom'` phone number with your Twilio phone number.

### Amazon S3 Setup

Twilio requires an XML script located at a public URL for each call it makes. The script at this URL tells Twilio what to do once the call is answered. Callbot is configured to push your scripts up to an Amazon S3 bucket out of the box. Signing up for Amazon S3 is free:

1. Signup for an [Amazon S3](https://console.aws.amazon.com/s3/) account.
2. Create a bucket and give Everyone "View" permissions in the S3 console.
3. Open `config.php` and enter your Access Key and Secret Key in the `credentials` array.
4. Locate the `uploadDir` element and replace BUCKET_NAME with the name of the bucket you created in Step 2.


---

### Run a Single Batch of Calls

The `call:single` command can be used to run a single batch of calls that share the same call script. It requires two arguments:

1. A comma-separated list of phone numbers to call
2. The local path to the call script

```
$ ./callbot call:single 5551234567,5551234561,5551234562 call-scripts/test-script.xml
```

`call:single` uses the default from phone number you provided in `congig.php`. You can override the default from number by passing the `from` option:

```
$ ./callbot call:single 5551234567 call-scripts/test-script.xml --from="5551234567"
```

> The root-level `call-scripts` directory is used to store your call scripts. An example script is provided to
> get you up and running quickly. The example script contains TwiML (Twilio Markup Language) that tells Twilio
> how to handle the outgoing call. Feel free to modify `test-script.xml` and create your own call scripts.
> Check out the Twilio docs for more info on [TwiML](https://www.twilio.com/docs/api/twiml).


---

## Run Multiple Batches of Calls

The `call:multi` command can be used to run multiple batches of calls, each batch having it's own call script.

#### Setup

Open `config.php` and located the `batches` array. You'll see an example batch:

```
'batches' => [
    [
        'to' => ['5551234567', '5551234567', '5551234567'],
        'script' => 'call-scripts/test-script.xml'
    ]
]
```

A batch has two required elements: `to` and `script`. `to` is an array of phone numbers to call and `script` is the local path to the call script to use for the batch.

`call:single` uses the default from phone number you provided in `congig.php`. You can override the default from number by including a `from` element with the batch:

```
'batches' => [
    [
        'to' => ['5551234567', '5551234567', '5551234567'],
        'from' => '5557654321',
        'script' => 'call-scripts/test-script.xml'
    ]
]
```

Add as many batches as you'd like to the `batches` array and then run:

```
$ ./callbot call:multi
```


---

## Display Results of Outgoing Calls

The `call:results` command can be used to display the results of outgoing calls.

Display the results of a specific call with the `id` option:

```
$ ./callbot call:results --id="UNIQUE_ID"
```

You can specify multiple unique ids with the `id` option.

```
$ ./callbot call:results --id="UNIQUE_ID_1, UNIQUE_ID_2, UNIQUE_ID_3"
```

### Using Filters to Narrow Call Results

#### Available Filters

| Option | Description                               |
| ------ | ----------------------------------------- |
| after  | Only show calls placed after this date. (`Y-m-d H:i:s` format)   |
| before | Only show calls placed before this date. (`Y-m-d H:i:s` format)  |
| on     | Only show calls calls placed on this date. (`Y-m-d` format)|
| to     | Only show calls to this phone number.      |
| from   | Only show calls from this phone number.    |
| status | Only show calls currently in this status. May be `queued`, `ringing`, `in-progress`, `canceled`, `completed`, `failed`, `busy`, or `no-answer`. |

#### Examples

Get results for calls made on **April 5, 2014** from **555-123-4567**

```
$ ./callbot call:results --on="2014-04-05" --from="5551234567"
```

Get results for calls made between **7:00 am** and **10:00 am** on **April 5, 2014** with a **completed** status

```
$ ./callbot call:results --after="2014-04-05 07:00:00" --before="2014-04-05 10:00:00" --status="failed"
```

Display details for all **failed** calls made to **555-123-4567**

```
$ ./callbot call:results --status="failed" --to="5551234567"
```

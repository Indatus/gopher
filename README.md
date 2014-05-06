# Callbot

A stand-alone PHP package for testing telecom dial-in apps. Callbot provides a simple CLI interface for making batches of test calls. It is configured to use Twilio out of the box, but can be configured to use any similar service. Credit to [brainwebb01](https://github.com/brianwebb01) for the original concept for this package.

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

## Configuration

### Twilio Setup

1. Signup for a free [Twilio](https://www.twilio.com/try-twilio) account.
2. Open `config/callservice.php` and fill in your Account SID and Auth Token.
3. Enter your Twilio number as the default `from` number and update `timezone` with your preferred timezone.
Select from PHP's supported timezone list [here](http://www.php.net/manual/en/timezones.php).

### Amazon S3 Setup

Twilio requires an XML script located at a public URL for each call it makes. The script at this URL tells Twilio what to do once the call is answered. Callbot is configured to push your scripts up to an Amazon S3 bucket out of the box.

1. Signup for an [Amazon S3](https://console.aws.amazon.com/s3/) account.
2. Create a bucket and give Everyone "View" permissions in the S3 console.
3. Open `config/filesystem.php` and locate the `s3` configuration section.
4. Enter your Access Key, Secret Key, and bucket name.


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

Open `batches.php` and look at the example batch provided:

```
'batches' => [
    'example-1' =>
        [
            'to' => ['5551234567', '5551234567', '5551234567'],
            'script' => 'call-scripts/test-script.xml'
        ]
]
```

A batch has two required elements: `to` and `script`. `to` is an array of phone numbers to call and `script` is the local path to the call script to use for the batch.

`call:multi` uses the default from phone number you provided in `callservice.php`. You can override the default from number by including a `from` element with the batch:

```
'batches' => [
    'example-1' =>
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

### Run Specific Batches

You can pass a comma-separated list of batch names to `call:multi` to specify which specific
batches to run. Make sure to give your batches unique names if you want to use this feature.

```
$./callbot call:multi example-1,example-2
```

---

## Display Details of Outgoing Calls

The `call:details` command can be used to display the details of outgoing calls.

Display the details of a specific call with the `id` option:

```
$ ./callbot call:details --id="UNIQUE_ID"
```

You can specify multiple unique ids with the `id` option.

```
$ ./callbot call:details --id="UNIQUE_ID_1,UNIQUE_ID_2,UNIQUE_ID_3"
```

### Using Filters to Narrow Call Details

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

Get details for calls made on **April 5, 2014** from **555-123-4567**

```
$ ./callbot call:details --on="2014-04-05" --from="5551234567"
```

Get details for calls made between **7:00 am** and **10:00 am** on **April 5, 2014** with a **completed** status

```
$ ./callbot call:details --after="2014-04-05 07:00:00" --before="2014-04-05 10:00:00" --status="failed"
```

Display details for all **failed** calls made to **555-123-4567**

```
$ ./callbot call:details --status="failed" --to="5551234567"
```

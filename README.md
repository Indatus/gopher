# Callbot

A stand-alone PHP package for testing telecom dial-in apps. Callbot provides a simple CLI interface for making batches of test calls. It is configured to use Twilio out of the box, but can be configured to use any similar service.

## Installation

1. `$ git clone git@gitlab.indatus.com:jarstingstall/callbot.git`
2. `$ cd callbot && composer install`

## Configuration

### Twilio Setup

1. Signup for a free [Twilio](https://www.twilio.com/try-twilio) account.
2. Rename `config.example.php` to `config.php` and enter your Account SID, Auth Token, and Twilio Phone Number

### Amazon S3 Setup

Twilio requires an XML script located at a public URL for each call it makes. The script at this URL tells Twilio what to do once the call is answered. Callbot is configured to compile your XML scripts and push them up to an Amazon S3 bucket out of the box. Signing up for Amazon S3 is free and easy:

1. Signup for a free [Amazon S3](https://console.aws.amazon.com/s3/) account.
2. Create a bucket and give Everyone "View" permissions in the S3 console.
3. Open `config.php` and enter your Access Key and Secret Key.

## Usage

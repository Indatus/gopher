# Callbot

A stand-alone PHP package for testing telecom dial-in apps. Callbot provides a simple CLI interface for making batches of test via Twilio by simply running a command on the CLI.

## Amazon S3 Setup

Twilio requires an XML script located at a public URL for each call it makes. The script at this URL tells Twilio what to do once the call is answered. Callbot is configured to compile your XML scripts and push them up to an Amazon S3 bucket out of the box. Signing up for Amazon S3 is free and easy:

1. Signup for [Amazon S3](https://console.aws.amazon.com/s3/).
2. Create a bucket and give Everyone "View" permissions in the S3 console.

## Installation

1. Checkout the repo with `git clone`
2. CD into the `callbot` directory and run `composer install`

## Configuration

## Usage

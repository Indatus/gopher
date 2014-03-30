#!/usr/bin/env php
<?php

require "vendor/autoload.php";
require 'CallCommand.php';
require 'ConfigReader.php';

use Symfony\Component\Console\Application;

$application = new Application;
$application->add(new CallCommand(new Services_Twilio_Twiml));
$application->run();

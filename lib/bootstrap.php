<?php

use AwsCafe\ClientFactory;

require __DIR__ . '/../vendor/autoload.php';

// Read the settings file and parse it into sections
$settings = parse_ini_file(__DIR__ . '/../config/amazon.ini', true);

// Create a global ClientFactory that we can use to create AWS API client
// objects based on our settings
$clientFactory = new ClientFactory();
$clientFactory->setSettings($settings);
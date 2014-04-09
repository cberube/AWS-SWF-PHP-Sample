<?php

require __DIR__ . '/../vendor/autoload.php';

use Aws\Swf\Exception\DomainAlreadyExistsException;
use Aws\Swf\Exception\TypeAlreadyExistsException;
use Aws\Swf\SwfClient;

// Read the settings file and parse it into sections
$settings = parse_ini_file(__DIR__ . '/../config/amazon.ini', true);

// Get the SWF domain name from our settings array for convenience
$domainName = $settings['swf']['domain'];

// Create an SWF client
$swfClient = SwfClient::factory(
    array(
        'key' => $settings['credentials']['key'],
        'secret' => $settings['credentials']['secret'],
        'region' => $settings['credentials']['region']
    )
);

// Register the SWF domain
echo "Registering SWF domain '$domainName'..." . PHP_EOL;

try {
    $response = $swfClient->registerDomain(
        array(
            'name' => $domainName,
            'description' => 'AWS Cafe tutorial workflow domain',
            'workflowExecutionRetentionPeriodInDays' => '1'
        )
    );
} catch (DomainAlreadyExistsException $alreadyExistsException) {
    echo "Domain already exists." . PHP_EOL;
}

// Register the "CustomerOrder" workflow type
echo "Registering 'CustomerOrder' workflow type..." . PHP_EOL;

try {
    $swfClient->registerWorkflowType(
        array(
            'domain' => $domainName,
            'name' => 'CustomerOrder',
            'version' => '1',
            'defaultTaskStartToCloseTimeout' => 3600,
            'defaultExecutionStartToCloseTimeout' => 3600,
            'defaultChildPolicy' => 'TERMINATE'
        )
    );
} catch (TypeAlreadyExistsException $typeAlreadyExistsException) {
    echo "Workflow type already exists." . PHP_EOL;
}

// Register the "BrewCoffee" activity type
echo "Registering 'BrewCoffee' activity type..." . PHP_EOL;

try {
    $swfClient->registerActivityType(
        array(
            'domain' => $domainName,
            'name' => 'BrewCoffee',
            'version' => '1',
            'defaultTaskStartToCloseTimeout' => 3600,
            'defaultTaskHeartbeatTimeout' => 3600,
            'defaultTaskScheduleToStartTimeout' => 3600,
            'defaultTaskScheduleToCloseTimeout' => 3600,
        )
    );
} catch (TypeAlreadyExistsException $typeAlreadyExistsException) {
    echo "Activity type already exists." . PHP_EOL;
}

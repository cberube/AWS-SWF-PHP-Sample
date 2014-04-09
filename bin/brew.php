<?php

require_once __DIR__ . '/../lib/bootstrap.php';

// Get our SWF client instance, as usual
$swfClient = $clientFactory->getSwfClient();

// Poll for a RegularOrder activity task

/** @var \Guzzle\Service\Resource\Model $result */
$result = $swfClient->pollForActivityTask(
    array(
        'domain' => $settings['swf']['domain'],
        'taskList' => array('name' => 'RegularOrder')
    )
);

// For the moment, we don't actually do anything at all -- we just signal
// that we've completed the activity
$response = $swfClient->respondActivityTaskCompleted(
    array(
        'taskToken' => $result->getPath('taskToken'),
    )
);

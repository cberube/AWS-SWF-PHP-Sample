<?php

require_once __DIR__ . '/../lib/bootstrap.php';

// Get an SWF client instance
$swfClient = $clientFactory->getSwfClient();

// For the moment, we'll create a random workflow ID; this is a free-form value, but
// it is used to differentiate workflow executions, and we cannot have two executions
// running with the same ID
$workflowId = uniqid();

// Start an execution of the CustomerOrder workflow type
$swfClient->startWorkflowExecution(
    array(
        'domain' => $settings['swf']['domain'],
        'workflowId' => $workflowId,
        'workflowType' => array(
            'name' => 'CustomerOrder',
            'version' => '1'
        ),
        'taskList' => array('name' => 'RegularOrder')
    )
);

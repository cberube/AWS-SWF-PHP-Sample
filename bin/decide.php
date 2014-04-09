<?php

require_once __DIR__ . '/../lib/bootstrap.php';

// Get our SWF client instance, as usual
$swfClient = $clientFactory->getSwfClient();

// For now, we just poll once for a decision task -- the expectation is that
// we are running all these scripts manually at this point, and know when it
// it appropriate to perform each step

/** @var \Guzzle\Service\Resource\Model $result */
$result = $swfClient->pollForDecisionTask(
    array(
        'domain' => $settings['swf']['domain'],
        'workflowType' => array(
            'name' => 'CustomerOrder',
            'version' => '1'
        ),
        'taskList' => array('name' => 'RegularOrder')
    )
);

// We need to determine some details of the current state of the item in the workflow
// in order to decide what should happen to it next. For the moment, we will do that
// just by examining the list of workflow events -- if we find any ActivityTaskCompleted
// events, we will decide that the workflow has been completed; otherwise, we will
// schedule a 'BrewCoffee' activity
$isComplete = false;
$taskToken = $result->getPath('taskToken');
$events = $result->getPath('events');

foreach ($events as $event) {
    if ($event['eventType'] == 'ActivityTaskCompleted') {
        $isComplete = true;
        break;
    }
}

// A random ID is sufficient for our ActivityId at the moment
$activityId = uniqid();

if ($isComplete) {
    // We found a completed activity task in the workflow history,
    // so we go ahead and mark this workflow as complete
    $decisionList = array(
        array (
            'decisionType' => 'CompleteWorkflowExecution',
            'completeWorkflowExecutionAttributes' => array()
        )
    );
} else {
    // We didn't find a completed activity, so we must still
    // need to brew the coffee for this order
    $decisionList = array(
        array (
            'decisionType' => 'ScheduleActivityTask',
            'scheduleActivityTaskDecisionAttributes' => array(
                'activityType' => array(
                    'name' => 'BrewCoffee',
                    'version' => '1'
                ),
                'activityId' => $activityId,
                'taskList' => array('name' => 'RegularOrder'),
            )
        )
    );
}

$result = $swfClient->respondDecisionTaskCompleted(
    array(
        'taskToken' => $taskToken,
        'decisions' => $decisionList
    )
);

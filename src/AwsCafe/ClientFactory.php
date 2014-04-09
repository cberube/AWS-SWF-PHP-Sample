<?php

namespace AwsCafe;

use Aws\Swf\SwfClient;

/**
 * This class builds our AWS clients using our configuration settings
 */
class ClientFactory
{
    /** @var array */
    private $settings;

    /**
     * @return SwfClient A new SwfClient instance built with our credentials and preferred region
     */
    public function getSwfClient()
    {
        $settings = $this->getSettings();

        return SwfClient::factory(
            array(
                'key' => $settings['credentials']['key'],
                'secret' => $settings['credentials']['secret'],
                'region' => $settings['credentials']['region']
            )
        );
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }
}
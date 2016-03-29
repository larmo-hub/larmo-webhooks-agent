<?php

namespace FP\Larmo\Agents\WebHookAgent;

use FP\Larmo\Agents\WebHookAgent\Exceptions\InvalidConfigurationException;

class TargetConnector
{
    private $jsonPacket;
    private $targets;
    private $header;

    public function __construct($uri, $packet)
    {
        $this->jsonPacket = json_encode($packet);
        $this->targets = $this->prepareTargets($uri);
        $this->header = $this->prepareHeader();
    }

    private function prepareTargets($uri)
    {
        $this->checkCorrectUri($uri);

        if (!is_array($uri)) {
            $targets = [$uri];
        } else {
            $targets = $uri;
        }

        return $targets;
    }

    private function checkCorrectUri($uri)
    {
        if (!$uri) {
            trigger_error("No target URIs specified in configuration. Sending will NOT proceed.", E_USER_WARNING);
            throw new InvalidConfigurationException;
        }
    }

    private function prepareHeader()
    {
        return [
            'accept: */*',
            'Content-Type: application/json',
            'Accept-Charset: utf-8',
        ];
    }

    public function send()
    {
        $output = ['errors' => [], 'results' => []];

        foreach ($this->targets as $targetUri) {
            try {
                $output['results'][$targetUri] = $this->sendDataToTargetUri($targetUri);
            } catch (\Exception $e) {
                $output['errors'][] = $e->getMessage();
            }
        }

        $this->triggerErrors($output['errors']);
        $this->triggerAlertsIfResultNotComplete($output['results']);
    }

    private function sendDataToTargetUri($targetUri)
    {
        $curl = curl_init($targetUri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->jsonPacket);
        $curl_response = curl_exec($curl);
        curl_close($curl);

        return $curl_response;
    }

    private function triggerErrors($errors) {
        if (!empty($errors)) {
            foreach ($errors as $uri => $error) {
                trigger_error('Errors in transmission for ' . $uri . ': ' . $error, E_USER_WARNING);
            }
        }
    }

    private function triggerAlertsIfResultNotComplete($results)
    {
        if (!empty($results)) {
            foreach ($results as $uri => $result) {
                $response = json_decode($result);
                if (empty($response)) {
                    trigger_error('Errors in transmission for ' . $uri . ': ' . 'Response is empty' , E_USER_WARNING);
                } else if ($response->message!=='OK') {
                    trigger_error('Response from ' . $uri . ': ' . json_encode($response->message), E_USER_NOTICE);
                }
            }
        }
    }
}

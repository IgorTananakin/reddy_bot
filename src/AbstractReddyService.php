<?php

namespace Mobcash\Helthlife;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractReddyService
{

    public $token;

    public function __construct()
    {
    }

    private function getServiceUrl($method): string
    {
        return "https://bot.reddy.team/v2{$this->token}/$method";
    }

    /**
     * @param $httpMethod
     * @param $method
     * @param $body
     * @return mixed
     * @throws Exception|GuzzleException
     */
    private function sendHttpRequest($httpMethod, $method, $body = [])
    {
        $url = $this->getServiceUrl($method);
        $client = new \GuzzleHttp\Client();
        $stringBody = json_encode($body);
        $response = $client->request($httpMethod, $url, [
            'http_errors' => false,
            'body' => $stringBody,
            'timeout' => 360
        ]);

        if ($response->getStatusCode() !== 200) {
            $errorObject = json_decode($response->getBody());
            $errorMessage = $errorObject->errorMessage ?? json_encode($errorObject);
            throw new Exception($errorMessage, $response->getStatusCode());
        }

        return json_decode($response->getBody()) ?? [];
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function getUpdate()
    {
        return $this->sendHttpRequest('GET', 'getupdate');
    }

    /**
     * @throws GuzzleException
     */
    public function sendMessage($message, $chatId = null, $reddyUserId = null,$userKey = null)
    {
        return $this->sendHttpRequest('POST', 'send', [
            'msg' => $message,
            'userid' => $reddyUserId,
            'chat' => $chatId,
            'userkey' =>$userKey
        ]);
    }


    static function tagPerson($id): string
    {
        return "[user=$id] [/user]";
    }


    /**
     */
    public function reportError($message, array $reportTo = [])
    {
        try {
            $reportTo = empty($reportTo) ? $this->reportErrorTo : $reportTo;
            foreach ($reportTo as $to) {
                $this->sendMessage("Info:\n" . $message, $to);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    /**
     * @throws GuzzleException
     */
    public function createChat($title, $about = '', $ukeys = [], $owner = null, $admins = [])
    {
        return $this->sendHttpRequest('POST', 'createchat', [
            'title' => $title,
            'about' => $about,
            'ukeys' => $ukeys,
            'owner' => $owner,
            'admins' => $admins
        ]);
    }
}

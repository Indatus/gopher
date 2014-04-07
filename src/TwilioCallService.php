<?php

namespace Indatus\Callbot;

use Services_Twilio;
use Indatus\Callbot\Config;
use Indatus\Callbot\Contracts\CallServiceInterface;

class TwilioCallService implements CallServiceInterface
{
    protected $filters = array();
    protected $twilio;
    protected $config;

    public function __construct(Services_Twilio $twilio, Config $config)
    {
        $this->twilio = $twilio;
        $this->config = $config;
    }

    public function call($from, $to, $uploadName)
    {
        $call = $this->twilio->account->calls->create(
            $from,
            $to,
            'https://s3.amazonaws.com/' . $this->config->get('fileStore.credentials.bucketName') . '/' . $uploadName,
            array('Method' => 'GET')
        );

        return $call->sid;
    }

    public function getResults($callIds)
    {
        // allow the client to pass in a single id or multiple
        if (!is_array($callIds)) {

            $callIds = array($callIds);

        }

        $results = array();

        foreach ($callIds as $id) {

            $results[] = $this->twilio->account->calls->get($id);

        }

        return $results;
    }

    public function getRange($range)
    {
        if (count($range) == 1) {

            $results = $this->twilio->account->calls->getIterator(0, 50, array(
                "StartTime>" => $range[0]
            ));

        }

        return $results;
    }

    public function getFilteredResults()
    {

        if (empty($this->filters)) {

            throw new \Exception('No filters provided');

        }

        return $this->twilio->account->calls->getIterator(0, 50, $this->filters);

    }

    public function addFilter($type, $value)
    {
        switch ($type) {
            case 'after':
                $this->filters['StartTime>'] = $value;
                break;

            case 'before':
                $this->filters['StartTime<'] = $value;
                break;

            case 'on':
                $this->filters['StartTime'] = $value;
                break;

            case 'to':
                $this->filters['To'] = $value;
                break;

            case 'from':
                $this->filters['From'] = $value;
                break;

            case 'status':
                $this->filters['Status'] = $value;
                break;

            default:
                throw new \InvalidArgumentException('Invalid filter type provided.');
                break;
        }
    }
}

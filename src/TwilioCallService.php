<?php namespace Indatus\Callbot;

use Services_Twilio;
use Indatus\Callbot\Config;
use Indatus\Callbot\Contracts\CallServiceInterface;

/**
 * This class is a Twilio implementation of the CallServiceInterface
 */
class TwilioCallService implements CallServiceInterface
{
    /**
     * Array of filters for detailed call results
     *
     * @var array
     */
    protected $filters = array();

    /**
     * Services_Twilio instance
     *
     * @var Services_Twilio
     */
    protected $twilio;

    /**
     * Config instance
     *
     * @var Indatus\Callbot\Config
     */
    protected $config;

    /**
     * Constructor injects dependancies
     *
     * @param Services_Twilio $twilio Services_Twilio instance
     * @param Config          $config Config instance
     */
    public function __construct(Services_Twilio $twilio, Config $config)
    {
        $this->twilio = $twilio;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function call($from, $to, $uploadName)
    {
        $call = $this->twilio->account->calls->create(
            $from,
            $to,
            $this->config->get('fileStore.uploadDir') . '/' . $uploadName,
            array('Method' => 'GET')
        );

        return $call->sid;
    }

    /**
     * {@inheritDoc}
     */
    public function getDetails($callIds)
    {
        $results = array();

        foreach ($callIds as $id) {

            $results[] = $this->twilio->account->calls->get($id);

        }

        return $results;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilteredResults()
    {

        if (empty($this->filters)) {

            throw new \Exception('No filters provided');

        }

        return $this->twilio->account->calls->getIterator(0, 50, $this->filters);

    }

    /**
     * {@inheritDoc}
     */
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

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
     * Constructor injects dependancies
     *
     * @param Services_Twilio $twilio Services_Twilio instance
     */
    public function __construct(Services_Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * {@inheritDoc}
     */
    public function call($from, $to, $uploadName)
    {
        $call = $this->twilio->account->calls->create(
            $from,
            $to,
            Config::getRemoteDir() . $uploadName,
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
    public function getFilteredDetails()
    {

        return $this->twilio->account->calls->getIterator(0, 50, $this->filters);

    }

    /**
     * {@inheritDoc}
     */
    public function addFilter($type, $value)
    {
        switch ($type) {
            case 'after':
                $date = $this->convertToUTC($value);
                $this->filters['StartTime>'] = $date;
                break;

            case 'before':
                $date = $this->convertToUTC($value);
                $this->filters['StartTime<'] = $date;
                break;

            case 'on':
                $date = $this->convertToUTC($value);
                $this->filters['StartTime'] = $date;
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

    /**
     * Convert timestamps to UTC before making API request
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    protected function convertToUTC($date)
    {
        $dateTime = new \DateTime(
            $date,
            new \DateTimeZone(Config::get('callservice.timezone'))
        );

        $offsetHours = $dateTime->format('P');

        return $dateTime
            ->modify($offsetHours)
            ->setTimezone(new \DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
    }
}

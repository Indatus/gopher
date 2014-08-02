<?php namespace Indatus\Gopher\Contracts;

interface CallServiceInterface
{
    /**
     * Make a single outgoing call
     *
     * @param string $from       From phone number
     * @param string $to         To phone number
     * @param string $uploadName Upload file name
     *
     * @return int Unique call id
     */
    public function call($from, $to, $uploadName);

    /**
     * Get an array of call details objects
     *
     * @param array $callIds Array of unique call ids
     *
     * @return array
     */
    public function getDetails($callIds);

    /**
     * Get an array of filtered call detail objects
     *
     * @return array
     */
    public function getFilteredDetails();

    /**
     * Add a filter to the filters array
     *
     * @param string $type  Type of filter
     * @param string $value Value for filter
     *
     * @return void
     */
    public function addFilter($type, $value);
}
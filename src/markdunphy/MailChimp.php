<?php namespace markdunphy;

use \Guzzle\Http\Client;
use \markdunphy\MailChimp\MailingList;

class MailChimp {

    const MAILCHIMP_API_URI = 'api.mailchimp.com/2.0';

    /**
     * The MailChimp API key.
     *
     * @var string
     */
    protected $key;

    /**
     * Base URI for all API calls.
     *
     * @var string
     */
    protected $base;

    /**
     * The MailChimp data center to access
     *
     * @var string
     */
    protected $data_center;

    /**
     * HTTP object for cURL requests.
     *
     * @var Guzzle\Http\Client
     */
    protected $http;

    /**
     * @param string $key The MailChimp API key to use.
     */
    public function __construct( $key ) {

        $this->setApiKey( $key );

        $this->base = $this->constructBaseUri();
        $this->http = $this->getHttpClient();

    } // __construct

    /**
     * Magic getter method to retrieve protected variables
     *
     * @param string $property the property to look for on this object
     */
    public function __get( $property ) {

        if ( isset( $this->$property ) ) {
            return $this->$property;
        }

    }

    /**
     * Retrieve all of your lists.
     *
     * http://apidocs.mailchimp.com/api/2.0/lists/list.php
     *
     * @param array $options an array of list options from the MailChimp API docs
     * @return array an array of markdunphy\MailChimp\List objects
     */
    public function getLists( array $options = [] ) {

        $lists   = $this->getResponse( '/lists/list.json', $options );
        $objects = [];

        foreach ( $lists as $list ) {
            $objects[] = new MailingList( $list );
        }

        return $objects;

    } // getLists

    /**
     * Set the API key to use. Also updates the data center to match.
     *
     * @param string $key
     *
     * @return markdunphy\MailChimp
     */
    public function setApiKey( $key ) {

        $this->api_key     = $key;
        $this->data_center = $this->detectDataCenter();

        return $this;

    } // setApiKey

    protected function getResponse( $url, $options ) {

        $url = $this->base . $url . '?' . $this->buildQueryString( $options );

        $response = $this->http->get( $url )->send()->json();

        if ( !empty( $response['errors'] ) ) {
            throw new Exception( var_export( $response['errors'] ) );
        }

        return $response['data'];
    }

    /**
     * Build a query string including the API key and any provided parameters.
     *
     * @param array $params extra parameters to pass to the query string
     *
     * @return string
     */
    protected function buildQueryString( array $params = [] ) {

        $params['apikey'] = $this->api_key;

        return http_build_query( $params );

    } // buildQueryString

    /**
     * Get an HTTP client to use for cURL requests.
     *
     * @return Guzzle\Http\Client
     */
    protected function getHttpClient() {

        return isset( $this->http ) ? $this->http : new Client( $this->base );

    } // getHttpClient

    /**
     * Construct a base URI to use for all API
     * calls
     *
     * @return markdunphy\MailChimp
     */
    protected function constructBaseUri() {

        return 'https://' . $this->data_center . '.' . self::MAILCHIMP_API_URI;

    } // constructBaseUri

    /**
     * Parse the data center from the API key.
     *
     * @return string
     */
    protected function detectDataCenter() {

        $parts = explode( '-', $this->api_key );

        return array_pop( $parts );

    } // detectedDataCenter

} // MailChimp

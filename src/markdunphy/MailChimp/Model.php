<?php namespace markdunphy\MailChimp;

use \markdunphy\MailChimp\Exception\PropertyNotFoundException;

class Model {

    protected $data = [];

    public function __construct( array $data ) {

        $this->data = $data;

    }

    public function __get( $property ) {

        if ( isset( $this->data[ $property ] ) ) {
            return $this->data[ $property ];
        }

        throw new PropertyNotFoundException( 'Could not find property ' . $property . ' in class ' . get_class( $this ) );

    }

}

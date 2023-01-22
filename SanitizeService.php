<?php

class SanitizeService {
    /**
     * Mainly used for sanitizing the input when saving a record to the DB (e.g from CRUD in the admin where we can have HTML)
     * @param string $var
     * @param $type
     * @return array|float|int|string
     */
    public static function sanitize(string $var, $type = ENT_QUOTES )
    {
        if ( is_string( $var ) ) {

            while ( $var != ( $temp = html_entity_decode( $var, ENT_QUOTES ) ) ) {
                $var = $temp;
            }

            while ( $var != ( $temp = stripslashes( $var ) ) ) {
                $var = $temp;
            }

            return htmlentities( $var );

        } else if ( is_array( $var ) ) {

            for ( $i = 0; $i < count( $var ); $i++ ) {
                $var[ $i ] = self::sanitize( $var[ $i ], $type );
            }

            return $var;

        } else if ( is_numeric( $var ) ) {

            return $var * 1;

        } else {

            return $var;

        }

    }

    /**
     * Mainly used for decoding the encoded record from the DB when saved with HTML
     * @param string $string
     * @return array|string
     */
    public static function sanitize_decode(string $string) {

        if ( is_string( $string ) ) {

            return html_entity_decode( $string, ENT_QUOTES );

        } else if ( is_array( $string ) ) {

            for ( $i = 0; $i < count( $string ); $i++ ) {
                $string[ $i ] = self::sanitize_decode( $string[ $i ] );
            }

            return $string;

        } else {

            return $string;
        }

    }
}
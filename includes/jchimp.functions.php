<?php

// function for checking is email is valid
function is_valid_email( $email ) {
    if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) && preg_match( '/@.+\./', $email ) ) {
        return true;
    } else {
        return false;
    }
}

function email_is_subscribed( $email ) {
    global $mailchimp, $list_id;

    try {
        $check = $mailchimp->lists->memberInfo( $list_id, array( array( 'email' => $email ) ) );
    } catch ( Exception $e ) {
        
    }

    if ( ! empty( $check['data'] ) ) {

        $status = isset( $check['data'][0]['status'] ) ? $check['data'][0]['status'] : false;

        if ( strtolower( $status ) == 'subscribed' ) {
            return true;
        } else {
            return false;
        }
        
    } else {
        return false;
    }
}

function get_full_name( $string = '' ) {

    $string = trim( $string );
    $string = preg_replace( array( '/\s{2,}/', '/[\t\n]/' ), ' ', $string );
    $string = explode( ' ', $string );

    if ( empty( $string ) ) {
        return false;
    }

    $count  = count( $string );
    $return = array();

    if ( $count == 1 ) {
        $return['first']    = ucwords( strtolower( $string[0] ) );
    } else if ( $count == 2 ) {
        $return['first']    = ucwords( strtolower( $string[0] ) );
        $return['last']     = ucwords( strtolower( $string[1] ) );
    } else {
        $return['first']    = ucwords( strtolower( $string[0] ) );
        $return['middle']   = ucwords( strtolower( $string[1] ) );
        $return['last']     = ucwords( strtolower( $string[2] ) );
    }

    return $return;
}

function output_error_message( $error = '' ) {
    echo json_encode( array( 'error' => $error ) );
    die();
}

function output_success_message( $message = '' ) {
    echo json_encode( array( 'success' => $message ) );
}

?>
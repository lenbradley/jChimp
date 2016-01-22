<?php

require_once dirname(__FILE__) . '/jchimp.config.php';
require_once dirname(__FILE__) . '/jchimp.functions.php';
require_once dirname(__FILE__) . '/jchimp.class.php';


if ( ! empty( $_POST ) ) {

    // Lets make sure that the API key is defined before we proceed

    if ( ! defined('MC_API_KEY') || MC_API_KEY == '' ) {
        output_error_message( MC_API_KEY_ERROR_MESSAGE );
    }

    // Now lets try to connect to the Mailchimp API

    try {
        $mailchimp = new Mailchimp( trim( MC_API_KEY ) );
    } catch ( Exception $e ) {
        output_error_message( MC_CONNECTION_ERROR_MESSAGE );
    }

    // Phew! We are connected. Lets see if they chose to opt in or out

    if ( ! empty( $_POST['OPT_IN'] ) && ! $_POST['OPT_IN'] ) {
        output_success_message();
    }

    if ( ! empty( $_POST['OPT_OUT'] ) && $_POST['OPT_OUT'] ) {
        output_success_message();
    }

    // They want in! Now lets try to process a list name either from a list id or an actual name

    if ( ! empty( $_POST['LIST_ID'] ) ) {
        $list_id = $_POST['LIST_ID'];
    } else {

        if ( ! empty( $_POST['LIST_NAME'] ) ) {
            $get_lists = $mailchimp->lists->getList( array( 'list_name' => $_POST['LIST_NAME'] ) );

            if ( ! empty( $get_lists['data'] ) ) {
                foreach ( $get_lists['data'] as $list ) {
                    if ( strtolower( $list['name'] ) == strtolower( $_POST['LIST_NAME'] ) ) {
                        $list_id = $list['id'];
                    }
                }
            }

            if ( ! isset( $list_id ) ) {
                output_error_message( MC_LIST_NOT_FOUND_MESSAGE );
            }
        } else {
            output_error_message( MC_LIST_NOT_PROVIDED_MESSAGE );
        }
    }

    $list_id = explode( ',', $list_id );

    // Time to get the subscribers name

    $name = array();

    if ( isset( $_POST['FULL_NAME'] ) ) {
        $exp_name = get_full_name( $_POST['FULL_NAME'] );

        if ( isset( $exp_name['first'] ) )  $name['first']  = $exp_name['first'];
        if ( isset( $exp_name['middle'] ) ) $name['middle'] = $exp_name['middle'];
        if ( isset( $exp_name['last'] ) )   $name['last']   = $exp_name['last'];
    } else {
        if ( isset( $_POST['FIRST_NAME'] ) )    $name['first']  = $_POST['FIRST_NAME'];
        if ( isset( $_POST['LAST_NAME'] ) )     $name['last']   = $_POST['LAST_NAME'];
    }

    if ( empty( $name ) ) {
        if ( isset( $_POST['REQUIRE_NAME'] ) && filter_var( $_POST['REQUIRE_NAME'], FILTER_VALIDATE_BOOLEAN ) ) {
            output_error_message( MC_NAME_REQUIRED_MESSAGE );
        }
    }

    // Name set, now onto the subscribers email address... lets hope that it is valid

    if ( ! is_valid_email( $_POST['EMAIL'] ) || empty( $_POST['EMAIL'] ) ) {
        output_error_message( MC_EMAIL_NOT_VALID_MESSAGE );
    }

    if ( email_is_subscribed( $_POST['EMAIL'] ) ) {
        output_error_message( MC_EMAIL_IS_SUBSCRIBED_MESSAGE );
    }

    // Alrighty... now we have to process any additional merge vars that are present

    $merge_vars = array();

    if ( ! empty( $_POST['MERGE_VARS'] ) ) {
        $merge_vars = $_POST['MERGE_VARS'];
    }

    if ( isset( $name['first'] ) ) $merge_vars['FNAME'] = $name['first'];
    if ( isset( $name['last'] ) ) $merge_vars['LNAME'] = $name['last'];

    // It's now or never... lets attempt to subscribe to the list

    if ( ! empty( $list_id ) && is_array( $list_id ) ) {

        foreach ( $list_id as $lid ) {

            try {
                $add_to_list = $mailchimp->lists->subscribe(
                    (string)    $lid,
                    (array)     array( 'email' => $_POST['EMAIL'] ),
                    (array)     $merge_vars,
                    (string)    $_POST['EMAIL_TYPE'],
                    filter_var( $_POST['DOUBLE_OPTIN'], FILTER_VALIDATE_BOOLEAN ),
                    filter_var( $_POST['REPLACE_INTERESTS'], FILTER_VALIDATE_BOOLEAN ),
                    filter_var( $_POST['SEND_WELCOME'], FILTER_VALIDATE_BOOLEAN )
                );
            } catch ( Exception $e ) {
                if ( count( $list_id ) < 2 ) {
                    output_error_message( MC_DEFAULT_ERROR_MESSAGE );
                }
            }
        }
    }

    // Success! Yay! Now it is time to let the script know that everything is swell

    output_success_message( MC_SUCCESS_MESSAGE );
}

?>
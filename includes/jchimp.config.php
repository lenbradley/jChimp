<?php
//
// jChimp Configuration
//
// All you need is your API key, which could be found by
// logging into your Mailchimp account and then going to
// Account Settings > Extras > API Keys. If you do not have
// an API key, just go ahead and create one. Otherwise, all
// you have to do is copy and past your API key below!
//

// Define Mailchimp API key
define( 'MC_API_KEY', '' );

// Define messages to output
define( 'MC_SUCCESS_MESSAGE',               'Thank you for joining our mailing list!' );
define( 'MC_DEFAULT_ERROR_MESSAGE',         'An error has occured. Please check all variables are correct and API key is set.' );
define( 'MC_API_KEY_ERROR_MESSAGE',         'An error has occured. Please check that the API key is correct.' );
define( 'MC_CONNECTION_ERROR_MESSAGE',      'Could not connect to Mailchimp. Please check that the API key is correct.' );
define( 'MC_LIST_NOT_FOUND_MESSAGE',        'The list name or ID specified was not found!' );
define( 'MC_LIST_NOT_PROVIDED_MESSAGE',     'A list name or list ID is required!' );
define( 'MC_NAME_REQUIRED_MESSAGE',         'A name must be entered to join our mailing list!' );
define( 'MC_EMAIL_NOT_VALID_MESSAGE',       'The email address you entered is not valid! Please enter a valid email address and try again.' );
define( 'MC_EMAIL_IS_SUBSCRIBED_MESSAGE',   'The email address you entered is already subscribed to our mailing list!' );

?>
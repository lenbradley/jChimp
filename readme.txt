INSTALLATION
============

1. Include jQuery by either downloading the latest version from http://jquery.com/download/ or include it via a reliable CDN, such as https://developers.google.com/speed/libraries/devguide#jquery
2. Include the file jquery.jchimp.js in the head of your HTML document, ie: <script src="http://{path}/scripts/jquery.jchimp.js"></script>
3. Make sure the "includes" folder and all its files are located somewhere accessible relative to the jQuery script. You'll need to link to jchimp.ajax.php from the script.
4. Make sure to open up "jchimp.config.php" within the "includes" folder and add your API key to MC_API_KEY before using this script.

Once both of the scripts are added to the <head> of the HTML document you are working with you will be ready to use jChimp!

USAGE
=====

1. Create a form element, like so:

<form method="POST" action="" id="jchimp">
    <input type="text" name="full_name" placeholder="Full Name"><br>
    <input type="text" name="email_address" placeholder="Email Address"><br>
    <input type="submit" value="Sign Up">
    <input type="hidden" name="list_id" value="{list_id_here}">
</form>

The input fields email_address and list_name/list_id are required, full_name is optional and can be replaced with first_name, last_name, or both.

2. Attach jChimp to form element by initializing a jQuery script, like so:

<script>
jQuery(document).ready( function($) {
    $('#jchimp').jChimp( 'http://{path}/jchimp.ajax.php', {options} );
});
</script>

http://{path}/jchimp.ajax.php is where the magic actually happens and this path MUST be correct. {options}, as described below, are optional.

OPTIONS
=======

These options are defined while attaching the script to the form. ie:

<script>
jQuery(document).ready( function($) {
    $('#jchimp').jChimp(
        'http://{path}/jchimp.ajax.php',
        {
            doubleOptin             : true,
            updateExisting          : false,
            sendWelcome             : true,
            emailType               : 'html',
            replaceInterests        : true,
            submitFormOnSuccess     : false,
            silentMode              : false,
            requireName             : false,
        }
    );
});
</script>

fullNameInput ( type: string, default: full_name ) : Name of form element where the user inserts their full name.
firstNameInput ( type: string, default: first_name ) : Name of form element where the user inserts their first name only.
lastNameInput ( type: string, default: last_name ) : Name of form element where the user inserts their last name only.
mergeVarsInput ( type: string, default: merge_var ) : Name of form element(s) to capture MailChimp merge vars. Used as an array input.
emailAddressInput ( type: string, default: email_address ) : Name of form element to capture email address. (required)
listIDInput ( type: string, default: list_id ) : Name of form element to specify the list ID defined by MailChimp.
listNameInput ( type: string, deafult: list_name ) : Name of form element to specify the list name defined by MailChimp.
optInInput ( type: string, default: opt_in ) : Name of form element to specify the Opt In. This element, if present and checked, controls if a user if sent to the MailChimp list.
optOutInput (type: string, default: opt_out ) : Name of form element to specify the Opt Out. This element, if present and checked, tells the script to not add to MailChimp list.
doubleOptin ( type: bollean, default: true ) : If true, an email will be sent to subscriber to confirm subscription.
updateExisting ( type: bollean, default: false ) : If true, people who match a record in MailChimp already will be updated. If false, users will be skipped if they already exist.
sendWelcome ( type: boolean, default: true ) : If true, will send a welcome email to new subscriber.
emailType ( type: string, default: 'html' ) : Tells MailChimp what type of email to send out.
replaceInterests ( type: boolean, default: true ) : Controls the Mailchimp option.
submitFormOnSuccess (type: boolean, default: false ) : Tells jChimp to submit the form on success.
silentMode ( type: boolean, default: false ) : If true, tells jChimp to operate in silent mode. No messages will be displayed.
requireName ( type: boolean, default:  false ) : Specifies if name ( first_name, last_name, or full_name ) is required.

CALLBACKS
=========

onSubmit : Called when submit button clicked
onComplete : Called when jChimp has completed all operations
onSuccess : Called when jChimp successfully completed all operations
onError : Called when an error occurs within jChimp
onFail : Called when something goes wrong with the script

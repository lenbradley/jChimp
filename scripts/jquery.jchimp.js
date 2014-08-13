/*!
 * jChimp : A jQuery Mailchimp Plugin
 * Author: Len Bradley @ http://www.ideamktg.com
 */

;( function($) {
    "use strict";

    $.fn.jChimp = function( script, options ) {
        
        // Set up default options
        var defaults = {            
            fullNameInput           : 'full_name',
            firstNameInput          : 'first_name',
            lastNameInput           : 'last_name',
            mergeVarsInput          : 'merge_var',
            emailAddressInput       : 'email_address',
            listIDInput             : 'list_id',
            listNameInput           : 'list_name',
            optInInput              : 'opt_in',
            optOutInput             : 'opt_out',
            doubleOptin             : true,
            updateExisting          : false,
            sendWelcome             : true,
            emailType               : 'html',
            replaceInterests        : true,
            submitFormOnSuccess     : false,
            silentMode              : false,
            requireName             : false,
            onSubmit                : function() {},
            onComplete              : function() {},
            onSuccess : function( e, message, silent ) {
                if ( ! silent && message != '' ) {
                    alert( message );
                }
            },
            onError : function( e, error, silent ) {
                if ( ! silent && error != '' ) {
                    alert( error );                    
                }
            },
            onFail : function( e, error, silent  ) {
                if ( ! silent ) {
                    alert( 'Script failed : ' + error );
                }
            }            
        };
        var options = $.extend( {}, defaults, options );

        // Define the form element
        var theForm = $(this);

        if ( typeof script === 'undefined' ) {

            if ( typeof $(theForm).attr('data-jchimp') !== 'undefined' ) {
                var script = $(theForm).data('jchimp');
            } else {
                submitTheForm( theForm );
            }
        }

        $(theForm).on( 'submit', function( e ) {
            e.preventDefault();

            if ( typeof options.onSubmit == 'function' ) {
               options.onSubmit( $(theForm) );
            }

            var mailchimpArgs = {};

            var fullNameInput           = $(theForm).find( 'input[name="' + options.fullNameInput + '"]' ).val();
            var firstNameInput          = $(theForm).find( 'input[name="' + options.firstNameInput + '"]' ).val();
            var lastNameInput           = $(theForm).find( 'input[name="' + options.lastNameInput + '"]' ).val();
            var emailAddressInput       = $(theForm).find( 'input[name="' + options.emailAddressInput + '"]' ).val();
            var listIDInput             = $(theForm).find( 'input[name="' + options.listIDInput + '"]' ).val();
            var listNameInput           = $(theForm).find( 'input[name="' + options.listNameInput + '"]' ).val();
            var optIn                   = $(theForm).find( 'input[name^="' + options.optInInput + '"]' ).prop( 'checked' );
            var optOut                  = $(theForm).find( 'input[name^="' + options.optOutInput + '"]' ).prop( 'checked' );
            var doubleOptin             = $(theForm).find( 'input[name="double_optin"]' ).val();
            var updateExisting          = $(theForm).find( 'input[name="update_existing"]' ).val();
            var sendWelcome             = $(theForm).find( 'input[name="send_welcome"]' ).val();
            var emailType               = $(theForm).find( 'input[name="email_type"]' ).val();
            var replaceInterests        = $(theForm).find( 'input[name="replace_interests"]' ).val();
            var mergeVars               = $(theForm).find( 'input[name^="' + options.mergeVarsInput + '"]' );

            if ( typeof optIn !== 'undefined' && optIn == false ) {
                submitTheForm( theForm );
                return true;
            }

            if ( typeof optOut !== 'undefined' && optOut == true ) {
                submitTheForm( theForm );
                return true;
            }

            mailchimpArgs.DOUBLE_OPTIN        = ( typeof doubleOptin        !== 'undefined' ) ? doubleOptin      : options.doubleOptin;
            mailchimpArgs.UPDATE_EXISTING     = ( typeof updateExisting     !== 'undefined' ) ? updateExisting   : options.updateExisting;
            mailchimpArgs.SEND_WELCOME        = ( typeof sendWelcome        !== 'undefined' ) ? sendWelcome      : options.sendWelcome;
            mailchimpArgs.EMAIL_TYPE          = ( typeof emailType          !== 'undefined' ) ? emailType        : options.emailType;
            mailchimpArgs.REPLACE_INTERESTS   = ( typeof replaceInterests   !== 'undefined' ) ? replaceInterests : options.replaceInterests;

            if ( options.requireName != false ) {
                mailchimpArgs.REQUIRE_NAME = true;
            }

            if ( typeof fullNameInput  !== 'undefined' && fullNameInput  !== '' ) mailchimpArgs.FULL_NAME  = fullNameInput;
            if ( typeof firstNameInput !== 'undefined' && firstNameInput !== '' ) mailchimpArgs.FIRST_NAME = firstNameInput;
            if ( typeof lastNameInput  !== 'undefined' && lastNameInput  !== '' ) mailchimpArgs.LAST_NAME  = lastNameInput;

            if ( typeof emailAddressInput !== 'undefined' && emailAddressInput !== '' ) {
                mailchimpArgs.EMAIL = emailAddressInput;
            }

            if ( typeof listIDInput !== 'undefined' && listIDInput !== '' ) {
                mailchimpArgs.LIST_ID = listIDInput;
            }

            if ( typeof listNameInput !== 'undefined' && listNameInput !== '' ) {
                mailchimpArgs.LIST_NAME = listNameInput;
            }            

            if ( typeof mergeVars !== 'undefined' ) {
                var mergeVarsObject = {};

                $(mergeVars).each( function() {
                    var key = between( $(this).attr('name').replace( /["']/g, "" ), '[', ']' );
                    var val = $(this).val();

                    mergeVarsObject[key] = val;
                });

                mailchimpArgs.MERGE_VARS = mergeVarsObject;
            }
            
            var mailchimp = $.post(
                script,
                mailchimpArgs
            ).success(
                function( response ) {

                    var result = $.parseJSON(response);

                    if ( typeof result.success !== 'undefined' ) {

                        if ( typeof options.onSuccess == 'function' ) {
                           options.onSuccess( $(theForm), result.success, options.silentMode );
                        }

                        if ( options.submitFormOnSuccess ) {
                            submitTheForm( theForm );
                            return true;
                        } else {
                            $(theForm)[0].reset();
                        }
                    } else {
                        if ( typeof options.onError == 'function' ) {
                           options.onError( $(theForm), result.error, options.silentMode );
                        }
                    }
                }
            ).error(
                function( response ) {
                    if ( typeof options.onFail == 'function' ) {
                       options.onFail( $(theForm), response );
                    }
                }
            ).complete(
                function () {
                    if ( typeof options.onComplete == 'function' ) {
                       options.onComplete( $(theForm) );
                    }
                }
            );
        });
    }

    function between( str, left, right ) {
        if( ! str || ! left || ! right ) return null;

        var left_loc = str.indexOf(left);
        var right_loc = str.indexOf(right);

        if ( left_loc == -1 || right_loc == -1 ) {
            return null;
        }

        return str.substring( left_loc + left.length, right_loc );
    }

    function submitTheForm( form ) {
        $(form).off().submit();
        return true;
    }

    $(document).ready( function() {
        if ( $('form[data-jchimp]').length ) {
            $('form[data-jchimp]').jChimp();
        }
    });    

}( jQuery ));
$(function() {
    jQuery.fn.extend({
        ajaxFormSubmit: function() {
            /** preserve "this" */
            var main_form = this;

            /** Request related vars */
            var form_href = $(main_form).prop('action');
            var form_method = $(main_form).prop('method');

            /** Array of input elements */
            var form_fields = $(main_form).find(':input:enabled:not(:button)');

            /** ajax data */
            var form_data = new FormData();

            /** promises */
            var form_data_build_promises = [];

            /** laravel validation code */
            var laravelValidationFailResponseCode = 422;

            /**
             *****************************
             * MODULE INTERNAL FUNCTIONS *
             *****************************
             */

            /** Override default submit */
            var _overrideSubmit = function(e) {
                // prevent default submit behaviour
                e.preventDefault();

                // show loader to prevent user input while ajax submit is assembled
                $(".load-modal").show();

                /** this deferred object gets resolved after confirm event, or if the form does not use confirm */
                var confirmDeferred = $.Deferred();

                /** this deferred object gets resolved after validation success, or if the form does not use validate */
                var validateDeferred = $.Deferred();

                /**
                 * the two defers are put in an array, so resolution of
                 * both is needed to be resolved before proceeding to
                 * the actual ajax request
                 */
                var beforeSendDefers = [];
                beforeSendDefers.push(confirmDeferred);
                beforeSendDefers.push(validateDeferred);

                // set fields
                _generateFormData();

                // checks if the form needs to be validated, or confirmed
                _beforeSend(confirmDeferred, validateDeferred);

                /**
                 * Check if both defers for confirm and validate are both resolved
                 */
                $.when.apply($, beforeSendDefers).done(function() {

                    // send ajax request
                    $.when(_sendAjax()).done(function(response) {

                        // check if the form has to redirect
                        if(_redirects()) {
                            // redirect
                            _redirectOnSuccess(response);
                        } else {
                            // loader off to show modal

                            console.log('hello im here @ success');
                            $(".load-modal").hide();
                            // show success modal
                            _showSuccessModal(response);
                        }

                    }).fail(function(response) {
                        // loader off to show modal

                            console.log('hello im here @ fail');
                        $(".load-modal").hide();
                        // show error modal
                        _showErrorModal(response);
                    });

                }).fail(function() {
                    // do nothing
                    // the either the validation has failed,
                    // or the confirmation was not triggerd
                    return;
                });
            };

            /** before submit */
            var _beforeSend = function(confirmsDefer, validatesDefer) {

                if(!_validates()) {
                    validatesDefer.resolve();
                } else {
                    var validateAction = $(main_form).data('validate-action');

                    if(typeof validateAction == 'undefined'
                        || validateAction.length < 1) {
                        console.error('Validate action not defined. Check if form has data-validate-action property.');
                    }


                    $.when.apply($, form_data_build_promises).then(
                    function() {
                        $.when(_sendValidateAjax()).done(function() {

                            validatesDefer.resolve();

                        }).fail(function(response) {
                            // loader off tio show errors (modal or form)

                            console.log('hello im here @ beforeSend');
                            $(".load-modal").hide();
                            // check if error is because of form validation error;
                            if(response.status == laravelValidationFailResponseCode) {
                                // if error is 422

                                if(_shoutsErrors()) {
                                    console.log('hello _showErrorModal');
                                    _showErrorModal(response);
                                } else {
                                    console.log('hello _showInputErrorsPerField');
                                    _showInputErrorsPerField(response);
                                }
                            } else {
                                // else if not form validation error, show error modal
                            }
                            validatesDefer.reject();

                        });

                    }, function() {
                        // something went wrong with the script.
                        console.error('Input error. Reload page and try again.');

                    });
                }

                if(!_confirms()) {
                    confirmsDefer.resolve();
                } else {
                    $.when(validatesDefer).then(function() {

                        // bind confirm event
                        // resolve confirm defer when the event is executed
                        $(main_form).off('confirm-ajax-submit').on('confirm-ajax-submit',function(e) {
                            confirmsDefer.resolve();
                        });

                        // activate confirm modal
                        var confirmModalId = $(main_form).data('confirm-modal');
                        var confirmModal = $('#' + confirmModalId);
                        $.fn.activateModalElement(confirmModal);

                    }, function() {
                        confirmsDefer.reject();
                    });
                }

            };

            /** Generate deferred objects to be passed before the form data values are assembled */
            var _generateFormData = function() {
                $.each(form_fields, function(k,v) {
                    var deferred = $.Deferred();
                    form_data_build_promises.push(deferred);

                    var formDataDeferred = _deferredFormDataSet(v, deferred);
                });
            };

            /**
             * Resolve only after the method is done to ensure
             * data will be part of the ajax request
             */
            var _deferredFormDataSet = function(element, deferred) {
                // defer.resolve();
                if($(element).is(':radio') || $(element).is(':checkbox')) {
                    // radios and checkboxes
                    if($(element).is(':checked')) {
                        form_data.set($(element).prop('name'), $(element).val());
                    }
                } else {
                    // everything else
                    form_data.set($(element).prop('name'), $(element).val());
                }

                deferred.resolve();
            };

            /** the ajax request */
            var _sendAjax = function() {
                return $.ajax({
                    url: form_href,
                    type: form_method,
                    processData: false, // intentional. prevents jquery from using JSON.stringify on the request data
                    contentType: false, // intentional. prevents jquery from putting a wrong content type.
                    data: form_data
                });
            };

            /** the validate request */
            var _sendValidateAjax = function() {
                var validateAction = $(main_form).data('validate-action');

                return $.ajax({
                    url: validateAction,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: form_data
                })
            };

            var _showInputErrorMessage = function(input_element_name, error_message_text) {
                var inputWrapper = $(form_fields).filter('[name=' + input_element_name + ']').closest('.input-wrapper');
                var messageContainer = $(inputWrapper).find('.error-message');

                // add error class to the input-wrapper
                $(inputWrapper).addClass('error');

                // always display the first
                $(messageContainer).html(error_message_text);

                return;
            };

            var _errorCrawl = function() {

            };

            var _clearErrorMessages = function() {
                $(main_form).find('.input-wrapper').each(function(k,v) {
                    $(v).removeClass('error');
                    $(v).find('error-message').html('');
                });
            };

            var _showSuccessModal = function(response) {
                /**
                 * This fail callback assumes with the structure of:
                 * [
                 *      'message': 'Sample success message'
                 * ]
                 */
                var success_modal_id = $(main_form).data('success-modal');

                if(typeof success_modal_id === 'undefined') {
                    // the loader will be closed
                    // but nothing will happen after
                    // not recommended since feedback is very minimal
                    console.error('Success modal not defined in the form tag. Check data-success-modal');
                    return;
                } else {
                    /** success modal */
                    var success_modal = $('#' + success_modal_id);

                    if($(success_modal).length < 1) {
                        console.error('Success modal not found check modal element ID or form data-success-modal property');
                        return;
                    }

                    if(typeof response.message !== 'undefined'
                        && response.message.length > 0) {
                        // implement message
                        // look for the .modal-message element
                        // replace the html with the response message
                        $(success_modal).find('.modal-message').html(response.message);
                    }

                    // show modal
                    $.fn.activateModalElement(success_modal);
                }
            };

            var _redirectOnSuccess = function(response) {
                /**
                 * This callback assumes with the structure of:
                 * [
                 *      'href': 'http://www.url-sample.com'
                 * ]
                 */
                if(typeof response.href !== 'undefined'
                    && response.href.length > 0) {
                    console.log(response);
                    $(".load-modal").hide();
                    // location.href = response.href;
                } else {
                    /**
                     * There's a chance your href key is not set, or your link is invalid
                     */
                    console.error('Invalid href for redirect');
                }
            };

            var _showInputErrorsPerField = function(response) {
                /**
                 * This fail callback assumes with the structure of:
                 * [
                 *      'errors': [
                 *          ***input element name 1***: [
                 *              <<<< a single dimension array of messages >>>>
                 *          ],
                 *          ***input element name 2***: [
                 *              <<<< a single dimension array of messages >>>>
                 *          ]
                 *      ]
                 * ]
                 */
                $.each(response.responseJSON.errors, function(input_element_name, error_messages) {
                    _showInputErrorMessage(input_element_name, error_messages[0]);
                });
            };

            var _showErrorModal = function(response) {
                /**
                 * This fail callback assumes with the structure of:
                 * [
                 *      'message': 'Sample error message'
                 * ]
                 */
                var error_modal_id = $(main_form).data('error-modal');

                if(typeof error_modal_id === 'undefined') {
                    // the loader will be closed
                    // but nothing will happen after
                    // not recommended since feedback is very minimal
                    console.error('Error modal not defined in the form tag. Check data-error-modal');
                    return;
                } else {
                    /** error modal */
                    var error_modal = $('#' + error_modal_id);

                    if($(error_modal).length < 1) {
                        console.error('Error modal not found check modal element ID or form data-error-modal property');
                        return;
                    }

                    if (response.status == laravelValidationFailResponseCode &&
                        typeof response.responseJSON.errors !== 'undefined'
                        ) {

                        var response_errors_objects = response.responseJSON.errors;
                        var first_key = Object.keys(response_errors_objects)[0];
                        console.log();
                        $(error_modal).find('.modal-message').html(response_errors_objects[first_key][0]);

                    } else if(typeof response.responseJSON.message !== 'undefined'
                        && response.responseJSON.message.length > 0) {
                        // implement message
                        // look for the .modal-message element
                        // replace the html with the response message
                        $(error_modal).find('.modal-message').html(response.responseJSON.message);
                    }

                    // show modal
                    $.fn.activateModalElement(error_modal);
                }
            }

            /**
             ******************************************
             * Checking of optional response handling *
             ******************************************
             */
            var _validates = function() {
                return $(main_form).hasClass('ajax-form--validates');
            };

            var _confirms = function() {
                return $(main_form).hasClass('ajax-form--confirms');
            };

            var _redirects = function() {
                return $(main_form).hasClass('ajax-form--redirects');
            };

            var _shoutsErrors = function() {
                return $(main_form).hasClass('ajax-form--modalized-error');
            };

            /** catch submit event */
            $(main_form).on('submit', function(e) {
                _clearErrorMessages();
                _overrideSubmit(e);
            });
        },
        confirmEventTrigger: function() {
            var trigger = this;
            var formTargetId = $(trigger).data('form-target');

            if(typeof formTargetId == 'undefined'
                || formTargetId.length < 1) {

                console.error('data-form-target property not set.');
                console.error(trigger);
                return;
            }

            if($('#' + formTargetId).length < 1) {
                console.error('form target with ID of ' + formTargetId + ' not found.');
                return;
            }

            $(trigger).on('click', function() {
                $('#' + formTargetId).trigger('confirm-ajax-submit');
            });
        }
    });

    $(document).find('form.ajax-form').each(function(k, v) {
        $(v).ajaxFormSubmit();
    });

    $(document).find('.ajax-form--confirm-trigger').each(function(k, v) {
        $(v).confirmEventTrigger();
    });
});

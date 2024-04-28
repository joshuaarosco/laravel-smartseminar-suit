/**
 * MODAL COMMON
 *
 * On trigger element, (ie: button, anchor)
 *    Add class "modal-trigger"
 *    Add property "data-modal-target", value is the ID of the modal element
 *
 * On the modal element
 *    Add ID same as the trigger's "data-modal-target"
 */
$(function() {
  jQuery.fn.extend({
    initModalTrigger: function() {
      var trigger_element = this;

      var modal_target_id = $(trigger_element).data('modal-target');

      var modal_element = $(document).find('#' + modal_target_id).first();

      // add trigger
      $(trigger_element).on('click', function() {
        $.fn.activateModalElement(modal_element);
        return;
      });

    }, // end initModalTrigger function
    activateModalElement: function($modal_element) {
      $modal_element.addClass('active');
      $($modal_element).trigger( "modal-activate" );
    },
    deactivateModalElement: function($modal_element) {
      $modal_element.removeClass('active');
      $($modal_element).trigger( "modal-deactivate" );
    },
    initModalClose: function() {
      var trigger = this;
      var target_modal = $(trigger).closest('.modal');

      $(trigger).on('click', function() {
        $.fn.deactivateModalElement(target_modal);
      });
    },
    addModalMessage: function(modal_element, message) {
      modal_element.find('.modal-message').html(message);
    },
  });

  $(document).find('.modal-trigger').each(function(k, v) {
    $(v).initModalTrigger();
  });

  $(document).find('.modal-close').each(function(k, v) {
    $(v).initModalClose();
  })
});

/**
 * Loading Animation
 * Turn on animation by calling $.fn.loaderAnimationOn();
 *  - function has optional parameter for text.
 */
$(function() {
    jQuery.fn.extend({
        loaderAnimationOn: function(custom_text) {
            var loader_container = $( '<div>', { 'class': 'loader-container' } );

            var loader_overlay = $( '<div>', { 'class': 'loader-overlay' } );
            var loader_spin_container = $( '<div>', { 'class': 'loader-spin' } );
            var loader_text_container = $( '<div>', { 'class': 'loader-text' } );

            $('body').append($(loader_container).append(loader_overlay, loader_spin_container, loader_text_container));

            $(loader_overlay).fadeIn();
            $(loader_spin_container).fadeIn();

            if( (custom_text != undefined) && (custom_text.length > 0) ) {
                $(loader_text_container).html(custom_text);
                $(loader_text_container).fadeIn();
            }
        },
        loaderAnimationOff: function() {
            var loader_container = $(document).find('.loader-container');

            var fadeoutPromises = [];

            $(loader_container).children().each(function(k,v) {
                var defer = new $.Deferred();
                fadeoutPromises.push(defer);
                $(v).fadeOut({
                    complete: function() {
                        defer.resolve();
                    }
                });
            });

            /** When all fade out is done, destroy the element */
            $.when.apply($, fadeoutPromises).done(function() {
                $(loader_container).remove();
            });
        }
    });
});

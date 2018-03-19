/**
 * @file
 * Default JS Slideshow Caroufredsel
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 */
(function ($, Drupal, window) {

    "use strict";

    Drupal.behaviors.gayaEditorialSlideshowBehavior = {
        attach: function (context, settings) {
            defaultCaroufredsel(context, settings);
        }
    };

    function defaultCaroufredsel(context, settings) {
        $(context).find('.ge_slideshow_theme_caroufredsel').each(function () {
            var autostart = $(this).attr('slideshow-autostart');
            var prev = "#" + $(this).attr('slideshow-prev');
            var next = "#" + $(this).attr('slideshow-next');
            var pager = "#" + $(this).attr('slideshow-pager');
            $(this).find('ul.list').carouFredSel({
                auto: autostart,
                prev: prev,
                next: next,
                pagination: pager,
            });
        });
    }
})(jQuery, Drupal, window);

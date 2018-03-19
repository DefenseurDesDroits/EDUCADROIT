/**
 * @file
 * Default JS Video Dailymotion
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 */
(function ($, Drupal, window) {

    "use strict";

    Drupal.behaviors.gayaEditorialVideoDailymotionBehavior = {
        attach: function (context, settings) {
            onDailymotionAPIReady(context, settings);
        }
    };

    /**
     * onDailymotionAPIReady
     *
     * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
     * @param context
     * @param settings
     */
    function onDailymotionAPIReady(context, settings) {
        $(context).find('.gaya_editorial_video.dailymotion-video').each(function () {
            var autoplay = $(this).attr('data-attr-autoplay');
            var autohide = $(this).attr('data-attr-autohide');
            var width = $(this).attr('data-attr-width');
            var height = $(this).attr('data-attr-height');
            var video_id = $(this).attr('data-attr-video-id');
            player_dailymotion[video_id] = DM.player(document.getElementById($(this).attr('id')), {
                video: video_id,
                width: width,
                height: height,
                params: {
                    autoplay: autoplay,
                    controls: autohide
                }
            });
        });
    }

    var player_dailymotion = [];

})(jQuery, Drupal, window);

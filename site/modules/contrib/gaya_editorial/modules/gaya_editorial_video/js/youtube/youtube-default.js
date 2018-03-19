/**
 * @file
 * Default JS Video Youtube
 *
 * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
 */
(function ($, Drupal, window) {

    "use strict";

    Drupal.behaviors.gayaEditorialVideoYoutubeBehavior = {
        attach: function (context, settings) {
            onYouTubeIframeAPIReady(context, settings);
        }
    };

    /**
     * onYoutubeIframAPIReady
     *
     * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
     * @param context
     * @param settings
     */
    function onYouTubeIframeAPIReady(context, settings) {
        $('.gaya_editorial_video.youtube-video').each(function () {
            var autoplay = $(this).attr('data-attr-autoplay');
            var autohide = $(this).attr('data-attr-autohide');
            var width = $(this).attr('data-attr-width');
            var height = $(this).attr('data-attr-height');
            var video_id = $(this).attr('data-attr-video-id');
            if( YT.Player !== undefined ) {
                player_youtube[video_id] = new YT.Player($(this).attr('id'), {
                    height: parseInt(height),
                    width: parseInt(width),
                    videoId: video_id
                });
            }
        });
    }

    /**
     * OnPlayerReady
     *
     * @author Sébastien Libbrecht <slibbrecht@gaya.fr>
     * @param event
     */
    function onPlayerReady(event) {
    }

    var player_youtube = [];
})(jQuery, Drupal, window);

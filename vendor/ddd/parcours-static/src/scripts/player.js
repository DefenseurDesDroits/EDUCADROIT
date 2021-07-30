// jshint ignore: start

(function ($, window, document) {
    var Player = {
        init: function () {
            this.videoLayer();
        },

        videoLayer: function () {
            var $btn = $('.video-toggle');

            if ($btn.length === 0) {
                return;
            }

            function onPlayerStateChange(event) {
                var url = $('#player').data('url');

                switch (event.data) {
                    case YT.PlayerState.ENDED:
                        console.log($.magnificPopup);
                        $.magnificPopup.close();
                        window.location = url;
                        break;
                }
            }

            $btn.each(function () {
                var $this = $(this);
                var url = $this.data('url');

                $this.magnificPopup({
                    type: 'iframe',
                    iframe: {
                        markup: '<div class="mfp-iframe-scaler">' +
                        '<div class="mfp-close"></div>' +
                        '<iframe id="player" class="mfp-iframe" data-url="'+url+'" frameborder="0" allowfullscreen></iframe>' +
                        '</div>',
                        patterns: {
                            youtube: {
                                index: 'youtube.com/',
                                id: 'v=',
                                src: '//www.youtube.com/embed/%id%?rel=0&autoplay=1&enablejsapi=1'
                            }
                        },
                        srcAction: 'iframe_src',
                    },
                    callbacks: {
                        open: function () {
                            var player = new YT.Player('player', {
                                events: {
                                    'onStateChange': onPlayerStateChange
                                }
                            });
                        }
                    },
                    midClick: true,
                    closeOnBgClick: false,
                    removalDelay: 500,
                    mainClass: 'mfp-fade mfp-video',
                });
            });
        }
    };

    $(document).ready(function () {
        Player.init();
    });
}(jQuery, window, document));

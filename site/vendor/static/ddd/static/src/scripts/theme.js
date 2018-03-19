'use strict';

var Theme = {};

Theme = {
    defaults: {
        carousel: {
            items: 1,
            nav: true,
            dots: true,
            responsiveClass: true,
            navText: [
                '<span class="sr-only">Précédent</span>',
                '<span class="sr-only">Suivant</span>'
            ]
        }
    },
    init: function() {
        this.domElement();
        this.bindEvents();

        this.handlePageOverlay();
        this.handleCarouselGallery();

        this.styledSelect();
        this.initNavMain();
        this.valign();
        this.mPopup();
        this.inputFile();
        this.formChosen();
        this.waypoints();
        this.cookies();

        this.windowRezise();
    },

    domElement: function() {
        this.$body              = $('body');
        this.$page              = $('.page-container');
        this.$btnMenu           = $('#navbar-toggle');
        this.$nav               = $('#nav');
        this.$carouselGallery   = $('#carousel-gallery');
    },

    bindEvents: function() {
        this.$btnMenu.on('click', this.handleNavbar.bind(this));
        this.$page.on('click', '.page-overlay', this.handleNavbar.bind(this));
    },

    windowRezise: function(){
        $(window).on('resize', function ($this) {
            Theme.initNavMain();
            Theme.valign();
            Theme.waypoints();
        });
    },

    waypoints: function(){
        var $stick = $('.stick-download');

        if ($stick.length === 0) return;

        if (document.body.offsetWidth > 991) {
            setTimeout(function () {
                $stick.addClass('stick-show');
            }, 3600);

            var waypoints = $('.main').waypoint(function(direction) {
                $stick.fadeOut();
            }, {
                offset: '-200px'
            });
        } else {
            $stick.fadeOut();
        }
    },

    cookies: function(){
        function createCookie(name,value,days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
            }
            else var expires = "";
            document.cookie = name+"="+value+expires+"; path=/";
        }

        function readCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        }

        $('.stick-download .btn').on('click', function(){
            createCookie('cookiesStick','validate',7);
            $('.stick-download').fadeOut();
        });

        if ( readCookie('cookiesStick') !== 'validate' ){
            $('.stick-download').fadeIn();
        }

		$('.disclaimer .close').on('click', function(){
			createCookie('cookiesDisclaimer','validate',7);
			$('.disclaimer').fadeOut();
			setTimeout(function () {
				$('.page-content').removeAttr('style');
			}, 400);
		});

		if ( readCookie('cookiesDisclaimer') !== 'validate' ){
			$('.disclaimer').fadeIn();
			setTimeout(function () {
				var $headerHeight = $('.header').height();
				$('.page-content').css('padding-top', $headerHeight);
			}, 400);
		}
    },

    handleAriaExpanded: function(element) {
        var $this = $(element);

        if ($this.attr('aria-expanded') == 'false') {
            $this.attr('aria-expanded', 'true');
            $this.focus();
        } else {
            $this.attr('aria-expanded', 'false');
        }
    },

    handlePageOverlay: function() {
        var overlay = document.createElement('div');
        overlay.className = 'page-overlay';

        this.$page.append(overlay);
    },

    initNavMain: function() {
        var $nav1 = $('#nav .nav-quick');
        var $nav2 = $('#nav .nav-main');

        if (document.body.offsetWidth < 992) {
            this.$nav.css('height', $nav1.height() + $nav2.height() + 20);

            $nav1.hide();
            $nav2.hide();
        }
        else {
            this.$nav.removeAttr('style');
        }
    },

    handleNavbar: function() {
        if (document.body.offsetWidth < 992) {
            if (this.$page.is('.open')) {
                setTimeout(function () {
                    $('#nav .nav-quick, #nav .nav-main').hide();
                }, 400);
            } else {
                $('#nav .nav-quick, #nav .nav-main').show();
            }
            this.$page.toggleClass('open');
            this.handleAriaExpanded(this.$btnMenu);
        }
    },

    styledSelect: function() {
        var formSelect = $('.form-select');

        if (formSelect.length) {
            formSelect.wrap('<div class="styled-select" />');
        }
    },

    valign: function(){
        $('.valign').matchHeight();
    },

    mPopup: function(){
        $('.open-popup-link').magnificPopup({
            type:'inline',
            midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
        });
    },

    inputFile: function(){
        $( '.inputfile' ).each( function()
        {
            var $input	 = $( this ),
                $label	 = $input.next( 'label' ),
                labelVal = $label.html();

            $input.on( 'change', function( e )
            {
                var fileName = '';

                if( this.files && this.files.length > 1 )
                    fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                else if( e.target.value )
                    fileName = e.target.value.split( '\\' ).pop();

                if( fileName )
                    $label.find( 'span' ).html( fileName );
                else
                    $label.html( labelVal );
            });
        });
    },

    formChosen: function(){
        var $formChosen = $('.form-chosen');
        var $url = $formChosen.find('.form-url');
        var $btnUrl = $formChosen.find('.input-url');
        var $download = $formChosen.find('.form-download');
        var $btnDownload = $formChosen.find('.input-download');

        $url.hide();
        $download.hide();

        $btnUrl.on('click', function(){
            $url.show();
            $download.hide();
        });

        $btnDownload.on('click', function(){
            $url.hide();
            $download.show();
        });
    },

    handleCarouselGallery: function(options) {
        var $element = this.$carouselGallery;

        var defaults = {
            items: 1,
            nav: true,
            responsiveClass: true,
            navText: [
                '<span class="sr-only">Précédent</span>',
                '<span class="sr-only">Suivant</span>'
            ],
            dots: false,
            mouseDrag: false,
            touchDrag: false,
            smartSpeed: 450,
            autoplay: false,
            animateOut: 'fadeOut',
			autoHeight: true,
            loop:true,
            afterInit : function() {
                // make individual items focusable
                $element.find('.owl-item').attr('aria-selected','false').attr('tabindex','0');
                $element.attr('tabindex','0');

                // on when an item has focus, let screen readers know it is active
                $element.find('.owl-item').on('focus',function() {
                    $element.find('.owl-item').attr('aria-selected','false');
                    $(this).attr('aria-selected','true');
                });

                // show instructions to keyboard users when the carousel is focused
                $element.find('.owl-wrapper-outer').append('');
            }
        };

        if ($element.length === 0) return;

		options = $.extend(this.defaults.carousel, defaults, options);

		$('body').waitForImages(function() {

			$element.owlCarousel(options);

			$element.trigger('play.owl.carousel', 1000);

			var $nav = $element.find('.owl-nav');
			var $nav2 = $nav.html().split('div').join('button type="button" aria-hidden="true"');

			$nav.replaceWith('<div class="owl-nav">' + $nav2 + '</div>');

			$element.find('.owl-next').click(function(){
				$element.trigger('next.owl.carousel');
			});
			$element.find('.owl-prev').click(function(){
				$element.trigger('prev.owl.carousel');
			});

			var $owl = $element;
			var $owlPause = $('.section-gallery .pause');
			var $owlresume = $('.section-gallery .resume');
			$owlPause.on('click', function(){
				$owl.trigger('stop.owl.autoplay');
				$owlresume.show();
				$owlPause.hide();
			});

			$owlresume.on('click', function(){
				$owl.trigger('play.owl.autoplay');
				$owlPause.show();
				$owlresume.hide();
			});

			var $manualGallery = $('.manual-gallery');
			if ($manualGallery.length !== 0) {
				var $button = $manualGallery.find('.summary li a');

				$button.on('click', function(e){
					e.preventDefault();
					var $this = $(this);
					var $index = $this.closest('li').index();
					$element.trigger("to.owl.carousel", [$index, 500, true])
					$('.show-summary .summary').hide();
				});

				var $showSummary = $('.show-summary');
				var $btnShowSummary = $showSummary.find('.btn');
				$btnShowSummary.on('click', function(){
					$showSummary.find('.summary').fadeToggle();
				});
			}

			$element.find('.owl-item').not('.active').attr('aria-hidden', 'true').attr('aria-selected','false');
			$element.find('.owl-item').not('.active').find('a').attr('tabindex', '-1');
			$element.on('initialized.owl.carousel changed.owl.carousel refreshed.owl.carousel', function (event) {
				if (!event.namespace) return;
				var carousel = event.relatedTarget,
					element = event.target,
					current = carousel.current();
				$element.find('.owl-item').not('.active').not('.cloned').removeAttr('aria-hidden').attr('aria-selected','true');
				$element.find('.owl-item').not('.active').not('.cloned').find('a').removeAttr('tabindex');

				$element.find('.owl-item.active').attr('aria-hidden', 'true').attr('aria-selected','false');
				$element.find('.owl-item.cloned').attr('aria-hidden', 'true').attr('aria-selected','false');
				$element.find('.owl-item.active').find('a').attr('tabindex', '-1');
				$element.find('.owl-item.cloned').find('a').attr('tabindex', '-1');
			});
        });
    }
};

(function($) {
    $(document).ready(function() {
        Theme.init();
    });
})(jQuery);

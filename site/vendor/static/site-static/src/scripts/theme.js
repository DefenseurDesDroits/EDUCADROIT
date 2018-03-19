'use strict';

(function($, window, document) {
    var Theme = {
        init: function() {
            this.sidebarToggle();
            this.inputChecked();
            this.quizSlide();
        },

        sidebarToggle: function() {
            var $sidebar = $('.sidebar');
            var $body = $('body');
            var $layer = $('.layer');

            if($sidebar.length === 0) { return; }

            $sidebar.on('shown.bs.collapse', function() {
                $body.addClass('sidebar-active');
            });

            $sidebar.on('hide.bs.collapse', function() {
                $body.removeClass('sidebar-active');
            });

            $layer.on('click', function() {
                $sidebar.collapse('hide');
            });
        },

        inputChecked: function() {
            var $form = $('.form-quiz');
            var $input = $form.find('input');

            if($form.length === 0 && $input.length === 0) { return; }

            $input.on('change', function() {
                var $this = $(this);

                $input.parent().removeClass('is-checked');

                if($this.parent().hasClass('is-checked')) {
                    $this.parent().removeClass('is-checked');
                } else {
                    $this.parent().addClass('is-checked');
                }
            });
        },

        quizSlide: function() {
            var $form = $('.form');
            var $quizFrame = $('.quiz-frame');
            var $quizBtn = $quizFrame.find('.btn');

            if($form.length === 0) { return; }

            $quizBtn.on('click', function() {
                var $this = $(this);

                if($this.data('form')) {
                    var $targetForm = $this.data('form');
                    var $inputChecked = $($targetForm).find('input:checked');

                    if ($inputChecked.length) {
                        var $target = $inputChecked.data('target');

                        $quizFrame.removeClass('active');
                        $($target).addClass('active animated fadeIn');
                    } else {
                        console.log('Choisir une réponse');
                    }
                } else if ($this.data('target')) {
                    $quizFrame.removeClass('active');
                    $($this.data('target')).addClass('active animated fadeIn');
                } else {
                    return;
                }
            });
        }
    };

    $(document).ready(function() {
        Theme.init();
    });
}(jQuery, window, document));

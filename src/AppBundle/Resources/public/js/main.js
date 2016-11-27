/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function ($) {

    // Use this variable to set up the common and page specific functions. If you
    // rename this variable, you will also need to rename the namespace below.
    var Newsletter = {
        // All pages
        'common': {
            init: function () {
                $('[data-toggle="tooltip"]').tooltip();
            },
            finalize: function () {
                // JavaScript to be fired on all pages, after page specific JS is fired
            }
        },
        // Home page
        'newsletter_edit': {
            init: function () {
                var loading = '<div class="text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>';

                var $postFromModal = $('#post-form-modal');

                $postFromModal.on('show.bs.modal', function (e) {

                    var $btn = $(e.relatedTarget);

                    var $modalContent = $postFromModal.find('.modal-content');

                    $modalContent.html(loading);

                    $modalContent.load($btn.attr("href"), function () {

                        $postFromModal.find('form').submit(function (e) {

                            var $form = $(this);

                            $.ajax({
                                type: "POST",
                                url: $form.attr('action'),
                                data: $form.serialize(),
                                success: function (data) {
                                    console.log(data);

                                    var $panel = $($btn.parents('.panel-body')[0]);

                                    switch ($form.attr('name')) {
                                        case 'edit_post':
                                            $panel.find('.post-title').html($form.find('#edit_post_title').val());
                                            break;
                                        case 'delete_post':
                                            $panel.parent().fadeOut(400, function () {
                                                $(this).remove();
                                            });
                                            break;
                                        case 'new_post':
                                            var $newPost = $($panel.find('ul').find('li')[0]).clone();
                                            $newPost.find('.post-title').html($form.find('#new_post_title').val());
                                            $newPost.hide().appendTo($panel.find('ul')).fadeIn();
                                            break;
                                    }

                                    $postFromModal.modal('hide');
                                }
                            });

                            e.preventDefault();
                        })
                    });
                }).on('shown.bs.modal', function () {
                    var simplemde = new SimpleMDE({
                        spellChecker: false,
                        forceSync: true,
                    });
                });

                $(".post-sortable").sortable({
                    cursor: "move",
                    update: function () {
                        var order_serialize = $(this).sortable("serialize", {key: "posts[]"});

                        $.ajax({
                            type: "POST",
                            url: "/app_dev.php/post/order",
                            data: order_serialize,
                            error: function () {
                                console.log('error');
                            }
                        });

                    }
                });
            },
            finalize: function () {
                // JavaScript to be fired on the home page, after the init JS
            }
        },
    };

    // The routing fires all common scripts, followed by the page specific scripts.
    // Add additional events for more control over timing e.g. a finalize event
    var UTIL = {
        fire: function (func, funcname, args) {
            var fire;
            var namespace = Newsletter;
            funcname = (funcname === undefined) ? 'init' : funcname;
            fire = func !== '';
            fire = fire && namespace[func];
            fire = fire && typeof namespace[func][funcname] === 'function';

            if (fire) {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function () {
            // Fire common init JS
            UTIL.fire('common');

            // Fire page-specific init JS, and then finalize JS
            $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function (i, classnm) {
                UTIL.fire(classnm);
                UTIL.fire(classnm, 'finalize');
            });

            // Fire common finalize JS
            UTIL.fire('common', 'finalize');
        }
    };

    // Load Events
    $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
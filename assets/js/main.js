/* News 1 Theme - Main JS */
(function($) {
    'use strict';

    /* ===== Mobile nav toggle ===== */
    $('#hamburger').on('click', function() {
        var $menu    = $('#primary-menu');
        var expanded = $(this).attr('aria-expanded') === 'true';
        $(this).attr('aria-expanded', !expanded);
        $menu.toggleClass('open');
    });

    /* ===== Mobile sub-menu toggle ===== */
    $('.nav-menu > li.menu-item-has-children > a').on('click', function(e) {
        if ($(window).width() <= 768) {
            e.preventDefault();
            $(this).parent().toggleClass('open');
        }
    });

    /* ===== Sticky nav shadow on scroll ===== */
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 60) {
            $('#site-nav').addClass('scrolled');
        } else {
            $('#site-nav').removeClass('scrolled');
        }
    });

    /* ===== Load more comments ===== */
    var commentsPerPage = 10;
    var $allComments    = $('.comment-list > .comment');
    var totalComments   = $allComments.length;

    if (totalComments > commentsPerPage) {
        $allComments.slice(commentsPerPage).hide().addClass('comment-hidden');
        $('#load-more-comments').show();
    }

    $('#load-more-comments').on('click', function() {
        var $hidden = $allComments.filter('.comment-hidden');
        $hidden.slice(0, commentsPerPage).removeClass('comment-hidden').fadeIn(300);

        if ($allComments.filter('.comment-hidden').length === 0) {
            $(this).hide();
        } else {
            $(this).text('Load More Comments (' + $allComments.filter('.comment-hidden').length + ' remaining)');
        }
    });

    /* ===== Lazy image load (native fallback) ===== */
    $('img').not('[loading]').attr('loading', 'lazy');

    /* ===== Copy link share button ===== */
    $(document).on('click', '.share-btn.copy', function() {
        var url = $(this).data('url') || window.location.href;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url);
        } else {
            var $tmp = $('<input>').val(url).appendTo('body').select();
            document.execCommand('copy');
            $tmp.remove();
        }
        var $btn = $(this);
        var orig = $btn.text();
        $btn.text('✓ Copied!');
        setTimeout(function() { $btn.text(orig); }, 2000);
    });

    /* ===== Close mobile nav when clicking outside ===== */
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#site-nav').length) {
            $('#primary-menu').removeClass('open');
            $('#hamburger').attr('aria-expanded', 'false');
        }
    });

})(jQuery);

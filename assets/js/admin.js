;(function ($) {
    const app = {
        init: () => {

            if (!aiovd.is_pro) {
                app.blockSettings();
            }

            $('.tab-info>span').on('click', app.handleSourceTab);
            $(document).on('click', '.aiovd_pro,.aiovd_disabled', app.showPromo);

            $(document).on('click', '.video-source:not(.aiovd_pro)', app.sourceSwitch);

            $(document).on('click', '.create-page-notice .notice_actions a', app.createPageNotice);
            $(document).on('click', '.view-page-notice a', app.viewPageNotice);

        },

        createPageNotice: function () {
            const notice_action = $(this).data('action');
            wp.ajax.send('aiovd_create_page', {
                data: {
                    notice_action,
                },

                success: (res) => {
                    console.log(res);

                    if (res.html) {
                        $('.aiovd_page_notice').html(res.html);
                    } else if (res.hide) {
                        $('.aiovd_page_notice').hide();
                    }
                },

                error: (error) => {
                    console.log('Error: ', error)
                }
            })
        },

        viewPageNotice: function (e) {
            e.preventDefault();

            const page_link = $('.view-page-notice').data('page_link');
            if (page_link) {
                window.open(page_link)
            }

            const notice_action = $(this).data('action');
            wp.ajax.send('aiovd_view_page', {
                data: {
                    notice_action,
                },

                success: (res) => {
                    console.log(res)
                },

                error: (error) => {
                    console.log('Error:', error)
                },
            })
        },

        sourceSwitch: function (e) {
            const input = $(this).find('input[type=checkbox]');
            input.prop('checked', !input.prop('checked'));
        },

        blockSettings: () => {
            $('.download_layout, .soundcloud_key, .facebook_cookie, .instagram_cookie').addClass('aiovd_disabled');
        },

        handleSourceTab: function () {
            $('.tab-info>span').removeClass('current');

            const $this = $(this);
            $this.addClass('current');

            $('.video-source').hide();

            if ($this.hasClass('active')) {
                $('.video-source.active').show();
            } else if ($this.hasClass('inactive')) {
                $('.video-source.inactive').show();

            } else {
                $('.video-source').show();

            }
        },

        showPromo: () => {
            $('.aiovd-promo').removeClass('hidden');
        },

    };

    $(document).ready(app.init);

})(jQuery);
(function ($) {
    const app = {
        init: () => {
            $(document).on('click', '#aiovd_submit', app.handleForm);

            $(document).on('paste', '#video_url', () => setTimeout(app.handleForm, 100));
            $(document).on('click', '.download-link', app.downloadLink);


        },

        downloadLink: function(){
            const link = $(this).data('link');
            window.open(link);
        },

        handleForm: function () {
            const $this = $('#aiovd_submit');

            if ($this.hasClass('active')) {
                return;
            }

            $('#aiovd-download').html('');

            $('.aiovd-form-error').removeClass('active');

            const video_url = $('#video_url').val();
            let error_msg = '';

            if ('' === video_url) {
                error_msg = 'Video link is empty';
            } else if (!/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(video_url)) {
                error_msg = 'Video link is invalid';
            }

            if (error_msg !== '') {
                $('.aiovd-form-error').addClass('active').text(error_msg);
                return;
            }

            $this.addClass('active');


            wp.ajax.send('aiovd_form', {
                data: {
                    video_url,
                },

                success: (data) => {
                    console.log(data);
                    $this.removeClass('active');

                    if(data.error){
                        $('.aiovd-form-error').addClass('active').text(data.error);
                    }

                    if(data.html) {
                        $('#aiovd-download').html(data.html);
                    }
                },

                error: (error) => {
                    $this.removeClass('active');
                    console.log(error);
                },

            });


        }

    };

    $(document).ready(app.init);

})(jQuery);
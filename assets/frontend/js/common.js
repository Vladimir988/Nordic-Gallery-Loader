(function ($) {
    $(document).ready(function () {

        $(document).ajaxComplete(function (event, xhr, settings) {
            if (settings.url === wpData.wooUrl) {
                $.ajax({
                    url: wpData.ajaxUrl,
                    data: {
                        action: 'get_variation_gallery_html'
                    },
                    type: 'POST',
                    success: function (response) {
                        if (response.length) {
                            $('div.woocommerce-product-gallery').replaceWith(response);

                            $('.slider-for').slick({
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                arrows: false,
                                fade: true,
                                asNavFor: '.slider-nav'
                            });

                            $('.slider-nav').slick({
                                slidesToShow: 4,
                                slidesToScroll: 1,
                                vertical: true,
                                asNavFor: '.slider-for',
                                dots: false,
                                arrows: false,
                                focusOnSelect: true,
                                verticalSwiping: true,
                                responsive: [
                                    {
                                        breakpoint: 992,
                                        settings: {
                                            vertical: false,
                                        }
                                    },
                                    {
                                        breakpoint: 768,
                                        settings: {
                                            vertical: false,
                                        }
                                    },
                                    {
                                        breakpoint: 580,
                                        settings: {
                                            vertical: false,
                                            slidesToShow: 3,
                                        }
                                    },
                                    {
                                        breakpoint: 380,
                                        settings: {
                                            vertical: false,
                                            slidesToShow: 2,
                                        }
                                    }
                                ]
                            });
                        }
                    }
                });
            }
        });

    });
})(jQuery);
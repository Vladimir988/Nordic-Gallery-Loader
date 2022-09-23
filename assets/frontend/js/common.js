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
                                infinite: false,
                                asNavFor: '.slider-nav'
                            });

                            $('.slider-nav').slick({
                                slidesToShow: 4,
                                slidesToScroll: 1,
                                asNavFor: '.slider-for',
                                dots: true,
                                arrows: false,
                                focusOnSelect: true,
                                infinite: false,
                                responsive: [
                                    {
                                        breakpoint: 992,
                                        settings: {
                                            infinite: true,
                                        }
                                    },
                                    {
                                        breakpoint: 768,
                                        settings: {
                                            infinite: true,
                                        }
                                    },
                                    {
                                        breakpoint: 580,
                                        settings: {
                                            infinite: true,
                                            slidesToShow: 3,
                                        }
                                    },
                                    {
                                        breakpoint: 380,
                                        settings: {
                                            infinite: true,
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
<?php

if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Used vars list
 *
 * @var array $galleryArray
 *
 */

?>

<div class="woocommerce-product-gallery">
    <div class="container">
        <div class="vehicle-detail-banner banner-content clearfix">
            <div class="banner-slider">
                <div class="slider slider-for">
                    <?php
                    foreach ($galleryArray as $item) {
                        echo sprintf(
                            '<div class="slider-banner-image"><img src="%s" alt="%s" class="wp-post-image"></div>',
                            $item['src'],
                            $item['title']
                        );
                    }
                    ?>
                </div>
                <div class="slider slider-nav thumb-image">
                    <?php
                    foreach ($galleryArray as $item) {
                        echo sprintf(
                            '<div class="thumbnail-image"><div class="thumbImg"><img src="%s" alt="%s"></div></div>',
                            $item['gallery_thumb_src'],
                            $item['alt']
                        );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

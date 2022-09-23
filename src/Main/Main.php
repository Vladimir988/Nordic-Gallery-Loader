<?php namespace NordicGLoader\Main;

use Premmerce\SDK\V2\FileManager\FileManager;
use WC_AJAX;

/**
 * Class Frontend
 *
 * @package NordicGLoader\Frontend
 */
class Main
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var string
     */
    private $textdomain;

    /**
     * @const plugin version
     */
    const VERSION = '1.0.0';

    const OPTION_META_KEY = 'option_variation_gallery_images';
    const AJAX_ACTION     = 'get_variation_gallery_html';

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->textdomain  = $fileManager->getPluginName();

        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_filter('woocommerce_available_variation', [$this, 'woocommerceVariations'], 90, 3);

        add_action('wp_ajax_' . self::AJAX_ACTION, [$this, 'getVariationGalleryHtml']);
        add_action('wp_ajax_nopriv_' . self::AJAX_ACTION, [$this, 'getVariationGalleryHtml']);
    }

    public function enqueueScripts()
    {
        wp_enqueue_script($this->textdomain, $this->fileManager->locateAsset('frontend/js/common.js'), ['jquery'], self::VERSION);
        wp_localize_script($this->textdomain, 'wpData', [
            'wooUrl'  => WC_AJAX::get_endpoint('get_variation'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
        ]);
        wp_enqueue_script($this->textdomain . '-slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery', 'jquery-migrate']);

        wp_enqueue_style($this->textdomain, $this->fileManager->locateAsset('frontend/css/style.css'), [], self::VERSION);
        wp_enqueue_style($this->textdomain . '-slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
        wp_enqueue_style($this->textdomain . '-slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
    }

    public function woocommerceVariations($availableVariation, $variationProductObject, $variation)
    {
        $variationId               = absint($variation->get_id());
        $hasVariationGalleryImages = (bool)get_post_meta($variationId, 'rtwpvg_images', true);
        if ($hasVariationGalleryImages) {
            $galleryImages = (array)get_post_meta($variationId, 'rtwpvg_images', true);
        } else {
            $galleryImages = $variationProductObject->get_gallery_image_ids();
        }

        array_unshift($galleryImages, absint(get_post_meta($variationId, '_thumbnail_id', true)));

        $gallery       = [];
        $galleryImages = array_values(array_unique($galleryImages));
        foreach ($galleryImages as $key => $variationGalleryImageId) {
            $gallery[$key] = $this->getGalleryImageOptions($variationGalleryImageId);
        }

        update_option(self::OPTION_META_KEY, $gallery);

        return $availableVariation;
    }

    public function getGalleryImageOptions($attachmentId, $productId = false)
    {
        $options = [
            'image_id'          => '',
            'title'             => '',
            'caption'           => '',
            'src'               => '',
            'alt'               => '',
        ];
        $attachment = get_post($attachmentId);

        if ($attachment) {
            $options['image_id'] = $attachmentId;
            $options['title']    = _wp_specialchars(get_post_field('post_title', $attachmentId), ENT_QUOTES, 'UTF-8', true);
            $options['caption']  = _wp_specialchars(get_post_field('post_excerpt', $attachmentId), ENT_QUOTES, 'UTF-8', true);
            $options['src']      = wp_get_attachment_url($attachmentId);

            $alt_text = [
                trim(wp_strip_all_tags(get_post_meta($attachmentId, '_wp_attachment_image_alt', true))),
                $options['caption'],
                wp_strip_all_tags($attachment->post_title),
            ];

            if ($productId) {
                $product = wc_get_product($productId);
                $alt_text[] = wp_strip_all_tags(get_the_title($product->get_id()));
            }

            $alt_text       = array_filter($alt_text);
            $options['alt'] = isset($alt_text[0]) ? $alt_text[0] : '';
        }

        return $options;
    }

    public function getVariationGalleryHtml()
    {
        $galleryArray = get_option(self::OPTION_META_KEY, []);

        if (! empty($galleryArray)) {
            $html = $this->fileManager->renderTemplate('frontend/woocommerce-product-gallery.php', [
                'galleryArray' => $galleryArray,
            ]);

            wp_send_json($html);
        }

        wp_send_json([]);
    }
}
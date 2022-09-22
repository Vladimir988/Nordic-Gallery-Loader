<?php namespace NordicGLoader;

use Premmerce\SDK\V2\FileManager\FileManager;
use NordicGLoader\Main\Main;

/**
 * Class NordicGLoaderPlugin
 *
 * @package NordicGLoader
 */
class NordicGLoaderPlugin
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * NordicGLoaderPlugin constructor.
     *
     * @param string $mainFile
     */
    public function __construct($mainFile)
    {
        $this->fileManager = new FileManager($mainFile);

        add_action('plugins_loaded', [$this, 'loadTextDomain']);
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        new Main($this->fileManager);
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('nordic-gallery-loader', false, $name . '/languages/');
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate() {}

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate() {}

    /**
     * Fired during plugin uninstall
     */
    public static function uninstall() {}
}
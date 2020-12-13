<?php
declare(strict_types=1);

namespace Yireo\Webp2\Plugin;

use Magento\Catalog\Block\Product\View\Gallery;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Yireo\Webp2\Browser\BrowserSupport;
use Yireo\Webp2\Config\Config;
use Yireo\Webp2\Image\Convertor;
use Yireo\Webp2\Image\File;
use Yireo\Webp2\Logger\Debugger;

/**
 * Class ReplaceGalleryImages
 *
 * @package Yireo\Webp2\Plugin
 */
class ReplaceGalleryImages
{
    /**
     * @var Convertor
     */
    private $convertor;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * @var BrowserSupport
     */
    private $browserSupport;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * ReplaceGalleryImages constructor.
     *
     * @param Convertor $convertor
     * @param File $file
     * @param Debugger $debugger
     * @param BrowserSupport $browserSupport
     * @param Config $config
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Convertor $convertor,
        File $file,
        Debugger $debugger,
        BrowserSupport $browserSupport,
        Config $config,
        CollectionFactory $collectionFactory
    ) {
        $this->convertor = $convertor;
        $this->file = $file;
        $this->debugger = $debugger;
        $this->browserSupport = $browserSupport;
        $this->config = $config;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Hook into the getGalleryImages() method to add WebP support
     *
     * @param Gallery $gallery
     * @param Collection $images
     *
     * @return Collection
     * @throws LocalizedException
     */
    public function afterGetGalleryImages(Gallery $gallery, $images)
    {
        if ($this->config->enabled() === false) {
            return $images;
        }

        if ($this->config->hasFullPageCacheEnabled($gallery->getLayout()) === true) {
            return $images;
        }

        if ($this->browserSupport->hasWebpSupport() === false) {
            return $images;
        }

        /** @var Collection $newImages */
        $newImages = $this->collectionFactory->create();

        foreach ($images as $image) {
            $newImages->addItem($this->convertImage($image));
        }

        return $newImages;
    }

    /**
     * @param DataObject $image
     *
     * @return DataObject
     */
    private function convertImage(DataObject $image): DataObject
    {
        $imageTypes = ['small_image_url', 'medium_image_url', 'large_image_url'];
        foreach ($imageTypes as $imageType) {
            $imageUrl = $image->getData($imageType);
            $webpUrl = $this->file->toWebp($imageUrl);

            try {
                $this->convertor->convert($imageUrl, $webpUrl);
            } catch (\Exception $e) {
                $this->debugger->debug($e->getMessage(), [$imageUrl, $webpUrl]);
                return $image;
            }

            $image->setData($imageType, $webpUrl);
        }

        $this->debugger->debug('Image', $image->getData());
        return $image;
    }
}

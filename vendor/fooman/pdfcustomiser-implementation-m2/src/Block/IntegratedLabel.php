<?php

namespace Fooman\PdfCustomiser\Block;

use Fooman\PdfCore\Helper\FileOps;
use Magento\Framework\Module\Dir;
use Magento\Framework\Filesystem\Io\File;

/**
 * Block to render custom labels
 *
 * @author     Kristof Ringleff
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IntegratedLabel extends \Fooman\PdfCore\Block\Pdf\Block
{
    // phpcs:ignore PSR2.Classes.PropertyDeclaration
    protected $_template = 'Fooman_PdfCustomiser::pdf/integrated-labels/label-left.phtml';

    /**
     * @var FileOps
     */
    private $fileOps;

    /**
     * @var File
     */
    private $file;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    private $moduleReader;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\Filesystem\Io\File $file,
        FileOps $fileOps
    ) {
        parent::__construct($context);
        $this->moduleReader = $moduleReader;
        $this->file = $file;
        $this->fileOps = $fileOps;
    }

    public function getThemedUrl($asset)
    {
        $params = [
            'area' => $this->getArea()
        ];
        return $this->getViewFileUrl($asset, $params);
    }

    public function getModuleImage($asset, $module = 'Fooman_PdfCustomiser')
    {
        $fullPath = $this->moduleReader->getModuleDir(Dir::MODULE_VIEW_DIR, $module) . $asset;

        if (!$this->fileOps->fileExists($fullPath)) {
            return '';
        }
        $content = $this->file->read($fullPath);
        return sprintf('<img src="@%s" />', base64_encode($content));
    }
}

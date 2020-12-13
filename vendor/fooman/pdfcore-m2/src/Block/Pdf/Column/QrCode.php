<?php
/**
 * @copyright Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fooman\PdfCore\Block\Pdf\Column;

class QrCode extends \Fooman\PdfCore\Block\Pdf\Column implements \Fooman\PdfCore\Block\Pdf\ColumnInterface
{
    const DEFAULT_WIDTH = 20;
    const DEFAULT_TITLE = 'QR Code';
    const COLUMN_TYPE = 'fooman_qrCode';
    const XML_PATH_QR_CODE_ATTRIBUTE = 'sales_pdf/all/qr_source';

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Fooman\PdfCore\Helper\ParamKey
     */
    protected $paramKeyHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Fooman\PdfCore\Helper\ParamKey $paramKeyHelper,
        array $data = []
    ) {
        $this->productRepository = $productRepository;
        $this->paramKeyHelper = $paramKeyHelper;
        parent::__construct($context, $data);
    }

    public function getGetter()
    {
        return [$this, 'getQrCode'];
    }

    public function getQrCode($row)
    {
        $qrAttribute = $this->getQrAttribute($row);
        $barcodeParams = [
            $this->escapeHtml($qrAttribute),
            'QRCODE,H',
            //the parameters below refer to x, y, width, and height of the barcode respectively
            '', '', '35', '13'
        ];
        return sprintf(
            '<table><tr><td height="13mm"><tcpdf method="write2DBarcode" %s /></td></tr></table>',
            $this->paramKeyHelper->getEncodedParams($barcodeParams)
        );
    }

    public function getQrAttribute($row)
    {
        $attribute = $this->_scopeConfig->getValue(
            self::XML_PATH_QR_CODE_ATTRIBUTE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        try {
            $product = $this->productRepository->getById($row->getProductId(), false, $row->getStoreId());
            $qrAttribute = $product->getData($attribute);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $qrAttribute = $row->getDataUsingMethod($attribute);
        }
        return $qrAttribute;
    }
}

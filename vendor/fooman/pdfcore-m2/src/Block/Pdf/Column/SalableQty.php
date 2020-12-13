<?php
/**
 * @copyright Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fooman\PdfCore\Block\Pdf\Column;

use Fooman\PdfCore\Model\Compatibility\GetSalableQuantityDataBySkuProxy;

class SalableQty extends \Fooman\PdfCore\Block\Pdf\Column implements \Fooman\PdfCore\Block\Pdf\ColumnInterface
{
    const DEFAULT_WIDTH = 16;
    const DEFAULT_TITLE = 'Salable Qty';
    const COLUMN_TYPE = 'fooman_textWithBreak';

    private $getProductSalableQty;

    private $moduleManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        GetSalableQuantityDataBySkuProxy $getProductSalableQty,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    )
    {
        $this->getProductSalableQty = $getProductSalableQty;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $data);
    }

    public function getGetter()
    {
        return [$this, 'getSalableQty'];
    }

    public function getSalableQty($row)
    {
        if (!$this->moduleManager->isEnabled('Magento_InventorySalesAdminUi')) {
            return '';
        }

        $orderItem = $this->getOrderItem($row);
        try {
            $formatted = [];
            $result = $this->getProductSalableQty->execute($orderItem->getSku());
            foreach ($result as $stock) {
                $formatted[] = '<b>' . $stock['stock_name'] . ':</b> ' . $stock['qty'];
            }
            return implode('<br/>', $formatted);
        } catch (\Exception $e) {
            return '';
        }
    }
}

<?php
/**
 * @copyright Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fooman\PdfCore\Model\Compatibility;

use Magento\Framework\ObjectManagerInterface;

/**
 * Using a custom proxy class here to cater for installations that remove MSI
 * via a composer replace hack
 * The concrete inventory class is hidden from the setup:di:compile command as it is only
 * used at runtime.
 */
class GetSalableQuantityDataBySkuProxy
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    private $subject;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    private function getSubject()
    {
        if (!$this->subject) {
            $this->subject = $this->objectManager->get(
                \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku::class
            );
        }
        return $this->subject;
    }

    public function execute(string $sku)
    {
        return $this->getSubject()->execute($sku);
    }
}

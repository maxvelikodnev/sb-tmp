<?php
namespace Fooman\PdfCustomiser\Controller\Adminhtml\Order;

use Magento\Framework\Data\Collection;
use Magento\Framework\Controller\ResultFactory;

/**
 * @author     Kristof Ringleff
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Pdfinvoices extends \Magento\Backend\App\Action
{
    /**
     * @var \Fooman\PdfCustomiser\Block\InvoiceFactory
     */
    private $invoiceDocumentFactory;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;

    /**
     * @var \Fooman\PdfCore\Model\PdfFileHandling
     */
    private $pdfFileHandling;

    /**
     * @var \Fooman\PdfCore\Model\PdfRenderer
     */
    private $pdfRenderer;

    /**
     * @var string
     */
    private $redirectUrl = 'sales/order/index';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context                        $context
     * @param \Magento\Ui\Component\MassAction\Filter                    $filter
     * @param \Fooman\PdfCore\Model\PdfFileHandling                      $pdfFileHandling
     * @param \Fooman\PdfCore\Model\PdfRenderer                          $pdfRenderer
     * @param \Fooman\PdfCustomiser\Block\InvoiceFactory                 $invoiceDocumentFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Fooman\PdfCore\Model\PdfFileHandling $pdfFileHandling,
        \Fooman\PdfCore\Model\PdfRenderer $pdfRenderer,
        \Fooman\PdfCustomiser\Block\InvoiceFactory $invoiceDocumentFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
    ) {
        $this->filter = $filter;
        $this->pdfFileHandling = $pdfFileHandling;
        $this->pdfRenderer = $pdfRenderer;
        $this->invoiceDocumentFactory = $invoiceDocumentFactory;
        $this->collectionFactory = $orderCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Print selected invoices
     *
     * @return \Magento\Framework\App\ResponseInterface | \Magento\Framework\Controller\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $this->processCollection($collection);

        if ($this->pdfRenderer->hasPrintContent()) {
            return $this->pdfFileHandling->sendPdfFile($this->pdfRenderer);
        }

        $this->messageManager->addErrorMessage(__('Nothing to print'));
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath($this->redirectUrl);
    }

    /**
     * @return bool
     */
    // phpcs:ignore PSR2.Methods.MethodDeclaration
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Sales::sales_invoice');
    }

    /**
     * Print selected invoices
     *
     * @param Collection $collection
     *
     * @return void
     */
    public function processCollection(Collection $collection)
    {
        foreach ($collection->getItems() as $order) {
            $invoices = $order->getInvoiceCollection();
            if ($invoices) {
                foreach ($invoices as $invoice) {
                    $document = $this->invoiceDocumentFactory->create(
                        ['data' => ['invoice' => $invoice]]
                    );

                    $this->pdfRenderer->addDocument($document);
                }
            }
        }
    }
}

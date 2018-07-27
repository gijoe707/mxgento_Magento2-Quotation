<?php
/**
 * Copyright © Magestore, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Quotation\Controller\Adminhtml\Quote;

class PrintPdf extends \Magestore\Quotation\Controller\Adminhtml\Quote\QuoteAbstract
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * PrintPdf constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magestore\Quotation\Helper\Data $helper
     * @param \Magestore\Quotation\Api\QuotationManagementInterface $quotationManagement
     * @param \Magestore\Quotation\Model\BackendCart $backendCart
     * @param \Magestore\Quotation\Model\BackendSession $backendSession
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper
     * @param \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magestore\Quotation\Helper\Data $helper,
        \Magestore\Quotation\Api\QuotationManagementInterface $quotationManagement,
        \Magestore\Quotation\Model\BackendCart $backendCart,
        \Magestore\Quotation\Model\BackendSession $backendSession,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper,
        \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        parent::__construct(
            $context,
            $helper,
            $quotationManagement,
            $backendCart,
            $backendSession,
            $registry,
            $productBuilder,
            $initializationHelper,
            $productTypeManager,
            $productRepository
        );
        $this->fileFactory = $fileFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $quoteId = $this->getRequest()->getParam('quote_id');
        if($quoteId){
            $quote = $this->quotationManagement->getQuoteRequest($quoteId);
            if($quote){
                if (class_exists('\Dompdf\Dompdf')) {
                    $html = $this->quotationManagement->getEmailHtml($quote);
                    $dompdf = new \Dompdf\Dompdf();
                    $dompdf->loadHtml($html);
                    $dompdf->set_option('isHtml5ParserEnabled', true);
                    $dompdf->set_option('isRemoteEnabled', true);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();
                    $dompdf->stream(__("Quotation_#%1.pdf", $quote->getRequestIncrementId())->__toString(), ['compress' => 0, 'Attachment' => 0]);
                }else{
                    $this->messageManager->addError(__("The DOMPDF library is mising, please install by the following command: composer require dompdf/dompdf"));
                }
            }
        }
    }
}

<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\Quotation\Controller\Adminhtml\Quote;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magestore\Quotation\Model\ResourceModel\Quote\CollectionFactory;
use Magento\Quote\Model\ResourceModel\Quote\Collection;

/**
 * Class AbstractMassAction
 * @package Magestore\Quotation\Controller\Adminhtml\Quote
 */
abstract class AbstractMassAction extends \Magento\Backend\App\Action
{
    /**
     * @var string
     */
    protected $redirectUrl = '*/*/index';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magestore\Quotation\Api\QuotationManagementInterface
     */
    protected $quotationManagement;

    /**
     * AbstractMassAction constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param \Magestore\Quotation\Api\QuotationManagementInterface $quotationManagement
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        \Magestore\Quotation\Api\QuotationManagementInterface $quotationManagement
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->quotationManagement = $quotationManagement;
    }


    /**
     * @return $this
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            return $this->massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath($this->redirectUrl);
        }
    }


    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_Quotation::quote_request');
    }


    /**
     * @return string
     */
    protected function getComponentRefererUrl()
    {
        return '*/*/index';
    }

    /**
     * @param Collection $collection
     * @return mixed
     */
    abstract protected function massAction(Collection $collection);
}

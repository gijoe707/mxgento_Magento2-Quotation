<?php
/**
 * Copyright © 2018 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\Quotation\Controller\Adminhtml\Quote;

use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Model\ResourceModel\Quote\Collection;
use Magestore\Quotation\Model\Source\Quote\Status as QuoteStatus;

/**
 * Class MassStatus
 * @package Magestore\Quotation\Controller\Adminhtml\Quote
 */
class MassStatus extends AbstractMassAction
{
    /**
     * @param AbstractCollection $collection
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    protected function massAction(Collection $collection)
    {
        $status = $this->getRequest()->getParam('status');
        $numberItemsChanged = 0;
        foreach ($collection as $quote) {
            if($status == QuoteStatus::STATUS_NEW){
                $this->quotationManagement->updateStatus($quote, $status, QuoteStatus::STATUS_PROCESSING);
            }else{
                $this->quotationManagement->updateStatus($quote, $status, $status);
            }
            $numberItemsChanged++;
        }

        if ($numberItemsChanged) {
            $this->messageManager->addSuccess(__('A total of %1 record(s) were updated.', $numberItemsChanged));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }

}

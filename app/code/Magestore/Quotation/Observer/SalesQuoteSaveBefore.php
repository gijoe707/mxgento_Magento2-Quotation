<?php
namespace Magestore\Quotation\Observer;
use Magento\Framework\Event\ObserverInterface;

class SalesQuoteSaveBefore implements ObserverInterface
{
    /**
     * @var \Magestore\Quotation\Api\QuotationManagementInterface
     */
    protected $quotationManagement;

    /**
     * SalesQuoteSaveBefore constructor.
     * @param \Magestore\Quotation\Api\QuotationManagementInterface $quotationManagement
     */
    public function __construct(
        \Magestore\Quotation\Api\QuotationManagementInterface $quotationManagement
    ) {
        $this->quotationManagement = $quotationManagement;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $quote \Magento\Quote\Model\Quote */
        $quote = $observer->getEvent()->getQuote();
        if (!$quote) {
            return;
        }
        $this->quotationManagement->setupIncrementId($quote);
        return $this;
    }
}
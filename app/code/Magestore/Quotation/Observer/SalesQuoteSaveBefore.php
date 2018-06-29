<?php
namespace Magestore\Quotation\Observer;
use Magento\Framework\Event\ObserverInterface;

class SalesQuoteSaveBefore implements ObserverInterface
{
    /**
     * @var \Magestore\Quotation\Api\IncrementManagementInterface
     */
    protected $incrementManagement;

    /**
     * SalesQuoteSaveBefore constructor.
     * @param \Magestore\Quotation\Api\IncrementManagementInterface $incrementManagement
     */
    public function __construct(
        \Magestore\Quotation\Api\IncrementManagementInterface $incrementManagement
    ) {
        $this->incrementManagement = $incrementManagement;
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
        $this->incrementManagement->setupIncrementId($quote);
        return $this;
    }
}
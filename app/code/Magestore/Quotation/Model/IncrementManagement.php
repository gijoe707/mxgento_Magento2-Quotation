<?php
/**
 * Copyright © Magestore, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Quotation\Model;

use Magestore\Quotation\Model\Source\Quote\Status as QuoteStatus;

/**
 * Class IncrementManagement
 * @package Magestore\Quotation\Model
 */
class IncrementManagement implements \Magestore\Quotation\Api\IncrementManagementInterface
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magestore\Quotation\Model\Quote\Increment $increment
     */
    protected $increment;

    /**
     * IncrementManagement constructor.
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $collectionFactory
     * @param Quote\Increment $increment
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $collectionFactory,
        \Magestore\Quotation\Model\Quote\Increment $increment
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->collectionFactory = $collectionFactory;
        $this->increment = $increment;
    }


    /**
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function setupIncrementId(\Magento\Quote\Api\Data\CartInterface $quote){
        $requestStatus = $quote->getRequestStatus();
        $requestIncrementId = $quote->getRequestIncrementId();
        if(
            !empty($requestStatus) &&
            (!in_array($requestStatus, [QuoteStatus::STATUS_NONE, QuoteStatus::STATUS_PENDING, QuoteStatus::STATUS_ADMIN_PENDING])) &&
            empty($requestIncrementId)
        ){
            $lastIncrementId = $this->getLastIncrementId();
            $this->increment->setLastId($lastIncrementId);
            $incrementId = $this->increment->getNextId();
            $quote->setRequestIncrementId($incrementId);
        }
        return $quote;
    }

    /**
     * @return int
     */
    public function getLastIncrementId(){
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('request_status', [
            'nin' => [QuoteStatus::STATUS_NONE, QuoteStatus::STATUS_PENDING, QuoteStatus::STATUS_ADMIN_PENDING]
        ]);
        $collection->addFieldToFilter('request_increment_id', [
            'neq' => 'NULL'
        ]);
        $collection->setOrder('request_increment_id', 'DESC');
        $lastQuoteRequest = $collection->getFirstItem();
        return ($lastQuoteRequest && $lastQuoteRequest->getId())?$lastQuoteRequest->getRequestIncrementId():0;
    }

    /**
     * @return $this
     */
    public function reGenerateIncrementId(){
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('request_status', [
            'nin' => [QuoteStatus::STATUS_NONE, QuoteStatus::STATUS_PENDING, QuoteStatus::STATUS_ADMIN_PENDING]
        ]);
        $collection->addFieldToFilter('request_increment_id', [
            'null' => true
        ]);
        foreach ($collection as $quote){
            $this->setupIncrementId($quote);
            $this->quoteRepository->save($quote);
        }
        return $this;
    }
}

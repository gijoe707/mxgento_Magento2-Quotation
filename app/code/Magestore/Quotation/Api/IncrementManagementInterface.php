<?php
/**
 * Copyright © Magestore, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Quotation\Api;

/**
 * Interface IncrementManagementInterface
 * @package Magestore\Quotation\Api
 */
interface IncrementManagementInterface
{
    /**
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function setupIncrementId(\Magento\Quote\Api\Data\CartInterface $quote);

    /**
     * @return int
     */
    public function getLastIncrementId();

    /**
     * @return $this
     */
    public function reGenerateIncrementId();
}

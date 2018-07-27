<?php
/**
 * Copyright © Magestore, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Quotation\Model\ResourceModel\Quote;

/**
 * Class Collection
 * @package Magestore\Quotation\Model\ResourceModel\Quote
 */
class Collection extends \Magento\Quote\Model\ResourceModel\Quote\Collection
{
    /**
     * Id field name getter
     *
     * @return string
     */
    public function getIdFieldName()
    {
        return $this->getResource()->getIdFieldName();
    }
}

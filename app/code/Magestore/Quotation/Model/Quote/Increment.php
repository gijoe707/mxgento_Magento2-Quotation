<?php
/**
 * Copyright © Magestore, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\Quotation\Model\Quote;

/**
 * Class Increment
 * @package Magestore\Quotation\Model\Quote
 */
class Increment extends \Magento\Eav\Model\Entity\Increment\Alphanum
{
    /**
     * Get allowed chars
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getAllowedChars()
    {
        return '0123456789';
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 28/09/2016
 * Time: 13:17
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class RowcounterViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{


    /**
     * @param int $idx
     * @param int $maxitems
     * @return string
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    public function render($idx, $maxitems)
    {
        return ceil($idx/$maxitems);
    }
}

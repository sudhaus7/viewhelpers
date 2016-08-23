<?php

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;

class CalculationViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * Returns the result of the mathematical operation determined by the string 'operator'
     *
     * @param float $firstnumber
     * @param float $secondnumber
     * @param string $operator
     * @return float
     *
     * @throws nothing
     *
     */
    public function render($firstnumber, $secondnumber, $operator) {

        switch ($operator) {

            case '+':
                $result = $firstnumber + $secondnumber;
                break;
            case '-':
                $result = $firstnumber - $secondnumber;
                break;
            case '*':
                $result = $firstnumber * $secondnumber;
                break;
            case '/':
                $result = $firstnumber / $secondnumber;
                break;
            case '%':
                $result = $firstnumber % $secondnumber;
                break;
            default:
                $result = 0.00;
        }

        return $result;
    }

}

<?php
namespace BFACTOR\BfactorViewhelpers\ViewHelpers;
class DummyViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * Return an array for creating dummy content
     *
     * @param int $actual actual
     * @param int $must must
     * @return array
     *
     * @throws nothing
     *
     */
    public function render($actual, $must) {
        $array = array();
        for ($i=$actual+1; $i < $must; $i++) {
            $array[] = $i;
        }
        return $array;
    }
}
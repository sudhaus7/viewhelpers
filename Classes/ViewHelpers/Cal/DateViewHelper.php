<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 02.10.17
 * Time: 14:17
 */

namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers\Cal;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @var array
     */
    protected $monthMapping;
    /**
     * @var array
     */
    protected $dayMapping;

    public function __construct()
    {
        $this->monthMapping = array(
            'en' => array(
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ),
            'de' => array(
                'Januar',
                'Februar',
                'März',
                'April',
                'Mai',
                'Juni',
                'Juli',
                'August',
                'September',
                'Oktober',
                'November',
                'Dezember'
            )
        );
        $this->dayMapping = array(
            'en' => array(
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday'
            ),
            'de' => array(
                'Montag',
                'Dienstag',
                'Mittwoch',
                'Donnerstag',
                'Freitag',
                'Samstag',
                'Sonntag'
            )
        );
    }
    public function initializeArguments()
    {
        $this->registerArgument('day', 'int', 'The day to convert', false, date('j'));
        $this->registerArgument('month', 'int', 'The month to convert', false, date('n'));
        $this->registerArgument('year', 'int', 'The year to convert', false, date('Y'));
        $this->registerArgument('format', 'string', 'The date() format', true);
        $this->registerArgument('language', 'string', 'language', false, 'de');
    }

    public function render()
    {
        $year = date('Y');
        $month = date('n');
        $day = date('j');
        $format = '';
        $language = 'en';
        foreach ($this->arguments as $key => $value) {
            $$key = $value;
        }
        $date = \DateTime::createFromFormat('Y-n-j', $year.'-'.$month.'-'.$day);
        $newFormat = $date->format($format);
        if ($language != 'en') {
            if (array_key_exists($language, $this->dayMapping)) {
                $newFormat = str_replace($this->dayMapping['en'], $this->dayMapping[$language], $newFormat);
            }
            if (array_key_exists($language, $this->monthMapping)) {
                $newFormat = str_replace($this->monthMapping['en'], $this->monthMapping[$language], $newFormat);
            }
        }
        return $newFormat;
    }
}

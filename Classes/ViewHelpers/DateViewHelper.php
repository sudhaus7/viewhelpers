<?php
namespace SUDHAUS7\Sudhaus7Viewhelpers\ViewHelpers;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     * @var array
     */
    protected $monthMapping;
    /**
     * @var array
     */
    protected $dayMapping;

    public function __construct() {
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

    /**
     * Return an array for creating dummy content
     *
     * @param mixed $date date to convert
     * @param string $format format to map for
     * @param string $language
     * @param int $respectlocale Use Locale Setting
     * @param string $length short,medium,long,full. Example for September: short=09,medium=Sep,long=September,full=September
     * @param string $tz timezone
     * @return string
     *
     * @throws nothing
     *
     */
    public function render($date, $format, $language='de', $respectlocale=0, $length='long',$tz='Europe/Berlin') {

        if (is_object($date)) {
            $dateTime = $date;
        } else if ($date == 'now') {
            $dateTime = new \DateTime('now');
        } else {
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($date);
        }
        if ($format == 'SOLR' || $format == 'XML') {
            $dateTime->setTimezone(new \DateTimeZone('Europe/London'));
            return $dateTime->format('Y-m-d\TH:i:s\Z');
        }
        if (defined('\DateTime::'.$format)) {

            $format = constant('\DateTime::'.$format);
            return $dateTime->format($format);
        }
        if ($respectlocale && class_exists('IntlDateFormatter')) {

            return $this->handleLocale($dateTime,$format,$length,$tz);
        }

        $new = $dateTime->format($format);
        if($language !== 'en') {
            $new = str_replace($this->monthMapping['en'], $this->monthMapping['de'], $new);
            $new = str_replace($this->dayMapping['en'], $this->dayMapping['de'], $new);
        }
        return $new;
        //IntlDateFormatter()
    }
    private function handleLocale($date,$format,$length,$tz) {
        $locale = setlocale(LC_ALL, 0);
        $locale = 'de_DE.utf8';


        if ($format == 'LOCALETIME') {
            if (class_exists('\MessageFormatter')) {
                return $this->messageformater($locale,$date,'time',$length);
            }
            $format = 'HH:ii';
        }
        if ($format == 'LOCALEDATE') {
            if (class_exists('\MessageFormatter')) {
                return $this->messageformater($locale,$date,'date',$length);
            }
            $format = 'dd. MMM yyyy';

        }
        if ($format == 'LOCALEDATETIME') {
            if (class_exists('\MessageFormatter')) {
                return $this->messageformater($locale,$date,'date',$length).' '.$this->messageformater($locale,$date,'time',$length);
            }
            $format = 'dd. MMM yyyy HH:ii';
        }

        $type = constant('\IntlDateFormatter::'.strtoupper($length));
        $formatter = new \IntlDateFormatter($locale,$type,$type,$tz,\IntlDateFormatter::GREGORIAN,$format);
        return $formatter->format($date);

    }
    private function messageformater ($locale,$date,$what,$length) {
        if (PHP_VERSION_ID < 50500) { // PHP < 5.5 needs conversion to timestamp
            return \MessageFormatter::formatMessage($locale, '{0, '.$what.', '.$length.'}', array($date->getTimestamp()));
        } else {
            // current code
            return \MessageFormatter::formatMessage($locale, '{0, '.$what.', '.$length.'}', array($date));
        }
    }
}

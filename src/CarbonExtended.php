<?php

namespace Lee;

use Carbon\Carbon;
use InvalidArgumentException;

class CarbonExtended extends Carbon
{
    protected $firstSasDateValues = [-67019, '1776-07-04'];

    protected $quarterRomanFormats = ['I', 'II', 'III', 'IV'];

    protected $timeAmPmToCarbonFormats = [
        'TIMEAMPM3.' => 'A',
        'TIMEAMPM5.' => 'h A',
        'TIMEAMPM7.' => 'h:i A',
        'TIMEAMPM11.' => 'h:i:s A',
    ];

    protected $quarterFormat = 'QTR.';

    protected $quarterRomanFormat = 'QTRR.';

    protected $julianDayFormat = 'JULDAY3.';

    protected $sasDateValueFormat = 'SAS_DATE_VALUE';

    protected $julianDateOnTwoDigitYearFormat = 'JULIAN5.';

    protected $julianDateOnFourDigitYearFormat = 'JULIAN7.';

    protected $packedJulianDateFormat = 'PDJULG4.';

    /**
     * Formatting quarter with customized QTR.format
     *
     * @param string $extendedFormat
     * @param string $quarterFormat
     *
     * @return string
     */
    public function formatQuarters(string $extendedFormat, string $quarterFormat)
    {
        $quarterValue = $this->quarter;

        return str_replace($quarterFormat, $quarterValue, $extendedFormat);
    }

    /**
     * Formatting Roman quarter with customized QTR.format
     *
     * @param string $extendedFormat
     * @param string $quarterRomanFormat
     *
     * @return string
     */
    public function formatRomanQuarters(string $extendedFormat, string $quarterRomanFormat)
    {
        $quarterValue = $this->quarter;
        $quarterRomanValue = $this->quarterRomanFormats[$quarterValue-1];

        return str_replace($quarterRomanFormat, $quarterRomanValue, $extendedFormat);
    }

    /**
     * Formatting julian day with customized JULDAY3. format
     *
     * @param string $extendedFormat
     * @param string $julianDayFormat
     *
     * @return string
     */
    public function formatJulianDay(string $extendedFormat, string $julianDayFormat)
    {
        $year = (string) $this->year;
        $month = (string) $this->month;
        $day = (string) $this->day;

        $firstDate = $year . '-01-01';
        $currentDate = $year . '-' . $month . '-' . $day;
        $currentDateCarbon = static::parse($currentDate);

        $firstDateCarbon = static::parse($firstDate);
        $julianDayOfCalendar = $firstDateCarbon->diffInDays($currentDateCarbon);
        $julianDayOfCalendar += 1;

        return str_replace($julianDayFormat, $julianDayOfCalendar, $extendedFormat);
    }

    /**
     * Formatting julian date with customized JULIAN5. or JULIAN7. format
     *
     * @param string $extendedFormat
     * @param string $julianDayFormat
     *
     * @return string
     */
    public function formatJulianDate(string $extendedFormat, string $julianDayFormat)
    {
        $year = (string) $this->year;
        $month = (string) $this->month;
        $day = (string) $this->day;

        $firstDate = $year . '-01-01';
        $currentDate = $year . '-' . $month . '-' . $day;
        $currentDateCarbon = static::parse($currentDate);

        $firstDateCarbon = static::parse($firstDate);
        $julianDateOfYear = $firstDateCarbon->diffInDays($currentDateCarbon);
        $julianDateOfYear += 1;

        $yearFormat = substr($julianDayFormat, -2);

        if ($yearFormat === '5.') {
            $year = (string) $year;
            $year = substr($year, -2);
        }

        if ($yearFormat === '7.') {
            $year = (string) $year;
        }

        $julianDateOfYear = $year . (string) $julianDateOfYear;

        return str_replace($julianDayFormat, $julianDateOfYear, $extendedFormat);
    }

    /**
     * Formatting packed julian date with customized PDJULG4. format
     *
     * @param string $extendedFormat
     * @param string $julianDayFormat
     *
     * @return string
     */
    public function formatPackedJulianDate(string $extendedFormat, string $packedJulianDateFormat)
    {
        $year = (string) $this->year;
        $month = (string) $this->month;
        $day = (string) $this->day;

        $firstDate = $year . '-01-01';
        $currentDate = $year . '-' . $month . '-' . $day;
        $currentDateCarbon = static::parse($currentDate);

        $firstDateCarbon = static::parse($firstDate);
        $julianDateOfYear = $firstDateCarbon->diffInDays($currentDateCarbon);
        $julianDateOfYear += 1;
        $julianDateOfYear = (string) $julianDateOfYear;

        if (strlen($julianDateOfYear) === 2) {
            $julianDateOfYear = '0' . $julianDateOfYear;
        }

        $julianDateOfYear = $year . (string) $julianDateOfYear . 'F';

        return str_replace($packedJulianDateFormat, $julianDateOfYear, $extendedFormat);
    }

    /**
     * Formatting SAS date value with customized SAS_DATE_VALUE format
     */
    public function formatSasDateValue(string $extendedFormat, string $sasDateValueFormat)
    {
        $year = (string) $this->year;
        $month = (string) $this->month;
        $day = (string) $this->day;

        $currentDate = $year . '-' . $month . '-' . $day;
        $currentDateCarbon = static::parse($currentDate);

        $firstSasDate = $this->firstSasDateValues[1];
        $firstSasDateCarbon = static::parse($firstSasDate);
        $isSasDateValue = $firstSasDateCarbon->gt($currentDateCarbon);
        if ($isSasDateValue === true) {
            $exceptionMessage = 'Cannot format SAS date value. Given date should be lower than %s';
            $exceptionMessage = sprintf($exceptionMessage, $this->firstSasDateValues[1]);

            throw new InvalidArgumentException($exceptionMessage);
        }

        $diffInDays = $firstSasDateCarbon->diffInDays($currentDateCarbon);
        $sasDateValue = $this->firstSasDateValues[0] + $diffInDays;

        return str_replace($sasDateValueFormat, $sasDateValue, $extendedFormat);
    }

    /**
     * Format time with AM and PM
     *
     * @param string $extendedFormat
     * @param string $timeAmPmFormat
     * @param string $carbonFormat
     *
     * @return string
     */
    public function formatTimeAmPm(string $extendedFormat, string $timeAmPmFormat, string $carbonFormat)
    {
        $timeAmPmValue = $this->format($carbonFormat);

        if ($timeAmPmValue[0] === '0') {
            $timeAmPmValue = substr($timeAmPmValue, 1);
        }

        return str_replace($timeAmPmFormat, $timeAmPmValue, $extendedFormat);
    }

    /**
     * using extended or normal format with given format string
     *
     * @param string $extendedFormat
     *
     * @return string
     */
    public function extendedFormat(string $extendedFormat)
    {
        $formattedResult = '';

        if (stristr($extendedFormat, $this->quarterFormat) !== false) {
            $formattedResult = $this->formatQuarters($extendedFormat, $this->quarterFormat);
        }

        if (stristr($extendedFormat, $this->quarterRomanFormat) !== false) {
            $formattedResult = $this->formatRomanQuarters($extendedFormat, $this->quarterRomanFormat);
        }

        if (stristr($extendedFormat, $this->julianDayFormat) !== false) {
            $formattedResult = $this->formatJulianDay($extendedFormat, $this->julianDayFormat);
        }

        if (stristr($extendedFormat, $this->sasDateValueFormat) !== false) {
            $formattedResult = $this->formatSasDateValue($extendedFormat, $this->sasDateValueFormat);
        }

        if (stristr($extendedFormat, $this->julianDateOnTwoDigitYearFormat) !== false) {
            $formattedResult = $this->formatJulianDate($extendedFormat, $this->julianDateOnTwoDigitYearFormat);
        }

        if (stristr($extendedFormat, $this->julianDateOnFourDigitYearFormat) !== false) {
            $formattedResult = $this->formatJulianDate($extendedFormat, $this->julianDateOnFourDigitYearFormat);
        }

        if (stristr($extendedFormat, $this->packedJulianDateFormat) !== false) {
            $formattedResult = $this->formatPackedJulianDate($extendedFormat, $this->packedJulianDateFormat);
        }

        foreach ($this->timeAmPmToCarbonFormats as $timeAmPmFormat => $carbonFormat) {
            if (stristr($extendedFormat, $timeAmPmFormat) !== false) {
                $formattedResult = $this->formatTimeAmPm($extendedFormat, $timeAmPmFormat, $carbonFormat);
                break;
            }
        }

        return $formattedResult;
    }
}

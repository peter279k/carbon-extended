<?php

namespace Lee;

use Carbon\Carbon;
use InvalidArgumentException;

class CarbonExtended extends Carbon
{
    protected $firstSasDateValues = [-67019, '1776-07-04'];

    protected $quarterRomanFormats = ['I', 'II', 'III', 'IV'];

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
     * using extended or normal format with given format string
     *
     * @param string $extendedFormat
     *
     * @return string
     */
    public function extendedFormat(string $extendedFormat)
    {
        $quarterFormat = 'QTR.';
        $quarterRomanFormat = 'QTRR.';
        $julianDayFormat = 'JULDAY3.';
        $sasDateValueFormat = 'SAS_DATE_VALUE';
        $julianDateOnTwoDigitYearFormat = 'JULIAN5.';
        $julianDateOnFourDigitYearFormat = 'JULIAN7.';
        $packedJulianDateFormat = 'PDJULG4.';

        if (stristr($extendedFormat, $quarterFormat) !== false) {
            $extendedFormat = $this->formatQuarters($extendedFormat, $quarterFormat);
        }

        if (stristr($extendedFormat, $quarterRomanFormat) !== false) {
            $extendedFormat = $this->formatRomanQuarters($extendedFormat, $quarterRomanFormat);
        }

        if (stristr($extendedFormat, $julianDayFormat) !== false) {
            $extendedFormat = $this->formatJulianDay($extendedFormat, $julianDayFormat);
        }

        if (stristr($extendedFormat, $sasDateValueFormat) !== false) {
            $extendedFormat = $this->formatSasDateValue($extendedFormat, $sasDateValueFormat);
        }

        if (stristr($extendedFormat, $julianDateOnTwoDigitYearFormat) !== false) {
            $extendedFormat = $this->formatJulianDate($extendedFormat, $julianDateOnTwoDigitYearFormat);
        }

        if (stristr($extendedFormat, $julianDateOnFourDigitYearFormat) !== false) {
            $extendedFormat = $this->formatJulianDate($extendedFormat, $julianDateOnFourDigitYearFormat);
        }

        if (stristr($extendedFormat, $packedJulianDateFormat) !== false) {
            $extendedFormat = $this->formatPackedJulianDate($extendedFormat, $packedJulianDateFormat);
        }

        return $extendedFormat;
    }
}

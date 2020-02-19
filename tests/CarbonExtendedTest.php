<?php

namespace Lee\Tests;

use InvalidArgumentException;
use Lee\CarbonExtended;
use PHPUnit\Framework\TestCase;

class CarbonExtendedTest extends TestCase
{
    /**
     * Test should throw InvalidArgumentException on invalid SAS date
     *
     * @return void
     */
    public function testShouldThrowInvalidArgumentExceptionOnOutOfRangeSasDateValue()
    {
        $invalidSasDate = '1775-09-08';
        $carbonExtended = CarbonExtended::createFromFormat('Y-m-d', $invalidSasDate);

        $this->expectException(InvalidArgumentException::class);

        $carbonExtended->extendedFormat('SAS_DATE_VALUE');
    }

    /**
     * Formatting date with customized format test
     *
     * @dataProvider formatDataProvider
     *
     * @param string $date
     * @param string $customizedFormat
     * @param string $expected
     *
     * @return void
     */
    public function testFormat(string $date, string $customizedFormat, string $expected)
    {
        $carbonExtended = CarbonExtended::createFromFormat('Y-m-d', $date);
        $result = $carbonExtended->extendedFormat($customizedFormat);

        $this->assertSame($expected, $result);
    }

    /**
     * The extended formats data provider
     *
     * @return array
     */
    public function formatDataProvider()
    {
        return [
            ['2013-03-17', 'QTR.', '1'],
            ['2013-03-17', 'JULDAY3.', '76'],
            ['2013-03-17', 'SAS_DATE_VALUE', '19434'],
            ['2012-05-01', 'JULIAN5.', '12122'],
            ['2012-05-01', 'JULIAN7.', '2012122'],
            ['2013-03-17', 'PDJULG4.', '2013076F'],
        ];
    }
}

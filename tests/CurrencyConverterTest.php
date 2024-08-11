<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\CurrencyConverter;
use App\Exceptions\TransactionProcessError;

class CurrencyConverterTest extends TestCase
{
    public function testConvertToEuReturnsCorrectAmount()
    {
        $rates = ['USD' => 1.2, 'JPY' => 130.0, 'EUR' => 1.0];

        $converterMock = $this->getMockBuilder(CurrencyConverter::class)
            ->onlyMethods(['formatRates'])
            ->getMock();

        $converterMock->expects($this->once())
            ->method('formatRates')
            ->willReturn($rates);

        $this->assertEquals(100.0 / 1.2, $converterMock->convertToEu(100.0, 'USD'));
        $this->assertEquals(100.0, $converterMock->convertToEu(100.0, 'EUR'));
    }

    public function testConvertToEuThrowsExceptionOnMissingCurrency()
    {
        $this->expectException(TransactionProcessError::class);

        $converterMock = $this->getMockBuilder(CurrencyConverter::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRates'])
            ->getMock();

        $converterMock->setRates(['USD' => 1.2]);

        $converterMock->convertToEu(100.0, 'JPY');
    }

    public function testGetRatesThrowsExceptionOnApiError()
    {
        $this->expectException(TransactionProcessError::class);

        $converterMock = $this->getMockBuilder(CurrencyConverter::class)
            ->onlyMethods(['getRates'])
            ->getMock();

        $converterMock->expects($this->once())
            ->method('getRates')
            ->willThrowException(new TransactionProcessError('Cannot get currency rates'));

        $converterMock->convertToEu(100.0, 'USD');
    }
}

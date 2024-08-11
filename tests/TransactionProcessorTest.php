<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\TransactionProcessor;
use App\CurrencyConverter;
use App\BinChecker;

class TransactionProcessorTest extends TestCase
{
    public function testGetCommissionsHandlesValidTransaction()
    {
        $converterMock = $this->getMockBuilder(CurrencyConverter::class)
            ->onlyMethods(['convertToEu'])
            ->getMock();

        $binCheckerMock = $this->getMockBuilder(BinChecker::class)
            ->onlyMethods(['isEu'])
            ->getMock();

        $converterMock->expects($this->any())
            ->method('convertToEu')
            ->willReturn(100.0);

        $binCheckerMock->expects($this->any())
            ->method('isEu')
            ->willReturn(true);

        $processor = new TransactionProcessor($converterMock, $binCheckerMock);

        $this->expectOutputString("1.00\n");
        $processor->getCommissions(__DIR__ . '/test_input.txt');
    }

    public function testGetCommissionsHandlesNonEuTransaction()
    {
        $converterMock = $this->getMockBuilder(CurrencyConverter::class)
            ->onlyMethods(['convertToEu'])
            ->getMock();

        $binCheckerMock = $this->getMockBuilder(BinChecker::class)
            ->onlyMethods(['isEu'])
            ->getMock();

        $converterMock->expects($this->any())
            ->method('convertToEu')
            ->willReturn(100.0);

        $binCheckerMock->expects($this->any())
            ->method('isEu')
            ->willReturn(false);

        $processor = new TransactionProcessor($converterMock, $binCheckerMock);

        $this->expectOutputString("2.00\n");
        $processor->getCommissions(__DIR__ . '/test_input.txt');
    }

    public function testGetCommissionsThrowsExceptionOnMissingField()
    {
        $converterMock = $this->getMockBuilder(CurrencyConverter::class)
            ->onlyMethods(['convertToEu'])
            ->getMock();

        $binCheckerMock = $this->getMockBuilder(BinChecker::class)
            ->onlyMethods(['isEu'])
            ->getMock();

        $processor = new TransactionProcessor($converterMock, $binCheckerMock);

        $this->expectOutputString("Transaction required fields missing for row {\"bin\":\"45717360\",\"amount\":\"100.00\"}\n");
        $processor->getCommissions(__DIR__ . '/test_invalid_input.txt');
    }
}

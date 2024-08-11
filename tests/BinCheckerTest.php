<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\BinChecker;
use App\Exceptions\TransactionProcessError;

class BinCheckerTest extends TestCase
{
    public function testIsEuReturnsTrueForEuCountry()
    {
        $binCheckerMock = $this->getMockBuilder(BinChecker::class)
            ->onlyMethods(['getLookup'])
            ->getMock();

        $binCheckerMock->expects($this->once())
            ->method('getLookup')
            ->willReturn(json_encode(['country' => ['alpha2' => 'DE']]));

        $this->assertTrue($binCheckerMock->isEu(45717360));
    }

    public function testIsEuReturnsFalseForNonEuCountry()
    {
        $binCheckerMock = $this->getMockBuilder(BinChecker::class)
            ->onlyMethods(['getLookup'])
            ->getMock();

        $binCheckerMock->expects($this->once())
            ->method('getLookup')
            ->willReturn(json_encode(['country' => ['alpha2' => 'US']]));

        $this->assertFalse($binCheckerMock->isEu(516793));
    }

    public function testGetLookupThrowsExceptionOnError()
    {
        $this->expectException(TransactionProcessError::class);

        $binCheckerMock = $this->getMockBuilder(BinChecker::class)
            ->onlyMethods(['getLookup'])
            ->getMock();

        $binCheckerMock->expects($this->once())
            ->method('getLookup')
            ->willThrowException(new TransactionProcessError('Can not find lookup for BIN: 123456'));

        $binCheckerMock->isEu(123456);
    }
}

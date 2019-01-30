<?php

/*
 * Phake - Mocking Framework
 *
 * Copyright (c) 2010, Mike Lively <mike.lively@sellingsource.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  *  Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *  *  Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *  *  Neither the name of Mike Lively nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Testing
 * @package    Phake
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2010 Mike Lively <m@digitalsandwich.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.digitalsandwich.com/
 */

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Assert;
use PHPUnit\Runner\Version;

class Phake_Client_PHPUnitTest extends TestCase
{
    private $client;

    public function setUp()
    {
        if (version_compare(Version::id(), '6.0.0') >= 0) {
            $this->markTestSkipped('The tested class is not compatible with current version of PHPUnit.');
        }

        $this->client = new Phake_Client_PHPUnit();
    }

    public function testImplementsIClient()
    {
        $this->assertInstanceOf('Phake_Client_IClient', $this->client);
    }

    public function testProcessVerifierResultReturnsCallsOnTrue()
    {
        if (version_compare('6.0.0', Version::id()) != 1) {
            $this->markTestSkipped('The tested class is not compatible with current version of PHPUnit.');
        }
        $result = new Phake_CallRecorder_VerifierResult(true, array('call1'));

        $this->assertEquals(array('call1'), $this->client->processVerifierResult($result));
    }

    public function testProcessVerifierThrowsExceptionOnFalse()
    {
        $result = new Phake_CallRecorder_VerifierResult(false, array(), 'failure message');

        $this->expectException(ExpectationFailedException::class, 'failure message');
        $this->client->processVerifierResult($result);
    }

    public function testProcessVerifierIncrementsAssertionCount()
    {
        $result = new Phake_CallRecorder_VerifierResult(true, array('call1'));

        $assertionCount = Assert::getCount();
        $this->client->processVerifierResult($result);
        $newAssertionCount = Assert::getCount();

        $this->assertGreaterThan($assertionCount, $newAssertionCount);
    }

    /**
     * Utilizes a dummy constraint to indicate that an assertion has happened.
     */
    public function testProcessObjectFreeze()
    {
        $assertionCount = Assert::getCount();
        $this->client->processObjectFreeze();
        $newAssertionCount = Assert::getCount();

        $this->assertGreaterThan($assertionCount, $newAssertionCount);
    }
}


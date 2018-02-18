<?php

namespace  PhpUnit\Application;

use Application\UuidHelper;
use Codeception\Test\Unit;

class UuidHelperTest extends Unit
{
    /**
     * @dataProvider getV4Data
     */
    public function testIsV4($uuid, $assert)
    {
        $checkUuid = UuidHelper::isV4($uuid);

        $this->assertEquals($checkUuid, $assert);
        $this->assertInternalType('bool', $checkUuid);
    }

    public function getV4Data()
    {
        return [
            ['d290f1ee-6c54-4b01-90e6-d701748f0851', true],
            ['10', false],
            ['abcded', false]
        ];
    }

    public function testGenerateV4()
    {
        $uuid = UuidHelper::v4();

        $this->assertEquals(
            (bool)preg_match('/[a-f0-9]{8}\-[a-f0-9]{4}\-4[a-f0-9]{3}\-(8|9|a|b)[a-f0-9]{3}\-[a-f0-9]{12}/', $uuid),
            true
        );
        $this->assertInternalType('string', $uuid);
    }

    public function testGenerateCode8()
    {
        $uuid = UuidHelper::code8();

        $this->assertEquals(
            (bool)preg_match('/[a-f0-9]{8}/', $uuid),
            true
        );
        $this->assertInternalType('string', $uuid);
    }

    public function testGetRemoteAddress() {
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '127.0.0.1';
        $urlRemoteAddress = UuidHelper::getRemoteAddress();
        $this->assertInternalType('string', $urlRemoteAddress);
        $this->assertEquals('127.0.0.1', $urlRemoteAddress);
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);

        $_SERVER['REMOTE_IP'] = '127.0.0.1';
        $urlRemoteAddress = UuidHelper::getRemoteAddress();
        $this->assertInternalType('string', $urlRemoteAddress);
        $this->assertEquals('127.0.0.1', $urlRemoteAddress);
        unset($_SERVER['REMOTE_IP']);

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $urlRemoteAddress = UuidHelper::getRemoteAddress();
        $this->assertInternalType('string', $urlRemoteAddress);
        $this->assertEquals('127.0.0.1', $urlRemoteAddress);
        unset($_SERVER['REMOTE_ADDR']);

        $urlRemoteAddress = UuidHelper::getRemoteAddress();
        $this->assertInternalType('string', $urlRemoteAddress);
        $this->assertEquals('', $urlRemoteAddress);
    }
}

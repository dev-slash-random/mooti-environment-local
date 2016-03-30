<?php

namespace Mooti\Test\Unit\System\Base;

use Mooti\System\Base\Util\Git;

class GitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getVersionSucceeds()
    {
        $git = new Git;
        self::assertContains('git', $git->getVersion());
    }
}

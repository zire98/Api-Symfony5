<?php

namespace App\Tests\Service;

use App\Service\FileUploader;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class FileUploaderUnitTest extends TestCase
{
    public function testSuccessUploadBase64File()
    {

        $base64Image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAADElEQVR4nGP4z8AAAAMBAQDJ/pLvAAAAAElFTkSuQmCC';

        $data = explode(',', $base64Image);
        /**
         * @var FilesystemOperator&MockObject
         */
        $filesystem = $this->getMockBuilder(FilesystemOperator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filesystem
            ->expects(self::exactly(1))
            ->method('write')
            ->with($this->isType('string'), base64_decode($data[1]));

        $fileUploader = new FileUploader($filesystem);
        $filename = $fileUploader->uploadBase64File($base64Image);
        $this->assertNotEmpty($filename);
        $this->assertStringContainsString('.', $filename);
    }
}

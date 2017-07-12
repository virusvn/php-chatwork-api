<?php

namespace Polidog\Chatwork\Api\Rooms;

use Polidog\Chatwork\ClientInterface;
use Polidog\Chatwork\Entity\Collection\EntityCollection;
use Polidog\Chatwork\Entity\Factory\FileFactory;
use Polidog\Chatwork\Entity\Factory\RoomFactory;
use Polidog\Chatwork\Entity\File;

class FilesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerFiles
     */
    public function testShow($apiResult)
    {
        $roomId = 1;
        $client = $this->prophesize(ClientInterface::class);
        $client->request("GET","rooms/{$roomId}/files",[
            'query' => []
        ])->willReturn($apiResult);

        $factory = new FileFactory();
        $api = new Files($roomId, $client->reveal(), $factory);
        $files = $api->show();
        $this->assertInstanceOf(EntityCollection::class, $files);
        foreach ($files as $file) {
            $this->assertInstanceOf(File::class, $file);
        }
    }

    /**
     * @dataProvider providerFile
     */
    public function testDetail($apiResult)
    {
        $fileId = 1;
        $roomId = 1;
        $client = $this->prophesize(ClientInterface::class);
        $client->request("GET","rooms/{$roomId}/files/{$fileId}")->willReturn($apiResult);

        $factory = new FileFactory();
        $api = new Files($roomId, $client->reveal(), $factory);
        $file = $api->detail($fileId);
        $this->assertInstanceOf(File::class, $file);
    }

    public function providerFiles()
    {
        $data = json_decode('[
  {
    "file_id": 3,
    "account": {
      "account_id": 123,
      "name": "Bob",
      "avatar_image_url": "https://example.com/ico_avatar.png"
    },
    "message_id": "22",
    "filename": "README.md",
    "filesize": 2232,
    "upload_time": 1384414750
  }
]', true);
        return [
            [$data]
        ];
    }

    public function providerFile()
    {
        $data = json_decode('{
  "file_id":3,
  "account": {
    "account_id":123,
    "name":"Bob",
    "avatar_image_url": "https://example.com/ico_avatar.png"
  },
  "message_id": "22",
  "filename": "README.md",
  "filesize": 2232,
  "upload_time": 1384414750
}', true);
        return [
            [$data]
        ];
    }

}

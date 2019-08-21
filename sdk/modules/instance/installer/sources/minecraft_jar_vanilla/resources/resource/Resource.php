<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Minecraft_Jar_Vanilla\Resources\Resource;

    use function \_\find;
    use \Electrum\Uri\Uri;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\Installer\Record;
    use \Electrum\Userland\Sdk\FFI\Instance\FileSystem\Path;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Minecraft_Jar_Vanilla\Lib\Api\Client\Client as ApiClient;

    class Resource extends Template\Instance\Installer\Source\Resource\Resource {

        /** @var Instance\Instance */
        private $Instance;

        public function __construct( Instance\Instance $Instance, string $id ) {

            parent::__construct( $id );

            $this->Instance = $Instance;

        }

        public function install( string $versionId ): array {

            $Version = $this->getVersion( $versionId );

            $Destination = $this->Instance->getFileSystem()->getFiles()->get(

                new Path\Path( $this->Instance, '/vanilla_' . $Version->getId() . '.jar' )

            );

            $Destination->downloadFrom( $this->getDownloadUri( $Version ) );

            return [

                $Destination

            ];

        }

        public function uninstall( Record\Record $Record ): void {}

        private function getDownloadUri( Version $Version ): Uri {

            $Request = ApiClient::createRequest( HttpMethodsEnum::get(), $Version->getUri() );

            $Request->send();

            return Uri::fromString(

                $Request->getResponse()->getJson()['downloads']['server']['url']

            );

        }

    }

?>
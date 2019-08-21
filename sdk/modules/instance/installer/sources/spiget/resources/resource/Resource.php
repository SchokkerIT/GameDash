<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Spiget\Resources\Resource;

    use function \_\find;
    use \Electrum\Uri\Uri;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\Installer\Record;
    use \Electrum\Userland\Sdk\FFI\Instance\FileSystem\Path;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Spiget\Lib\Api\Client\Client as ApiClient;

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

                new Path\Path( $this->Instance, '/plugins/' . $this->getTitle() . '_' . $Version->getId() . '.jar' )

            );

            $Destination->downloadFrom(

                Uri::fromString( ApiClient::getBaseUri()->toString() . '/resources/' . $this->getId() . '/download?version=' . $Version->getId() )

            );

            return [

                $Destination

            ];

        }

        public function uninstall( Record\Record $Record ): void {}

    }

?>
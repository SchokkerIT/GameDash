<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource\Version;

    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Lib;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource;

    class Versions extends Template\Instance\Installer\Source\Resource\Version\Versions {

        private $Gateway;
        private $Resource;

        public function __construct( Gateway\Gateway $Gateway, Resource\Resource $Resource ) {

            $this->Gateway = $Gateway;
            $this->Resource = $Resource;

        }

        public function hasVersions(): bool {

            return $this->Resource->getUniversalResource()->getVersions()->has();

        }

        public function getAll(): array {

            return [ $this->getLatest() ];

        }

        public function getLatest(): VersionInterface {

            return $this->get( (string)$this->Resource->getUniversalResource()->getVersions()->getLatest() );

        }

        public function get( string $id ): VersionInterface {

            return new Version( $this->Gateway, $this->Resource, $id );

        }

    }

?>
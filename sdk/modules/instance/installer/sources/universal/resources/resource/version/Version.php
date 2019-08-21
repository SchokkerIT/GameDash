<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource\Version;

    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Lib;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource;

    class Version extends Template\Instance\Installer\Source\Resource\Version\Version {

        private $Gateway;
        private $Resource;

        public $name;
        public $timestampCreated;

        private $id;
        private $data;

        public function __construct( Gateway\Gateway $Gateway, Resource\Resource $Resource, string $id ) {

            $this->Gateway = $Gateway;
            $this->Resource = $Resource;

            $this->id = $id;

        }

        public function getName(): string {}

        public function hasName(): bool {

            return false;

        }

        public function getTimestampCreated(): int {}

        public function hasTimestampCreated(): bool {

            return false;

        }

        public function exists(): bool {}

        public function __toString() {

            return $this->id;

        }

    }

?>
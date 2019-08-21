<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource;

    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Lib;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource;

    class Author extends Template\Instance\Installer\Source\Resource\Author {

        private $Gateway;
        private $Resource;

        public $id;
        public $name;
        public $icon;

        private $data;

        public function __construct( Gateway\Gateway $Gateway, Resource\Resource $Resource ) {

            $this->Gateway = $Gateway;
            $this->Resource = $Resource;

        }

        public function getName(): string {

            return $this->Resource->getUniversalResource()->getAuthor()->getName();

        }

        public function getIcon(): string {

            return $this->Resource->getUniversalResource()->getAuthor()->getIcon();

        }

        public function hasIcon(): bool {

            return $this->Resource->getUniversalResource()->getAuthor()->hasIcon();

        }

        public function hasAuthor(): bool {

            return $this->Resource->getUniversalResource()->hasAuthor();

        }

    }

?>
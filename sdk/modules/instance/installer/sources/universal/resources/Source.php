<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;

    class Source extends Template\Instance\Installer\Source\Source {

        private $Gateway;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

        }

        public function getResources(): Template\Instance\Installer\Source\Resource\Resources {

            return new Resource\Resources( $this->Gateway );

        }

        public function getCategories(): Template\Instance\Installer\Source\Category\Categories {

            return new Category\Categories( $this->Gateway );

        }

    }

?>
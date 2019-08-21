<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Category;

    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Lib;

    class Categories extends Template\Instance\Installer\Source\Category\Categories {

        private $Gateway;
        private $Instance;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );

        }

        public function hasCategories(): bool {

            return true;

        }

        public function getAll(): array {

            $output = [];

                foreach( $this->Instance->getInstaller()->getSources()->getUniversal()->getCategories()->getAll() as $Category ) {

                    foreach( $Category->getServices() as $Service ) {
  
                        if( (string)$Service === $this->Instance->getService()->getId() ) {

                            break;

                        }

                        continue;

                    }

                    $output[] = $Category;

                }

            return $output;

        }

        public function get( string $id ): Template\Instance\Installer\Source\Category\Category {

            return new Category( $this->Gateway, $id );

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Lib;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;

    class Resources extends Template\Instance\Installer\Source\Resource\Resources {

        private $Gateway;
        private $Category;
        private $INstance;
        private $UniversalSource;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = $this->DependencyContainer->get('instance.instance');

            $this->UniversalSource = $this->Instance->getInstaller()->getSources()->getUniversal();

        }

        public function search( string $query, int $page, int $perPage ): array {

            $output = [];

                $i = 0;

                foreach( $this->UniversalSource->getResources()->getAll() as $i => $Resource ) {

                    if( $page * $perPage > $i + 1 || !stristr( $Resource->getName(), $query ) ) {

                        continue;

                    }

                    if( $i + 1 > $perPage ) {

                        break;

                    }

                    if( $this->hasCategory() ) {

                        if( (string)$Resource->getCategory() !== (string)$this->getCategory() ) {

                            continue;

                        }

                    }

                    $output[] = $Resource;

                }

            return $output;

        }

        public function getAll( int $page, int $perPage ): array {

            $output = [];

                foreach( $this->UniversalSource->getResources()->getAll() as $i => $Resource ) {

                    if( $page * $perPage > $i + 1 ) {

                        continue;

                    }

                    if( $i + 1 > $perPage ) {

                        break;

                    }

                    if( $this->hasCategory() ) {

                        if( (string)$Resource->getCategory() !== (string)$this->getCategory() ) {

                            continue;

                        }

                    }

                    if( $Resource->hasCategory() ) {

                        foreach( $Resource->getCategory()->getServices() as $Service ) {

                            if( (string)$Service === $this->Instance->getService()->getId() ) {

                                continue;

                            }

                        }

                    }

                    $output[] = $Resource;

                }

            return $output;

        }

        public function get( string $id ): Template\Instance\Installer\Source\Resource\Resource {

            return new Resource( $this->Gateway, $id );

        }

        public function setCategory( Template\Instance\Installer\Source\Category\Category $Category ): void {

            $this->Category = $Category;

        }
    }

?>
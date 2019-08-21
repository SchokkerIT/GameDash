<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Spiget\Resources\Category;

    use function \_\map;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Spiget\Lib\Api\Client\Client as ApiClient;

    class Categories
        extends Template\Instance\Installer\Source\Category\Categories
        implements Template\Instance\Installer\Source\Category\IHasCategories {

        public function getAll(): array {

            $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'categories' );

            $Request->send();

            return map($Request->getResponse()->getJson(), function( $result ): Category {

                $Category = new Category( $result['id'] );

                $Category->setTitle( $result['name'] );

                return $Category;

            });

        }

        public function get( string $id ): Template\Instance\Installer\Source\Category\Category {

            $Category = new Category( $id );

                $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'categories/' . $id );

                $Request->send();

                $Category->setTitle( $Request->getResponse()->getJson()['name'] );

            return $Category;

        }

        public function hasCategories(): bool {

            return true;

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Category;

    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources as SpigetSource;

    class Category extends Template\Instance\Installer\Source\Category\Category {

        private $Gateway;

        private $id;
        private $data;

        public function __construct( Gateway\Gateway $Gateway, string $id ) {

            $this->Gateway = $Gateway;

            $this->id = $id;

        }

        public function getName(): string {

            return $this->getData()['name'];

        }

        public function getResources(): Template\Instance\Installer\Source\Resource\Resources {

            $Resources = new SpigetSource\Resource\Resources( $this->Gateway );

            $Resources->setCategory( $this );

            return $Resources;

        }

        public function __toString() {

            return $this->id;

        }

        private function getData(): array {

            if( !$this->data ) {

                $Request = SpigetSource\Lib\Api\Client::request(HttpMethodsEnum::get(), 'categories/' . $this->id);

                $Request->getParameters()->unserialize([

                    'page' => 0,
                    'size' => 1000,

                ]);

                $Request->send();

                $this->data = $Request->getResponse()->getJson();

            }

            return $this->data;

        }

    }

?>
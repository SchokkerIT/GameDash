<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Billing\Product\Minecraft\Action\Resources;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Datacenter;
    use \Electrum\Userland\Sdk\FFI\Billing\Product\Action\Configuration;
    use \Electrum\Userland\Sdk\FFI\Billing\Price\Price;

    class CreateInstance extends Template\Billing\Product\Action\Action {

        /** @var Gateway\Gateway */
        private $Gateway;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

        }

        public function getConfigurationItems(): array {

            return [

                $this->createNameConfigurationItem(),
                $this->createRamConfigurationItem(),
                $this->createLocationConfigurationItem()

            ];

        }

        private function createNameConfigurationItem(): Configuration\Item\Variant\Text\Text {

            $Name = new Configuration\Item\Variant\Text\Text('name', 'Name');

            $Name->getValue()->getValidation()->setFunction(function( string $value ): Configuration\Item\Value\Validation\Result {

                if( strlen( $value ) > 32 ) {

                    return new Configuration\Item\Value\Validation\Result(false, 'Name must not be longer than 32 characters');

                }

                return new Configuration\Item\Value\Validation\Result(true);

            });

            return $Name;

        }

        private function createRamConfigurationItem(): Configuration\Item\Variant\Option\Options {

            $Options = new Configuration\Item\Variant\Option\Options('ram', 'RAM');

            $Options->create('0.5 GB', 500)
                ->setPrice( new Price( 2 ) );

            $Options->create('1 GB', 1000)
                ->setPrice( new Price( 4 ) );

            $Options->create('2 GB', 2000)
                ->setPrice( new Price( 8 ) );

            $Options->create('3 GB', 3000)
                ->setPrice( new Price( 12 ) );

            $Options->create('4 GB', 4000)
                ->setPrice( new Price( 16 ) );

            $Options->create('5 GB', 5000)
                ->setPrice( new Price( 20 ) );

            $Options->create('6 GB', 6000)
                ->setPrice( new Price( 24 ) );

            $Options->create('7 GB', 7000)
                ->setPrice( new Price( 28 ) );

            $Options->create('8 GB', 8000)
                ->setPrice( new Price( 32 ) );

            $Options->create('10 GB', 10000)
                ->setPrice( new Price( 40 ) );

            $Options->create('12 GB', 120000)
                ->setPrice( new Price( 44 ) );

            return $Options;

        }

        private function createLocationConfigurationItem(): Configuration\Item\Variant\Option\Options {

            $Options = new Configuration\Item\Variant\Option\Options('location', 'Location');

                foreach( Datacenter\Datacenters::getAll() as $Datacenter ) {

                    $Options->create($Datacenter->getId(), $Datacenter->getId());

                }

            return $Options;

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Name;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\FileSystem;

    class Name extends Template\Service\Name\Name {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var Instance\Instance */
        private $Instance;

        /** @var FileSystem\FileSystem */
        private $FileSystem;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );
            $this->FileSystem = $this->Instance->getFileSystem();

        }

        public function set( string $name ): void {

            $ServerProperties = $this->FileSystem->getConfigEditor()->getFiles()->get(

                new FileSystem\Path\Path($this->Instance, 'server.properties')

            );

            $Settings = $ServerProperties->getSettings();

            if( !$Settings->exists('motd') ) {

                $Settings->create('motd', $name);

            }
            else {

                $Settings->get('motd')->setValue( $name );

            }

            $Settings->commit();

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Network;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Ip\Ip;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\FileSystem\ConfigEditor;

    class Network extends Template\Service\Network\Network {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var Instance\Instance */
        private $Instance;

        /** @var ConfigEditor\ConfigEditor */
        private $ConfigEditor;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );
            $this->ConfigEditor = $this->Instance->getFileSystem()->getConfigEditor();

        }

        public function canAllocateIp(): bool {

            return true;

        }

        public function allocateIp( Ip $Ip ): void {

            $Path = new Instance\FileSystem\Path\Path( $this->Instance, 'server.properties' );

            $Settings = $this->ConfigEditor->getFiles()->get( $Path )->getSettings();

            $Settings->get('server-ip')->setValue( $Ip->toString() );
            $Settings->get('server-port')->setValue( 25565 );

            $Settings->commit();

        }

    }

?>
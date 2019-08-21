<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\SteamCmd\Resources\Resource;

    use function \_\find;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\Installer\Record;
    use \Electrum\Userland\Sdk\FFI\Service;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Node;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\SteamCmd\Lib\App;

    class Resource extends Template\Instance\Installer\Source\Resource\Resource {

        /** @var Instance\Instance */
        private $Instance;

        /** @var Service\Service */
        private $Service;

        /** @var Node\Node */
        private $Node;

        public function __construct( Instance\Instance $Instance, string $id ) {

            parent::__construct( $id );

            $this->Instance = $Instance;

            $this->Service = $this->Instance->getService();
            $this->Node = $this->Instance->getInfrastructure()->getNode();

        }

        public function install( string $versionId ): array {

            $Thread = $this->Node->getProcesses()->getThreads()->create();

            set_time_limit( 0 );

            if( $this->Node->getOperatingSystems()->getCurrent()->compare( Node\Os\Systems::get('linux_ubuntu') ) ) {

                $args = array_merge(

                    [ $this->Node->getFileSystem()->getFiles()->getRegistered()->get('steamcmd')->getFile()->getPath()->toString() . '/steamcmd.sh' ],

                    $this->getApp()->getArgs(),

                    [

                        '+force_install_dir',

                        $this->Instance->getFileSystem()->getDirectory()->getFile()->getPath()->toString(),

                        '+app_update',

                        $this->getId(),

                        'validate',

                        '+quit'

                    ]

                );

                $Thread->spawn( 'bash', $args );

            }
            else {

                $Thread->spawn();

            }

            $i = 0;

            while( true ) {

                if( $i > 100 ) {

                    throw new \Exception('Installation timed out');

                }

                if( !$Thread->exists() ) {

                    break;

                }

                $i++;

                sleep( 5 );

            }

            set_time_limit( 30 );

            return $this->Instance->getFileSystem()->getFiles()->get(

                new Instance\FileSystem\Path\Path($this->Instance, '/')

            )->getDirectoryContents();

        }

        public function uninstall( Record\Record $Record ): void {}

        private function getApp(): App\App {

            return App\Apps::get( $this->getId() );

        }

    }

?>
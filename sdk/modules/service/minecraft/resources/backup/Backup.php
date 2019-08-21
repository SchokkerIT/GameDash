<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Backup;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\Backup\Record\Record;
    use \Electrum\Userland\Sdk\FFI\Instance\Backup\Storage\Storage;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Node;

    class Backup extends Template\Service\Backup\Backup {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var Instance\Instance */
        private $Instance;

        /** @var Node\Node */
        private $Node;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );
            $this->Node = $this->Instance->getInfrastructure()->getNode();

        }

        public function create( Storage $Storage ): void {

            $Group = $this->getFileGroup()->toAbsolute();

            $Group->zip(

                $Storage->getFile()

            );

        }

        public function restore( Record $Record ): void {

            $this->Instance->getFileSystem()->getDirectory()->deleteContents();

            $Record->getStorage()->getFile()->unzip(

                $this->Instance->getFileSystem()->getDirectory()->getFile()

            );

        }

        public function getFileGroup(): Instance\FileSystem\File\Group {

            $Group = new Instance\FileSystem\File\Group( $this->Instance );

            $GetFile = function( string $path ): Instance\FileSystem\File\File {

                return new Instance\FileSystem\File\File( $this->Instance, new Instance\FileSystem\Path\Path( $this->Instance, $path ) );

            };

            $Group->addFiles([

                $GetFile( 'server.properties' ),
                $GetFile( 'eula.txt' ),
                $GetFile( 'plugins' ),
                $GetFile( 'world' ),
                $GetFile( 'world_nether' ),
                $GetFile( 'world_the_end' ),
                $GetFile( 'banned-players.json' ),
                $GetFile( 'usercache.json' ),
                $GetFile( 'whitelist.json' )

            ]);

            $RootDirectory = $this->Instance->getFileSystem()->getFiles()->get( new Instance\FileSystem\Path\Path($this->Instance, '/') );

            foreach( $RootDirectory->getDirectoryContents() as $File ) {

                if( $File->isDirectory() || $File->getExtension() !== 'jar' ) {

                    continue;

                }

                $Group->addFile( $File );

            }

            foreach( $Group->getFiles() as $File ) {

                if( !$File->exists() ) {

                    $Group->removeFile( $File );

                }

            }

            return $Group;

        }

    }

?>
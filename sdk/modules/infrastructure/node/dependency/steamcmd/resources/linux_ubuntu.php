<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Infrastructure\Node\Dependency\SteamCmd\Resources;

    use \Electrum\Utilities;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Node\FileSystem;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Node\FileSystem\Enums\FileTypes as FileTypesEnum;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Node\FileSystem\Query\Path;

    class Linux_Ubuntu extends Template\Infrastructure\Node\Dependency\Dependency {

        private $Gateway;
        private $Node;
        private $FileSystem;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Node = new FFI\Infrastructure\Node\Node( $this->Gateway->getParameters()->get('node.id')->getValue
            () );
            $this->FileSystem = $this->Node->getFileSystem();

        }

        public function install(): void {

            $Path = $this->getDirectoryPath();

            $zipFilePath = Utilities\FileSystem\Path::join( $Path->getValue(), 'steamcmd.zip' );

            $this->FileSystem->downloadFile(

                'https://assets.gamedash.io/infrastructure/dependency/lib/steamcmd/linux.zip', $zipFilePath

            );

            $this->FileSystem->unzipFile( $zipFilePath, $Path->getValue() );

            $this->FileSystem->deleteFile( $zipFilePath );

            $this->setExecutable();

        }

        public function uninstall(): void {

            $this->FileSystem->getPaths()->get('steamcmd')->delete();

        }

        public function isAvailable(): bool {

            return (string)$this->Node->getOperatingSystem() === 'linux_ubuntu';

        }

        private function getDirectoryPath(): FFI\Infrastructure\Node\FileSystem\Path\Path {

            $Paths = $this->FileSystem->getPaths();

            if( !$Paths->get('steamcmd')->exists() ) {

                $Path = $Paths->create( 'steamcmd', FileSystem\FileSystem::FILETYPE_FILE, Utilities\FileSystem\Path::join( $Paths->get('lib') ->getValue(),
                    'steamcmd' ) );

            }
            else {

                $Path = $Paths->get('steamcmd');

            }

            return $Path;

        }

        private function setExecutable(): void {

            $Query = $this->FileSystem->createQuery( FileTypesEnum::file() );

            $Query->getPaths()->create(

                'path', new Path\Generic( $this->Node, Utilities\FileSystem\Path::join( $this->getDirectoryPath()->getValue(), 'steamcmd.sh' ) )

            );

            $Query->execute('setExecutable');

        }

    }

?>
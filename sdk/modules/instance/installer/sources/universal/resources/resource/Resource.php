<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Resource;

    use \Electrum\Utilities;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Infrastructure;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Resources\Lib;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Universal\Category;

    class Resource extends Template\Instance\Installer\Source\Resource\Resource {

        private $Gateway;
        private $Instance;
        private $FileSystem;
        private $Author;

        public $name;
        public $description;
        public $shortDescription;
        public $downloadCount;
        public $timestampCreated;

        private $id;
        private $data;

        public function __construct( Gateway\Gateway $Gateway, string $id ) {

            $this->Gateway = $Gateway;

            $this->id = $id;

            $this->Instance = new Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );
            $this->FileSystem = $this->Instance->getFileSystem();

            $this->Author = new Author( $this->Gateway, $this );

        }

        public function getName(): string {

            return $this->getUniversalResource()->getName();

        }

        public function getDescription(): string {

            return $this->getUniversalResource()->getDescription();

        }

        public function hasDescription(): bool {

            return $this->getUniversalResource()->hasDescription();

        }

        public function getShortDescription(): string {

            return $this->getUniversalResource()->getShortDescription();

        }

        public function hasShortDescription(): bool {

            return $this->getUniversalResource()->hasShortDescription();

        }

        public function isHidden(): bool {

            return $this->getUniversalResource()->isHidden();

        }

        public function getCategory(): Template\Instance\Installer\Source\Category\Category {

            return new Category\Category( $this->Gateway );

        }

        public function hasCategory(): bool {

            return $this->getUniversalResource()->hasCategory();

        }

        public function exists(): bool {

            return $this->getUniversalResource()->exists();

        }

        public function install( Template\Instance\Installer\Source\Resource\Version\Version $Version ): void {

            $directoryContents = $this->Instance->getFileSystem()->walkDirectory('/');

            $Installation = $this->getUniversalResource()->getInstallation();

            $url = $Installation->getUrl();
            $Destination = $Installation->getDestination();

            $Workspace = $this->Instance->getFileSystem()->getWorkspaces()->create();

            $workspacePath = Utilities\FileSystem\Path::join( $Workspace->getPath(), 'target' );
            $instancePath = Utilities\FileSystem\Path::resolve( $this->InstanceFileSystem->getDirectory()->getPath(), $Destination->getPath() );

            $this->NodeFileSystem->downloadFile( $workspacePath, $url );

            if( Utilities\FileSystem\Path::hasExtension( $url ) ) {

                if( Utilities\FileSystem\Path::getExtension( $url ) === 'zip' ) {

                    if( !$this->NodeFileSystem->directoryExists( $instancePath ) ) {

                        $this->NodeFileSystem->createDirectory( $instancePath );

                    }

                    $this->NodeFileSystem->unzip( $workspacePath, $instancePath );

                }

            }
            else {

                $this->NodeFileSystem->moveFile( $workspacePath, $instancePath );

            }

            foreach( $this->Instance->getFileSystem()->walkDirectory('/') as $Path ) {

                if(

                    Utilities\Arrays::exists($directoryContents, function( $_Path ) use ( $Path ) {

                        return $Path->compare( $_Path );

                    })

                ) {

                    continue;

                }

                $this->Gateway->getHelpers()->get('addFile')->execute([ $Path ]);

            }

        }

        public function uninstall(): void {}

        public function getVersions(): Template\Instance\Installer\Source\Resource\Version\Versions {

            return new Version\Versions( $this->Gateway, $this );

        }

        public function getAuthor(): Template\Instance\Installer\Source\Resource\Author {

            return $this->Author;

        }

        public function getDownloadCount(): int {

            return $this->getUniversalResource()->getDownloadCount();

        }

        public function hasDownloadCount(): bool {

            return true;

        }

        public function getTimestampCreated(): int {

            return $this->getUniversalResource()->getTimestampCreated();

        }

        public function hasTimestampCreated(): bool {

            return true;

        }

        public function __toString() {

            return $this->id;

        }

        public function getUniversalResource() {

            return $this->Instance->getInstaller()->getSources()->getUniversal()->getResources()->get( $this->id );

        }

    }

?>
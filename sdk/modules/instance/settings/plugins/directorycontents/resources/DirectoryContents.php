<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Settings\Plugins\DirectoryContents\Resources;

    use function \_\map;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\FileSystem;

    class DirectoryContents extends Template\Instance\Settings\Plugin {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var Instance\Instance */
        private $Instance;

        /** @var FileSystem\FileSystem */
        private $FileSystem;

        private $PluginParameters;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );
            $this->FileSystem = $this->Instance->getFileSystem();

            $this->PluginParameters = $this->Gateway->getParameters()->get('PluginParameters')->getValue();

        }

        public function getData(): array {

            return [

                'options' => map($this->getDirectoryContents(), function( $Path ) {

                    return self::formatRelativePath( $Path->getRelative() );

                })

            ];

        }

        public function validateValue( $value ): bool {

            foreach( $this->getDirectoryContents() as $Path ) {

                if( self::formatRelativePath( $Path->getRelative() ) === $value ) {

                    return true;

                }

            }

            return false;

        }

        private function getDirectoryContents(): array {

            $paths = [];

            foreach( $this->PluginParameters->get('directories')->getValue() as $directory ) {

                if( !$this->Instance->getFileSystem()->directoryExists( $directory ) ) {

                    continue;

                }

                $Query = $this->FileSystem->createQuery( FFI\Infrastructure\Node\FileSystem\Enums\FileTypes::directory() );

                $Query->getPaths()->create('path', new Path\Generic(

                    $this->Instance, $directory

                ));

                foreach( $Query->execute('getContents') as $Path ) {

                    if( $this->PluginParameters->get('types')->exists() ) {

                        if( !in_array( (string)$Path->getFileType(), $this->PluginParameters->get('types')->getValue() ) ) {

                            continue;

                        }

                    }

                    if( $this->PluginParameters->get('extensions')->exists() ) {

                        if(

                            !$Path->hasExtension()

                                ||

                            !in_array( $Path->getExtension(), $this->PluginParameters->get('extensions')->getValue())

                        ) {

                            continue;

                        }

                    }

                    $paths[] = $Path;

                }

            }

            return $paths;

        }

        private static function formatRelativePath( string $value ): string {

            return substr( $value, 1 );

        }

    }

?>
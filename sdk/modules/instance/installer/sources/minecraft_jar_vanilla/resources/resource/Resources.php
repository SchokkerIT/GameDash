<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Minecraft_Jar_Vanilla\Resources\Resource;

    use function \_\map;
    use \Electrum\Uri\Uri;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Minecraft_Jar_Vanilla\Lib\Api\Client\Client as ApiClient;
    use \Electrum\Userland\Sdk\FFI\Instance;

    class Resources extends Template\Instance\Installer\Source\Resource\Resources {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var Instance\Instance */
        private $Instance;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = Instance\Instances::get( $this->Gateway->getParameters()->get('instance.id')->getValue() );

        }

        public function isSearchable(): bool {

            return false;

        }

        public function getAll( int $page, int $perPage ): array {

            return [ $this->get('vanilla') ];

        }

        public function get( string $id ): Template\Instance\Installer\Source\Resource\Resource {

            $Resource = new Resource( $this->Instance, $id );

                $Resource->setTitle('Vanilla');
                $Resource->setVersions( $this->getVersions() );
                $Resource->setAuthor( new Author('Mojang') );

            return $Resource;

        }

        public function exists( string $id ): bool {

            return true;

        }

        /** @return Version[] */
        private function getVersions(): array {

            $Request = ApiClient::createRequest( HttpMethodsEnum::get(), Uri::fromString( 'https://launchermeta.mojang.com/mc/game/version_manifest.json' ) );

            $Request->send();

            $LatestVersion = $this->getLatestVersion();

            return map($Request->getResponse()->getJson()['versions'], function( $result ) use ( $LatestVersion ): Version {

                $Version = new Version( $result['id'] );

                    $Version->setIsLatest( $LatestVersion->getId() === $result['id'] );

                return $Version;

            });

        }

        private function getLatestVersion(): Version {

            $Request = ApiClient::createRequest( HttpMethodsEnum::get(), Uri::fromString( 'https://launchermeta.mojang.com/mc/game/version_manifest.json' ) );

            $Request->send();

            $results = $Request->getResponse()->getJson();

            return new Version( $results['latest']['release'] );

        }

    }

?>
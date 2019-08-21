<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\SteamCmd\Resources\Resource;

    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\SteamCmd\Lib\App;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\SteamCmd\Lib\Api\Client\Client as ApiClient;
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

            return [];

        }

        public function get( string $id ): Template\Instance\Installer\Source\Resource\Resource {

            if( !App\Apps::exists( $id ) ) {

                throw new \Exception('Steam cmd app "' . $id . '" is not supported');

            }

            $Resource = new Resource( $this->Instance, $id );

                $Resource->setTitle('Vanilla');
                $Resource->setVersions([ $this->getLatestVersion( $id ) ]);
                $Resource->setAuthor( new Author('Mojang') );

            return $Resource;

        }

        public function exists( string $id ): bool {

            return App\Apps::exists( $id );

        }

        private function getLatestVersion( string $id ): Version {

            $App = App\Apps::get( $id );

            $Request = ApiClient::createRequest(

                HttpMethodsEnum::get(), 'ISteamApps/UpToDateCheck/v1'

            );

            $Request->getParameters()->get('appid')->setValue( $App->getPublicId() );
            $Request->getParameters()->get('version')->setValue(0);
            $Request->getParameters()->get('format')->setValue('json');

            $Request->send();

            $results = $Request->getResponse()->getJson();

            $Version = new Version( $results['response']['required_version'] );

            $Version->setIsLatest( true );

            return $Version;

        }

    }

?>
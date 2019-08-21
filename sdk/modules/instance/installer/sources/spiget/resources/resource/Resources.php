<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Spiget\Resources\Resource;

    use function \_\map;
    use \Electrum\Time\Time;
    use \Electrum\Uri\Uri;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Spiget;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Spiget\Lib\Api\Client\Client as ApiClient;
    use \Electrum\Userland\Sdk\FFI\Instance;

    class Resources extends Template\Instance\Installer\Source\Resource\Resources implements Template\Instance\Installer\Source\Resource\ISearchable {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var Instance\Instance */
        private $Instance;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = Instance\Instances::get( $this->Gateway->getParameters()->get('instance.id')->getValue() );

        }

        public function search( string $query, int $page, int $perPage ): array {

            $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'search/resources/' . $query );

            $Request->getParameters()->get('page')->setValue( $page );
            $Request->getParameters()->get('size')->setValue( $perPage );
            $Request->getParameters()->get('fields')->setValue('id,name,tag,downloads,releaseDate');
            $Request->getParameters()->get('sort')->setValue('-updateDate');

            $Request->send();

            return map($Request->getResponse()->getJson(), function( $result ): Resource {

                $Resource = new Resource( $this->Instance, $result['id'] );

                $Resource->setTitle( $result['name'] );
                $Resource->setDescription( $result['tag'] );
                $Resource->setDownloadCount( $result['downloads'] );
                $Resource->setTimeCreated( Time::createFromTimestamp( $result['releaseDate'] ) );

                return $Resource;

            });

        }

        public function isSearchable(): bool {

            return true;

        }

        public function getAll( int $page, int $perPage ): array {

            if( $this->Category ) {

                $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'categories/' . $this->Category->getName() . '/resources' );

                $Request->getParameters()->get('page')->setValue( $page );
                $Request->getParameters()->get('size')->setValue( $perPage );
                $Request->getParameters()->get('fields')->setValue('id,name,description,tag,downloads,releaseDate');
                $Request->getParameters()->get('sort')->setValue('-updateDate');

                $Request->send();

            }
            else {

                $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'resources/new' );

                $Request->getParameters()->get('page')->setValue( $page );
                $Request->getParameters()->get('size')->setValue( $perPage );
                $Request->getParameters()->get('fields')->setValue('id,name,description,tag,downloads,releaseDate');
                $Request->getParameters()->get('sort')->setValue('-updateDate');

                $Request->send();

            }

            return map($Request->getResponse()->getJson(), function( $result ): Resource {

                $Resource = new Resource( $this->Instance, $result['id'] );

                $Resource->setTitle( $result['name'] );
                $Resource->setDescription( $result['tag'] );
                $Resource->setDownloadCount( $result['downloads'] );
                $Resource->setTimeCreated( Time::createFromTimestamp( $result['releaseDate'] ) );

                return $Resource;

            });

        }

        public function get( string $id ): Template\Instance\Installer\Source\Resource\Resource {

            $Resource = new Spiget\Resources\Resource\Resource( $this->Instance, $id );

                $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'resources/' . $id );

                $Request->getParameters()->get('fields')->setValue('name,description,version,versions,tag,downloads,author,category,releaseDate,updateDate');

                $Request->send();

                $result = $Request->getResponse()->getJson();

                $Resource->setTitle( $result['name'] );
                $Resource->setDescription( $result['tag'] );
                $Resource->setFullDescription( $result['description'] );
                $Resource->setVersions(map($result['versions'], function( $version ) use ( $result ) {

                    $Version = new Version( $version['id'] );

                    $Version->setIsLatest( $result['version']['id'] === $version['id'] );

                    return $Version;

                }));
                $Resource->setTimeCreated( Time::createFromTimestamp( $result['releaseDate'] ) );

                $authorId = $result['author']['id'];

                $Resource->setAuthor(

                    (function() use ( $authorId ): Author {

                        $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'authors/' . $authorId );

                        $Request->send();

                        $result = $Request->getResponse()->getJson();

                        $Author = new Author( $authorId );

                        $Author->setIconUri( Uri::fromString( 'data:image/jpeg;charset=utf-8;base64,' . $result['icon']['data'] ) );

                        return $Author;

                    })()

                );

            return $Resource;

        }

        public function exists( string $id ): bool {

            $Request = ApiClient::createRequest( HttpMethodsEnum::get(), 'resources/' . $id );

            $Request->getParameters()->get('fields')->setValue('id');

            $Request->send();

            return $Request->getResponse()->getStatusCode() === 200;

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Client\Connected;

    use function \_\map;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \xPaw\MinecraftQuery;
    use \xPaw\MinecraftQueryException;

    class Clients extends Template\Service\Client\Connected\Clients {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var FFI\Instance\Instance */
        private $Instance;

        /** @var FFI\Instance\FileSystem\ConfigEditor\File\File */
        private $ServerProperties;
        private $Connection;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );
            $this->ServerProperties = $this->Instance->getFileSystem()->getConfigEditor()->getFiles()->get('server.properties');

        }

        /** @return Client[] */
        public function getAll(): array {

            return map($this->createConnection()->getPlayers(), function( $name ) {

                return $this->get( $name );

            });

        }

        public function get( string $name ): Template\Service\Client\Connected\Client {

            return new Client( $this->Gateway, $name );

        }

        public function isAvailable(): bool {

            if( !$this->ServerProperties->exists() ) {

                return false;

            }

            if(

                !$this->ServerProperties->getSettings()->exists('enable-query')

                    ||

                $this->ServerProperties->getSettings()->get('enable-query')->getValue() !== 'true'

                    ||

                !$this->ServerProperties->getSettings()->exists('query.port')

            ) {

                return false;

            }

            try {

                $this->createConnection();

            }
            catch( MinecraftQueryException $exception ) {

                return false;

            }

            return true;

        }

        private function createConnection(): MinecraftQuery {

            if( !$this->Connection ) {

                $Query = new MinecraftQuery;

                $Connect = function( int $port ) use ( $Query ) {

                    $Query->Connect(

                        $this->Instance->getNetwork()->getIp(),

                        $port

                    );

                };

                $Connect(

                    intval( $this->ServerProperties->getSettings()->get('query.port')->getValue() )

                );

                $this->Connection = $Query;

            }

            return $this->Connection;

        }

    }

?>
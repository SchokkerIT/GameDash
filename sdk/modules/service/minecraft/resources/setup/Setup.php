<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Setup;

    use \Electrum\Utilities\Enum\Common\DataTypes as DataTypesEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Config\Config;
    use \Electrum\Userland\Instance\Setup\Method\Parameter;

    class Setup extends Template\Service\Resources\Setup\Setup {

        private $Gateway;
        private $Instance;
        private $FileSystem;
        private $Settings;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')
                ->getValue() );

            $this->FileSystem = $this->Instance->getFileSystem();
            $this->Settings = $this->Instance->getSettings();

        }

        public function install( Parameter\Parameters $Parameters ): void {

            $this->installBaseJar();

            $port = $this->Gateway->getHelpers()->get('getFreeNetworkPort')->execute();
            $ram = $Parameters->get('ram')->getValue();

            $this->Settings->create('port');
            $this->Settings->create('ram');

            $this->Settings->get('port')->setValue( $port );
            $this->Settings->get('ram')->setValue( $ram );

            $maxConnectedClients = $this->getScaledMaxClientsValue( $ram );

            $this->writeDefaultServerProperties( $port, $maxConnectedClients );

        }

        public function uninstall( Parameter\Parameters $Parameters ): void {}

        public function reset( Parameter\Parameters $Parameters ): void {

            $ram = $this->Settings->get('ram')->getValue();
            $port = $this->Settings->get('port')->getValue();

            $this->installBaseJar();

            $maxConnectedClients = $this->getScaledMaxClientsValue( $ram );

            $this->writeDefaultServerProperties( $port, $maxConnectedClients );

            if( $this->Instance->getProcess()->getStatus()->isOnline() ) {

                $this->Instance->getProcess()->restart();

            }

        }

        public function upgrade( Parameter\Parameters $Parameters ): void {

            $ram = $Parameters->get('ram')->getValue();

            $this->Settings->get('ram')->setValue( $ram );

            $ServerProperties = $this->FileSystem->getConfigEditor()->getFiles()->get('server.properties');

            $ServerProperties->getSettings()->get('max-players')->setValue( $this->getScaledMaxClientsValue( $ram ) );

            $ServerProperties->commit();

            if( $this->Instance->getProcess()->getStatus()->isOnline() ) {

                $this->Instance->getProcess()->restart();

            }

        }

        public function move( Parameter\Parameters $Parameters ): void {



        }

        public function getRequiredParameters(): array {

            return [

                'install' => [

                    $this->Gateway->getHelpers()->get('createRequiredParameter', [ 'ram', DataTypesEnum::integer() ])

                ],

                'update' => [

                    $this->Gateway->getHelpers()->get('createRequiredParameter', [ 'ram', DataTypesEnum::integer() ])

                ]

            ];

        }

        private function getScaledMaxClientsValue( int $ram ): int {

            return floor( $ram / 64 );

        }

        private function installBaseJar(): void {

            $Source = $this->Instance->getInstaller()->getSources()->get('instance/installer/sources/minecraft_jar_vanilla');

            $Record = $Source->getResources()->get('vanilla')->install();

            $this->Instance->getSettings()->get('jar')->setValue( $Record->getFiles()->getAll()[0]->getName() );

        }

        private function writeDefaultServerProperties( int $port, int $maxConnectedClients ): void {

            if( !$this->FileSystem->fileExists('server.properties') ) {

                $this->FileSystem->createFile('server.properties');

            }
            
            $File = $this->FileSystem->getConfigEditor()->getFiles()->get('server.properties');

            $Settings = $File->getSettings();

            $Settings->get('op-permission-level')->setValue( 4 );
            $Settings->get('allow-nether')->setValue( 'true' );
            $Settings->get('level-name')->setValue( 'world' );
            $Settings->get('enable-query')->setValue( 'true' );
            $Settings->get('query.port')->setValue( $port );
            $Settings->get('allow-flight')->setValue( 'true' );
            $Settings->get('announce-player-achievements')->setValue( 'true' );
            $Settings->get('server-port')->setValue( $port );
            $Settings->get('max-world-size')->setValue( 29999984 );
            $Settings->get('level-type')->setValue( 'DEFAULT' );
            $Settings->get('enable-rcon')->setValue( 'false' );
            $Settings->get('network-compression-threshold')->setValue( 256 );
            $Settings->get('max-build-height')->setValue( 256 );
            $Settings->get('spawn-npcs')->setValue( 'true' );
            $Settings->get('white-list')->setValue( 'false' );
            $Settings->get('spawn-animals')->setValue( 'true' );
            $Settings->get('hardcore')->setValue( 'hardcore' );
            $Settings->get('snooper-enabled')->setValue( 'true' );
            $Settings->get('online-mode')->setValue( 'true' );
            $Settings->get('pvp')->setValue( 'false' );
            $Settings->get('difficulty')->setValue( 1 );
            $Settings->get('enable-command-block')->setValue( 'true' );
            $Settings->get('gamemode')->setValue( 0 );
            $Settings->get('player-idle-timeout')->setValue( 15 );
            $Settings->get('max-players')->setValue( $maxConnectedClients );
            $Settings->get('max-tick-time')->setValue( 60000 );
            $Settings->get('spawn-monsters')->setValue( 'true' );
            $Settings->get('generate-structures')->setValue( 'true' );
            $Settings->get('motd')->setValue( Config::getValue('company.tradingName') );

            $Settings->commit();

        }

    }

?>
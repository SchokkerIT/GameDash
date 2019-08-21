<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Client\Connected;

    use function \_\find;
    use \Electrum\Uri\Uri;
    use \Electrum\Base64\Base64;
    use \Electrum\Json\Json;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Http\Client\Client as HttpClient;
    use \Electrum\Userland\Sdk\FFI\Instance\FileSystem\File;

    class Client extends Template\Service\Client\Connected\Client {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var FFI\Instance\Instance */
        private $Instance;

        /** @var string */
        private $name;

        /** @var string */
        private $uuid;

        public function __construct( Gateway\Gateway $Gateway, string $name ) {

            $this->Gateway = $Gateway;

            $this->name = $name;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );

        }

        public function getId(): string {

            return $this->name;

        }

        public function getName(): string {

            return $this->name;

        }

        public function getImage(): string {

            $Request = HttpClient::createRequest( HttpMethodsEnum::get(),Uri::fromString('https://minotar.net/avatar/' . $this->name) );

            $Request->send();

            return Base64::encode( $Request->getResponse()->getString() );

        }

        public function hasImage(): bool {

            return true;

        }

        public function getActions(): array {

            $actions = [

                $this->Gateway->getHelpers()->get('createClientAction')->execute([

                    $this, 'kill', 'Kill', function() {

                        $this->Instance->getConsole()->getIo()->getInput()->send('kill ' . $this->getName());

                    }

                ]),

                $this->Gateway->getHelpers()->get('createClientAction')->execute([

                    $this, 'kick', 'Kick', function() {

                        $this->Instance->getConsole()->getIo()->getInput()->send('kick ' . $this->getName());

                        $this->waitForPlayerLeave();

                    }

                ]),

                $this->Gateway->getHelpers()->get('createClientAction')->execute([

                    $this, 'ban', 'Ban', function() {

                        $this->Instance->getConsole()->getIo()->getInput()->send('ban ' . $this->getName());

                        $this->waitForPlayerLeave();

                    }

                ]),

                $this->Gateway->getHelpers()->get('createClientAction')->execute([

                    $this, 'banIp', 'Ban IP', function() {

                        $this->Instance->getConsole()->getIo()->getInput()->send('ban-ip ' . $this->getName());

                        $this->waitForPlayerLeave();

                    }

                ]),

                $this->Gateway->getHelpers()->get('createClientAction')->execute([

                    $this, 'heal', 'Heal', function() {

                        $this->Instance->getConsole()->getIo()->getInput()->send('effect give ' . $this->getName() . ' instant_health');

                    }

                ])

            ];

            if( !$this->isOpped() ) {

                $actions[] = $this->Gateway->getHelpers()->get('createClientAction')->execute([

                    $this, 'op', 'Op', function() {

                        $this->Instance->getConsole()->getIo()->getInput()->send('op ' . $this->getName());

                        $this->waitForOp( true );

                    }

                ]);

            }
            else {

                $actions[] = $this->Gateway->getHelpers()->get('createClientAction')->execute([

                    $this, 'deop', 'De-Op', function() {

                        $this->Instance->getConsole()->getIo()->getInput()->send('deop ' . $this->getName());

                        $this->waitForOp( false );

                    }

                ]);

            }

            return $actions;

        }

        private function getUuid(): string {

            if( !$this->uuid ) {

                $Request = HttpClient::request(

                    HttpMethodsEnum::get(),
                    Uri::fromString( 'https://api.mojang.com/users/profiles/minecraft/' . $this->name )

                );

                $Request->send();

                $this->uuid = $Request->getResponse()->getJson()['id'];

            }

            return $this->uuid;

        }

        private function isOpped(): bool {

            if( !$this->Instance->getFileSystem()->getFiles()->get( File\Path( $this->Instance, 'ops.json' ) ) ) {

                return false;

            }

            $contents = $this->Instance->getFileSystem()->getFiles()->get( File\Path( $this->Instance, 'ops.json' ) )->read();

            if( !$contents ) {

                return false;

            }

            $ops = Json::decode( $contents );

            foreach( $ops as $op ) {

                if( $op['name'] === $this->getName() ) {

                    return true;

                }

            }

            return false;

        }

        private function waitForOp( bool $op ): void {

            while(true) {

                if( $this->isOpped() === $op ) {

                    return;

                }

                sleep(1);

            }

        }

        private function waitForPlayerLeave(): void {

            while(true) {

                $Clients = new Clients( $this->Gateway );

                if(

                    !find( $Clients->getAll(), function( Client $Client ) {

                       return $Client->getId() === $this->getId();

                    })

                ) {

                    return;

                }

                sleep( 1 );

            }

        }

    }

?>
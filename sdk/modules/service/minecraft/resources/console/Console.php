<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Console;

    use function \_\map;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Node;
    use \Electrum\Userland\Sdk\FFI\Instance;

    class Console extends Template\Service\Console\Console {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var FFI\Instance\Instance */
        private $Instance;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );

        }

        public function sendInput( string $input ): ?string {

            $Thread = $this->Instance->getProcess()->getThreads()->getCurrent();

            $Thread->getIo()->write( $input );

            return null;

        }

        public function getOutput( ?int $tail ): array {

            $Thread = $this->Instance->getProcess()->getThreads()->getCurrent();

            return map($Thread->getIo()->getOutput( $tail ), function( Node\Process\Io\Output\Item $Item ): Instance\Console\Io\Output\Item {

                return Instance\Console\Io\Output\Item::fromInfrastructureNodeProcess( $Item );

            });

        }

        public function getOutputEvents(): array {

            return [

                $this->Instance->getConsole()->getIo()->getOutput()->getEvents()->create(

                    'discord', [ '<(.*?)>' ], function( string $value, array $matches ): void {

                        $messagePos = strpos($value, '<' . $matches[0] . '>') + strlen( '<' . $matches[0] . '>' ) + 1;

                        $message = substr( $value, $messagePos );

                        $sendWhisper = function( string $message ) use( $matches ): void {

                            $this->sendInput('msg ' . $matches[0] . ' ' . $message);

                        };

                        if( $message === 'gamedash.discord' ) {

                            $sendWhisper('Join us at https://discord.gg/SPyNQY');

                        }
                        else if( $message === '?suicide' ) {

                            $this->sendInput('kill ' . $matches[0]);

                        }

                    }

                )

            ];

        }

        public function getFormatting(): array {

            return [

                [

                    'color' => '#00A',
                    'patterns' => ["(Â§|\\?)1.+"]

                ],

                [

                    'color' => '#0A0',
                    'patterns' => ["(Â§|\\?)2.+"]

                ],

                [

                    'color' => '#0AA',
                    'patterns' => ["(Â§|\\?)3.+"]

                ],

                [

                    'color' => '#A00',
                    'patterns' => ["(Â§|\\?)4.+"]

                ],

                [

                    'color' => '#A0A',
                    'patterns' => ["(Â§|\\?)5.+"]

                ],

                [

                    'color' => '#FA0',
                    'patterns' => ["(Â§|\\?)6.+"]

                ],

                [

                    'color' => '#AAA',
                    'patterns' => ["(Â§|\\?)7.+"]

                ],

                [

                    'color' => '#555',
                    'patterns' => ["(Â§|\\?)8.+"]

                ],

                [

                    'color' => '#55F',
                    'patterns' => ["(Â§|\\?)9.+/"]

                ],

                [

                    'color' => '#5F5',
                    'patterns' => ["(Â§|\\?)a.+"]

                ],

                [

                    'color' => '#5FF',
                    'patterns' => ["(Â§|\\?)b.+"]

                ],

                [

                    'color' => '#f55',
                    'patterns' => ["(Â§|\\?)c.+"]

                ],

                [

                    'color' => '#F5F',
                    'patterns' => ["(Â§|\\?)d.+"]

                ],

                [

                    'color' => '#FF0',
                    'patterns' => ["(Â§|\\?)e.+"]

                ],

                [

                    'color' => '#2aa198',
                    'patterns' => ["([0-9]{1,2}\\.[0-9]{1,2} )?([0-9]{2}:){2}[0-9]{2}"]

                ],

                [

                    'color' => '#93a1a1',
                    'patterns' => ["Player .+connected.+ /g }, // Connect/disconnect"]

                ],

                [

                    'color' => '#2aa198',
                    'patterns' => ["(Server (\\w+ )?(t|T)hread/)?INFO"]

                ],

                [

                    'color' => '#cb4b16',
                    'patterns' => ["(Server (\\w+ )?(t|T)hread/)?(ERROR|WARN(ING)?)"]

                ],

                [

                    'color' => '#d33682',
                    'patterns' => ["(Server (\\w+ )?(t|T)hread/)?SEVERE"]

                ]

            ];

        }

        public function isAvailable(): bool {

            return true;

        }

    }

?>
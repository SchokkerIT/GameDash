<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Process;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Infrastructure\Node\FileSystem\Enums\FileTypes as FileTypesEnum;

    class Process extends Template\Service\Process\Process {

        private $Gateway;
        private $Instance;
        private $Process;
        private $Node;
        private $Settings;
        private $FileSystem;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')
                ->getValue() );
            $this->Process = $this->Instance->getProcess();
            $this->Node = $this->Instance->getNode();
            $this->Settings = $this->Instance->getSettings();
            $this->FileSystem = $this->Instance->getFileSystem();

        }

        public function start(): void {

            if( !$this->Settings->get('process_command_start')->exists() ) {

                throw new \Exception('Start command does not exist');

            }

            if( !$this->eulaExists() || !$this->eulaIsApproved() ) {

                $this->generateEula();

            }

            $Thread = $this->Node->getChildProcess()->getThreads()->create();

            $Command = $Thread->getHelpers()->getCommand(

                $this->Gateway->getHelpers()->get('parseCommand')->execute([

                    $this->Settings->get('process_command_start')->getValue(),

                    [

                        'ram' => $this->Settings->get('ram')->getValue(),
                        'jar' => $this->Settings->get('jar')->getValue()

                    ]

                ])

            );

            //$Thread->getUsage()->getCpu()->getLimit()->set( 35 );

            $this->Gateway->getHelpers()->get('spawnThread')->execute([ $Thread, $Command->getExecutable(),
                $Command->getArgs() ]);

        }

        public function stop(): void {

            $Console = $this->Instance->getConsole();

            $Console->sendInput('say Server will shut down in 5 seconds');

            $Console->sendInput('save-all');

            //sleep(5);

            $Thread = $this->Process->getThreads()->getCurrent();

            $Thread->getTermination()->terminate();

        }

        public function restart(): void {}

        public function isOnline(): bool {

            return $this->Process->hasId() && $this->Node->getChildProcess()->getThreads()->get( $this->Process->getId() )->exists();

        }

        private function generateEula(): void {

            if( !$this->eulaExists() ) {

                $Query = $this->FileSystem->createQuery( FileTypesEnum::file() );

                $Query->getPaths()->create('path', new FFI\Instance\FileSystem\Query\Path\Generic(

                    $this->Instance, 'eula.txt', FileTypesEnum::file()

                ));

                $Query->execute('create');

            }

            $Eula = $this->FileSystem->getConfigEditor()->getFiles()->get('eula.txt');

            $Eula->getSettings()->get('eula')->setValue('true');

            $Eula->getSettings()->commit();

        }

        private function eulaExists(): bool {

            $Query = $this->FileSystem->createQuery( FileTypesEnum::file() );

            $Query->getPaths()->create('path', new FFI\Instance\FileSystem\Query\Path\Generic(

                $this->Instance, 'eula.txt', FileTypesEnum::file()

            ));

            return $Query->execute('exists');

        }

        private function eulaIsApproved(): bool {

            $Eula = $this->FileSystem->getConfigEditor()->getFiles()->get('eula.txt');

            return $Eula->getSettings()->get('eula')->exists() && $Eula->getSettings()->get('eula')->getValue() === 'true';

        }

    }

?>
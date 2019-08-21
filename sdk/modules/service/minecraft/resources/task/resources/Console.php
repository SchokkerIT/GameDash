<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Task\Resources;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Instance\Task\Parameter\Parameters;

    class Console extends Template\Service\Task\Resource {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var FFI\Instance\Instance */
        private $Instance;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance(

                $this->Gateway->getParameters()->get('instance.id')->getValue()

            );
        }

        public function getTitle(): string {

            return 'Console';

        }

        public function getDescription(): string {

            return 'Execute a console command at the specified time(s)';

        }

        public function execute( Parameters $Parameters ): void {

            $this->Instance->getConsole()->sendInput( $Parameters->get('input')->getValue() );

        }

        public function getExecutionCooldown(): int {

            return 0;

        }

        public function getAvailableParameters(): array {

            return [

                $this->Gateway->getHelpers()->get('createAvailableParameter')->execute(['input', 'Input'])
                    ->setIsRequired( true )

            ];

        }

    }

?>
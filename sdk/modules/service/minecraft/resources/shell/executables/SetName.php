<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Shell\Executables;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Instance\Shell\Executable\Result;

    class SetName extends Template\Service\Shell\Executable\Executable {

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

        public function getDescription(): string {

            return 'Set the name of the instance';

        }

        public function execute(): ?Result {

            $Result = new Result;

            $Result->setOutput([ 'error' ]);

            return $Result;

        }

    }

?>
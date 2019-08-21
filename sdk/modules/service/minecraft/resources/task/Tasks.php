<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Task;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;

    class Tasks extends Template\Service\Task\Tasks {

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

        public function getResources(): array {

            return [

                new Resources\Console( $this->Gateway ),
                new Resources\Backup( $this->Gateway )

            ];

        }

    }

?>
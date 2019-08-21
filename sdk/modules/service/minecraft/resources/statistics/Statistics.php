<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\Statistics;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;
    use \Electrum\Userland\Sdk\FFI\Instance;
    use \Electrum\Userland\Sdk\FFI\Instance\Network;
    use \xPaw\MinecraftPing;

    class Statistics extends Template\Service\Statistics\Statistics {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var Instance\Instance */
        private $Instance;

        /** @var Network\Network */
        private $Network;

        /** @var MinecraftPing */
        private $Connection;

        private $queryResult;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );

            $this->Network = $this->Instance->getNetwork();

            $this->Connection = new MinecraftPing( $this->Network->getIp(), $this->Network->getPort()->get() );

        }

        public function countConnectedClients(): int {

            return $this->query()['players']['online'];

        }

        public function countMaxConnectedClients(): int {

            return $this->query()['players']['max'];

        }

        private function query() {

            if( !$this->queryResult ) {

                $this->queryResult = $this->Connection->query();

            }

            return $this->queryResult;

        }

    }

?>
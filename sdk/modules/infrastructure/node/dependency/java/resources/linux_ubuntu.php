<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Infrastructure\Node\Dependency\Java\Resources;

    use \Electrum\Utilities;
    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Infrastructure;

    class Linux_Ubuntu extends Template\Infrastructure\Node\Dependency\Dependency {

        private $Gateway;
        private $Node;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Node = new Infrastructure\Node\Node( $this->Gateway->getParameters()->get('node.id')->getValue() );

        }

        public function install(): void {

            $Thread = $this->Node->getProcesses()->getThreads()->create();

            $Thread->spawn(

                'apt-get',

                [

                    '--yes',

                    '--force-yes',

                    'install',

                    'default-jdk'

                ],

                [

                    'await' => true

                ]

            );

        }

        public function uninstall(): void {

            $Thread = $this->Node->getProcesses()->getThreads()->create();

            $Thread->spawn(

                'apt-get',

                [

                    '--yes',

                    '--force-yes',

                    'remove',

                    'default-jdk'

                ],

                [

                    'await' => true

                ]

            );

            $Thread->spawn(

                'apt',

                [

                    '--yes',

                    '--force-yes',

                    'autoremove'

                ],

                [

                    'await' => true

                ]

            );

        }

        public function isAvailable(): bool {

            return $this->Node->getOperatingSystems()->getCurrent()->getName() === 'linux_ubuntu';

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Infrastructure\Node\Dependency\LibC\Resources;

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

            $Command = $Thread->getHelpers()->getCommand('apt-get --yes --force-yes install lib32gcc1 libc6-dbg gdb valgrind lib32stdc++6');

            $Thread->spawn(

                $Command->getExecutable(),

                $Command->getArgs(),

                [

                    'await' => true

                ]

            );

        }

        public function uninstall(): void {

            $Thread = $this->Node->getProcesses()->getThreads()->create();

            $Command = $Thread->getHelpers()->getCommand('apt-get --yes --force-yes remove lib32gcc1 libc6-dbg gdb valgrind lib32stdc++6');

            $Thread->spawn(

                $Command->getExecutable(),

                $Command->getArgs(),

                [

                    'await' => true

                ]

            );

            $Command = $Thread->getHelpers()->getCommand('apt --yes --force-yes autoremove');

            $Thread->spawn(

                $Command->getExecutable(),

                $Command->getArgs(),

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
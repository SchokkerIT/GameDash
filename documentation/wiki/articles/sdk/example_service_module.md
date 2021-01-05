## Example service module

All of our official service modules can be found on our Github repository. This is a great resource to learn how the GameDash SDK works in practice as it supports many types of different games.

https://github.com/SchokkerIT/GameDash

### Example resource

```php
<?php

    namespace GameDash\Sdk\Module\Implementation\Service\Minecraft\Resources\Process;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \GameDash\Sdk\Module\Template;
    use \GameDash\Sdk\FFI\Instance;
    use \GameDash\Sdk\FFI\Infrastructure\Node\Process\ChildProcess\ChildProcessNotFoundException;
    use \GameDash\Sdk\FFI\Infrastructure\Node\Process\ChildProcess\ChildProcessNotRunningException;

    class Process extends Template\Service\Process\Process {

        /** @var Instance\Instance */
        private $Instance;

        public function __construct( Gateway\Gateway $Gateway ) {

            //instance id fetched from gateway
            $instanceId = $Gateway->getParameters()->get('instance.id')->getValue();

            //storing the instance object in a class parameter so we can access it from outside of the constructor
            $this->Instance = Instance\Instances::get( $instanceId );

        }

        public function start(): void {

            //create a default child process object. Sets default values such as the instances' relay channel
            $ChildProcess = $this->Instance->getProcess()->getChildProcesses()->createDefault();

            //set the executable
            $ChildProcess->setExecutable('java');

            //set the args
            $ChildProcess->setArgs([

                '-jar',
                //get the jar file from the instances' settings. This setting was created during setup
                $this->Instance->getSettings()->get('jar')->getValue(),
                'nogui'

            ]);

            $ChildProcess->spawn();

        }

        public function stop(): void {

            $Console = $this->Instance->getConsole();
    
            //send input to the console
            $Console->getIo()->getInput()->send('say Server will shut down in 5 seconds');

            $Console->getIo()->getInput()->send('save-all');

            try {

                $ChildProcess = $this->Process->getChildProcesses()->getCurrent();

                //stop the child process associated with this instance
                $ChildProcess->stop();

            }
            catch( ChildProcessNotRunningException $e ) {}

        }

        public function restart(): void {}

        public function isOnline(): bool {

            //if the instance does not have a process id there's obviously no process to to check the status of
            if( !$this->Process->hasId() ) {

                return false;

            }

            try {

                //check if the child process has exited yet
                return !$this->Node->getProcesses()->getChildProcesses()->get( $this->Process->getId() )->hasExited();

            }
            catch( ChildProcessNotFoundException $e ) {
    
                //return false if the child process could not be found
                return false;

            }

        }

        //mark the process usage as measurable
        public function usageIsMeasurable(): bool {

            return true;

        }

    }

?>
```

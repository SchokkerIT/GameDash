<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\SteamCmd\Lib\App;

    use function \_\find;
    use \Electrum\Json\Json;
    use \Electrum\Comparable\Comparable;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\FileSystem\File\File;

    class App implements Comparable {

        /** @var int */
        private $id;

        public function __construct( int $id ) {

            $this->id = $id;

        }

        public function getId(): int {

            return $this->id;

        }

        public function getTitle(): string {

            return $this->read()['title'];

        }

        public function getPublicId(): string {

            return $this->read()['publicId'];

        }

        public function getArgs(): array {

            $read = $this->read();

            if( !isset( $read['args'] ) ) {

                return [];

            }

            return $read['args'];

        }

        public function compare( $value ): bool {

            return $value instanceof App && $value->getId() === $this->getId();

        }

        private function read(): array {

            return find(Json::decode( ( new File( dirname(__FILE__) . '/apps.json' ) )->read() ), function( $app ) {

                return $app['id'] === $this->getId();

            });

        }

    }

?>
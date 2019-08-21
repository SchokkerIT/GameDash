<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\SteamCmd\Lib\App;

    use function \_\map;
    use function \_\find;
    use \Electrum\Json\Json;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI\FileSystem\File\File;

    class Apps {

        /** @return App[] */
        public static function getAll(): array {

            return map(Json::decode( ( new File( dirname(__FILE__) . '/apps.json' ) )->read() ), function( $app ) {

                return self::get( $app['id'] );

            });

        }

        public static function get( string $id ): App {

            return new App( $id );

        }

        public static function exists( string $id ): bool {

            return find(self::getAll(), function( App $App ) use ( $id ): bool {

                return (string)$App->getId() === (string)$id;

            }) != null;

        }

    }

?>
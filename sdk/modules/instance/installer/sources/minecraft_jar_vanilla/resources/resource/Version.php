<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Minecraft_Jar_Vanilla\Resources\Resource;

    use function \_\find;
    use \Electrum\Uri\Uri;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Minecraft_Jar_Vanilla\Lib\Api\Client\Client as ApiClient;

    class Version extends Template\Instance\Installer\Source\Resource\Version {

        public function getUri(): Uri {

            $Request = ApiClient::createRequest(HttpMethodsEnum::get(), Uri::fromString( 'https://launchermeta.mojang.com/mc/game/version_manifest.json' ));

            $Request->send();

            $results = $Request->getResponse()->getJson();

            return Uri::fromString(

                find($results['versions'], function( $version ) {

                    return $version['id'] === $this->getId();

                })['url']

            );

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Instance\Installer\Sources\Minecraft_Jar_Vanilla\Lib\Api\Client;

    use \Electrum\Http;
    use \Electrum\Uri\Uri;
    use \Electrum\Enums\Network\Http\Protocols as HttpProtocolsEnum;
    use \Electrum\Enums\Network\Http\Methods as HttpMethodsEnum;

    class Client {

        public static function createRequest( HttpMethodsEnum $Method, Uri $Uri ): Http\Client\Request {

            return Http\Client\Client::createRequest($Method, $Uri);

        }

    }

?>
<?php

    namespace Electrum\Userland\Sdk\Module\Installed\Service\Minecraft\Resources\FileSystem\ConfigEditor;

    use \Electrum\Userland\Sdk\Module\Gateway;
    use \Electrum\Userland\Sdk\Module\Template;
    use \Electrum\Userland\Sdk\FFI;

    class ConfigEditor extends Template\Service\FileSystem\ConfigEditor\ConfigEditor {

        /** @var Gateway\Gateway */
        private $Gateway;

        /** @var FFI\Instance\Instance */
        private $Instance;

        public function __construct( Gateway\Gateway $Gateway ) {

            $this->Gateway = $Gateway;

            $this->Instance = new FFI\Instance\Instance( $this->Gateway->getParameters()->get('instance.id')->getValue() );

        }

        public function getPaths(): array {

            return [

                new FFI\Instance\FileSystem\Path\Path($this->Instance, '*.properties')

            ];

        }

        public function getEols(): array {

            return [ '{default}' ];

        }

        public function getSeparators(): array {

            return [

                '=',
                '{whitespace}={whitespace}'

            ];

        }

        public function getIgnoredCharacters(): array {

            return [ '#' ];

        }

        public function getFormatting(): array {

            return [

                'snooper-enabled' => [

                    'description' => 'This is a test',

                    'options' => [

                        [

                            'name' => 'Enabled',
                            'value' => 'true'

                        ],

                        [

                            'name' => 'Disabled',
                            'value' => 'false'

                        ]

                    ]

                ]

            ];

        }

    }

?>
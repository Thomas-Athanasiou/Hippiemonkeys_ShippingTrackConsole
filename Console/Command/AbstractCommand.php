<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2023 Hippiemonkeys Web Inteligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ShippingTrackConsole
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ShippingTrackConsole\Console\Command;

    use Psr\Log\LoggerInterface,
        Symfony\Component\Console\Command\Command as SymfonyCommand,
        Symfony\Component\Console\Input\InputInterface,
        Symfony\Component\Console\Output\OutputInterface,
        Magento\Framework\App\State,
        Magento\Framework\App\Area;

    abstract class AbstractCommand
    extends SymfonyCommand
    {
        protected const
            RESULT_SUCCESS = 0,
            RESULT_FAILURE = 1,
            RESULT_INVALID = 2;

        /**
         * Executes the command but may throw an exception (user code)
         *
         * @api
         * @access public
         *
         * @param \Symfony\Component\Console\Input\InputInterface $input
         * @param \Symfony\Component\Console\Output\OutputInterface $output
         *
         * @throws \Exception
         *
         * @return int
         */
        protected abstract function executeInternal(InputInterface $input, OutputInterface $output): int;

        /**
         * Constructor
         *
         * @access public
         *
         * @param string $name
         * @param string $description
         * @param \Psr\Log\LoggerInterface $logger
         * @param \Magento\Framework\App\State $state
         */
        public function __construct(string $name, string $description, LoggerInterface $logger, State $state)
        {
            $this->_logger = $logger;
            $this->_state = $state;
            $this->setDescription($description);
            parent::__construct($name);
        }

        /**
         * @inheritdoc
         */
        public function execute(InputInterface $input, OutputInterface $output): int
        {
            $result = self::RESULT_SUCCESS;

            try
            {
                $this->getState()->setAreaCode(Area::AREA_GLOBAL);
                $result = $this->executeInternal($input, $output);
            }
            catch (\Exception $exception)
            {
                $message = $exception->getMessage();
                $this->getLogger()->error($message);
                $output->writeln($message);
                $result = static::RESULT_FAILURE;
            }

            return $result;
        }

        /**
         * Logger property
         *
         * @access private
         *
         * @var \Psr\Log\LoggerInterface $_logger
         */
        private $_logger;

        /**
         * Gets Logger
         *
         * @access protected
         *
         * @return \Psr\Log\LoggerInterface
         */
        protected function getLogger(): LoggerInterface
        {
            return $this->_logger;
        }

        /**
         * State property
         *
         * @access private
         *
         * @var \Magento\Framework\App\State $_state
         */
        private $_state;

        /**
         * Gets State
         *
         * @access protected
         *
         * @return \Magento\Framework\App\State
         */
        protected function getState(): State
        {
            return $this->_state;
        }
    }
?>
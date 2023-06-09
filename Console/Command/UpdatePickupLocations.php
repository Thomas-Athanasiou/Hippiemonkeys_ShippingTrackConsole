<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2023 Hippiemonkeys Web Intelligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ShippingTrackConsole
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ShippingTrackConsole\Console\Command;

    use Psr\Log\LoggerInterface,
        Symfony\Component\Console\Input\InputInterface,
        Symfony\Component\Console\Output\OutputInterface,
        Magento\Framework\App\State,
        Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface;

    class UpdatePickupLocations
    extends AbstractCommand
    {
        /**
         * Constructor.
         *
         * @access public
         *
         * @param string $name
         * @param string $description
         * @param \Psr\Log\LoggerInterface $logger
         * @param \Magento\Framework\App\State $state
         * @param \Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface $pickuplocationManagement
         */
        public function __construct(
            string $name,
            string $description,
            LoggerInterface $logger,
            State $state,
            PickupLocationManagementInterface $pickuplocationManagement
        )
        {
            parent::__construct($name, $description, $logger, $state);
            $this->_pickuplocationManagement = $pickuplocationManagement;
        }

        /**
         * @inheritdoc
         */
        protected function executeInternal(InputInterface $input, OutputInterface $output): int
        {
            $this->getPickupLocationManagement()->updatePickupLocations();
            return self::RESULT_SUCCESS;
        }

        /**
         * Pickup Location Management property
         *
         * @access private
         *
         * @var \Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface $_pickuplocationManagement
         */
        private $_pickuplocationManagement;

        /**
         * Gets Pickup Location Management
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ShippingTrack\Api\PickupLocationManagementInterface $_pickuplocationManagement
         */
        protected function getPickupLocationManagement(): PickupLocationManagementInterface
        {
            return $this->_pickuplocationManagement;
        }
    }
?>
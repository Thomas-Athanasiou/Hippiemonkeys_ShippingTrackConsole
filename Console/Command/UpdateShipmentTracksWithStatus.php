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
        Symfony\Component\Console\Input\InputArgument,
        Symfony\Component\Console\Input\InputInterface,
        Symfony\Component\Console\Output\OutputInterface,
        Magento\Framework\App\State,
        Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface,
        Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface;

    class UpdateShipmentTracksWithStatus
    extends AbstractCommand
    {
        protected const
            ARGUMENT_STATUS = 'status',
            ARGUMENT_LIMIT = 'limit',

            KEYWORD_ALL = 'all';

        /**
         * Constructor
         *
         * @access public
         *
         * @param string $name
         * @param string $description
         * @param \Psr\Log\LoggerInterface $logger
         * @param \Magento\Framework\App\State $state
         * @param \Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface $statusRepository
         * @param \Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface $shipmenttrackManagement
         * @param int $status
         */
        public function __construct(
            string $name,
            string $description,
            LoggerInterface $logger,
            State $state,
            StatusRepositoryInterface $statusRepository,
            ShipmentTrackManagementInterface $shipmentTrackManagement
        )
        {
            parent::__construct($name, $description, $logger, $state);
            $this->_statusRepository = $statusRepository;
            $this->_shipmentTrackManagement = $shipmentTrackManagement;
        }

        /**
         * @inheritdoc
         */
        public function executeInternal(InputInterface $input, OutputInterface $output): int
        {
            $result = self::RESULT_SUCCESS;

            $limitArgument = (string) $input->getArgument(static::ARGUMENT_LIMIT);
            $statusArgument = (string) $input->getArgument(static::ARGUMENT_STATUS);

            $limit = (int) $limitArgument;
            $status = $this->getStatusRepository()->getByCode($statusArgument);

            $shipmentTrackManagement = $this->getShipmentTrackManagement();
            if($limitArgument === static::KEYWORD_ALL)
            {
                $shipmentTrackManagement->updateShipmentTracksWithStatus($status);
            }
            else if(is_numeric($limitArgument) && $limit > 0)
            {
                $shipmentTrackManagement->updateShipmentTracksWithStatusAndLimit($status, $limit);
            }
            else
            {
                $output->writeln(__('Invalid limit "%1" specified', $limitArgument));
                $result = self::RESULT_FAILURE;
            }

            return $result;
        }

        /**
         * @inheritdoc
         */
        protected function configure(): void
        {
            $this->addArgument(static::ARGUMENT_STATUS, InputArgument::REQUIRED, 'Status');
            $this->addArgument(static::ARGUMENT_LIMIT, InputArgument::REQUIRED, 'Limit');
        }

        /**
         * Status Repository property
         *
         * @access private
         *
         * @var \Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface $_statusRepository
         */
        private $_statusRepository;

        /**
         * Gets Status Repository
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ShippingTrack\Api\StatusRepositoryInterface $_statusRepository
         */
        protected function getStatusRepository(): StatusRepositoryInterface
        {
            return $this->_statusRepository;
        }

        /**
         * Shipment Track Management property
         *
         * @access private
         *
         * @var \Hippiemonkeys\ShippingTrack\Api\ShipmentTrackManagementInterface $_shipmentTrackManagement
         */
        private $_shipmentTrackManagement;

        /**
         * Gets Shipment Track Management
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ShippingTrack\Api\\ShipmentTrackManagementInterface $_shipmentTrackManagement
         */
        protected function getShipmentTrackManagement(): ShipmentTrackManagementInterface
        {
            return $this->_shipmentTrackManagement;
        }
    }
?>
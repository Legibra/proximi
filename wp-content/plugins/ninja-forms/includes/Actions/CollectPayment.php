<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Action_CollectPayment
 */
final class NF_Actions_CollectPayment extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'collectpayment';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
     * @var string
     */
    protected $_timing = 'late';

    /**
     * @var int
     */
    protected $_priority = 0;

    /**
     * @var array
     */
    protected $payment_gateways = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Collect Payment', 'ninja-forms' );

        $settings = Ninja_Forms::config( 'ActionCollectPaymentSettings' );

        $this->_settings = array_merge( $this->_settings, $settings );

        add_action( 'ninja_forms_loaded', array( $this, 'register_payment_gateways' ), -1 );

        add_filter( 'ninja_forms_action_type_settings', array( $this, 'maybe_remove_action' ) );
    }

    public function save( $action_settings )
    {

    }

    public function process( $action_settings, $form_id, $data )
    {
        
        $payment_gateway = $action_settings[ 'payment_gateways' ];

        $payment_gateway_class = $this->payment_gateways[ $payment_gateway ];

        /*
         * Get our payment total.
         *
         * If we have selected "Calc" as our total type, then we want to use payment_total_calc
         *
         * If we have selected "Field" as our total type, then we want to use payment_total_field
         *
         * If we have selected "Custom" as our total type, then we want to use payment_total_custom
         */
        $total_type = isset( $action_settings[ 'payment_total_type' ] ) ? $action_settings[ 'payment_total_type' ] : 'payment_total_custom';

        switch ( $total_type ) {
            case 'calculation':
                $payment_total = $action_settings[ 'payment_total_calc' ];
                break;
            case 'field':
                $payment_total = $action_settings[ 'payment_total_field' ];
                break;
            case 'custom':
                $payment_total = $action_settings[ 'payment_total_custom' ];
                break;
            default:
                $payment_total = $action_settings[ 'payment_total_custom' ];
                break;
        }

        return $payment_gateway_class->process( $action_settings, $form_id, $data, $payment_total );
    }

    public function register_payment_gateways()
    {
        $this->payment_gateways = apply_filters( 'ninja_forms_register_payment_gateways', array() );

        foreach( $this->payment_gateways as $gateway ){

            if( ! is_subclass_of( $gateway, 'NF_Abstracts_PaymentGateway' ) ){
                continue;
            }

            $this->_settings[ 'payment_gateways' ][ 'options' ][] = array(
                'label' => $gateway->get_name(),
                'value' => $gateway->get_slug(),
            );

            $this->_settings = array_merge( $this->_settings, $gateway->get_settings() );
        }
    }

    public function maybe_remove_action( $action_type_settings )
    {
        if( empty( $this->payment_gateways ) ){
            unset( $action_type_settings[ $this->_name ] );
        }

        return $action_type_settings;
    }
}

<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Field_File
 */
class NF_Fields_File extends NF_Abstracts_Input
{
    protected $_name = 'textbox';

    protected $_section = 'common';

    protected $_icon = 'text-width';

    protected $_aliases = array( 'input' );

    protected $_type = 'file';

    protected $_templates = 'textbox';

    protected $_test_value = 'Lorem ipsum';

    protected $_settings = array( 'disable_browser_autocomplete', 'mask', 'custom_mask' );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Single Line Text', 'ninja-forms' );
    }
}

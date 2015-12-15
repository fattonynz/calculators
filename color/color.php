<?php
class color
  {
    public $output;
    private $current_cal;
    private $value;
    public $response;
    public $data;
    function __construct( )
      {
        $this->response             = array( );
        $this->response[ 'result' ] = 'ok';
        if ( isset( $_POST[ 'from_field' ] ) AND $_POST[ 'from_field' ] != '' )
          {
            $this->current_cal = urlencode( stripslashes( $_POST[ 'from_field' ] ) );
          }
        else
          {
            $this->response[ 'result' ]     = 'error';
            $this->response[ 'error_desc' ] = 'Not enough parameters provided';
          }
        if ( isset( $_POST[ 'value' ] ) AND $_POST[ 'value' ] != '' )
          {
            $this->value = urlencode( stripslashes( $_POST[ 'value' ] ) );
          }
        else
          {
            $this->response[ 'result' ]     = 'error';
            $this->response[ 'error_desc' ] = 'Not enough parameters provided';
          }
        if ( $this->response[ 'result' ] == 'error' )
          {
            echo json_encode( $this->response );
            exit( );
          }
      }
    public function do_calculation( )
      {
        if ( is_nan( $this->value ) )
          {
            exit;
          }
        $data = array( );
        switch ( $this->current_cal )
        {
            case 'srm':
                $this->data[ 'srm' ]      = $this->value;
                $this->data[ 'lovibond' ] = ( $this->value + 0.76 ) / 1.3546;
                $this->data[ 'ebc' ]      = $this->value * 1.97;
                break;
            case 'lovibond':
                $this->data[ 'srm' ]      = ( 1.3546 * $this->value ) - 0.76;
                $this->data[ 'lovibond' ] = $this->value;
                $this->data[ 'ebc' ]      = ( ( 1.3546 * $this->value ) - 0.76 ) * 1.97;
                break;
            case 'ebc':
                $this->data[ 'srm' ]      = $this->value * 0.508;
                $this->data[ 'lovibond' ] = ( ( $this->value * 0.508 ) + 0.76 ) / 1.3546;
                $this->data[ 'ebc' ]      = $this->value;
                break;
        }
      }
  }
$color = new color();
$color->do_calculation();
$data               = $color->data;
$response           = $color->response;
$response[ 'data' ] = $data;
echo json_encode( $response );
?>
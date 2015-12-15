<?php
class Alcohol
  {
    public $output;
    private $gravity;
    private $brix;
    function __construct( )
      {
        $this->output = array( );
        if ( isset( $_POST[ 'gravity' ] ) AND $_POST[ 'gravity' ] !== "" )
          {
            $this->gravity = urlencode( stripslashes( $_POST[ 'gravity' ] ) );
            $this->gravity_to_brix();
          }
        if ( isset( $_POST[ 'brix' ] ) AND $_POST[ 'brix' ] !== "" )
          {
            $this->brix = urlencode( stripslashes( $_POST[ 'brix' ] ) );
            $this->brix_to_gravity();
          }
        if ( isset( $_POST[ 'ob' ] ) AND isset( $_POST[ 'mb' ] ) )
          {
            $mb = urlencode( stripslashes( $_POST[ 'mb' ] ) );
            $ob = urlencode( stripslashes( $_POST[ 'ob' ] ) );
            $this->b_f_g( $ob, $mb );
          }
        if ( isset( $_POST[ 'action' ] ) AND $_POST[ 'action' ] == "ab" )
          {
            $og = urlencode( stripslashes( $_POST[ 'og' ] ) );
            $fg = urlencode( stripslashes( $_POST[ 'fg' ] ) );
            $this->calculate_alcohol( $og, $fg );
          }
      }
    function gravity_to_brix( )
      {
        if ( is_nan( $this->gravity ) )
          {
            exit;
          }
        $this->output[ 'g_to_b' ] = ( ( 182.4601 * $this->gravity - 775.6821 ) * $this->gravity + 1262.7794 ) * $this->gravity - 669.5622;
      }
    function brix_to_gravity( )
      {
        if ( is_nan( $this->brix ) )
          {
            exit;
          }
        $this->output[ 'b_to_g' ] = ( $this->brix / ( 258.6 - ( $this->brix / 258.2 * 227.1 ) ) ) + 1;
      }
    function b_f_g( $ob, $mb )
      {
        $this->output[ 'bfg' ] = 1.001843 - 0.002318474 * $ob - 0.000007775 * $ob * $ob - 0.000000034 * $ob * $ob * $ob + 0.00574 * $mb + 0.00003344 * $mb * $mb + 0.000000086 * $mb * $mb * $mb;
      }
    function calculate_alcohol( $og, $fg )
      {
        $abv                   = ( $og - $fg ) * 131;
        $abv                   = sprintf( '%.01F', $abv ) + ' %';
        $this->output[ 'abv' ] = $abv;
        $abw                   = $abv * 0.79336;
        $abw                   = sprintf( '%.01F', $abw ) + ' %';
        $this->output[ 'abw' ] = $abw;
      }
  }
$alcohol              = new Alcohol();
$response             = array( );
$response[ 'result' ] = 'ok';
$response[ 'data' ]   = $alcohol->output;
echo json_encode( $response );
?>
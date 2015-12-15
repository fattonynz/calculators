<?php
class temperature
  {
    private $sg;
    private $mt;
    private $cts;
    public $csg;
    public $tmp_unit;
    public $output;
    public $unit;
    public $ag;
    function __construct( )
      {
        $action = $_REQUEST[ 'action' ];
        if ( $action == "convert" AND $_REQUEST[ 'c_to' ] == "c" )
          {
            $this->f_to_c( $_REQUEST[ 'mt' ], $_REQUEST[ 'cts' ] );
            exit;
          }
        if ( $action == "convert" AND $_REQUEST[ 'c_to' ] == "f" )
          {
            $this->c_to_f( $_REQUEST[ 'mt' ], $_REQUEST[ 'cts' ] );
            exit;
          }
        $this->sg   = floatval( $_REQUEST[ 'sg' ] );
        $this->mt   = $_REQUEST[ 'mt' ];
        $this->cts  = $_REQUEST[ 'cts' ];
        $this->unit = $_REQUEST[ 'unit' ];
        if ( $this->unit == "c" )
          {
            $this->mt  = ( 9 / 5 ) * $this->mt + 32;
            $this->cts = ( 9 / 5 ) * $this->cts + 32;
          }
        if ( $action == "cal" )
          {
            $this->cal();
          }
      }
    function cal( )
      {
        $kk          = $this->sg * ( ( 1.00130346 - 0.000134722124 * $this->mt + 0.00000204052596 * pow( $this->mt, 2 ) - 0.00000000232820948 * pow( $this->mt, 3 ) ) / ( 1.00130346 - 0.000134722124 * $this->cts + 0.00000204052596 * pow( $this->cts, 2 ) - 0.00000000232820948 * pow( $this->cts, 3 ) ) );
        $has_decimal = $kk != intval( $kk );
        if ( $has_decimal )
          {
            $kk = number_format( $kk, 3 );
          }
        $this->ag = $kk;
      }
    function c_to_f( $mt, $cts )
      {
        $mt                         = ( 9 / 5 ) * $mt + 32;
        $cts                        = ( 9 / 5 ) * $cts + 32;
        $data                       = array( );
        $data[ 'mt' ]               = $mt;
        $data[ 'cts' ]              = $cts;
        $this->response             = array( );
        $this->response[ 'result' ] = 'ok';
        $response[ 'data' ]         = $data;
        echo json_encode( $response );
      }
    function f_to_c( $mt, $cts )
      {
        $mt                         = ( $mt - 32 ) * 5 / 9;
        $cts                        = ( $cts - 32 ) * 5 / 9;
        $data                       = array( );
        $data[ 'mt' ]               = $mt;
        $data[ 'cts' ]              = $cts;
        $this->response             = array( );
        $this->response[ 'result' ] = 'ok';
        $response[ 'data' ]         = $data;
        echo json_encode( $response );
      }
  }
$temp            = new temperature();
$res             = array( );
$res[ 'status' ] = "ok";
$d[ 'ag' ]       = $temp->ag;
$res[ 'data' ]   = $d;
echo json_encode( $res );
?>
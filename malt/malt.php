<?php
class malt
  {
    private $cg;
    private $fg;
    private $points;
    private $potential;
    public $output;
    function __construct( )
      {
        $this->output             = array( );
        $this->output[ 'result' ] = "ok";
        if ( isset( $_POST[ 'cg' ] ) AND $_POST[ 'cg' ] !== "" )
          {
            $this->cg = floatval( urlencode( stripslashes( $_POST[ 'cg' ] ) ) );
          }
        if ( isset( $_POST[ 'fg' ] ) AND $_POST[ 'fg' ] !== "" )
          {
            $this->fg = floatval( urlencode( stripslashes( $_POST[ 'fg' ] ) ) );
          }
        if ( $this->cg !== NULL AND $this->fg !== NULL )
          {
            $this->cal();
          }
      }
    function cal( )
      {
        $ag                     = ( $this->cg + $this->fg ) / 2;
        $this->points           = round( ( $ag / 100 ) * 46 );
        $this->potential        = number_format( ( ( $this->points / 1000 ) + 1 ), 3 );
        $data                   = array( );
        $data[ 'points' ]       = $this->points;
        $data[ 'potential' ]    = $this->potential;
        $this->output[ 'data' ] = $data;
      }
  }
$malt = new malt();
echo json_encode( $malt->output );
?>
<?php
class yeast
  {
    public $type;
    public $yeast_types;
    function __construct( )
      {
        $this->type        = array(
             "Ale" => 750000,
            "Larger" => 1500000 
        );
        $data              = array( );
        $data[ 'result' ]  = "okay";
        $ss                = array( );
        $ss[ 'num' ]       = $this->num_cells();
        $ss[ 'num_liq' ]   = $this->numLiquid();
        $ss[ 'grams_dry' ] = $this->gramsDry();
        $kk                = $ss;
        $data[ 'data' ]    = $kk;
        echo json_encode( $data );
      }
    function num_cells( )
      {
        $mg         = floatval( $_REQUEST[ 'mg' ] );
        $wv         = $_REQUEST[ 'wv' ];
        $unit       = $_REQUEST[ 'unit' ];
        $yeast_type = $_REQUEST[ 'type' ];
        if ( $unit == "us" )
          {
            $wortVolumeMl = $wv * 3785.411784;
          }
        else
          {
            $wortVolumeMl = $wv * 1000;
          }
        $measuredPlato = 259 - ( 259 / $mg );
        $numCells      = ( $this->type[ $yeast_type ] * $wortVolumeMl * $measuredPlato ) * 0.000000001;
        return intval( $numCells );
      }
    function numLiquid( )
      {
        $ret = round( $this->num_cells() / 100 );
        return $ret;
      }
    function gramsDry( )
      {
        $ret = round( $this->num_cells() / 20 );
        return $ret;
      }
  }
$yeast = new yeast();
?>
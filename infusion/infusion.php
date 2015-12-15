<?php
class infusion
  {
    public $output;
    public $const_v;
    public $unit;
    public $water_req;
    private $st;
    private $gw;
    private $thick;
    private $tt;
    private $taow;
    function __construct( )
      {
        $this->output = array( );
        if ( isset( $_POST[ 'unit' ] ) AND $_POST[ 'unit' ] !== "" )
          {
            $this->unit = urlencode( stripslashes( $_POST[ 'unit' ] ) );
          }
        if ( isset( $_POST[ 'st' ] ) AND $_POST[ 'st' ] !== "" )
          {
            $this->st = floatval( urlencode( stripslashes( $_POST[ 'st' ] ) ) );
          }
        if ( isset( $_POST[ 'gw' ] ) AND $_POST[ 'gw' ] !== "" )
          {
            $this->gw = floatval( urlencode( stripslashes( $_POST[ 'gw' ] ) ) );
          }
        if ( isset( $_POST[ 'gtwr' ] ) AND $_POST[ 'gtwr' ] !== "" )
          {
            $this->thick = floatval( urlencode( stripslashes( $_POST[ 'gtwr' ] ) ) );
          }
        if ( isset( $_POST[ 'tt' ] ) AND $_POST[ 'tt' ] !== "" )
          {
            $this->tt = floatval( urlencode( stripslashes( $_POST[ 'tt' ] ) ) );
          }
        if ( isset( $_POST[ 'taow' ] ) AND $_POST[ 'taow' ] !== "" )
          {
            $this->taow = urlencode( stripslashes( $_POST[ 'taow' ] ) );
          }
        if ( $this->unit == "metric" )
          {
            $this->const_v = 0.41;
          }
        else
          {
            $this->const_v = 0.2;
          }
        $this->cal();
      }
    function cal( )
      {
        if ( $this->gw <= 0 | is_nan( $this->gw ) )
          {
            $this->output[ 'res' ] = "Grain weight must be a number greater than 0!";
            return false;
          }
        if ( $this->thick <= 0 | is_nan( $this->thick ) )
          {
            $this->output[ 'res' ] = "Mash thickness must be a number greater than 0!";
            return false;
          }
        if ( $this->st <= 0 | is_nan( $this->st ) )
          {
            $this->output[ 'res' ] = "Current temperature must be a number greater than 0!";
            return false;
          }
        if ( $this->tt <= 0 | is_nan( $this->tt ) )
          {
            $this->output[ 'res' ] = "Target temperature must be a number greater than 0!";
            return false;
          }
        if ( $this->st >= $this->tt )
          {
            $this->output[ 'res' ] = "Target temperature must be greater than the current temperature.";
            return false;
          }
        if ( $this->unit == "us" )
          {
            if ( $this->st > 212 )
              {
                $this->output[ 'res' ] = "Current temperature must be lower than boiling (212F)!";
                return false;
              }
            if ( $this->tt > 212 )
              {
                $this->output[ 'res' ] = "Target temperature must be lower than boiling (212F)!";
                return false;
              }
          }
        else
          {
            if ( $this->st > 100 )
              {
                $this->output[ 'res' ] = "Current temperature must be lower than boiling (100C)!";
                return false;
              }
            if ( $this->tt > 100 )
              {
                $this->output[ 'res' ] = "Target temperature must be lower than boiling (100C)!";
                return false;
              }
          }
        if ( $this->unit == "metric" )
          {
            $vm                    = $this->gw * ( .4 + $this->thick );
            $vs                    = $vm * ( $this->tt - $this->st ) / ( 100 - $this->tt );
            $this->output[ 'res' ] = number_format( $vs, 3 );
          }
        else
          {
            $vm                    = $this->gw * ( .192 + $this->thick );
            $vs                    = $vm * ( $this->tt - $this->st ) / ( 212 - $this->tt );
            $this->output[ 'res' ] = number_format( $vs, 3 );
          }
      }
  }
$infusion         = new infusion();
$resp             = array( );
$resp[ 'status' ] = "ok";
$resp[ 'data' ]   = $infusion->output;
echo json_encode( $resp );
?>
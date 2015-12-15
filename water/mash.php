<?php
class mashwater
  {
    public $thermodynamicConstant;
    public $mashOutTemp;
    public $volumeUnit;
    public $weightUnit;
    public $tempUnit;
    public $metric;
    public $boiling_temp;
    public $output;
    private $grain_weight;
    private $grain_temperature;
    private $boil_volume;
    private $layter_deadspace;
    private $mash_thickness;
    private $boiling_temperature;
    private $grain_absorbtion_factor;
    private $mash_time;
    private $temperature;
    function __construct( )
      {
        $this->output                = array( );
        $this->metric                = false;
        $this->thermodynamicConstant = 0.2;
        $this->mashOutTemp           = 168;
        $this->volumeUnit            = 'gal';
        $this->tempUnit              = '&deg;F';
        if ( $this->metric )
          {
            $this->boiling_temp = 100;
          }
        else
          {
            $this->boiling_temp = 212;
          }
        if ( isset( $_POST[ 'gw' ] ) And $_POST[ 'gw' ] !== "" )
          {
            $this->grain_weight = floatval( urlencode( stripslashes( $_POST[ 'gw' ] ) ) );
          }
        if ( isset( $_POST[ 'gt' ] ) And $_POST[ 'gt' ] !== "" )
          {
            $this->grain_temperature = floatval( urlencode( stripslashes( $_POST[ 'gt' ] ) ) );
          }
        if ( isset( $_POST[ 'bv' ] ) And $_POST[ 'bv' ] !== "" )
          {
            $this->boil_volume = floatval( urlencode( stripslashes( $_POST[ 'bv' ] ) ) );
          }
        if ( isset( $_POST[ 'ld' ] ) And $_POST[ 'ld' ] !== "" )
          {
            $this->layter_deadspace = floatval( urlencode( stripslashes( $_POST[ 'ld' ] ) ) );
          }
        if ( isset( $_POST[ 'mt' ] ) And $_POST[ 'mt' ] !== "" )
          {
            $this->mash_thickness = urlencode( stripslashes( $_POST[ 'mt' ] ) );
          }
        if ( isset( $_POST[ 'bt' ] ) And $_POST[ 'bt' ] !== "" )
          {
            $this->boiling_temperature = floatval( urlencode( stripslashes( $_POST[ 'bt' ] ) ) );
          }
        if ( isset( $_POST[ 'gaf' ] ) And $_POST[ 'gaf' ] !== "" )
          {
            $this->grain_absorbtion_factor = floatval( urlencode( stripslashes( $_POST[ 'gaf' ] ) ) );
          }
        if ( isset( $_POST[ 'mash_time' ] ) And $_POST[ 'mash_time' ] !== "" )
          {
            $this->mash_time = floatval( urlencode( stripslashes( $_POST[ 'mash_time' ] ) ) );
          }
        if ( isset( $_POST[ 't' ] ) And $_POST[ 't' ] !== "" )
          {
            $this->temperature = floatval( urlencode( stripslashes( $_POST[ 't' ] ) ) );
          }
        if ( $this->grain_weight == "" || $this->grain_temperature == "" || $this->boil_volume == "" || $this->layter_deadspace == "" || $this->mash_thickness == "" || $this->boiling_temperature == "" || $this->grain_absorbtion_factor == "" || $this->mash_time == "" || $this->temperature == "" )
          {
            exit;
          }
        if ( isset( $_POST[ 'action' ] ) AND $_POST[ 'action' ] == "Batch-Sparge" )
          {
            $this->output = $this->singleinfusionbatchsparge();
          }
        if ( isset( $_POST[ 'action' ] ) AND $_POST[ 'action' ] == "Mash-Out-Batch-Sparge" )
          {
            $this->output = $this->singleInfusionMashOutBatchSparge();
          }
        if ( isset( $_POST[ 'action' ] ) AND $_POST[ 'action' ] == "Mash-Out-Batch-Sparge-Equal-Runnings" )
          {
            $this->output = $this->singleInfusionMashOutBatchSpargeEqualRunnings();
          }
        if ( isset( $_POST[ 'action' ] ) AND $_POST[ 'action' ] == "Single-Infusion-Two-Equal-Batch-Sparges" )
          {
            $this->output = $this->singleInfusionTwoEqualBatchSparges();
          }
        if ( isset( $_POST[ 'action' ] ) AND $_POST[ 'action' ] == "No-Sparge-BIAB" )
          {
            $this->output = $this->singleInfusionNoSpargeBiab();
          }
        if ( isset( $_POST[ 'action' ] ) AND $_POST[ 'action' ] == "Sparge-Rinse-BIAB" )
          {
            $this->output = $this->singleInfusionSpargeRinseBiab();
          }
      }
    function strikeStep( $mashwater, $striketemp )
      {
        $msg = "Strike grains with \t" . number_format( $mashwater, 2 ) . "" . $this->volumeUnit . "" . "\t of water at \t " . number_format( $striketemp, 1 ) . "" . $this->tempUnit . ".";
        return $msg;
      }
    function mashreststep( $mashtemp, $mashtime )
      {
        $msg = "Mash at \t " . "" . number_format( $mashtemp, 1 ) . "" . $this->tempUnit . " \t for  \t {$mashtime} min .";
        return $msg;
      }
    function vorlaufandlauterstep( $runnings, $which )
      {
        $msg = "Vorlauf and lauter \t " . "" . number_format( $runnings, 2 ) . " " . $this->volumeUnit . " \t  in your  \t {$which}  runnings.";
        return $msg;
      }
    function spargestep( $spargewater, $spargetemp )
      {
        $msg = "Add  \t " . number_format( $spargewater, 2 ) . " " . $this->volumeUnit . "  \t of sparge water at \t{$spargetemp}   {$this->tempUnit}.";
        return $msg;
      }
    function mashoutstep( $mashout, $mashouttemp )
      {
        $msg = "Mash out with \t" . number_format( $mashout, 2 ) . "\t {$this->volumeUnit} \t of water at {$mashouttemp} \t {$this->tempUnit}.";
        return $msg;
      }
    function combinedrunningsstep( $combinedrunnings )
      {
        $msg = "Your combined runnings should be \t" . number_format( $combinedrunnings, 2 ) . "\t {$this->volumeUnit} .";
        return $msg;
      }
    function rinsespargestep( $spargewatervolume )
      {
        $msg = "Rinse the grain in \t" . number_format( $spargewatervolume, 2 ) . "\t {$this->volumeUnit} \t sparge water at {$this->mashOutTemp} \t {$this->tempUnit} .";
        return $msg;
      }
    function drainbagstep( $combinedrunningsvolume )
      {
        $msg = "Drain the grain bag into the mash water to make \t" . number_format( $combinedrunningsvolume, 2 ) . "\t {$this->volumeUnit} \t wort .";
        return $msg;
      }
    function drainbagandcombinerunningsstep( $combinedrunningsvolume )
      {
        $msg = "Drain the grain bag and combine the mash and sparge water to make \t " . number_format( $combinedrunningsvolume, 2 ) . "\t {$this->volumeUnit} \t wort .";
        return $msg;
      }
    //										
    // Returns the temperature of water required to raise the grain to the										
    // correct mash temperature										
    //
    function strikeWaterTemp( $mashthickness, $mashtemp, $graintemp )
      {
        if ( $this->metric )
          {
            $mt = ( $mashthickness * 0.119826427 ) * 4;
          }
        else
          {
            $mt = $mashthickness * 4;
          }
        $final = ( $this->thermodynamicConstant / $mt ) * ( $mashtemp - $graintemp ) + $mashtemp;
        return $final;
      }
    //										
    // Returns the volume in gallons of boiling water required to raise the										
    // mash to a given temperature										
    //
    function mashInfusionTemp( $mashvolume, $grainweight, $mashtemp, $targettemp )
      {
        $final = ( ( $targettemp - $mashtemp ) * ( $this->thermodynamicConstant * $grainweight + $mashvolume ) / ( $this->boiling_temperature - $targettemp ) ) / 4;
        return $final;
      }
    //										
    // Returns the volume in gallons of boiling water required to raise the mash										
    // to the mash out temperature										
    //	
    function mashOutWaterVolume( $mashvolume, $grainweight, $mashtemp )
      {
        $final = $this->mashInfusionTemp( $mashvolume, $grainweight, $mashtemp, $this->mashOutTemp );
        return $final;
      }
    function array_to_list( $arrays )
      {
        $count     = count( $arrays );
        $new_array = array( );
        for ( $i = 0; $i < count( $arrays ); $i++ )
          {
            $new_array[ $i ] = "<li>" . $arrays[ $i ] . "</li>";
          }
        return $new_array;
      }
    //										
    // A single infusion mash with a single batch sparge										
    //	
    function singleinfusionbatchsparge( )
      {
        $mashWaterVolume        = $this->grain_weight * $this->mash_thickness + $this->layter_deadspace;
        $correctedMashThickness = $mashWaterVolume / $this->grain_weight;
        $spargeWaterVolume      = $this->boil_volume - $mashWaterVolume + ( $this->grain_weight * $this->grain_absorbtion_factor ) + $this->layter_deadspace;
        $firstRunnings          = $mashWaterVolume - ( $this->grain_weight * $this->grain_absorbtion_factor ) - $this->layter_deadspace;
        $secondRunnings         = $spargeWaterVolume;
        $combinedRunnings       = $firstRunnings + $secondRunnings;
        $strikeWaterTemp        = $this->strikeWaterTemp( $correctedMashThickness, $this->temperature, $this->grain_temperature );
        $arr                    = array(
             0 => $this->strikeStep( $mashWaterVolume, $strikeWaterTemp ),
            1 => $this->mashreststep( $this->temperature, $this->mash_time ),
            2 => $this->vorlaufandlauterstep( $firstRunnings, 'first' ),
            3 => $this->spargestep( $spargeWaterVolume, $this->mashOutTemp ),
            4 => $this->vorlaufandlauterstep( $secondRunnings, 'second' ),
            5 => $this->combinedrunningsstep( $combinedRunnings ) 
        );
        return $this->array_to_list( $arr );
      }
    //										
    // A single infusion mash with a mash out raise the mash out temp before										
    // a single batch sparge										
    //
    function singleInfusionMashOutBatchSparge( )
      {
        // Water required to mash plus additional water to fill the mash tun										
        $mashWaterVolume        = ( $this->grain_weight * $this->mash_thickness ) + $this->layter_deadspace;
        // Mash thickness changes slightly because of the lauter deadspace										
        $correctedMashThickness = $mashWaterVolume / $this->grain_weight;
        // Calculate amount of boiling water is needed to bring the mash to the mash										
        // out temperature										
        $mashOutWaterVolume     = $this->mashOutWaterVolume( $mashWaterVolume, $this->grain_weight, $this->temperature );
        // Mash water + mash out water - water absorbed by grain - lauter deadspace										
        $firstRunnings          = $mashWaterVolume + $mashOutWaterVolume - ( $this->grain_weight * $this->grain_absorbtion_factor ) - $this->layter_deadspace;
        // Use the rest of the water for the sparge										
        $spargeWaterVolume      = $this->boil_volume - $firstRunnings;
        // Grain is already saturated and deadspace is full										
        $secondRunnings         = $spargeWaterVolume;
        // Combined runnings										
        $combinedRunnings       = $firstRunnings + $secondRunnings;
        // Calculate strike water temperature										
        $strikeWaterTemp        = $this->strikeWaterTemp( $correctedMashThickness, $this->temperature, $this->grain_temperature );
        $arr                    = array(
             0 => $this->strikeStep( $mashWaterVolume, $strikeWaterTemp ),
            1 => $this->mashreststep( $this->temperature, $this->mash_time ),
            2 => $this->mashoutstep( $mashOutWaterVolume, $this->boiling_temperature ),
            3 => $this->vorlaufandlauterstep( $firstRunnings, 'first' ),
            4 => $this->spargestep( $spargeWaterVolume, $this->mashOutTemp ),
            5 => $this->vorlaufandlauterstep( $secondRunnings, 'second' ),
            6 => $this->combinedrunningsstep( $combinedRunnings ) 
        );
        return $this->array_to_list( $arr );
      }
    //										
    // A single infusion mash with a mash out to bring the volume of the first										
    // runnings to half the boil volume and a single batch sparge with the										
    // remaining water.										
    //	
    function singleInfusionMashOutBatchSpargeEqualRunnings( )
      {
        $mashWaterVolume    = $this->grain_weight * $this->mash_thickness;
        // Mash out water volume + first runnings = half of the pre-boil volume										
        $mashOutWaterVolume = ( $this->boiling_temperature / 2 ) - ( $mashWaterVolume - ( $this->grain_weight * $this->grain_absorbtion_factor ) - $this->layter_deadspace );
        // Sparge water makes up the other half of the pre-boil volume										
        $spargeWaterVolume  = $this->boil_volume / 2;
        // Mash water + mash out water - grain absorption - lauter deadspace										
        $firstRunnings      = $mashWaterVolume + $mashOutWaterVolume - ( $this->grain_weight * $this->grain_absorbtion_factor ) - $this->layter_deadspace;
        $secondRunnings     = $spargeWaterVolume;
        $combinedRunnings   = $firstRunnings + $secondRunnings;
        $strikeWaterTemp    = $this->strikeWaterTemp( $this->mash_thickness, $this->temperature, $this->grain_temperature );
        $arr                = array(
             0 => $this->strikeStep( $mashWaterVolume, $strikeWaterTemp ),
            1 => $this->mashreststep( $this->temperature, $this->mash_time ),
            2 => $this->mashoutstep( $mashOutWaterVolume, $this->mashOutTemp ),
            3 => $this->vorlaufandlauterstep( $firstRunnings, 'first' ),
            4 => $this->spargestep( $spargeWaterVolume, $this->mashOutTemp ),
            5 => $this->vorlaufandlauterstep( $secondRunnings, 'second' ),
            6 => $this->combinedrunningsstep( $combinedRunnings ) 
        );
        return $this->array_to_list( $arr );
      }
    //										
    // A single infusion mash with two batch sparges, each using half of the total										
    // sparge water.										
    //	
    function singleInfusionTwoEqualBatchSparges( )
      {
        $mashWaterVolume        = $this->grain_weight * $this->mash_thickness + $this->layter_deadspace;
        $correctedMashThickness = $mashWaterVolume / $this->grain_weight;
        $firstRunnings          = $mashWaterVolume - ( $this->grain_weight * $this->grain_absorbtion_factor ) - $this->layter_deadspace;
        //										
        $spargeWaterVolume      = ( $this->boil_volume - $firstRunnings ) / 2;
        //										
        $secondRunnings         = $spargeWaterVolume;
        //										
        $thirdRunnings          = $spargeWaterVolume;
        //										
        $combinedRunnings       = $firstRunnings + $secondRunnings + $thirdRunnings;
        //										
        $strikeWaterTemp        = $this->strikeWaterTemp( $correctedMashThickness, $this->temperature, $this->grain_temperature );
        $arr                    = array(
             0 => $this->strikeStep( $mashWaterVolume, $strikeWaterTemp ),
            1 => $this->mashreststep( $this->temperature, $this->mash_time ),
            2 => $this->vorlaufandlauterstep( $firstRunnings, 'first' ),
            3 => $this->spargestep( $spargeWaterVolume, $this->mashOutTemp ),
            4 => $this->vorlaufandlauterstep( $secondRunnings, 'second' ),
            5 => $this->spargestep( $spargeWaterVolume, $this->mashOutTemp ),
            6 => $this->vorlaufandlauterstep( $thirdRunnings, 'third' ),
            7 => $this->combinedrunningsstep( $combinedRunnings ) 
        );
        return $this->array_to_list( $arr );
      }
    //										
    // A single infusion BIAB mash										
    //
    function singleInfusionNoSpargeBiab( )
      {
        // Mash with just enough water										
        $mashWaterVolume        = $this->boil_volume + ( $this->grain_weight * $this->grain_absorbtion_factor );
        //										
        $correctedMashThickness = $mashWaterVolume / $this->grain_weight;
        //										
        $combinedRunningsVolume = $mashWaterVolume - ( $this->grain_weight * $this->grain_absorbtion_factor );
        $strikeWaterTemp        = $this->strikeWaterTemp( $correctedMashThickness, $this->temperature, $this->grain_temperature );
        $arr                    = array(
             0 => $this->strikeStep( $mashWaterVolume, $strikeWaterTemp ),
            1 => $this->mashreststep( $this->temperature, $this->mash_time ),
            2 => $this->drainbagstep( $combinedRunningsVolume ) 
        );
        return $this->array_to_list( $arr );
      }
    //										
    // A single infusion BIAB mash with a sparge rinse										
    //
    function singleInfusionSpargeRinseBiab( )
      {
        $mashWaterVolume        = ( $this->grain_weight * $this->mash_thickness );
        $strikeWaterTemp        = $this->strikeWaterTemp( $this->mash_thickness, $this->temperature, $this->grain_temperature );
        //										
        $spargeWaterVolume      = $this->boil_volume - $mashWaterVolume + ( $this->grain_weight * $this->grain_absorbtion_factor );
        //										
        $combinedRunningsVolume = $mashWaterVolume + $spargeWaterVolume - ( $this->grain_weight * $this->grain_absorbtion_factor );
        $arr                    = array(
             0 => $this->strikeStep( $mashWaterVolume, $strikeWaterTemp ),
            1 => $this->mashreststep( $this->temperature, $this->mash_time ),
            2 => $this->rinsespargestep( $spargeWaterVolume ),
            3 => $this->drainbagandcombinerunningsstep( $combinedRunningsVolume ) 
        );
        return $this->array_to_list( $arr );
      }
  }
$mash                 = new mashwater();
$output               = $mash->output;
$response             = array( );
$response[ 'status' ] = "ok";
$response[ 'data' ]   = "";
for ( $i = 0; $i < count( $output ); $i++ )
  {
    $response[ 'data' ] .= "<br/>" . $output[ $i ];
  }
echo json_encode( $response );
?>
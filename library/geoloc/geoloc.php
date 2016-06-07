<?php
class Geoloc {
    private function p_get_ip() {
        $ip = json_decode(file_get_contents('https://api.ipify.org?format=json'))->ip;
        return $ip;
    }
    private function p_geoloc($gps = FALSE){
        include_once("library/geoloc/geoipcity.inc");
        include_once("library/geoloc/geoipregionvars.php");

        $gi = geoip_open(realpath("library/geoloc/geolitecity.dat"), GEOIP_STANDARD);
        $ip = $this->p_get_ip();
        $record = geoip_record_by_addr($gi, $ip);
        geoip_close($gi);
        $lat =  $record->latitude;
        $lng = $record->longitude;
        if($gps == TRUE) {
            return array ('lat' => $lat, 'lng' => $lng);
        }
        else{
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng;
            if($json = file_get_contents($url)) {
                $data = json_decode($json, true);
                return $data['results'][0];
            }
        }
        return FALSE;
    }
    private function p_get_gps($place_id){
        $key_api = 'AIzaSyCNyQ1EdWYofXYBMoMNij3fkEUEak6TGtk';
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?place_id='.$place_id.'&key='.$key_api;
        if($json = file_get_contents($url)) {
            $data = json_decode($json, true)['results'][0]['geometry']['location'];
            return $data;
        }
        return FALSE;
    }
    function get_tmpcoord($place_id){
        return $this->p_get_gps($place_id);
    }

    function get_coord(){
        return $this->p_geoloc(TRUE);
    }
    function get_place_id(){
        return $this->p_geoloc()['place_id'];
    }
    function get_adresse_formatted(){
        return $this->p_geoloc()['formatted_address'];
    }
    function get_city_by_placeid($place_id){
        $key_api = 'AIzaSyCNyQ1EdWYofXYBMoMNij3fkEUEak6TGtk';
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?place_id='.$place_id.'&key='.$key_api;
        if($json = file_get_contents($url)) {
            $data = json_decode($json, true)['results'][0]['address_components'];
            foreach($data as $line)
            {
                if (in_array("locality", $line["types"])) {
                    return $line['long_name'];
                }
            }
        }
        return FALSE;
    }
    function get_distance_m($lat1, $lng1, $lat2, $lng2) {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon

        $rlo1 = deg2rad($lng1);
        $rla1 = deg2rad($lat1);
        $rlo2 = deg2rad($lng2);
        $rla2 = deg2rad($lat2);

        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;

        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return ($earth_radius * $d);
    }
}
?>
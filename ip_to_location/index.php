<html>
   <body>
      <?php

         function countryCityFromIP($ipAddr)
         {
            //function to find country and city from IP address
            //Developed by Roshan Bhattarai [url]http://roshanbh.com.np[/url]

            //verify the IP address for the
            ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
            $ipDetail=array(); //initialize a blank array

            //get the XML result from hostip.info
            $xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);

            //get the city name inside the node <gml:name> and </gml:name>
            preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);

            //assing the city name to the array
            $ipDetail['city']=$match[2];

            //get the country name inside the node <countryName> and </countryName>
            preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);

            //assign the country name to the $ipDetail array
            $ipDetail['country']=$matches[1];

            //get the country name inside the node <countryName> and </countryName>
            preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
            $ipDetail['country_code']=$cc_match[1]; //assing the country code to array

            //return the array containing city, country and country code
            return $ipDetail;

         }
         $ip=$_SERVER['REMOTE_ADDR'];
         $datas=countryCityFromIP($ip);
         $country_code=$datas["country_code"];
         echo $country_code."<br>";
         if($country_code=="PAK" or $country_code=="IND" or $country_code=="USA" or $country_code=="US") {
            echo "You have not permission<br>";
            echo $country_code;
         }
         else {
            echo "Content here";
         }
      ?>
   </body>
</html>


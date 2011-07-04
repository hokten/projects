<?php
   session_start();
   // Gerekli olan session değişkenlerine ilk değerleri veriliyor.
   $_SESSION['inen']=1;
   $_SESSION['toplam']=1;
   $_SESSION['devam']=0;

   // Megaupload nesnesi fixme sınıfın adını değiştir.
   class megaupload
   {
      public $filesDir = '';
      public $file;

      function __construct()
      {
         set_time_limit(false);
         $this->buffer = 0;
         $this->progress = 0;
         $this->file = fopen("ahmet.flv",'w');
      }	

      function start($downLink)
      {
         $name = explode('/',$downLink);
         $curl = curl_init($downLink);
         curl_setopt_array(
            $curl,
            Array(
               CURLOPT_WRITEFUNCTION => Array($this,'progress'),
               CURLOPT_HTTPHEADER => Array('Referer: http://www.google.com , Host: http://localhost')
            )
         );
         curl_exec($curl);
         fclose($this->file);
         curl_close($curl);
      }

      function progress($curl,$data)
      {
         $len = fwrite($this->file,$data);
         $this->progress += $len;
         session_start();
         // Bu kismi ben ekledim. Islemin iptali icin..
         if($_SESSION['devam']==1)
         {
            curl_close($curl);
            fclose($this->file);
            session_destroy();
         }
         $_SESSION['inen'] = $this->progress;
         $_SESSION['toplam']=curl_getinfo($curl,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
         session_commit();
         return $len;
      }
   }

   $file_contents = file_get_contents("http://www.youtube.com/watch?v=k0TONBwqc7E");
   if (preg_match('/fmt_url_map/i',$file_contents))
   {
      $vidUrl = end(explode('fmt_url_map=',$file_contents));
      $vidUrl = current(explode('&',$vidUrl));
      $vidUrl = current(explode('%2C',$vidUrl));
      $vidUrl = urldecode(end(explode('%7C',$vidUrl)));
   }
   //echo $vidUrl; Test amacli
   $mu = new megaupload;
   $mu->start($vidUrl);
?>


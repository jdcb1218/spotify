<?php
/**
 * @file
 * @author Juan Ceballos
 * Contains \Drupal\spoty\Controller\spotyController.
 * Please place this file under your spoty(module_root_folder)/src/Controller/
 */
namespace Drupal\spoty\Controller;
/**
 * Provides route responses for the spoty module.
 */
class SpotyController {
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function releases() {

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.spotify.com/v1/browse/new-releases",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer " . $this->getToken(),
		    "cache-control: no-cache"
		  ),
		));

		  $data['storage'] = json_decode(curl_exec($curl));
		  $events = $data['storage']->albums->items;

		return [
        	'#theme' => 'events_listing_display',
        	'#events' => $events,
        ];
  }

  /**
   * Returns a info Artista.
   *
   * @return array
   *   A simple renderable array.
   */

  public function artists() {
  	 	
  	 	$args = explode("/",\Drupal::service('path.current')->getPath());
  	 	$id_artista = array_pop($args);
 		// Get Albunes Artista.
 		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.spotify.com/v1/artists/".$id_artista."/albums",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer " . $this->getToken(),
		    "cache-control: no-cache"
		  ),
		));

		$data['storage'] = json_decode(curl_exec($curl));
		$artistas = $data['storage']->items;

 		$curl_uid = curl_init();
		curl_setopt_array($curl_uid, array(
		  CURLOPT_URL => 'https://api.spotify.com/v1/artists/'.$id_artista,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer " . $this->getToken(),
		    "cache-control: no-cache"
		  ),
		));

		$data['profile'] = json_decode(curl_exec($curl_uid));
		$profile = $data['profile'];

		return [
        	'#theme' => 'artista_listing_display',
        	'#artistas' => $artistas,
        	'#profile' => $profile,
        ];
  }


  public function search($keyword){

	  $curl = curl_init();
	  curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.spotify.com/v1/search?q=".$keyword."&type=artist",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	    "authorization: Bearer " . $this->getToken(),
	    "cache-control: no-cache"
	  ),
	));

	$data['search'] = json_decode(curl_exec($curl));
	return $data['search'];		  
  }

  /**
   * Returns a info Token Api(Spoty).
   *
   * @return array
   *   A simple renderable array.
   */

    public function getToken(){
	    $url =  \Drupal::state()->get('endpoint');
		$data = array('grant_type' => 'client_credentials', 'client_id' => \Drupal::state()->get('cliend_id'),'client_secret' => \Drupal::state()->get('cliend_secret'));

		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
	    $params = explode('","',file_get_contents($url, false, $context));

	    $token = explode('":"',array_shift($params));
        return $token[true];
    }
}
?>
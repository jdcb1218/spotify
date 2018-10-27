<?php

namespace Drupal\spoty\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Unicode;
use Drupal\spoty\Controller\SpotyController;
use Drupal\Core\Url;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class AutocompleteController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request, $field_name, $count) {
    $statistics = new SpotyController;
    $results = [];

    // Get the typed string from the URL, if it exists.
    if ($input = $request->query->get('q')) {
      $data = array($statistics->search($request->query->get('q')));
     
      foreach ($data[FALSE]->artists->items as $key => $value) {
        $image_variables = [
          '#theme' => 'image',
          '#uri' => $value->images[0]->url,
          '#alt' => $value->name,
          '#title' => $value->name,
        ];
        $thumb = \Drupal::service('renderer')->render($image_variables);


      preg_match( '@src="([^"]+)"@' , $thumb, $match );
      $src = array_pop($match);


      $link =  \Drupal::l(t($value->name), Url::fromUri('https://open.spotify.com/artist/' . $value->id, array('attributes' => array('target' => '_blank'))));

        if (isset($src)) {
           $results[] = [
            'value' => $link. '</br>' . $thumb . '',
          ];         
        }
      }
    }
    
    return new JsonResponse($results);
  }
}
?>


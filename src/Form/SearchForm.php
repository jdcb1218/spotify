<?php

/**
 * @file
 * @author Juan Ceballos
* Primarily Drupal hooks and global API functions.
*/

namespace Drupal\spoty\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;

//$testObject = new SpotyController();

/**
 * Contribute form.
 */
class SearchForm extends FormBase {
  /**
   * {@inheritdoc}
   */

  public function getFormId() {
    return 'spoty_search_form';
  }

  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = array(
        '#type' => 'textfield',
        '#autocomplete_route_name' => 'spoty.autocomplete',
        '#autocomplete_route_parameters' => array('field_name' => 'name', 'count' => 10),
    );
    return $form;
  }

  /**
   * {@submitForm}
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      \Drupal::state()->set($key,$value);
    }
  }
}
?>
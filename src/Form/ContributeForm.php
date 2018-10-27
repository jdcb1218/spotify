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
use Drupal\spoty\Controller\SpotyController;




/**
 * Contribute form.
 */

class ContributeForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'spoty_contribute_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $statistics = new SpotyController;

     $form['endpoint'] = array(
      '#type' => 'textfield',
      '#title' => t('End Point'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('endpoint'),
      '#description' => t('End Point: https://accounts.spotify.com/api/token'), 
    );
     $form['cliend_id'] = array(
      '#type' => 'textfield',
      '#title' => t('Client(%)id'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('cliend_id'),
       '#description' => t('Client(%)id: 381d05f71c694a9a8d94e06423147e70'), 
    );
     $form['cliend_secret'] = array(
      '#type' => 'textfield',
      '#title' => t('Client Secret'),
      '#required' => TRUE,
      '#default_value' =>  \Drupal::state()->get('cliend_secret'),
      '#description' => t('Client Secret: 850b68d5523a40ca924d19a337e99f66'), 
    );

    $form['token'] = [
      '#type' => 'item',
      '#title' => t('Your Access Token Spoty is:'),
      '#markup' => $statistics->getToken(),
    ];

    $form['lanzamientos'] = [
      '#type' => 'item',
      '#title' => t('Releases top Spoty Music'),
      '#markup' => \Drupal::l(t('Internal Link'), Url::fromUri('internal:/spoty/releases', array('attributes' => array('target' => '_blank')))),
    ];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save'),
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
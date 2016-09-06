<?php
/**
 * @file
 * Contains \Drupal\example\Form\ExampleForm.
 */

namespace Drupal\webform_references\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityConfirmFormBase;

/**
 * Implements an example form.
 */
class WebformReferencesNode extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_references_node_autocomplete';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $pid = NULL) {
    $player = Mp3playerController::mp3player_players($pid);

    if($pid) {
      $player = $player[$pid];
      $this->pid = $pid;
      $this->name = $player['name'];
    }

    if (empty($player)) {
      drupal_set_message(t('The specified player was not found.'), 'error');
      drupal_goto('admin/settings/mp3player');
    }
    if($player['name'] == 'Default') {
      drupal_set_message(t('You cannot delete the Default player.'), 'error');
      drupal_goto('admin/settings/mp3player');
    }

    $form['pid'] = array('#type' => 'value', '#value' => $pid);

    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    db_delete('mp3player_players')->condition('pid', $form_state->getValues()['pid'])->execute();
    $form_state->setRedirect('mp3player.player_list');

  }

}

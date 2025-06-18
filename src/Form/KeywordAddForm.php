<?php

namespace Drupal\keyword_brandsafe_tagging\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KeywordAddForm extends FormBase {

  protected Connection $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('database')
    );
  }

  public function getFormId(): string {
    return 'keyword_brandsafe_add_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['keyword'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Keyword'),
      '#required' => TRUE,
    ];

    $form['language'] = [
      '#type' => 'select',
      '#title' => $this->t('Language'),
      '#required' => TRUE,
      '#options' => [
        'English' => $this->t('English'),
        'Bahasa Malaysia' => $this->t('Bahasa Malaysia'),
        'Chinese' => $this->t('Chinese'),
      ],
    ];

    $form['severity'] = [
      '#type' => 'select',
      '#title' => $this->t('Severity'),
      '#options' => [
        'low' => $this->t('Low'),
        'medium' => $this->t('Medium'),
        'high' => $this->t('High'),
      ],
      '#default_value' => 'medium',
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Active'),
      '#default_value' => 1,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $now = \Drupal::time()->getCurrentTime();

    $this->database->insert('keyword_brandsafe')
      ->fields([
        'keyword' => $form_state->getValue('keyword'),
        'language' => $form_state->getValue('language'),
        'severity' => $form_state->getValue('severity'),
        'status' => (int) $form_state->getValue('status'),
        'created' => $now,
        'updated' => $now,
      ])
      ->execute();

    $this->messenger()->addMessage($this->t('Keyword "%keyword" has been added.', [
      '%keyword' => $form_state->getValue('keyword'),
    ]));
    $form_state->setRedirect('<current>');
  }

}

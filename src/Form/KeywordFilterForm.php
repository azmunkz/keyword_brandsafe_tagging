<?php

namespace Drupal\keyword_brandsafe_tagging\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class KeywordFilterForm extends FormBase
{
  public function getFormId(): string
  {
    return 'keyword_brandsafe_filter_form';
  }

  public function buildForm(array $form, FormStateInterface $formState): array
  {
    $form['language'] = [
      '#type' => 'select',
      '#title' => $this->t('Language'),
      '#options' => [
        '' => $this->t('- All -'),
        'English' => $this->t('English'),
        'Bahasa Malaysia' => $this->t('Bahasa Malaysia'),
        'Chinese' => $this->t('Chinese'),
      ],
      '#defaul_value' => \Drupal::request()->query->get('language'),
    ];

    $form['severity'] = [
      '#type' => 'select',
      '#title' => $this->t('Severity'),
      '#options' => [
        '' => '- All -',
        'low' => $this->t('Low'),
        'medium' => $this->t('Medium'),
        'high' => $this->t('High')
      ],
      '#default_value' => \Drupal::request()->query->get('severity')
    ];

    $form['status'] = [
      '#type' => 'select',
      '#title' => $this->t('Status'),
      '#options' => [
        '' => $this->t('- All - '),
        1 => 'Active',
        0 => 'Inactive'
      ],
      '#default_value' => \Drupal::request()->query->get('status'),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Filter'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $formState): void
  {
    $params = [];

    foreach (['language', 'seveirty', 'status'] as $field) {
      $value = $formState->getValue($field);
      if ($value !== '' && $value !== NULL) {
        $params[$field] = $value;
      }
    }

    $formState->setRedirect('keyword_brandsafe_tagging.keyword_list', [], ['query' => $params]);
  }
}

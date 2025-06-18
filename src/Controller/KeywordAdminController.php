<?php

namespace Drupal\keyword_brandsafe_tagging\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\keyword_brandsafe_tagging\Form\KeywordFilterForm;

class KeywordAdminController extends ControllerBase
{
  protected Connection $database;

  public function __construct(Connection $database)
  {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container): static
  {
    return new static(
      $container->get('database')
    );
  }

  public function keywordList(): array {
    $query = $this->database->select('keyword_brandsafe', 'k')
      ->fields('k')
      ->orderBy('id', 'DESC');

    // Apply filters
    $input = \Drupal::request()->query->all();
    foreach (['language', 'severity', 'status'] as $field) {
      if (!empty($input[$field]) || $input[$field] === "0") {
        $query->condition($field, $input[$field]);
      }
    }

    $results = $query->execute();
    $rows = [];

    foreach ($results as $record) {
      $rows[] = [
        'id' => $record->id,
        'keyword' => $record->keyword,
        'language' => $record->language,
        'severity' => ucfirst($record->severity),
        'status' => $record->status ? $this->t('Active') : $this->t('Inactive'),
        'operations' => $this->t('Coming soon'),
      ];
    }

    return [
      'filter_form' => \Drupal::formBuilder()->getForm(KeywordFilterForm::class),
      'table' => [
        '#type' => 'table',
        '#header' => [
          'id' => $this->t('ID'),
          'keyword' => $this->t('Keyword'),
          'language' => $this->t('Language'),
          'severity' => $this->t('Severity'),
          'status' => $this->t('Status'),
          'operations' => $this->t('Operations'),
        ],
        '#rows' => $rows,
        '#empty' => $this->t('No keywords found.'),
      ],
    ];
  }
}

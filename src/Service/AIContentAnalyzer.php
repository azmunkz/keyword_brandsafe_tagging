<?php

namespace Drupal\keyword_brandsafe_tagging\Service;

use Drupal\key\KeyRepositoryInterface;

class AIContentAnalyzer {
  protected KeyRepositoryInterface $keyRepository;

  public function __construct(KeyRepositoryInterface $keyRepository)
  {
    $this->keyRepository = $keyRepository;
  }

  public function testConnection(): string
  {
    $key = $this->keyRepository->getKey('openai_key')->getKeyValue();
    return !empty($key) ? 'OpenAI key loaded.' : 'OpenAI key missing.';
  }
}

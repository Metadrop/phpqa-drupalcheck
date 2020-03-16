<?php

namespace PhpQaDrupalCheck\QA\Tools\Analyzer;

use DrupalCheck\Application;
use Edge\QA\OutputMode;

/**
 * Drupal chec
 * @package PhpQaDrupalCheck\Tools\Analyzer
 */
class DrupalCheck extends \Edge\QA\Tools\Tool
{

    public static $SETTINGS = array(
        'optionSeparator' => '=',
        'internalClass' => Application::class,
        'outputMode' => OutputMode::XML_CONSOLE_OUTPUT,
        'composer' => 'mglaman/drupal-check',
    );

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $format = $this->config->value('drupal-check.format');
        $drupalRoot = $this->config->value('drupal-check.drupalRoot');
        $args = [
          'drupal-root' => !empty($drupalRoot) ? $drupalRoot : 'web',
          'memory-limit' => $this->config->value('drupal-check.memoryLimit'),
          'format' => !empty($format) ? $format : 'checkstyle',
        ];

        foreach (['analysis', 'style', 'deprecation'] as $check) {
          if ($this->config->value('drupal-check.' . $check) === TRUE) {
            $args[] = $check;
          }
        }

        return [$this->options->getAnalyzedDirs(' '), '--no-progress'] + array_filter($args);
    }
}

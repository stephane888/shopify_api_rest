<?php

namespace Drupal\shopify_api_rest\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\shopify_api_rest\Entity\CreneauCnfInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CreneauCnfController.
 *
 *  Returns responses for Creneau cnf routes.
 */
class CreneauCnfController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Creneau cnf revision.
   *
   * @param int $creneau_cnf_revision
   *   The Creneau cnf revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($creneau_cnf_revision) {
    $creneau_cnf = $this->entityTypeManager()->getStorage('creneau_cnf')
      ->loadRevision($creneau_cnf_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('creneau_cnf');

    return $view_builder->view($creneau_cnf);
  }

  /**
   * Page title callback for a Creneau cnf revision.
   *
   * @param int $creneau_cnf_revision
   *   The Creneau cnf revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($creneau_cnf_revision) {
    $creneau_cnf = $this->entityTypeManager()->getStorage('creneau_cnf')
      ->loadRevision($creneau_cnf_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $creneau_cnf->label(),
      '%date' => $this->dateFormatter->format($creneau_cnf->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Creneau cnf.
   *
   * @param \Drupal\shopify_api_rest\Entity\CreneauCnfInterface $creneau_cnf
   *   A Creneau cnf object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(CreneauCnfInterface $creneau_cnf) {
    $account = $this->currentUser();
    $creneau_cnf_storage = $this->entityTypeManager()->getStorage('creneau_cnf');

    $build['#title'] = $this->t('Revisions for %title', ['%title' => $creneau_cnf->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all creneau cnf revisions") || $account->hasPermission('administer creneau cnf entities')));
    $delete_permission = (($account->hasPermission("delete all creneau cnf revisions") || $account->hasPermission('administer creneau cnf entities')));

    $rows = [];

    $vids = $creneau_cnf_storage->revisionIds($creneau_cnf);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\shopify_api_rest\Entity\CreneauCnfInterface $revision */
      $revision = $creneau_cnf_storage->loadRevision($vid);
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $creneau_cnf->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.creneau_cnf.revision', [
            'creneau_cnf' => $creneau_cnf->id(),
            'creneau_cnf_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $creneau_cnf->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => Url::fromRoute('entity.creneau_cnf.revision_revert', [
                'creneau_cnf' => $creneau_cnf->id(),
                'creneau_cnf_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.creneau_cnf.revision_delete', [
                'creneau_cnf' => $creneau_cnf->id(),
                'creneau_cnf_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
    }

    $build['creneau_cnf_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}

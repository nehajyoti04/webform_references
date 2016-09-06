<?php

namespace Drupal\webform_references\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

class WebformReferencesController extends ControllerBase {

  public function webform_references_node_autocomplete($bundles, $string = '') {
    $options = array(
      'string' => $string,
      'limit' => 10,
    );

    $references = $this->webform_references_node_potential_references($bundles, $options);

  }

/**
* Retrieves an array of referenceable nodes.
*
* @param string $bundles
*   List of content types separated by (+) sign to be referenced.
* @param array $options
*   An array of options to limit the scope of the returned list. The following
*   key/value pairs are accepted:
*   - string: string to filter titles on (used by autocomplete).
*   - ids: array of specific node ids to lookup.
*   - limit: maximum size of the the result set. Defaults to 0 (no limit).
*
* @return array
*   An array of valid nodes in the form:
*   array(
*     nid => array(
*       'title' => The node title,
*       'rendered' => The text to display in widgets (can be HTML)
*     ),
*     ...
*   )
*/
  function webform_references_node_potential_references($bundles, $options = array()) {
    $options += array(
      'string' => '',
      'ids' => array(),
      'limit' => 0,
    );
    $results = &drupal_static(__FUNCTION__, array());
    // Create unique id for static cache.
    $cid = ($options['string'] !== '' ? $options['string'] : implode('-', $options['ids']))
      . ':' . $options['limit'];
    if (!isset($results[$cid])) {
      $references = _webform_references_node_potential_references_standard($bundles, $options);
      // Store the results.
      $results[$cid] = !empty($references) ? $references : array();
    }
    return $results[$cid];
  }


}

<?php

namespace Google\Web_Stories_Dependencies\AmpProject\Dom\Document;

use Google\Web_Stories_Dependencies\AmpProject\Dom\Document;
/**
 * Filter the Dom\Document before it is saved.
 *
 * @package ampproject/amp-toolbox
 */
interface BeforeSaveFilter extends Filter
{
    /**
     * Preprocess the DOM to be saved into HTML.
     *
     * @param Document $document Document to be preprocessed before saving it into HTML.
     */
    public function beforeSave(Document $document);
}

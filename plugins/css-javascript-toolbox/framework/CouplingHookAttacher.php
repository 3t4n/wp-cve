<?php
/**
*
*/


/**
*
*/
final class CJTBlocksCouplingHookAttacher {


    const GROUP = 'cjt';

    /**
    * put your comment there...
    *
    * @var mixed
    */
    public $callback;

    /**
    * put your comment there...
    *
    * @var mixed
    */
    public $filters;

    /**
    * put your comment there...
    *
    * @var mixed
    */
    private $filtersMap = array();

    /**
    * put your comment there...
    *
    * @var mixed
    */
    public $groupMap = array();

    /**
    * put your comment there...
    *
    * @var mixed
    */
    public $textMap = array();

    /**
    *
    */
    public $typeHandler = null;

    /**
    * put your comment there...
    *
    * @param mixed $callback
    * @return CJTBlocksCouplingHookAttacher
    */
    public function __construct($callback) {

        $this->typeHandler = $this;
        $this->callback = $callback;

        // Initialize filters
        $this->filters = array(

            // Unattched hooks are attached (dynamically) to either Backend or Frontend by the coupling controller
            'unattached' => array(

                /* Database location map name */    /* Wordpress Hook name Part */
                'header' => array(
                    'name'  => 'head',
                    'handler' => $this->callback,
                    'title' => cssJSToolbox::getText('Header'),
                    'text' => cssJSToolbox::getText('Puts your code right before the HTML </header> tag'),
                    'group' => self::GROUP,
                ),
                'footer' => array(
                    'name'  => 'footer',
                    'handler' => $this->callback,
                    'title' => cssJSToolbox::getText('Footer'),
                    'text' => cssJSToolbox::getText('Puts your code at the end of the page'),
                    'group' => self::GROUP,
                ),
            ),
        );

        if ( ! class_exists( 'CJTPlus' ) ) {
            $this->filters['unattached'] = array_merge( $this->filters['unattached'], [
                'wp_body_open' => array(
                    'name' => 'wp_body_open',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('Body Open'),
                    'text' =>  cssJSToolbox::getText('Puts your code right after the HTML tag'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
                'before_the_content'  => array(
                    'name' => 'the_content',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('Before Post Content'),
                    'text' => cssJSToolbox::getText('Puts your code before the post content'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
                'after_the_content'  => array(
                    'name' => 'the_content',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('After Post Content'),
                    'text' => cssJSToolbox::getText('Puts your code after the post content'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
                'before_the_excerpt'  => array(
                    'name' => 'the_excerpt',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('Before Post Excerpt'),
                    'text' => cssJSToolbox::getText('Puts your code just before the post excerpt'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
                'after_the_excerpt'  => array(
                    'name' => 'the_excerpt',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('After Post Excerpt'),
                    'text' => cssJSToolbox::getText('Puts your code after the post excerpt'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
                'loop_start'   => array(
                    'name' => 'loop_start',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('Before Posts List'),
                    'text' => cssJSToolbox::getText('Puts your code right before the posts list, or before the post when the posts query result is a single post'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
                'loop_end'     => array(
                    'name' => 'loop_end',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('After Posts List'),
                    'text' => cssJSToolbox::getText('Puts your code right after the posts list, or after the post when the posts query result is a single post'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
                'the_post'     => array(
                    'name' => 'the_post',
                    'handler' => '',
                    'title' => cssJSToolbox::getText('Before Post'),
                    'text' => cssJSToolbox::getText('Puts your code right before the post'),
                    'group' => self::GROUP,
                    'disabled' => true,
                ),
            ] );
        }

        do_action(CJTPluggableHelper::ACTION_BLOCKS_COUPLING_INIT_ATTACHER, $this);

        // Generate filters map even if not bind done yet
        // so that filter names can be used elsewhere
        $this->generateMap();
    }

    /**
    * Bind all obtained (through the map) filters to Wordpress hooks
    *
    */
    public function & bind() {

        foreach ($this->filtersMap as $hookFullName => $hooks) {

            foreach ($hooks as $hook) {

                add_filter($hookFullName, $hook['handler'], 30);
            }

        }

        return $this;
    }

    /**
    * Generate Hook names by adding the passed prefix to the hook name(s)
    *
    * @param mixed $hooks
    * @param mixed $prefix
    */
    public function genHooks($hooks, $prefix = '') {

        // Bind hooks strighatly
        foreach ($hooks as $locationName => $hook) {

            // Create filters map
            $hookFullName = "{$prefix}{$hook['name']}";

            $hook['locationName'] = $locationName;
            $this->filtersMap[$hookFullName][$locationName] = $hook;

            // Generate localized text map
            $this->textMap[$hook['locationName']]['title'] = $hook['title'];
            $this->textMap[$hook['locationName']]['text'] = $hook['text'];

            // Make Group map
            $this->groupMap[$hook['group']][] = $hook['locationName'];
        }

    }

    /**
    * Generate unattached hook names by adding either (wp_ or admin_ prefix to the hook name(s)
    *
    * @param mixed $filters
    * @return CJTBlocksCouplingHookAttacher
    */
    public function & genUnattachedHooks($filters) {

        $this->genHooks($filters, is_admin() ? 'admin_' : 'wp_');

        return $this;
    }

    /**
    * Generate all filter names by calling the FILTER-TYPE-HANDLER method
    *
    */
    public function generateMap() {

        $typeHandler = $this->typeHandler;

        foreach ($this->filters as $typeName => $filters) {

            $typeName = ucfirst($typeName);

            $attacherMethodName = "gen{$typeName}Hooks";

            $typeHandler->$attacherMethodName($filters);
        }
    }

    /**
    * put your comment there...
    *
    */
    public function getFiltersList() {

        return $this->filtersMap;

    }

    /**
    * put your comment there...
    *
    * @param mixed $group
    */
    public function getGroupMap($group) {

        return $this->groupMap[$group];
    }

    /**
    * put your comment there...
    *
    * @param mixed $filter
    */
    public function getHooksByFilterName($filterName) {

        return $this->filtersMap[$filterName];

    }

    /**
    * put your comment there...
    *
    * @param mixed $locationName
    */
    public function getHookText($locationName, $prop = 'text') {

        $text = isset($this->textMap[$locationName]) ?
                $this->textMap[$locationName][$prop] :
                '';

        return $text;
    }

}

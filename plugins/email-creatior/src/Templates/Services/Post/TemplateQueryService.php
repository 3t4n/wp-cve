<?php


namespace WilokeEmailCreator\Templates\Services\Post;


use WilokeEmailCreator\Shared\Post\Query\IQueryPost;
use WilokeEmailCreator\Shared\Post\Query\QueryPost;

class TemplateQueryService extends QueryPost implements IQueryPost
{
    public function parseArgs(): IQueryPost
    {
        $this->aArgs = $this->commonParseArgs();

        return $this;
    }

}

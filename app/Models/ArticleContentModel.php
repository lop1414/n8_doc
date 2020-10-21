<?php

namespace App\Models;

use App\Common\Models\BaseModel;

class ArticleContentModel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'article_contents';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'article_id';
}

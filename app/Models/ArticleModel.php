<?php

namespace App\Models;

use App\Common\Models\BaseModel;

class ArticleModel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联文章内容模型 一对一
     */
    public function article_content(){
        return $this->hasOne('App\Models\ArticleContentModel', 'article_id', 'id');
    }
}

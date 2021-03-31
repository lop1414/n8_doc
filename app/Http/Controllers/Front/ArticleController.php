<?php

namespace App\Http\Controllers\Front;

use App\Common\Controllers\Front\FrontController;
use App\Common\Enums\StatusEnum;
use App\Common\Enums\SystemAliasEnum;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Common\Traits\BuildTree;
use App\Models\ArticleModel;
use Illuminate\Http\Request;

class ArticleController extends FrontController
{
    use BuildTree;

    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 列表 (无分页)
     */
    public function get(Request $request){
        $data = $request->all();

        $articles = $this->getArticles($data);

        return $this->success($articles);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 树
     */
    public function tree(Request $request){
        $data = $request->all();

        $articles = $this->getArticles($data);

        $tree = $this->buildTree($articles);

        return $this->success($tree);
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomException
     * 获取文章
     */
    private function getArticles($data){
        $this->validRule($data, [
            'system_alias' => 'required',
        ]);

        Functions::hasEnum(SystemAliasEnum::class, $data['system_alias']);

        $articleModel = new ArticleModel();
        $builder = $articleModel->where('system_alias', $data['system_alias'])
            ->orderBy('order', 'desc');

        if(!empty($data['status'])){
            Functions::hasEnum(StatusEnum::class, $data['status']);
            $builder->where('status', $data['status']);
        }else{
            $builder->where('status', StatusEnum::ENABLE);
        }

        if(!empty($data['keyword'])){
            $builder->where('name', 'LIKE', "%{$data['keyword']}%");
        }

        $articles = $builder->get();

        return $articles;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 详情
     */
    public function read(Request $request){
        $data = $request->all();

        $this->validRule($data, [
            'id' => 'required',
        ]);

        $articleModel = new ArticleModel();
        $article = $articleModel->find($data['id']);

        if(empty($article)){
            throw new CustomException([
                'code' => 'NOT_FOUND_TARGET',
                'message' => '找不到目标',
            ]);
        }

        // 关联文章内容
        $article->article_content;

        return $this->success($article);
    }
}
